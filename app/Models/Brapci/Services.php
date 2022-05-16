<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class Services extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci3.checked';
	protected $primaryKey           = 'id_at';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
			'id_at','at_chapter','at_portuguese',
			'at_english','at_spanish','at_pdf',

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

	function index()
		{
			$Socials = new \App\Models\Socials();
			$user = $Socials->user();

			$sv['res/ai/nlp/findTermsCandidates'] = 'brapci.content_candidatesTerms';
			$sv['res/tools'] = 'brapci.files';

			$sx = menu($sv);

			$sx = bs(bsc($sx,12));
			return $sx;
		}
}
