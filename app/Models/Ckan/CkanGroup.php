<?php

namespace App\Models\Ckan;

use CodeIgniter\Model;

class CkanGroup extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'ckangroups';
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

	function list_group_api()
		{
			$sx = '';
			$API = new \App\Models\Ckan\CkanAPI();
			$dt = array();
			$dt['cmd'] = 'group_list';
			$rsp = $API->API($dt);
			$rsp = (array)json_decode($rsp);
			if (isset($rsp['success']))
				{
					if ($rsp['success'] == true)
						{
							$sx .= h('brapci.ckan_groups_list',3);
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
}
