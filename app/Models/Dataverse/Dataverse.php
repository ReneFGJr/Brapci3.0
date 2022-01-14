<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Dataverse extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'dataverses';
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

	var $http = 'https://vitrinedadosabertos.inep.rnp.br/';

	function getDataverses($root='')
		{
			$api =  'api/info/metrics/dataverses';
			$url = $this->http.$api;
			echo $url;
		}

	function createDataverseAPI($dt=array())
	{
		//https://guides.dataverse.org/en/latest/api/native-api.html
		$dt = '{
			"name": "Scientific Research",
			"alias": "science",
			"dataverseContacts": [
			  {
				"contactEmail": "pi@example.edu"
			  },
			  {
				"contactEmail": "student@example.edu"
			  }
			],
			"affiliation": "Scientific Research University",
			"description": "We do all the science.",
			"dataverseType": "LABORATORY"
		  }';
		  /*
		  export API_TOKEN=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
			export SERVER_URL=https://demo.dataverse.org
			export PARENT=root
			curl -H X-Dataverse-key:$API_TOKEN -X POST $SERVER_URL/api/dataverses/$PARENT --upload-file dataverse-complete.json
		  */
	}	
}
