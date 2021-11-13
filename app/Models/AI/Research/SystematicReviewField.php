<?php

namespace App\Models\AI\Research;

use CodeIgniter\Model;

class SystematicReviewField extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci_ai.SystematicReviews_Fields';
	protected $primaryKey           = 'id_fs';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_fs','fs_field','fs_context','fs_type','fs_sample'
	];
	protected $typeFields        = [
		'hidden','string:100','text','text','text'
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

		function exclusion($id,$study)
			{
				$SystematicReviewValue = new \App\Models\AI\Research\SystematicReviewValue();
				$SystematicReviewProtocol = new \App\Models\AI\Research\SystematicReviewProtocol();
				$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();

				$sql = "select * from ".$this->table." 
						INNER JOIN ".$SystematicReviewValue->table." ON sd_field = id_fs
						LEFT JOIN ".$SystematicReviewProtocol->table." 
								ON (sp_field = sd_field) and (sr_study = $study) and (sp_corpus = $id)
								and (sp_criterie = id_sd)
						where fs_field = 'hasExclusionCriterie'
						and sd_study = $study
						";
				$dt = $this->query($sql)->getresult();


				/************************************************** SALVAR */
				$action = get("action");

				$exclusion = 0;
				
				$sx =  'Options:<br>';
				$sx .= '<form method="post">';
				for ($r=0;$r < count($dt);$r++)
					{
						$line = (array)$dt[$r];
						$chk = '';
						$var = 'id_'.$line['id_fs'].'_'.$line['id_sd'];
						
						if ($action != '')
						{						
							$field = $line['id_fs'];
							$study = $line['sd_study'];
							$criterie = $line['id_sd'];
							$corpus = $id;
							$value = get($var);

							$SystematicReviewProtocol->atualiza($study,$field,$corpus,$criterie,$value);

							if ($value != '') { $chk = 'checked'; $exclusion = 1; }
						} else {
							if ($line['sp_context'] != '')
								{
									$chk = 'checked';
								}
						}

						$sx .= '<input type="checkbox" name="'.$var.'" '.$chk.'> ';
						$sx .= $line['sd_desc'];
						$sx .= '<br>';
					}
				$sx .= '<input type="submit" name="action" value="'.lang('ai.Save').'">';
				$sx .= '</form>';

				if ($exclusion == 1)
					{
						$SystematicReviewCorpus->changeStatus($id,4);
						$sx .= wclose('no_refresh');
					}
				return $sx;
			}
		function edit($id)
			{
				$this->path = PATH.MODULE.'research/systematic_review';
				$this->path_back = PATH.MODULE.'research/systematic_review';
				$tela = form($this);
				return $tela;
			}

		function tableview()
			{
				$this->path = PATH.MODULE.'research/systematic_review';
				$tela = tableview($this);
				return $tela;
			}

		function check()
			{
				$chk = array();
				array_push($chk,array('hasInclusionCriterie','ai.InclusionCriterie','TEXT','ai.InclusionCriterieEx'));
				array_push($chk,array('hasExclusionCriterie','ai.ExclusionCriterie','TEXT','ai.ExclusionCriterieEx'));
				for ($r=0;$r < count($chk);$r++)
					{
						$ln = $chk[$r];
						$dt = $this->where('fs_field',$ln[0])->findAll();
						if (count($dt) == 0)
							{
								$dt['fs_field'] = $ln[0];
								$dt['fs_context'] = $ln[1];
								$dt['fs_type'] = $ln[2];
								$dt['fs_sample'] = $ln[3];
								$this->insert($dt);
							}
					}
			}
				
}
