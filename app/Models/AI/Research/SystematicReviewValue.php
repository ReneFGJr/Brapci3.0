<?php

namespace App\Models\AI\Research;

use CodeIgniter\Model;

class SystematicReviewValue extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci_ai.SystematicReviews_Values';
	protected $primaryKey           = 'id_sd';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_sd','sd_study','sd_field','sd_desc'
	];
	protected $typeFields        = [
		'hidden',
		'qr:id_sr:sr_title:brapci_ai.SystematicReviews_Studies',
		'qr:id_fs:fs_field:brapci_ai.SystematicReviews_Fields','text'
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
			$this->path = URL.MODULE.'research/systematic_review/criterieEd/';
			$sx = form($this);
			return $sx;
		}
}
