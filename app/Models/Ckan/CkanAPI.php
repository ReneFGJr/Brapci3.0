<?php

namespace App\Models\Ckan;

use CodeIgniter\Model;

class CkanAPI extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'ckanapis';
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

	var $apikey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJodzNKODZpelhOTktkX0w5N0RzVy1VajBQdVVIQjhPMEJUdG9JOUZpWW1WWm1YdWtxMjkydFZRY2ZVd0tmM0NiaWRhUGZmX19uVW8yZEFSWiIsImlhdCI6MTY0NTE5NDM2OH0.vuiJCZsYXLHL7EsJ3CoTV73D6ye8T_qn0lL9jap7vNk';
	var $url = '';	

	function API($dt)
		{
			if (!isset($dt['type'])) { $dt['type'] = 'GET'; }
			$cmd = 'http://20.197.232.69/api/3/action/'.$dt['cmd'];
			$auth = array('Authorization: '.$this->apikey);
			//$auth = json_encode($auth);


			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $cmd);
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $dt['type']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			if (isset($dt['data'])) 
				{ curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dt['data'])); }
			curl_setopt($ch, CURLOPT_HTTPHEADER, $auth);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}
}
