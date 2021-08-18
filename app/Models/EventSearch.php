<?php

namespace App\Models;

use CodeIgniter\Model;

class EventSearch extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'eventsearches';
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

	function form()
		{
			$sx = '<form action="'.base_url(PATH).'">';
			$sx .= '<div class="input-group">
  						<input type="text" class="form-control" id="search" aria-describedby="inputGroupFileAddon04" aria-label="search">
  						<input type="submit" class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon04" value="Search">
					</div>';

			return $sx;
		}

	function logo()
		{
			$sx = 'LoGO';

			return $sx;
		}
}
