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

	function view_issue_articles($id)
		{
			$tela = '';
			$RDF = new \App\Models\RDF\RDF();
			$dt = $RDF->le($id,0,'brapci');

			$dtd = $dt['data'];
			$vol = '';
			$nr = '';
			$year = '';
			$journal = '';
			for ($r=0;$r < count($dtd);$r++)
				{
					$dtl = $dtd[$r];
					$class = trim($dtl['c_class']);
					$value = trim($dtl['n_name']);
					$id1 = $dtl['d_r1'];
					$id2 = $dtl['d_r2'];
					switch($class)
						{
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
								$tela .= bsc(bscard($RDF->content($id2),'','p-1'),12,'p-1');
								break;
							case 'hasIssue':
								$journal = $RDF->le_content($id2);
								break;
							case 'altLabel':
								break;
							case 'prefLabel':
								break;
							default:
								$tela .= '<br>'.$class.'==>'.$value.'=='.$id1.'=='.$id2;
								break;
						}
				}
			$tela = h($journal.', '.$nr.', '.$vol.', ' .$year,5).$tela;
			$tela = bs($tela);
			return $tela;
		}

	function view_issue($idx = 0)
		{
			$this->where('is_source_rdf',$idx);
			$this->orderBy('is_year desc, is_vol, is_nr');
			$dt = $this->FindAll();

			if (count($dt) == 0)
				{
					$this->view_issue_import($idx);
					$this->where('is_source_rdf',$idx);
					$this->orderBy('is_year desc, is_vol, is_nr');
					$dt = $this->FindAll();
				}
			$sx = '';
			$xyear = '';
			for ($r=0;$r < count($dt);$r++)
				{
					$dtx = $dt[$r];
					$year = $dtx['is_year'];
					if ($year != $xyear)
						{
							$sx .= bsc(h($year,5),12);
							$xyear = $year;
						}
					$link = '<a href="'.base_url(URL.'res/v/'.$dtx['is_source_issue']).'">';
					$linka = '</a>';
					$sx .= bsc($link.$dtx['is_vol'].'<br/>'.$dtx['is_nr'].$linka,1,'p-2 m-1 shadown bordered bw');
				}
			$sx = bs($sx);
			return $sx;
		}

	function view_issue_import($idx = 0)
	{
		$this->setDatabase('brapci');
		$RDF = new \App\Models\RDF\RDF();

		$dt = $RDF->le_data($idx);
		$dt = $dt['data'];

		$sx = '<h3>' . msg('ISSUE') . '</h3>';
		$ar = array();

		for ($r = 0; $r < count($dt); $r++) {
			$line = $dt[$r];
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
				$data['is_nr'] = '';

				/*********************** Le os dados */
				$dt = $RDF->le($ar[$r]);
				/*********************** Recupera propriedades */
				$dt = $dt['data'];

				for ($y = 0; $y < count($dt); $y++) {
					$dtc = $dt[$y];
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
