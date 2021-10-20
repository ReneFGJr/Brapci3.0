<?php

namespace App\Models\Oaipmh;

use CodeIgniter\Model;

class Oaipmh extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'OAI_log';
	protected $primaryKey           = 'id_log';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_log','log_id','log_journal',
		'log_issue','log_total','log_new',
		'log_del','log_token','log_action'
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

	function index($d1,$d2)
		{
			switch($d1)
				{
					case 'get_proceedings':
						$sx = $this->harvesting_proceedings($d2);
					break;

					default:
						$sx = $this->harvesting($d1,$d2);
						break;
				}
			return $sx;
		}

	function resume($id)
		{
			$OaipmhListRecord = new \App\Models\Oaipmh\OaiPMHListRecord();
			$tela = $OaipmhListRecord->resume($id);
			return $tela;
		}



	function harvesting($id)
		{
				$this->EventProceedingsIssue = new \App\Models\EventProceedingsIssue();
				$dt = $this->EventProceedingsIssue->find($id);

				/* Load ListSets */
				$OaipmhListSetSepc = new \App\Models\Oaipmh\OaipmhListSetSepc();
				$sx = $OaipmhListSetSepc->harvesting($dt);

				/* Load ListSets */
				$OaipmhListRecord = new \App\Models\Oaipmh\OaipmhListRecord();
				$sx .= $OaipmhListRecord->harvesting($dt);

				$id = date("YmdHis");

				$data['log_id'] = $id;
				$data['log_journal'] = $dt['epi_procceding'];
				$data['log_issue'] = $dt['id_epi'];
				$data['log_action'] = 'HARV';
				$data['log_total'] = 0;
				$data['log_new'] = 0;
				$data['log_del'] = 0;
				$data['log_token'] = '';
				$this->insert($data);

				$dt = $this->where('log_id',$id)
						->findAll();

				$sql = "select count(*) as total, li_process 
							from OAI_ListRecords
							where li_journal = ".$data['log_journal']."
							and li_issue = ".$data['log_issue']."
							group by li_process";
				$query = $this->query($sql);
				foreach ($query->getResult() as $row)
				{
					$st = $row->li_process;
					$to = $row->total;
					if ($st == 0) { $data['log_new'] = $to; }
					if ($st == 9) { $data['log_del'] = $to; }
				}
				$data['log_action'] = 'succ';
				$this->set($data);
				$this->where('log_id',$id);
				$this->update();

				return $sx;				
		}

	function harvesting_proceedings($id)
		{
			$sx = '';

			$JournalIssue = new \App\Models\Journal\JournalIssue();
			$dt = $JournalIssue->find($id);
			
			$OaipmhListSetSepc = new \App\Models\Oaipmh\OaipmhListSetSepc();
			$sx .= $OaipmhListSetSepc->harvesting($dt,'EV');

			$OaipmhListRecord = new \App\Models\Oaipmh\OaipmhListRecord();
			$sx .= $OaipmhListRecord->harvesting($dt,'EV');			

			return $sx;
		}
}
