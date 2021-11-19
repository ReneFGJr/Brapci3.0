<?php

namespace App\Models\INPI;

use CodeIgniter\Model;

class InpiRpi extends Model
{
	protected $DBGroup              = 'inpi';
	protected $table                = 'INPI_RPI';
	protected $primaryKey           = 'id_pb';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_pb','pb_number','pb_date',
		'pb_type','pb_ano','pb_file',
		'pb_status'
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

	function atualiza($dta)
		{
			$sx = 'Action: ';
			$type = $dta['pb_type'];
			$nr = $dta['pb_number'];
			$file = $dta['pb_file'];

			$dt = $this->where('pb_file',$file)
				->where('pb_type',$type)
				->findAll();

			if (count($dt) ==  0)
				{
					$this->insert($dta);
					$sx .= 'Append';
				} else 
				{
					$sx .= 'none';
				}
			return $sx;
		}
}
