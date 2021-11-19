<?php

namespace App\Models\Authority;

use CodeIgniter\Model;

class AuthorityNames extends Model
{
	protected $DBGroup              = 'default';
	public $table                		= 'brapci_authority.AuthorityNames';
	protected $primaryKey           = 'id_a';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_a','a_prefTerm','a_class','a_lattes','a_brapci','a_orcid','a_uri','a_use','a_country','a_UF'
	];

	protected $typeFields        = [
		'hidden',
		'string:100',
		'string:100',
		'string:100',
		'string:100',
		'string:100',
		'string:100',
		'string:1',
		'string:20',
		'string:2'
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

	function summaryCreate()
		{
			$this->select('count(*) as total');
			$dt = $this->findAll();
			print_r($dt);
		}

	function get_id_by_name($name,$dt=array())
		{
			$name = trim($name);
			$this->where('a_prefTerm',$name);
			$dt = $this->findAll();
			return $dt;
		}

	function edit($id)
		{
			$this->id = $id;
			$this->path = base_url(PATH . MODULE.  '/index/edit/' . $id);
			IF ($id > 0)
				{
					$this->path_back = base_url(PATH . MODULE.  '/index/viewid/' . $id);
				} else {
					$this->path_back = base_url(PATH . MODULE.  '/index/');
				}
			
			$tela = form($this);
			return $tela;
		}

}
