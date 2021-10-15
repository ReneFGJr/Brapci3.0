<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class Checked extends Model
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

	function check($id,$tpy=0)
		{
			$dt = $this->where('at_rdf',$id)->FindAll();
			if (count($dt) == 0)
				{
					$sql = "insert into ".$this->table." (at_rdf,at_type) values ($id,$tpy)";
					$this->query($sql);
				}
			return 0;
		}
}
