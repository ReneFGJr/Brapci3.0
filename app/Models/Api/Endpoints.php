<?php

namespace App\Models\Api;

use CodeIgniter\Model;

class Endpoints extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'endpoints';
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

	function index($d1, $d2, $d3, $d4)
	{

		$q = get("q");
		$v = get("verb");
		if (strlen($v) > 0) { $d1 = $v; }

		switch($d1)
			{
				case 'LattedId':
					$dt = $this->LattexID($q);
			}

		$dt['verb'] = $d1;
		if (!isset($dt['erro']))
			{
				$dt['erro'] = '0';
				$dt['descript'] = 'Successful';
			}
		
		$dt['date'] = date("Y-m-d H:m:s");			
		
		$tela = json_encode($dt);
		return $tela;
	}

	function LattexID($q='Name for query')
	{
		$dt = array();
		$file = '/home/cedap/CVlattesASCII.csv';		
		$handle = fopen($file, "r");
		$q = mb_strtoupper(ascii($q));
		$dt['query'] = $q;
		$tot = 0;
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				// process the line read.
				if (strpos(' ' . $line, $q)) {
					$d = explode(';', $line);
					$dt[$d[0]] = $d[1];
					$tot++;
					if ($tot > 10)
						{
							$dt = array();
							$dt['erro'] = 101;
							$dt['descript'] = lang('api.multiple_results');
						}
				}
			}
			fclose($handle);
		}
		return $dt;
	}
}
