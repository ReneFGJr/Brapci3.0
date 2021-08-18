<?php

namespace App\Models;

use CodeIgniter\Model;

class EventProceedings extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'event_proceedings';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = 
	[
		'id_ep', 'ep_nome', 'ep_url',
		'ep_url_oai'
	];

	protected $typeFields        = [
		'hi',
		'st100*',
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
		$sx = 'X';
		
		switch ($d1) {
			case 'ajax':

			break;

			default:
				$sx = $cab;
				$sx .= h("Proceedings - View", 1);
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
}
