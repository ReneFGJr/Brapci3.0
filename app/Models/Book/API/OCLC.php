<?php
// http://classify.oclc.org/classify2/Classify?isbn=9781501110368&summary=true

namespace App\Models\API;

use CodeIgniter\Model;

class OCLC extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'oclcs';
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

	function book($isbn,$id) {
		$rsp = array('count' => 0);

		$ISBN = new \App\Models\Isbn\Isbn();
		$Language = new \App\Models\Languages\Language();		
		
		$type = 'OCLC';
		$t = $ISBN->get($isbn,$type);
		
		if (count($t) == 0) {
			return array();
		}
		return $t;
	}	
}
