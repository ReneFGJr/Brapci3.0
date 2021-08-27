<?php

namespace App\Models;

use CodeIgniter\Model;

class EventProceedings extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'event_proceedings';
	protected $primaryKey           = 'id_ep';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = 
	[
		'id_ep', 'ep_nome', 'ep_abrev',
		'ep_url', 'ep_url_oai'
	];

	protected $typeFields        = [
		'hi',
		'st100*',
		'st50*',
		'st100*',
		'st100*',
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

	function index($d1, $id, $dt=array(),$cab='')
	{	
		switch ($d1) {
			case 'gets':
				$sx = $cab;
				$st = get("process");
				$this->Oaipmh = new \App\Models\Oaipmh();
				switch($st)
					{
						case '0':
							$this->OaiPMHRegister = new \App\Models\OaiPMHRegister();
							$st = $this->OaiPMHRegister->process_00($id);
						break;

						case '1':
							$this->OaiPMHRegister = new \App\Models\OaiPMHRegister();
							$st = $this->OaiPMHRegister->process_01($id);
						break;

						default:
							$this->OaiPMHRegister = new \App\Models\OaiPMHRegister();
							$st = $this->OaiPMHRegister->process_01($id);
						break;

					}
				$sx .= bs($st);
				break;			
			break;

			case 'edit':
				$sx = $cab;
				$this->id = $id;
				$st = form($this);
				$sx .= bs(bsc($st,12));
			break;

			case 'ed_issue':
				$sx = $cab;
				$this->id = $id;
				$this->EventProceedingsIssue = new \App\Models\EventProceedingsIssue();
				$st = $this->EventProceedingsIssue->editar($this);
				$sx .= bs(bsc($st,12));
			break;

			case 'viewid':
				$sx = $cab;
				$st = $this->viewid($id);
				$sx .= bs(bsc($st,12));
				$sx .= bsclose(3);
				break;

			case 'issue':
				$sx = $cab;
				$this->EventProceedingsIssue = new \App\Models\EventProceedingsIssue();
				$st = $this->EventProceedingsIssue->viewIssue($id);
				$sx .= bs($st);
				break;	

			case 'harvesting':
				$sx = $cab;
				$this->Oaipmh = new \App\Models\Oaipmh();
				$st = $this->Oaipmh->index($id,$dt);
				$sx .= bs($st);
				break;					

			default:
				$sx = $cab;
				$st = h("Proceedings - View", 1);
				$this->id = $id;
				$dt =
					[
						'services' => $this->paginate(3),
						'pages' => $this->pager
					];
				$sx .= tableview($this,$dt);
				$sx = bs($sx);
				break;
		}
		return $sx;
	}	

	function issues_ed()
		{

		}

	function imports()
		{
			/*************************************** */
			$this->OpenDataCountry = new \App\Models\OpenDataCountry();
			$this->OpenDataCountry->inport();
			$this->OpenDataLanguage = new \App\Models\OpenDataLanguage();
			$this->OpenDataLanguage->inport();	
		}

	function le($id)
		{
			$dt = $this->find($id);
			return $dt;
		}

	function headProceeding($dt)
		{
			$sx = '';
			$sx .= bsc(bssmall(lang('ep_nome')),12);
			$sx .= bsc('<h4>'.$dt['ep_nome'].'</h4>',12);

			return $sx;
		}

	function viewid($id)
		{
			$this->EventProceedingsIssue = new \App\Models\EventProceedingsIssue();

			$dt = $this->where('id_ep', $id)->findAll();
			$dt = $dt[0];

			$sx = bsc(h($dt['ep_nome'],2));

			$sx .= $this->EventProceedingsIssue->issues($id);

			return $sx;
		}
}
