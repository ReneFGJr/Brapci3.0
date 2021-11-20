<?php

namespace App\Models\Roboti;

use CodeIgniter\Model;

class Task extends Model
{
	protected $DBGroup              = 'roboti';
	protected $table                = 'Task';
	protected $primaryKey           = 'id_task';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_task','task_description','task_url',
		'task_w1','task_w2','task_w3',
		'task_w4','task_w5','task_w6',
		'task_w7','task_hour','task_min',
		'task_day','updated_at'
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

	function scheduler()
		{
			$dt = $this->orderBy('updated_at')->findAll();
			$dt = $dt[0];
			set_time_limit(500);

			$url = $dt['task_url'];
			echo $url;
			
			$s = curl_init();
			curl_setopt($s,CURLOPT_URL,$url);
			$rsp = curl_exec($s);
			curl_close($s);
			echo '<pre>';
			print_r($s);

			$sql = "update ".$this->DBGroup.'.'.$this->table." set updated_at = now() where id_task = ".$dt['id_task'];
			$this->query($sql);
			echo '<h1>'.$sql.'</h1>';
		}

}
