<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Datasets extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'datasets';
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

	function CreateDatasets($dd='')	
		{
			$url = $this->url.'api/dataverses/lattesdata';
			$id = $dd['id'];
	
			$json = json_encode($dd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			$id = strzero(1,8);
			$file = '.tmp/dataverse/dataverse-'.$id.'.json';
			file_put_contents($file, $json);
	
			$dd['AUTH'] = true;
			$dd['POST'] = true;
			$dd['FILE'] = $file;
	
			$rsp = $this->curlExec($dd);
			$rsp = json_decode($rsp,true);
			
			$sta = $rsp['status'];
			switch($sta)
				{
					case 'OK':
						$sx = 'OK';
					break;
					case 'ERROR':
						$sx = '<pre style="color: red;">'; 
						$sx .= $rsp['message'];	
						$sx .= '<br>Dataverse Name: <b>'.$dd['alias'].'</b>';
						$sx .= '<br><a href="'.$this->url.'dataverse/'.$dd['alias'].'" target="_blank">'.$url.'/'.$dd['alias'].'</a>';
						$sx .= '</pre>';
						break;
				}
			return $sx;
			}
}
