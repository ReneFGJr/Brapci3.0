<?php

namespace App\Models\AI\Research;

use CodeIgniter\Model;

class SystematicReviewCorpus extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci_ai.SystematicReviews_Corpus';
	protected $primaryKey           = 'id_c';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_c', 'c_study', 'id',
		'title', 'author', 'journal',
		'year', 'volume', 'number',
		'pages', 'doi', 'issn',
		'month', 'note', 'eprint',
		'keyword','c_fulltext'
	];

	protected $typeFields        = [
		'hidden', 'string:10', 'string:10',
		'text', 'text', 'string:100',
		'string:10', 'string:10', 'string:10',
		'string:100', 'string:100', 'string:10',
		'string:10', 'text', 'string:100',
		'text','text'
	];	

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

	function check_duplicate($id)
	{
		$sql = "select * from 
					(
						select title, c_study, count(*) as total, max(id_c) as max
						from " . $this->table . "
						where c_study = $id and c_duplicata = 0
						group by title, c_study
					) as tabela
					where total > 1";

		$rlt = $this->query($sql)->getresult();

		for ($r = 0; $r < count($rlt); $r++) {
			$line = (array)$rlt[$r];
			$sql = "update " . $this->table . " set c_duplicata = 1, c_status = 9 where id_c = " . $line['max'];
			$this->query($sql);
		}
	}

	function update_textfull($id,$txt)
		{
			$txt = troca($txt,"'","´");
			$sql = "update " . $this->table . " 
					set c_fulltext = '$txt'
					where id_c = " . $id;

			$this->query($sql);
			return true;
		}

	function autoClass_mth2()
	{
		$tela = '';
		$offset = round(get("offset"));
		$sql = "select * from brapci_ai.SystematicReviews_Corpus
					where c_status = 3 limit 1 offset " . $offset;
		$dt = $this->query($sql)->getresult();

		if (count($dt) > 0) {
			//print_r($dt);
		}
		return $tela;
	}
	function autoClass_mth1()
	{
		$tela = '';
		$offset = round(get("offset"));
		$sql = "select * from brapci_ai.SystematicReviews_Corpus
					where c_status = 0 limit 1 offset " . $offset;
		$dt = $this->query($sql)->getresult();

		if (count($dt) > 0) {
			$line = (array)$dt[0];
			$id = $line['id_c'];
			$this->changeStatus($id, 3);

			/*****************************/
			$tela .= $this->classification($id);
			$tela .= metarefresh(PATH . MODULE . 'research/systematic_review/autoclass', 0);
		}
		return $tela;
	}

	function changeStatus($id, $st)
	{
		$par = '';
		if ($st == 'd')
			{
				$par = ', c_duplicata = 1';
				$st = 9;
			}
		$sql = "update brapci_ai.SystematicReviews_Corpus 
			set c_status = $st
			$par
			where id_c = " . $id;
		$this->query($sql);
	}

	function btn_edit($id)
		{
			$sx = '<a href="'.PATH.MODULE.'research/systematic_review/corpus_edit/'.$id.'" class="btn btn-primary btn-sm">
						editar
					</a> ';
			return $sx;
		}
		function btn_edit_full($id)
		{
			$sx = '<a href="'.PATH.MODULE.'research/systematic_review/corpus_edit_text/'.$id.'" class="btn btn-primary btn-sm">
						editar Full
					</a> ';
			return $sx;
		}		
		function btn_brapci($id)
		{
			$sx = '<a href="'.PATH.MODULE.'research/systematic_review/brapci_api/'.$id.'" class="btn btn-primary btn-sm">
						Brapci Search
					</a> ';
			return $sx;
		}
		function btn_google($dt)
		{
			$title = htmlentities($dt['title']);

			$url = 'https://scholar.google.com.br/scholar?hl=pt-BR&as_sdt='.$title;			
			$sx = '<a href="'.$url.'" target="new_'.date("Hmis").'" class="btn btn-primary btn-sm">
						Google Academic
					</a> ';
			return $sx;
		}				
	function btn_recheck($id)
		{
			$sx = '<a href="'.PATH.MODULE.'research/systematic_review/corpus_status/'.$id.'/0" class="btn btn-primary btn-sm">
						recheck
					</a> ';
			return $sx;
		}	

	function btn_duplicate($id)
		{
			$sx = '<a href="'.PATH.MODULE.'research/systematic_review/corpus_status/'.$id.
					'/d" class="btn btn-primary btn-sm">
						duplicate
					</a> ';
			return $sx;
		}		

	function btn_status($id)
		{
			$sx = '<a href="'.PATH.MODULE.'research/systematic_review/corpus_status/'.$id.
					'/1" class="btn btn-primary btn-sm">
						inclusion
					</a> ';
			return $sx;
		}	

	function btn_url($dt)
		{
			$sx = '';
			$url = trim($dt['url']);
			$url2 = trim($dt['eprint']);
			if (strlen($url2) > 0)
				{
					$url = $url2;
					$url = trim(troca($url,'http://http','http'));
					$url = trim(troca($url,'[',''));
					$url = trim(troca($url,']',''));
				}

			if ($dt['c_brapci'] > 0)
				{
					$url = 'https://brapci.inf.br/index.php/res/v/'.$dt['c_brapci'];
				}

			if (strlen($url) > 0)
				{					
					$sx = '<a href="'.$url.'" target="_blank" class="btn btn-primary btn-sm">
								URL
							</a> ';

				} 
			return $sx;
		}		

	function btn_exclusion($dt)
		{
			$id = $dt['id_c'];
			$study = $dt['c_study'];

			$SystematicReviewField = new \App\Models\AI\Research\SystematicReviewField();

			$sx = '<span class="h5 text-primary">
						'.lang('ai.ExclusionCriterie').'
					</span> ';
			$sx .= '<br>';
			$sx .= $SystematicReviewField->exclusion($id, $study);
			return $sx;
		}

	function btn_inclusion($dt)
		{
			$id = $dt['id_c'];
			$study = $dt['c_study'];

			$SystematicReviewField = new \App\Models\AI\Research\SystematicReviewField();

			$sx = '<span class="h5 text-primary">
						'.lang('ai.InclusionCriterie').'
					</span> ';
			$sx .= '<br>';
			$sx .= $SystematicReviewField->inclusion($id, $study);
			return $sx;
		}		

	function btn_fulltext($dt)
		{
			$sx = '';
			$tx = $dt['c_fulltext'];
			if (strlen($tx) > 0)
			{
				$tx = troca($tx,'.'.chr(13),'¢');
				$tx = troca($tx,chr(13),' ');
				$tx = troca($tx,'¢','<br><br>');

				$sx = '<span class="btn btn-primary btn-sm">
							Texto Completo
						</span> ';

				

				$sx .= '<div id="fulltext">';
				$sx .= $tx;
				$sx .= '</div>';
			} 
			return $sx;
		}		

	function edit($id)
		{
			$this->path = PATH.MODULE.'research/systematic_review/corpus_edit/'.$id;
			$this->path_back = PATH.MODULE.'research/systematic_review/corpusId/'.$id;
			$this->id = $id;
			$tela = form($this);
			return $tela;
		}

	function classification($id)
	{
		$ContentAnalysis = new \App\Models\AI\Research\ContentAnalysis();
		$dt = $this->find($id);
		$st = $dt['c_status'];
		$tela = $this->show($dt, 'ABNT');
		$tela .= 'Status: '.$st;
		

		switch ($st) {
			case 0:
				$tela .= $this->class_status_0($id, $dt);
				$tela1 = $this->btn_edit($id);
				$tela1 .= $this->btn_recheck($id);							
				$tela1 .= $this->btn_duplicate($id);				
				
				$tela1 .= $ContentAnalysis->btn_ContentAnalysis($id);
				$tela1 .= $this->btn_url($dt);
				$tela1 .= $this->btn_fulltext($dt);				

				$tela1 .= '<hr>';
				$tela1 .= $this->btn_brapci($id);
				$tela1 .= $this->btn_google($dt);

				/*************/
				$tela2 = '<hr>';
				$tela2 .= bsc($this->btn_exclusion($dt),6);
				$tela2 .= bsc($this->btn_inclusion($dt),6);

				$tela .= bs(bsc($tela1,12).$tela2);
				break;

			case 1:
				$tela1 = '';
				$tela1 .= $ContentAnalysis->btn_ContentAnalysis($id);
				$tela1 .= $this->btn_url($dt);
				$tela1 .= $this->btn_edit($id);
				$tela2 = '<hr>';				
				$tela2 .= bsc($this->btn_exclusion($dt),6);
				$tela2 .= bsc($this->btn_inclusion($dt),6);

				$tela .= bs(bsc($tela1,12).$tela2);
				break;

			case 3:
				$tela .= $this->class_status_3($id, $dt);
				$tela1 = $this->btn_edit($id);
				$tela1 .= $this->btn_edit_full($id);
				$tela1 .= $this->btn_url($dt);
				$tela1 .= $this->btn_recheck($id);
				$tela1 .= $this->btn_duplicate($id);
				$tela1 .= $this->btn_google($dt);

				/*************/
				$tela2 = '<hr>';
				$tela2 .= bsc($this->btn_exclusion($dt),6);
				$tela2 .= bsc($this->btn_inclusion($dt),6);
				

				$tela .= bs(bsc($tela1,12).$tela2);
				break;

			case 4:
				$tela .= $this->class_status_3($id, $dt);
				$tela1 = $this->btn_edit($id);
				$tela1 .= $this->btn_recheck($id);
				$tela1 .= $this->btn_duplicate($id);
				$tela .= bs(bsc($tela1,12));

				/*************/
				$tela1 = $this->btn_exclusion($dt);
				
				$tela .= bs(bsc($tela1,12));
				break;				

			default:
				$tela .= 'Status: ' . $st;
		}
		return $tela;
	}

	function class_status_3($id, $dt)
	{

	}

	function class_status_0($id, $dt)
	{
		$tela = '';
		$SystematicReviewField = new \App\Models\AI\Research\SystematicReviewField();
		$SystematicReviewField->check();

		$ArticleBusca = new \App\Models\Brapci\ArticleBusca();
		$rdfid = $ArticleBusca->search($dt['title']);
		if ($rdfid == 0)
			{
				$rdfid = $ArticleBusca->search_word($dt['title']);
			}

		if ($rdfid > 0) {
			$tela .= bsmessage('Artigo localizado ' . $rdfid, 1);
			$sql = "update brapci_ai.SystematicReviews_Corpus 
								set c_brapci = $rdfid, 
								c_status = 1
								where id_c = " . $dt['id_c'];
			$this->query($sql);
		}
		return $tela;
	}

	function show($dt, $ref = '')
	{
		$ref = mb_strtolower($ref);
		switch ($ref) {
			case 'abnt':
				$ABNT = new \App\Models\Metadata\Abnt();
				$tela = bs(bsc($ABNT->show($dt),12));
				break;
			default:
				$tela = '<hr>';
				$tela .= $dt['author'];
				$tela .= '<hr>';
				$tela .= $dt['title'];
				$tela .= '<hr>';
				$tela .= $dt['journal'];
				$tela .= '<hr>';
				$tela .= $dt['volume'];
				$tela .= '<hr>';
				$tela .= $dt['issn'];
				$tela .= '<hr>';
				$tela .= $dt['number'];
				$tela .= '<hr>';
				$tela .= $dt['doi'];
				$tela .= '<hr>';
				$tela .= '<a href="' . ($dt['eprint']) . '">' . ($dt['eprint']) . '</a>';
				$tela .= '<hr>';
				$tela .= $dt['url'];
				$tela .= '<hr>';
				break;
		}
		return $tela;
	}
	function list($id, $st = 0)
	{
		if ($st == 'd') {
			$rlt = $this->where('c_study', $id)
				->where('c_duplicata', 1)
				->orderBy('title, c_duplicata')
				->findAll();
		} else {
			$rlt = $this->where('c_study', $id)
				->where('c_status', $st)
				->orderBy('title, c_duplicata')
				->findAll();
		}
		$sx = '';
		$sx .= '<ol>';
		for ($r = 0; $r < count($rlt); $r++) {
			$c = '<span>';
			$ca = '</span>';

			$line = (array)$rlt[$r];
			if ($line['c_duplicata']) {
				$c = '<span style="text-decoration:line-through; color: #DDD;">';
			}
			$url = PATH . MODULE . 'research/systematic_review/corpusId/' . $line['id_c'];
			$link = onclick($url, '1024', '800');
			$sx .= '<li>' . $link . $c . $line['title'] . '</a>';
			$sx .= '. <b>' . $line['journal'] . '</b>';
			$sx .= ', ' . $line['year'];
			$sx .= $ca . '</li>';
		}
		$sx .= '</ol>';
		return $sx;
	}

	function view($id, $d4)
	{
		$this->check_duplicate($id);
		$sql = "select count(*) as total, c_duplicata from " . $this->table . " where c_study = " . $id . " group by c_duplicata";
		$rlt = $this->query($sql)->getresult();
		$dup = 0;
		for ($r = 0; $r < count($rlt); $r++) {
			$line = (array)$rlt[$r];
			if ($line['c_duplicata'] == 1) {
				$dup = $line['total'];
			}
		}
		$sx = '';
		$link = '<a href="' . PATH . MODULE . 'research/systematic_review/viewid/' . $id . '/d">';
		$linka = '</a>';
		$sx .= bsc('<span class="supersmall">' . lang('ai.sr_status_dp') . '</span>' . $link . h($dup, 3) . $linka, 2);

		/*********************************************************************** */
		$sql = "select count(*) as total, c_status from " . $this->table . " where c_study = " . $id . " group by c_status";
		$rlt = $this->query($sql)->getresult();
		$n = array(0, 0, 0, 0, 0);
		for ($r = 0; $r < count($rlt); $r++) {
			$line = (array)$rlt[$r];
			if ($line['c_status'] == 0) {
				$n[0] = $line['total'];
			}
			if ($line['c_status'] == 1) {
				$n[1] = $line['total'];
			}
			if ($line['c_status'] == 2) {
				$n[2] = $line['total'];
			}
			if ($line['c_status'] == 3) {
				$n[3] = $line['total'];
			}
			if ($line['c_status'] == 4) {
				$n[4] = $line['total'];
			}
		}

		for ($r = 0; $r < count($n); $r++) {
			$link = '<a href="' . PATH . MODULE . 'research/systematic_review/viewid/' . $id . '/' . $r . '">';
			$linka = '</a>';
			$sx .= bsc('<span class="supersmall">' . lang('ai.sr_status_' . $r) . '</span>' . $link . h($n[$r] . $linka, 3), 2);
		}
		$sx = bs($sx);

		$sx .= 'Actions:';
		$sx .= '<ul>';
		$sx .= anchor(PATH.MODULE.'research/systematic_review/autoBrapci','Identificar na Brapci');
		$sx .= '</ul>';

		$sx .= $this->list($id, $d4);
		return $sx;
	}
}
