<?php

namespace App\Models\Benancib;

use CodeIgniter\Model;

class Brapci extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapcis';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	function export($id)
	{
		$RDF = new \App\Models\Rdf\RDF();
		$Language = new \App\Models\AI\NLP\Language();

		$dir = '.tmp/benancib/harvesting/';
		$file1 = $dir . 'benancib_' . $id . '.xml';
		$file2 = $dir . 'benancib_' . $id . '.pdf';

		$xml = simplexml_load_file($file1);
		$auths = array();

		/***************************************************** Publicação *********/
		$name = 'Encontro Nacional de Pesquisa e Pós-graduação em Ciência da Informação';
		$name = 'Encontro Nacional de Pesquisa em Ciência da Informação';
		$class = "Brapci:Event";
		//$idpub = $RDF->conecpt($name, $class);
		$idpub = 101894;

		/***************************************************** Unidade Proceeding */
		$class = "brapci:Proceeding";
		$reg = (array)$xml->record;
		$year = $reg['dc.ano.evento'];
		$name = "enancib.org." . $year . "." . $id;
		$idc = $RDF->conecpt($name, $class);

		/***************************************************** ISSUE */
		$class = "brapci:IssueProceeding";
		$name = 'Encontro Nacional de Pesquisa e Pós-graduação em Ciência da Informação';
		$name .= ', ' . troca($reg['dc.edicao.evento'], 'º', '');
		$name .= '.';
		$name .= ', ' . trim($reg['dc.ano.evento']);
		$name .= ', ' . trim($reg['dc.cidade.evento']);
		$id_issue = $RDF->conecpt($name, $class);

		/******************************************************* ISSUE YEAR */
		$class = "Date";
		$term = $reg['dc.ano.evento'];
		$id_year = $RDF->conecpt($term, $class);
		$RDF->propriety($id_issue, 'brapci:hasDateTime', $id_year);

		/******************************************************* ISSUE YEAR */
		$class = "frbr:Place";
		$term = $reg['dc.cidade.evento'];
		$id_place = $RDF->conecpt($term, $class);
		$RDF->propriety($id_issue, 'brapci:hasPlace', $id_place);

		/****************************************************** ASSOCIA ISSUE AO LINK */
		$RDF->assoc($id_issue, $idc, 'brapci:hasIssueProceedingOf');
		/****************************************************** ASSOCIA EVENTO A ISSUE */
		$RDF->assoc($idpub, $id_issue, 'brapci:hasIssueProceeding');


		/*********************************** TITLES */
		$title = (string)$reg['dc.title'];
		$lang = $Language->getTextLanguage($title);
		$idt = $RDF->literal($title, $lang, $idc, 'brapci:hasTitle');

		if (isset($reg['dc.title.alternative'])) {
			$title = (string)$reg['dc.title.alternative'];
			$lang = $Language->getTextLanguage($title);
			$idt = $RDF->literal($title, $lang, $idc, 'brapci:hasTitle');
		}

		/************************************* Author */
		if (isset($reg['dc.contributor.author'])) {
			$auths = $reg['dc.contributor.author'];
			if (is_array($auths)) {
			} else {
				$auths = array();
				$auths[0] = $reg['dc.contributor.author'];
			}
		}

		for ($r = 0; $r < count($auths); $r++) {
			$auth = (string)$auths[$r];
			$ida = $RDF->conecpt($auth, 'foaf:Person');
			$RDF->propriety($idc, 'brapci:hasAuthor', $ida);
		}
		/************************************** Subject */
		if (isset($reg['dc.keywords'][0])) {
			for ($r = 0; $r < count($reg['dc.keywords']); $r++) {
				$term = (string)$reg['dc.keywords'][$r];
				$idt = $RDF->conecpt($term, 'dc:Subject');
				$RDF->propriety($idc, 'brapci:hasSubject', $idt);
			}
		}
		if (isset($reg['dc.subject'])) {
			for ($r = 0; $r < count($reg['dc.subject']); $r++) {
				$term = (string)$reg['dc.subject'][$r];
				$idt = $RDF->conecpt($term, 'dc:Subject');
				$RDF->propriety($idc, 'brapci:hasSubject', $idt);
			}
		}
		/************************************** Subject */
		if (isset($reg['dc.resumo'])) {
			$term = (string)$reg['dc.resumo'];
			$lang = $Language->getTextLanguage($term);
			$prop = 'brapci:hasAbstract';
			$RDF->RDF_literal($term, $lang, $idc, $prop);
		}
		if (isset($reg['dc.description.abstract'])) {
			$term = (string)$reg['dc.description.abstract'];
			$lang = $Language->getTextLanguage($term);
			$prop = 'brapci:hasAbstract';
			$RDF->RDF_literal($term, $lang, $idc, $prop);
		}

		/************************************** Section */
		if (isset($reg['dc.type'])) {
			$term = (string)$reg['dc.type'];
			$lang = $Language->getTextLanguage($term);
			$idt = $RDF->conecpt($term, 'brapci:ProceedingSection');
			$RDF->propriety($idc, 'brapci:hasSectionOf', $idt);
		}

		$section = '';
		if (isset($reg['dc.numero.gt'])) {
			$section = 'GT' . (string)$reg['dc.numero.gt'];
		}

		if (isset($reg['dc.titulo.gt'])) {
			$section .= ' ' . (string)$reg['dc.titulo.gt'];
			$section = trim($section);
		}
		if (strlen($section) > 0) {
			$lang = $Language->getTextLanguage($section);
			$idt = $RDF->conecpt($section, 'brapci:ProceedingSection');
			$RDF->propriety($idc, 'brapci:hasSectionOf', $idt);
		}

		/************************************************* Cited */
		$Cited = new \App\Models\Brapci\Cited();
		if (isset($reg['dc.referencias'])) {
			$cited = (array)$reg['dc.referencias'];
			$cites = (array)$cited['cited'];

			for ($r = 0; $r < count($cites); $r++) {
				$cit = $cites[$r];
				$ord = $r + 1;
				$Cited->cited($idc, $cit, $ord);
			}
		}

		/************************************************* Arquivo PDF */
		if (file_exists($file2)) {
			dircheck('_repository');
			dircheck('_repository/enancib');
			dircheck('_repository/enancib/' . $year);
			$dir = '_repository/enancib/' . $year . '/';
			$file3 = $dir . 'work_' . $idc . '.pdf';

			if (!copy($file2, $file3)) {
				echo "falha ao copiar $file3...\n";
			}

			$class = 'brapci:FileStorage';
			$idf = $RDF->conecpt($file3, $class);

			$RDF->propriety($idc, 'brapci:hasFileStorage', $idf);

			//exit;
		}

		$link = '<a href="' . PATH . MODULE . 'v/' . $idc . '" target="new' . $idc . '">' . $title . '</a>';
		$sx = '';
		$sx .= $link;
		return $sx;
	}
}
