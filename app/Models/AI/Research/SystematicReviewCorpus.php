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
		'author', 'title', 'journal',
		'year', 'volume', 'number',
		'pages', 'doi', 'issn',
		'month', 'note', 'eprint',
		'keyword'
	];

	protected $typeFields        = [
		'hidden', 'string:10', 'string:10',
		'text', 'text', 'string:100',
		'string:10', 'string:10', 'string:10',
		'string:100', 'string:100', 'string:10',
		'string:10', 'text', 'string:100',
		'text'
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
			$sql = "update " . $this->table . " set c_duplicata = 1 where id_c = " . $line['max'];
			$this->query($sql);
		}
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
		$sql = "update brapci_ai.SystematicReviews_Corpus 
			set c_status = $st
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
	function btn_recheck($id)
		{
			$sx = '<a href="'.PATH.MODULE.'research/systematic_review/corpus_status/'.$id.'/0" class="btn btn-primary btn-sm">
						recheck
					</a> ';
			return $sx;
		}	

	function btn_exclusion($dt)
		{
			$id = $dt['id_c'];
			$study = $dt['c_study'];

			$SystematicReviewField = new \App\Models\AI\Research\SystematicReviewField();

			$sx = '<span class="btn btn-primary btn-sm">
						Exclusion Criterie
					</span> ';

			$sx .= $SystematicReviewField->exclusion($id, $study);
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
		$dt = $this->find($id);
		$st = $dt['c_status'];
		$tela = $this->show($dt, 'ABNT');
		$tela .= 'Status: '.$st;
		

		switch ($st) {
			case 0:
				$tela .= $this->class_status_0($id, $dt);
				break;

			case 3:
				$tela .= $this->class_status_3($id, $dt);
				$tela1 = $this->btn_edit($id);
				$tela1 .= $this->btn_recheck($id);
				$tela .= bs(bsc($tela1,12));

				/*************/
				$tela1 = $this->btn_exclusion($dt);
				$tela .= bs(bsc($tela1,4));
				break;

			case 4:
				$tela .= $this->class_status_3($id, $dt);
				$tela1 = $this->btn_edit($id);
				$tela1 .= $this->btn_recheck($id);
				$tela .= bs(bsc($tela1,12));

				/*************/
				$tela1 = $this->btn_exclusion($dt);
				$tela .= bs(bsc($tela1,4));
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

		$sx .= $this->list($id, $d4);
		return $sx;
	}
}
