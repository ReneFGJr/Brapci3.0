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

	function CreateDatasets($name='',$parent='')	
		{
		$url = $this->url.'api/dataverses/lattesdata';

		$dd['name'] = 'Bolsistas Produtividade PQ1A';
		$dd['alias'] = 'science';
		$dd['dataverseContacts'] = array();
		array_push($dd['dataverseContacts'], array('contactEmail' => 'renefgj@gmail.com'));
		array_push($dd['dataverseContacts'], array('contactEmail' => 'rene@sisdoc.com.br'));

		$dd['affiliation'] = 'INEP';
		$dd['description'] = 'Descricao do Projeto de Teste';
		$dd['dataverseType'] = 'LABORATORY';

		$json = json_encode($dd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo $json;
		$rsp = $this->curl($url,$json);

		echo '<pre style="color: blue;">=====>';
		print_r($rsp);
		echo '</pre>';		
		}	
}
