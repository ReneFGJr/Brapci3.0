<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class SelectionsBibliographics extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = '_bibliographic_selections';
	protected $primaryKey           = 'id_bb';
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

	function show_list($id,$class='')
		{
			$dt = $this->where('bb_user', $id)->orderBy('bb_data desc')->findAll();
			$sx = '';

			for ($r=0;$r < count($dt);$r++)
				{
					$line = $dt[$r];
					$sx .= '<li class="'.$class.'">'.$line['bb_title'].'</li>';
				}
			return $sx;
			

		}
}
