<?php

namespace App\Models\Ckan;

use CodeIgniter\Model;

class CkanPackages extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'ckanpackages';
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

	function package_list()
		{
			$sx = '';
			$API = new \App\Models\Ckan\CkanAPI();
			$dt = array();
			$dt['cmd'] = 'package_list';
			$rsp = $API->API($dt);
			$rsp = (array)json_decode($rsp);
			if (isset($rsp['success']))
				{
					if ($rsp['success'] == true)
						{
							$sx .= h('brapci.ckan_package_list',3);
							$sx .= '<ul>';
							foreach($rsp['result'] as $item)
								{
									$sx .= '<li>'.$item.'</li>';
								}
							$sx .= '</ul>';
						} else {
							$sx .= bsmessage("error API CKAN",3);
						}
				}
			return $sx;
		}	

	function createPackage()
		{
			$sx = '';
			$API = new \App\Models\Ckan\CkanAPI();
			$dt = array();
			$dt['cmd'] = 'package_create';
			$dt['type'] = 'POST';

			$data = array();
			$data['name'] = 'my_dataset_4';
			$data['notes'] = 'Notes My DataSet';
			$data['owner_org'] = 'ufrgs';
			$data['version'] = '2';
			$data['author'] = 'INEP';
			$data['isopen'] = 'true';
			$data['license_id'] = 'cc-by';
			$data['license_title'] = 'Creative Commons Attribution';
			$data['maintainer'] = 'INEP';
			//$data['maintainer_email'] = array('inep@inep.gov.br');
			//$data['tags'] = array('inep','Censo Educação Basica'); 
			//$data['tags'] = 'INEP; Censo Educação Basica';
			//$data['resources'] = array();
			$data['url'] = 'http://www.inep.gov.br/';
			$dt['data'] = $data;

			$rsp = $API->API($dt);
			$rsp = (array)json_decode($rsp);

		}
}
