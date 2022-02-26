<?php

namespace App\Models\Journal;

use CodeIgniter\Model;

class JournalIssue extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci.source_issue';
	protected $primaryKey           = 'id_is';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_is', 'is_source', 'is_source_rdf',
		'is_source_issue', 'is_year', 'is_issue',
		'is_vol', 'is_nr', 'is_place',
		'is_edition', 'is_thema', 'is_cover',
		'is_url_oai', 'is_works'
	];
	var $typeFields        = [
		'hidden', 'sql:id_jnl:jnl_name:brapci.source_source', 'hidden',
		'string:10', 'year', 'string:10',
		'string:10', 'string:10', 'string:10',
		'none', 'string:100', 'none',
		'string:100', 'none', 'none'
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

	function MOD()
	{
		$url = $_SERVER['HTTP_REFERER'];
		if (strpos($url, 'proceeding') > 0) {
			$MOD = 'proceeding';
		} else {
			$MOD = 'journal';
		}
		return $MOD;
	}

	function join_issue($issue1,$issue2)
		{
			$RDFData = new \App\Models\Rdf\RDFData();
			$RDFData->change($issue1,$issue2);
			$RDFData->remove($issue2);
			$sx = bsmessage('brapci.joined',1);
			$sx .= '<a href="'.(PATH.MODULE.'/v/'.$issue1).'">'.lang('brapci.return').'</a>';
			$sx = bs(bsc($sx,12));

			$this->where('is_source_issue',$issue2)->delete();
			return $sx;			
		}
	function issue_trash($idx,$act='')
		{
			$sx = h('brapci.issue.trash',1);
			$dt = $this->find($idx);
			$ok = false;

			if ((isset($dt['is_source_issue'])) and ($dt['is_source_issue'] > 0))
				{					
					$sx .= 'Datas';
					$id_rdf = $dt['is_source_rdf'];
					$RDF = new \App\Models\Rdf\RDF();
					$dtrdf = $RDF->le($id_rdf);
					echo '<pre>';
					print_r($dtrdf);		
				} else {
					$sx .= '<a href="'.PATH.MODULE.'v/'.$dt['is_source_rdf'].'" class="btn btn-outline-warning">'.lang('brapci.return')."</a>";
					$ok = true;
				}

				if ($ok == true)
					{
						if ($act == 'confirm')
							{
								$sx .= bsmessage('brapci.issue_exclued',1);
								$this->where('id_is',$idx)->delete();
							} else {
								$sx .= ' | ';
								$sx .= '<a href="'.PATH.MODULE.'admin/issue/delete/'.$idx.'/confirm'.'" class="btn btn-outline-danger">'.lang('brapci.delete')."</a>";		
							}
					}

				
				$sx = bs(bsc($sx,12));
			return $sx;			
		}

	function view_issue($idx = 0)
	{
		$MOD = $this->MOD();
		$this->where('is_source_rdf', $idx);
		$this->orderBy('is_year desc, is_vol, is_nr');
		$dt = $this->FindAll();

		if (count($dt) == 0) {
			$sx = '';
			$sx .= bs(bsc(lang('brapci.empty_issue'), 12));
			$sx .= bs(bsc($this->btn_check_issues($idx), 12));
			return $sx;
		}

		$sx = bsc(h(lang('brapci.issue_list'), 2, 'p-5'), 12);
		$xyear = '';
		for ($r = 0; $r < count($dt); $r++) {
			$dtx = $dt[$r];

			$link0 = '<a href="' . (PATH . 'res/v/' . $dtx['is_source_issue']) . '">';
			$link1 = '<a href="' . (PATH . 'res/admin/' . $MOD . '/edit_issue/' . $dtx['id_is']) . '">';
			if (strlen($dtx['is_url_oai']) > 0) {
				$link2 = '<a href="' . PATH . 'res/admin/' . $MOD . '/issue_harvesting/' . $dtx['id_is'] . '">';
			} else {
				$link2 = '';
			}
			$link3 = '<a href="' . PATH . 'res/admin/issue/delete/' . $dtx['id_is'] . '">';

			$linka = '</a>';

			if ($dtx['is_source_issue'] <= 0) {
				$link0 = '<a name="tag">';
			}

			$year = $dtx['is_year'];
			if ($year == $xyear) {
				$sz = 6;
			} else {
				$xyear = $year;
				$sz = 3;
			}
			$sx .= bsc(h($link0 . $year . $linka, $sz), 1, 'text-end');
			$sx .= bsc($link0 . $dtx['is_nr'] . ' ' . $dtx['is_vol'] . $linka, 3);
			$sx .= bsc($link0 . $dtx['is_place'] . $linka, 3);
			$sx .= bsc($link0 . $dtx['is_thema'] . $linka, 4);

			$ed = $link1 . bsicone('edit', 24) . $linka;
			$ed .= ' ';
			if (strlen($link2) > 0) {
				$ed .= $link2 . bsicone('harversting', 24) . $linka;
			}
			if (strlen($link3) > 0) {
				$ed .= $link3 . bsicone('trash', 24) . $linka;
			}

			$sx .= bsc($ed, 1, 'text-end');
			$sx .= bsc('<hr>', 12);

			//'p-2 m-1 shadown bordered bw'
		}
		//$sx .= '<style> div { border: 1px solid #000000; } </style>';
		$sx .= bsc($this->btn_check_issues($idx), 12);
		$sx = bs($sx);
		return $sx;
	}

	function harvesting_oaipmh($id)
	{
		$sx = '';
		$id = round($id);
		$dt = $this->Find($id);

		$url = trim($dt['is_url_oai']);

		if (strlen($url) > 0) {

			$Oaipmh = new \App\Models\Oaipmh\Oaipmh();
			//$sx = $Oaipmh->index('get_proceedings', $dt['id_is']);

			$Journal = new \App\Models\Journal\Journals();
			$dj = $Journal->find($dt['is_source']);
			$sx .= $Journal->journal_header($dj);

			$action = get("action");
			if ($action == 'harvesting')
			{
				/* Identifica lista de registros */
				$OaipmhListSetSepc = new \App\Models\Oaipmh\OaipmhListSetSepc();
				$sx .= $OaipmhListSetSepc->harvesting($dt, 'EV');

				/* Identifica lista de artigos */
				$OaipmhListRecord = new \App\Models\Oaipmh\OaipmhListRecord();
				$sx .= $OaipmhListRecord->harvesting($dt, 'EV');
			} else {
				$sx .= '<a href="'.PATH.MODULE.'admin/journal/issue_harvesting/'.$id.'?action=harvesting" class="bnt btn-outline-primary">'.bsicone('harversting',24).'</a>';
			}

			$this->update_issue($id);
		} else {
			$sx = bsmessage(lang("brapci.no_url_oai"), 3);
		}
		$sx = bs(bsc($sx, 12));
		return $sx;
	}

	function header($dt)
	{
		$name = $dt['concept']['n_name'];
		$name = bs(bsc(h($name, 5), 12, 'border border-dark text-center mb-5'));
		return $name;
	}

	function edit($reg, $idj = 0)
	{
		$MOD = $this->MOD();
		if ($MOD == 'proceeding') {
			$source = 'sql:id_jnl:jnl_name:brapci.source_source where jnl_collection = \'EV\' order by jnl_name';
		} else {
			$source = 'sql:id_jnl:jnl_name:brapci.source_source where jnl_collection = \'JA\' order by jnl_name';
		}
		if ($reg > 0)
			{
				$source = 'sql:id_jnl:jnl_name:brapci.source_source order by jnl_name';
			}
		$this->typeFields[1] = $source;

		$sx = h(lang('Editar'), 1);
		$this->id = $reg;

		if ($reg > 0) {
			$this->path_back = (PATH . MODULE . 'admin/' . $MOD . "/viewid/" . get('is_source'));
		}

		$this->path = (PATH . MODULE . 'admin/' . $MOD . '/edit_issue/' . $reg . '/' . $idj);

		$sx .= form($this);
		$sx = bs(bsc($sx, 12));

		$this->update_check();
		return $sx;
	}

	function update_check()
	{
		$Journal = new \App\Models\Journal\Journals();
		$dt = $this->where('is_source_rdf', 0)->FindAll();
		for ($r = 0; $r < count($dt); $r++) {
			$line = $dt[$r];
			$dj = $Journal->find($line['is_source']);
			$id_jnl = $dj['jnl_frbr'];

			$this->set('is_source_rdf', $id_jnl);
			$this->where('id_is', $line['id_is'])->update();
		}
	}

	function ArticlesIssue($id)
	{
		$sx = '';
		$RDF = new \App\Models\Rdf\RDF();
		$dt = $RDF->le($id);
		$args = array();

		$tps = array('hasIssueOf', 'hasIssueProceedingOf');

		

		for ($q = 0; $q < count($tps); $q++) {
			$art = $RDF->recover($dt, $tps[$q]);
			if (count($art) > 0)
			{
				$sx .= h(lang('brapci.articles_issue').': '.count($art).' '.lang('brapci.works'),6);
			}
			for ($r = 0; $r < count($art); $r++) {
				$d = $art[$r];
				$sx .= $RDF->c($d);
				$sx .= '<hr>';
			}
		}
		$sx = bs(bsc($sx, 12));
		return $sx;
	}

	function check_issue($id_rdf)
	{
		$sx = '';
		$RDF = new \App\Models\Rdf\RDF();
		$Journal = new \App\Models\Journal\Journals();
		echo h($id_rdf);

		$dt = $RDF->le($id_rdf);

		$stf = array('hasIssue', 'hasIssueProceeding');
		$issue = array();
		for ($r = 0; $r < count($stf); $r++) {
			$tmp_issue = $RDF->recover($dt, $stf[$r]);
			$issue = array_merge($issue, $tmp_issue);
		}

		for ($z = 0; $z < count($issue); $z++) {
			$idx = $issue[$z];
			$di = $this->where('is_source_issue', $idx)->findAll();
			if (count($di) == 0) {
				$issue_rdf = $RDF->le($idx);

				$issue_name = trim($issue_rdf['concept']['n_name']);
				if ($issue_name != 'ISSUE:') {
					$dtj = $Journal->where('jnl_frbr', $id_rdf)->findAll();

					/************************************************ VOL */
					$nr = $RDF->recover($issue_rdf, 'hasPublicationNumber');
					if (count($nr) > 0) {
						$nr = $RDF->c($nr[0]);
					} else {
						$nr = '';
					}
					$vl = $RDF->recover($issue_rdf, 'hasPublicationVolume');
					if (count($vl) > 0) {
						$vl = $RDF->c($vl[0]);
					} else {
						$vl = '';
					}

					$year = $RDF->recover($issue_rdf, 'dateOfPublication');
					if (count($year) == 0) {
						$year = sonumero($issue_name);
						$year = substr($year, strlen($year) - 4, 4);
					} else {
						$year = $RDF->c($year[0]);
					}
					$year = round($year);
					if ((isset($dtj[0])) and ($year > 1950)) {
						$dtj = $dtj[0];
						$dt = array();
						$dt['is_source'] = $dtj['id_jnl'];
						$dt['is_source_rdf'] = $id_rdf;
						$dt['is_source_issue'] = $idx;
						$dt['is_year'] = $year;
						$dt['is_issue'] = '';
						$dt['is_vol'] = $vl;
						$dt['is_nr'] = $nr;
						$dt['is_place'] = '';
						$dt['is_edition'] = '';
						$dt['is_cover'] = '';
						$dt['is_url_oai'] = '';
						$this->insert($dt);
						$sx .= bsmessage('Issue: ' . $year . ' ' . $nr . ' ' . $vl . ' Insered', 1);
					}
				}
			} else {
				$sx .= '<p>Já existe (' . $RDF->c($idx) . ')</p>';
			}
		}
		return $sx . h('Exported', 4);
	}

	function btn_check_issues($id)
	{
		$Social = new \App\Models\Socials();
		$sx = '';
		if ($Social->perfil("#ADM")) {
			$url = (PATH . 'res/admin/issue/check/' . $id);
			$sx = onclick($url, 800, 200, 'btn btn-outline-primary') . lang('brapci.check_issues') . '<span>';
			//$sx = '<a href="'.$url.'" class="btn btn-outline-primary">'.lang('journal_check_issue').'</a>';
		}
		return $sx;
	}

	function btn_new_issue($dt)
	{
		$MOD = $this->mod();

		$id_rdf = $dt['jnl_frbr'];
		$id = $dt['id_jnl'];
		$url = (PATH . MODULE . 'admin/' . $MOD . '/edit_issue/0/' . $id);
		$sx = '<a href="' . $url . '" class="btn btn-outline-primary">' . lang('journal_issue_new') . '</a>';

		/************************************************ JOURNAL */
		if ($MOD == '/journal') {
			$sx .= ' ';
			$url = (PATH . MODULE . '/index/' . $MOD . '/harvesting/' . $id);
			$sx .= '<a href="' . $url . '" class="btn btn-outline-primary">' . lang('journal_issue_harvesting') . '</a>';

			$sx .= ' ';
			$url = (PATH . MODULE . '/index/inport_rdf/' . $id);
			$sx .= '<a href="' . $url . '" class="btn btn-outline-primary">' . lang('journal_issue_import') . '</a>';
		}
		return $sx;
	}
	function update_issue($id)
	{
		$OaipmhListRecord = new \App\Models\Oaipmh\OaipmhListRecord();
		$dts = $OaipmhListRecord
			->where('lr_issue', $id)
			->findAll();
		$total = count($dts);

		$this->set('is_works', $total, true)->where('id_is', $id);
		$this->update();
		return '';
	}

	function view_issue_articles($id)
	{
		$sx = '';
		$RDF = new \App\Models\Rdf\RDF();
		$dt = $RDF->le($id, 0, 'brapci');

		$dtd = $dt['data'];
		$vol = '';
		$nr = '';
		$year = '';
		$journal = '';
		for ($r = 0; $r < count($dtd); $r++) {
			$dtl = $dtd[$r];
			$class = trim($dtl['c_class']);
			$value = trim($dtl['n_name']);
			$id1 = $dtl['d_r1'];
			$id2 = $dtl['d_r2'];
			switch ($class) {
				case 'dateOfPublication':
					$year = $RDF->le_content($id2);
					break;
				case 'hasPublicationVolume':
					$vol = $RDF->le_content($id2);
					break;
				case 'hasPublicationNumber':
					$nr = $RDF->le_content($id2);
					break;
				case 'hasIssueOf':
					//$sx .= bsc(bscard('',$RDF->content($id2)),12,'m-1');
					break;
				case 'hasIssue':
					$journal = $RDF->le_content($id2);
					break;
				case 'altLabel':
					break;
				case 'prefLabel':
					$IssueName = $value;
					break;
				default:
					$sx .= '<br>' . $class . '==>' . $value . '==' . $id1 . '==' . $id2;
					break;
			}
		}
		$sx = h($journal . ', ' . $nr . ', ' . $vol . ', ' . $year, 5) . $sx;
		$sx .= $IssueName;
		$sx = bs($sx);
		return $sx;
	}

	function inport_rdf($id)
	{
		$sx = '';
		$Journals = new \App\Models\Journal\Journals();
		/********************************** RECUPERA DADOS */
		$dt = $Journals->find($id);

		$id = $dt['id_jnl'];
		$id_rdf = $dt['jnl_frbr'];

		$RDF = new \App\Models\Rdf\RDF();
		$dtr = $RDF->le($id_rdf);

		if (isset($dtr['data'])) {
			$data = $dtr['data'];
			$ids = array();
			for ($r = 0; $r < count($data); $r++) {
				$ln = $data[$r];
				if ($ln['c_class'] == 'hasIssue') {
					$id_issue = $ln['d_r1'];
					$dti = $this->where('is_source_issue', $id_issue)->FindAll();
					if (count($dti) == 0) {
						$ddd = $RDF->le($id_issue);
						$vol = $RDF->get_content($ddd, 'hasPublicationVolume');
						$nur = $RDF->get_content($ddd, 'hasPublicationNumber');
						$year = $RDF->get_content($ddd, 'dateOfPublication');
						if (isset($vol[0])) {
							$vol = $RDF->get_literal($vol[0]);
						} else {
							$vol  = '';
						}
						if (isset($nur[0])) {
							$num = $RDF->get_literal($nur[0]);
						} else {
							$num  = '';
						}
						if (isset($year[0])) {
							$year = $RDF->get_literal($year[0]);
						} else {
							$year = '';
						}

						$dt = array();
						$dt['is_source'] = $id;
						$dt['is_source_rdf'] = $id_rdf;
						$dt['is_source_issue'] = $id_issue;
						$dt['is_year'] = $year;
						$dt['is_issue'] = $xxx;
						$dt['is_vol'] = $vol;
						$dt['is_nr'] = $num;
						$dt['is_place'] = '';
						$dt['is_edition'] = '';
						$dt['is_cover'] = '';
						$dt['is_url_oai'] = '';
						$this->insert($dt);
						$sx .= bsmessage($id_issue . ' - ' . $year . ' - ' . $vol . ' - ' . $num . ' - ' . lang('brapci.insered'));
					}
				}
			}
		}
		return $sx;
	}



	function xxxxxxxxxxxxxxxxxxxxx_view_issue_import($idx = 0)
	{
		$this->setDatabase('brapci');
		$RDF = new \App\Models\Rdf\RDF();

		$dt = $RDF->le_data($idx);
		$dt = (array)$dt['data'];

		$sx = '<h3>' . msg('brapci.issue') . '</h3>';
		$ar = array();

		for ($r = 0; $r < count($dt); $r++) {
			$line = (array)$dt[$r];
			if ($line['c_class'] == 'hasIssue') {
				$n = $line['d_r1'];
				array_push($ar, $n);
			}
		}
		for ($r = 0; $r < count($ar); $r++) {
			$idi = $ar[$r];
			/************************** */
			$di = $this->where('is_source_issue', $idi)->findAll();

			if (count($di) == 0) {
				/* Não está registrado */
				$data = array();
				$data['is_source_rdf'] = $idx;
				$data['is_source_issue'] = $idi;
				$data['is_year'] = '';
				$data['is_issue'] = '';
				$data['is_vol'] = '';
				$data['is_nr'] = '';

				/*********************** Le os dados */
				$dt = $RDF->le($ar[$r]);
				/*********************** Recupera propriedades */
				$dt = (array)$dt['data'];

				for ($y = 0; $y < count($dt); $y++) {
					$dtc = (array)$dt[$y];
					/*************************************** Recupera classe */
					$class = $dtc['c_class'];
					$value = $dtc['n_name'];
					$dt2 = $dtc['d_r2'];
					switch ($class) {
						case 'hasPublicationVolume':
							$dte = $RDF->le($dt2, 1);
							$data['is_vol'] = $dte['concept']['n_name'];
							break;
						case 'dateOfPublication':
							$dte = $RDF->le($dt2, 1);
							$data['is_year'] = $dte['concept']['n_name'];
							break;
						case 'hasPublicationNumber':
							$dte = $RDF->le($dt2, 1);
							$data['is_nr'] = $dte['concept']['n_name'];
							break;
						case 'altLabel':
							$data['is_issue'] = $value;
							break;
					}
				}
				$this->db->table('brapci.source_issue')->insert($data);
				//$this->db->insert($data);		
			} else {
				$sx .= 'Pass<br>';
			}
		}
		$sx = '';
		return ($sx);
	}
}
