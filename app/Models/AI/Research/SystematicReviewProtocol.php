<?php

namespace App\Models\AI\Research;

use CodeIgniter\Model;

class SystematicReviewProtocol extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'Brapci_ai.SystematicReviews_Protocol';
	protected $primaryKey           = 'id_sp';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_sp','sr_study','sp_field','sp_context','sp_corpus','sp_criterie'
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

	function atualiza($study, $field, $corpus,$criterie,$value)
		{
			$dt = $this->where('sr_study',$study)
				->where('sp_field',$field)
				->where('sp_corpus',$corpus)
				->where('sp_criterie',$criterie)
				->findAll();
			if (count($dt) > 0)
				{
					$line = (array)$dt[0];
					$sql = "update ".$this->table." set sp_context = '".$value."' where id_sp = ".$line['id_sp'];
					$this->query($sql);
				} else {
					$dt['sr_study'] = $study;
					$dt['sp_field'] = $field;
					$dt['sp_context'] = $value;
					$dt['sp_corpus'] = $corpus;
					$dt['sp_criterie'] = $criterie;
					$this->insert($dt);
				}
		}
}
