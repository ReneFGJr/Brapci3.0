<?php

namespace App\Models\Oaipmh;

use CodeIgniter\Model;

class Index extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'indices';
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

	function index($d1,$d2,$d3,$d4='',$d5='')
		{
			$sx = '';
			switch($d1)
				{
					case 'process_record':
						$OaipmhRegister = new \App\Models\Oaipmh\OaipmhRegister();
						if ($d2 == 'A')
							{
								$sx .= h('PROCESSAR');
								$dt = $OaipmhRegister->where('lr_jnl',$d3)->where('lr_procees',1)->orderBy('id_lr','asc')->first();
								$sx .= $this->header_journal($dt);
								$d2a = $dt['id_lr'];
								$sx .= $OaipmhRegister->process_record($d2a);
								$sx .= metarefresh(PATH.MODULE.'admin/oai/process_record/A/'.$d3);
							} else {
								$sx .= $OaipmhRegister->process_record($d2,$d3);;
							}					
						
						break;
					case 'set_status':
						$OaipmhRegister = new \App\Models\Oaipmh\OaipmhRegister();
						$dt['lr_procees'] = 0;
						$OaipmhRegister->set_status($d2,$dt);
						$sx .= metarefresh(PATH.MODULE.'admin/oai/record/'.$d2);
						break;

					case 'record':
						$OaipmhRegister = new \App\Models\Oaipmh\OaipmhRegister();
						$dt = $OaipmhRegister->find($d2);
						$sx .= $this->header_journal($dt);

						/* Show Record */
						$sx .= $OaipmhRegister->record($d2,$d3);					

					break;

					case 'get_record':
						$OaipmhRegister = new \App\Models\Oaipmh\OaipmhRegister();

						if ($d2 == 'A')
							{
								$dt = $OaipmhRegister->where('lr_jnl',$d3)->where('lr_procees',0)->orderBy('id_lr','asc')->first();
								if ($dt=='')
									{
										$sx .= metarefresh(PATH.MODULE.'v/'.$d3);
										return $sx;
										exit;
									}
								$sx .= $this->header_journal($dt);

								$d2a = $dt['id_lr'];

								/* Get Record */
								$sx .= bs(bsc($OaipmhRegister->get_record($d2a),12));								
								/* Show Record */
								$sx .= $OaipmhRegister->record($d2a);	

								$sx .= metarefresh(PATH.MODULE.'admin/oai/get_record/A/'.$d3);
								
							} else {
								$dt = $OaipmhRegister->find($d2);
								$sx .= $this->header_journal($dt);

								/* Get Record */
								$sx .= bs(bsc($OaipmhRegister->get_record($d2,$d3),12));								
								/* Show Record */
								$sx .= $OaipmhRegister->record($d2,$d3);
							}

						//$sx .= metarefresh(PATH.MODULE.'admin/oai/record/'.$d2);
					break;					

					case 'records':
						$OaipmhListRecord = new \App\Models\Oaipmh\OaipmhListRecord();
						$sx = $OaipmhListRecord->listrecords($d2,$d3,$d4,$d5);
					break;
				}
				return $sx;
		}	

		function header_journal($dt)
			{
				$Journal = new \App\Models\Journal\Journals();
				/* Show Journal */						
				$dj = $Journal->where('jnl_frbr', $dt['lr_jnl'])->FindAll();
				return $Journal->header($dj[0]);
			}
}
