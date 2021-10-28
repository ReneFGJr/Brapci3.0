<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class RDFClass extends Model
{
	var $DBGroup              = 'default';
	protected $table                = 'brapci.rdf_class';
	protected $primaryKey           = 'id_c';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'c_class', 'c_prefix', 'c_type', 'c_url', 'c_equivalent'
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

	function class($c, $force = True)
	{
		$this->Prefix = new \App\Models\RDF\RDFPrefix();
		$this->Prefix->DBGroup = $this->DBGroup;

		if (strpos($c, ':')) {
			$prefix = substr($c, 0, strpos($c, ':'));
			$Prefix = $this->Prefix->prefixo($prefix);
			$Class = substr($c, strpos($c, ':') + 1, strlen($c));
		} else {
			$Class = $c;
			$prefix = '';
			$Prefix = 0;
		}

		/* Localiza todos as classes */
		$ID = $this->where('c_prefix', $Prefix)->where('c_class', $Class)->first();
		if (is_array($ID) == 0) {
			$ID = $this->where('c_class', $Class)->first();
			if (is_array($ID) == 0) {
				$data['c_class'] = $Class;
				$data['c_prefix'] = $Prefix;
				/*********************** Tipo */
				$data['c_type'] = 'C';
				if (substr($Class, 0, 1) == strtolower(substr($Class, 0, 1))) {
					$data['c_type'] = 'P';
				}

				$data['c_url'] = $Class;
				if ($force == True) {
					$this->insert($data);
					$ID = $this->where('c_prefix', $Prefix)->where('c_class', $Class)->first();
				} else {
					return (-1);
				}
			}
		}
		return $ID['id_c'];
	}

	function inport($url = '')
	{
		$sx = '';
		$ID = 3;
		$ID_file = 9;
		$URL = 'http://cedapdados.ufrgs.br';
		$IDP = 'hdl:20.500.11959/CedapDados/' . $ID . '/' . $ID_file;
		$url = $URL . '/api/access/datafile/:persistentId?persistentId=' . $IDP;
		$lang = 'pt-BR';
		$dir = '.tmp';
		$file = md5($url);
		$filename = $dir . '/' . $file;
		return $filename;
		exit;

		/* Leitura do Arquivo */
		if (!is_dir($dir)) {
			mkdir($dir);
		}
		if (file_exists($filename)) {
			$txt = file_get_contents($filename);
		} else {
			/************************************* */
			$txt = file_get_contents($url);
			file_put_contents($filename, $txt);
		}
		$txt = str_replace(array('"'), array(''), $txt);
		$lns = explode(chr(10), $txt);
		$hd = explode(chr(9), $lns[0]);

		for ($r = 01; $r < count($lns); $r++) {
			$ln = explode(chr(9), $lns[$r]);
			if (count($ln) > 1) {
				for ($y = 0; $y < count($hd); $y++) {
					$dt[$hd[$y]] = $ln[$y];
				}

				$dz = $this->where('prefix_ref', $dt[$hd[0]])->findAll();

				if (isset($dz[0])) {
				} else {
					$this->insert($dt);
				}
			}
		}
		$sx .= bsmessage('DataSet File inported', 1);
		return $sx;
	}
}
