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
	function view_articles($id) /* OK */
	{		
		$RDF = new \App\Models\Rdf\RDF();
		$PDF = new \App\Models\PDF\PDF();
		$RDFData = new \App\Models\Rdf\RDFData();
		$dt = $RDF->le($id);
		$dados = $RDFData->view_data($dt);

		$parts = array();
		$telax = '';

		$data = $dt['data'];
		$right_side = '';
	
		$d = array();
		$d['section'] = '';
		$d['issue'] = 'Número da edição';
		$url = '';

		for ($r = 0; $r < count($data); $r++) {
			$line = $data[$r];
			$class = trim($line['c_class']);
			$txt = trim($line['n_name']);
			$lang = trim($line['n_lang']);

			if ((strlen(trim($lang)) == 0) or ($lang=='0')) {
				$lang = 'pt-BR';
			}
			switch ($class) {
				case 'hasSectionOf':
					$sec = $RDF->c($line['d_r2']);
					if (strlen($d['section']) > 0) { $d['section'] .= ' - '; }
					$d['section'] .= $sec;
					break;
				case 'hasIssueOf':
					$issue = $RDF->c($line['d_r1']);
					$issue = anchor(URL.'res/v/'.$line['d_r1'],$issue);
					$d['issue'] = $issue;
					break;	
				case 'hasIssueProceedingOf':
					$issue = $RDF->c($line['d_r1']);
					$issue = anchor(URL.'res/v/'.$line['d_r1'],$issue);
					$d['issue'] = $issue;
					break;									
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
				case 'hasFileStorage':
					$pdf = $line['n_name2'];
					$right_side = $PDF->pdf_download($line);
					break;

				case 'hasAuthor':
					$txt = $RDF->c($line['d_r2']);
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
					//$txt = $RDF->le_content($line['d_r2']);
					$txt = $RDF->le($line['d_r2']);
					$lang = $txt['concept']['n_lang'];
					$name = $txt['concept']['n_name'];
					if (!isset($d['subject'][$lang])) {
						$d['subject'][$lang] = array();
					}
					$keyw = '<a href="' . URL . 'res/v/' . $line['d_r2'] . '" class="rounded-pill me-1" 
								style="padding: 1px 6px;
								background-color: #ddd;
								color: #000;"
								>' . trim($name).'</a>&nbsp;';
					array_push($d['subject'][$lang], $keyw);
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
		if (isset($d['author']))
		{
			foreach ($d['author'] as $name => $idx) {
				$link = '<a href="' . base_url(PATH . 'res/v/' . $idx) . '" 
						class="form-control h5 link-secondary" >';
				$linka = '<a>';
				$auth .= $link . $name . $linka;
			}
		}

		/********************************************************************* MOSTRA *****/
		/**********************************************************************************/
		/**********************************************************************************/

		/***************************************************************************************/		


		$SubHeaders = new \App\Models\Brapci\SubHeaders();
		$dt['section'] = $d['section'];
		$top = $SubHeaders->headers($dt);
		$parts[0] = $top;

		/************************************************************** Title and Abstract  */
		$tit = '';
		$abs = '';

		$pref = array('pt-BR', 'es', 'es-ES', 'en');
		$cl = 'h3';		

		for ($r = 0; $r < count($pref); $r++) {
			$lg = $pref[$r];

			/********************************************************************* TITLE ****/
			if (isset($d['title'][$lg])) {
				//p-1 m-1 px-2 mt-6
				$tit .= '<div class="text-center mt-6 ' . $cl . ' ">' . $d['title'][$lg] . '</div>';
				$cl = ' h4 fst-italic';
			}

			/********************************************************************* URL ****/
			if (strlen($url) > 0) {
				$tit .= '<div class="url">' . $url . '</div>';
				$url = '';
			}
		}

		if (strlen($auth) > 0) {
				$tit .=  bsc($auth,12,'author text-end');
				$auth = '';
			}
		
			
			/********************************************************************* ABSTRACT **/
		for ($r = 0; $r < count($pref); $r++) 
		{
			$lg = $pref[$r];
			
			if (isset($d['abs'][$lg])) {
				$lang = ' <sup>('.$lg.')</sup>';
				$abs .=
					bsc(lang('brapci.abstract_' . $lg),12,'abs_title fw-bold').
					bsc('<p style="text-align: justify; text-justify: inter-word;">'.$d['abs'][$lg].$lang.'</p>',12);
				
				/***************************************************************** SUBJECT ****/
				$subj = '';
				$abs .= '<style> .keywords { line-height: 150%; } </style>'.cr();

				if (isset($d['subject'][$lg])) {
					for ($z = 0; $z < count($d['subject'][$lg]); $z++) {
						$key = $d['subject'][$lg][$z];
						$subj .= $key.cr();
					}
					if (strlen($subj)) {
						$abs .= bsc(
							'<span class="abs_keyword fw-bold">'.lang('brapci.keyword_' . $pref[$r]) . '</span>'.
							': ' .cr() .
							$subj,12,'mb-5 keywords');
					}					
				}
				
			}
		}

		/********************************* CITED */
		$cited = new \App\Models\Brapci\Cited();
		$cites = $cited->show($id);

		/*************************************************** Screen */				
		//$parts[0] = $d['issue'];
		$parts[1] = $tit;
		$parts[2] = $abs;
		$parts[3] = $cites;
		$sx = '';

		$sxh = bs(bsc($parts[0],12),array('fluid'=>'1','class'=>'text-center'));
		$sx .= bsc(h($d['issue'],6),12);
		$sx .= bsc($parts[1],12);
		
		$sx .= bsc($parts[2],11);
		$sx .= bsc($right_side,1);

		$sx .= bsc($parts[3],12);

		$sx = $sxh.bs($sx);
		return $sx;
	}
}
