<?php

namespace App\Models\Journal;

use CodeIgniter\Model;

class Articles extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'articles';
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


	/******************************************************************* GERA ARQUIVO DE TEXTO */
	function view_articles($id)
	{		
		$RDF = new \App\Models\RDF\RDF();
		$PDF = new \App\Models\PDF\PDF();
		$RDFData = new \App\Models\RDF\RDFData();
		$dt = $RDF->le($id);
		$dados = $RDFData->view_data($dt);

		$tela = '';
		$telax = '';
		$data = $dt['data'];
		$d = array();
		$url = '';

		for ($r = 0; $r < count($data); $r++) {
			$line = $data[$r];
			$class = trim($line['c_class']);
			$txt = $line['n_name'];
			$lang = $line['n_lang'];
			if (strlen(trim($lang)) == 0) {
				$lang = 'pt-BR';
			}
			switch ($class) {
				case 'hasPageStart':
					/* none */
					break;
				case 'hasPageEnd':
					/* none */
					break;
				case 'isPubishIn':
					/* none */
					break;
				case 'prefLabel':
					/* none */
					break;
				case 'hasId':
					/* none */
					break;
				case 'hasSectionOf':
					/* none */
					break;
				case 'hasAuthor':
					$txt = $RDF->le_content($line['d_r2']);
					$d['author'][$txt] = $line['d_r2'];
					break;
				case 'hasTitle':
					$d['title'][$lang] = $txt;
					break;
				case 'hasAbstract':
					$d['abs'][$lang] = $txt;
					break;
				case 'hasUrl':
					$url .= '<a href="' . $txt . '" class="btn btn-warning m-2 p-2">URL</a> ';
					break;
				case 'hasSubject':
					$txt = $RDF->le_content($line['d_r2']);
					if (!isset($d['subject'][$lang])) {
						$d['subject'][$lang] = array();
					}
					array_push($d['subject'][$lang], '<a href="' . $line['d_r2'] . '" class="link-primary p-2">' . trim($txt).'.</a>');
					break;
				default:
					$telax .= $class . '==>' . $txt . '==>' . $line['d_r2'] . '--<br>';
					break;
			}
		}

		/* CHECK */
		if (isset($d['title'])) {
			$IA_title = new \App\Models\AI\Title();

			if (isset($d['title']['pt-BR'])) {
				$title = $d['title']['pt-BR'];
			}
			$IA_title->check($d['title'], $id);
		}

		/********************************************************************* AUTHOR ****/
		$auth = '';
		foreach ($d['author'] as $name => $idx) {
			$link = '<a href="' . base_url(PATH . 'res/v/' . $idx) . '" class="form-control h5 link-primary">';
			$linka = '<a>';
			$auth .= $link . $name . $linka;
		}

		/********************************************************************* MOSTRA *****/
		/**********************************************************************************/
		/**********************************************************************************/
		$pref = array('pt-BR', 'es', 'en');
		$cl = 'h3';
		
		$tela .= '<div class="row">';
		for ($r = 0; $r < count($pref); $r++) {
			$lg = $pref[$r];

			/********************************************************************* TITLE ****/
			if (isset($d['title'][$lg])) {
				//p-1 m-1 px-2 mt-6
				$tela .= '<div class="text-center mt-6 ' . $cl . ' ">' . $d['title'][$lg] . '</div>';
				$cl = ' h4 fst-italic';
			}

			/********************************************************************* URL ****/
			if (strlen($url) > 0) {
				$tela .= '<div class="url">' . $url . '</div>';
				$url = '';
			}


			if (strlen($auth) > 0) {
				$tela .=  '<div class="author text-end row">' . $auth . '</div>';
				$auth = '';
			}

			/********************************************************************* ABSTRACT **/
			$pref = array('pt-BR','es','en','es-ES');

			$lg = $pref[$r];
			if (isset($d['abs'][$lg])) {
				$tela .=
					'<div class="abs_title fw-bold">' . lang('brapci.abstract_' . $lg) . '</div>' .
					'<div class="title text-justify m-1 p-2">'.
						$d['abs'][$lg].
					'</div>';					
				
				/***************************************************************** SUBJECT ****/
				$subj = '';
				if (isset($d['subject'][$lang])) {
					for ($z = 0; $z < count($d['subject'][$lang]); $z++) {
						$key = $d['subject'][$lang][$z];
						$subj .= $key;
					}
					if (strlen($subj)) {
						$tela .= '<div class="keyword p-1 m-1">' .
							'<span class="abs_keyword fw-bold">'.lang('brapci.keyword_' . $lang) . '</span>'.
							': ' .
							$subj . '</div>';
					}
				}
			}
		}
		$tela .= '</div>';

		/***************************************************************************************/		
		$right_side = $PDF->Download($id);
		$tela = bs(bsc($tela,11).bsc($right_side,1,'mt-6'));

		$tela .= $dados;
		$tela .= $telax;
		
		$tela .= '<style> div { border: 1px solid #000; }</style>';

		/************ PDF */
		return $tela;
	}
}
