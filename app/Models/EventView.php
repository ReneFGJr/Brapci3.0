<?php

namespace App\Models;

use CodeIgniter\Model;

class EventView extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'eventviews';
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

	function view($dt)
		{
			$RDF = new \App\Models\RDFData();			
			$class = $dt['concept']['prefix_ref'].':'.$dt['concept']['c_class'];
			switch($class)
				{
					case 'frbr:Work':
						$sx = $this->viewWork($dt);
						$sx .= $RDF->view_data($dt);						
					break;

					default:
					$RDF = new \App\Models\RDFData();
					$sx = $this->viewWork($dt);
					$sx .= $RDF->view_data($dt);
				}
			return $sx;
		}

	function viewWork($dt)
		{
			$sx = '';
			$class = $dt['concept']['prefix_ref'].':'.$dt['concept']['c_class'];
			$sx .= '<h1>'.$class.'</h1>';

			$sx = bs($sx);
			return $sx;
		}
}
