<?php

namespace App\Models\Book\API;

use CodeIgniter\Model;

class Covers extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'covers';
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

	function index($isbn)
	{
		$file = '_covers/image/' . $isbn . '.jpg';
		if (!file_exists($file)) {
			$this->getFIND_old($isbn);
		} else {
			Header("Content-Type: image/jpeg"); 
			header("Content-Length: " . filesize($file));
			echo file_get_contents("$file");
			exit;
		}
	}

	function getFIND_old($isbn)
	{
		$url = 'https://www.ufrgs.br/find/_covers/image/' . $isbn . '.jpg';
		$img = $this->upload_cover($isbn,$url);
		$file = '_covers/image/' . $isbn . '.jpg';

		if (file_exists($file)) {
			echo "OK";
			exit;
		} else {
			echo "ERRO";
			exit;
		}
	}


	function upload_cover($isbn, $endPoint)
	{
		/************************ Busca */
		echo h('==='.$endPoint);
		exit;
		if (($data = @file_get_contents($endPoint)) === false) {
			$error = error_get_last();
			echo "HTTP request failed. Error was: " . $error['message'];
			echo '<br>'.$endPoint;
		} else {
			dircheck('_covers');
			dircheck('_covers/image/');
			$file = '_covers/image/' . $isbn . '.jpg';
			file_put_contents($file, $data);
		}
	}
}
