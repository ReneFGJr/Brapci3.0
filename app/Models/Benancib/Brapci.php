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

		$class = "Brapci:Proceeding";
		$reg = (array)$xml->record;
		$year = $reg['dc.ano.evento'];
		$name = "enancib.org.".$year.".".$id;
		$idc = $RDF->conecpt($name,$class);

		/***************************************************** ISSUE */
		$class = "Brapci:Issue";
		$term = 'Enancib-'.$reg['dc.ano.evento'];
		$id_issue = $RDF->conecpt($term,'brapci:Issue');

		/******************************************************* ISSUE YEAR */
		$class = "Date";
		$term = $reg['dc.ano.evento'];
		$id_year = $RDF->conecpt($term,$class);
		$RDF->propriety($id_issue,'brapci:hasDateTime',$id_year);

		/******************************************************* ISSUE YEAR */
		$class = "frbr:Place";
		$term = $reg['dc.cidade.evento'];
		$id_place = $RDF->conecpt($term,$class);
		$RDF->propriety($id_issue,'brapci:hasPlace',$id_place);	

		/******************************************************* ISSUE YEAR */
		$class = "brapci:Edition";
		$term = romano($reg['dc.edicao.evento']);
		$id_edition = $RDF->conecpt($term,$class);
		$RDF->propriety($id_issue,'brapci:isEdition',$id_edition);	

		/******************************************************* ISSUE YEAR */
		$class = "brapci:Proceeding";
		$term = 'Encontro Nacional de Pesquisa e Pós-graduação em Ciência da Informação';
		$id_journal = $RDF->conecpt($term,$class);
		$RDF->propriety($id_journal,'brapci:isEdition',$id_issue);

		/* Associa trabalho ao issue */
		$RDF->assoc($id_issue,$idc,'brapci:hasIssue');		

		/*********************************** TITLES */
		$title = (string)$reg['dc.title'];
		$lang = $Language->getTextLanguage($title);
		$idt = $RDF->literal($title,$lang,$idc,'brapci:hasTitle');
	
		if (isset($reg['dc.title.alternative']))
			{
				$title = (string)$reg['dc.title.alternative'];
				$lang = $Language->getTextLanguage($title);
				$idt = $RDF->literal($title,$lang,$idc,'brapci:hasTitle');
			}

		/************************************* Author */
		$auths = $reg['dc.contributor.author'];
		if (is_array($auths))
			{
				
			} else {
				$auths = array();
				$auths[0] = $reg['dc.contributor.author'];
			}
		
		for ($r=0;$r < count($auths);$r++)
			{
				$auth = (string)$auths[$r];
				$ida = $RDF->conecpt($auth,'foaf:Person');
				$RDF->propriety($idc,'brapci:hasAuthor',$ida);
			}
		/************************************** Subject */
		for ($r=0;$r < count($reg['dc.keywords']);$r++)
			{
				$term = (string)$reg['dc.keywords'][$r];
				$idt = $RDF->conecpt($term,'dc:Subject');
				$RDF->propriety($idc,'brapci:hasSubject',$idt);
			}
		for ($r=0;$r < count($reg['dc.subject']);$r++)
			{
				$term = (string)$reg['dc.subject'][$r];
				$idt = $RDF->conecpt($term,'dc:Subject');
				$RDF->propriety($idc,'brapci:hasSubject',$idt);
			}
			
		/************************************** Subject */
		if (isset($reg['dc.resumo']))		
			{
				$term = (string)$reg['dc.resumo'];
				$lang = $Language->getTextLanguage($term);
				$prop = 'brapci:hasAbstract';
				$RDF->RDF_literal($term,$lang, $idc, $prop);
			}		
		if (isset($reg['dc.description.abstract']))		
			{
				$term = (string)$reg['dc.description.abstract'];
				$lang = $Language->getTextLanguage($term);
				$prop = 'brapci:hasAbstract';
				$RDF->RDF_literal($term,$lang, $idc, $prop);
			}	

		/************************************** Section */
		if (isset($reg['dc.type']))		
			{
				$term = (string)$reg['dc.type'];
				$lang = $Language->getTextLanguage($term);
				$idt = $RDF->conecpt($term,'brapci:ProceedingSection');
				echo '=====>'.$idt;
				$RDF->propriety($idc,'brapci:hasSectionOf',$idt);				
			}

		$section = '';
		if (isset($reg['dc.numero.gt']))		
			{
				$section = 'GT'.(string)$reg['dc.numero.gt'];
			}	

		if (isset($reg['dc.titulo.gt']))		
			{
				$section .= ' '.(string)$reg['dc.titulo.gt'];
				$section = trim($section);
			}	
		if (strlen($section) > 0)
			{
				$lang = $Language->getTextLanguage($section);
				$idt = $RDF->conecpt($section,'brapci:ProceedingSection');
				$RDF->propriety($idc,'brapci:hasSectionOf',$idt);				
			}
						
					
		
		$link = '<a href="'.URL.'/res/v/'.$idc.'" target="new'.$idc.'">'.$title.'</a>';
		$sx = '';
		$sx .= $link;
		echo $sx;
		return $sx;
	}
}