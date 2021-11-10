<?php

namespace App\Models\AI\Research;

use CodeIgniter\Model;

class SystematicReviewField extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'SystematicReviews_Fields';
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
				
}
