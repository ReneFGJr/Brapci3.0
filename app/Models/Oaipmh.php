<?php

namespace App\Models;

use CodeIgniter\Model;

class Oaipmh extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'oai_log';
	protected $primaryKey           = 'id_log';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_log','log_id','log_journal','log_issue','log_total','log_new','log_del','log_token'
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

	function harvesting($id)
		{
				$this->EventProceedingsIssue = new \App\Models\EventProceedingsIssue();
				$dt = $this->EventProceedingsIssue->find($id);

				/* Load ListSets */
				$this->OaipmhListSetSepc = new \App\Models\OaipmhListSetSepc();
				$sx = $this->OaipmhListSetSepc->harvesting($dt);

				/* Load ListSets */
				$this->OaipmhListRecord = new \App\Models\OaipmhListRecord();
				$sx .= $this->OaipmhListRecord->harvesting($dt);

				$id = date("YmdHis");

				$data['log_id'] = $id;
				$data['log_journal'] = $dt['epi_procceding'];
				$data['log_issue'] = $dt['id_epi'];
				$data['log_action'] = 'harv';
				$data['log_total'] = 0;
				$data['log_new'] = 0;
				$data['log_del'] = 0;
				$data['log_token'] = '';
				$this->insert($data);

				return $sx;				
		}

	function harvesting_proceedings($id)
		{
				$this->EventProceedingsIssue = new \App\Models\EventProceedingsIssue();
				$dt = $this->EventProceedingsIssue->find($id);
				print_r($dt);
		}
}
