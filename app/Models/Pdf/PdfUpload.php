<?php

namespace App\Models\PDF;

use CodeIgniter\Model;

class Upload extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'uploads';
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

	function upload($id = '')
	{
		$RDF = new \App\Model\RDF();
		$data = $RDF->le_data($id);
		
		for ($r = 0; $r < count($data); $r++) {
			$attr = trim($data[$r]['c_class']);
			$vlr = trim($data[$r]['n_name']);

			if ($attr == 'prefLabel') {
				$file = trim($vlr);
				$file = troca($file, '/', '_');
				$file = troca($file, '.', '_');
				$file = troca($file, ':', '_');
			}
		}	
}
