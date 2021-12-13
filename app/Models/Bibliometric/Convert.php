<?php

namespace App\Models\Bibliometric;

use CodeIgniter\Model;

class Convert extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'converts';
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

	var $metadata = array(
		'type' => '',
		'author' => array(),
		'title' => array(),
		'issue' => array(),
		'abstract' => array(),
		'note' => array(),
		'year' => array(),
		'keyword' => array(),
		'issue' => array(),
		'edition' => array(),
		'url' => array(),
		'isbn' => array(),
		'editor' => array(),
		'place' => array(),
		'page' => array()
	);
	var $data = array();

	function RIS_to_standard($txt)
	{
		$metadata = array();
		$sx = '';
		$ln = troca($txt, chr(10), chr(13));
		$ln = explode(chr(13), $ln);
		$data = $this->metadata;
		$conv = array(
			'A1' => 'author',
			'A2' => 'author',
			'TI' => 'title',
			'T1' => 'title',
			'T2' => 'title',
			'T3' => 'title',
			'PY' => 'year',
			'ET' => 'edition',
			'AB' => 'abstract',
			'N1' => 'note',
			'KW' => 'keyword',
			'U1' => 'issue',
			'UR' => 'url',
			'PP' => 'place',
			'PB' => 'editor',
			'M1' => 'issue',
			'SN' => 'isbn',
		);
		$vlr = '';
		for ($r = 0; $r < count($ln); $r++) {
			$l = ($ln[$r]);
			if (strlen($l) > 1) {
				$tp = substr($l, 0, 2);
				//var_dump($tp);

				$tpu = uppercase($tp);
				if (($tp != $tpu) or ($tp == '  ')) {
					$tp = 'STR';
				}

				$vlr .= trim(substr($l, 5, strlen($l)));

				switch ($tp) {
					case 'STR':
						break;
					case 'TY':
						$data['type'] = $vlr;
						$vlr = '';
						break;
					case 'ER':
						array_push($metadata, $data);
						$data = $this->metadata;
						$vlr = '';
						break;
					default:
						if (isset($conv[$tp])) {
							$meta = $conv[$tp];
							array_push($data[$meta], $vlr);
							$vlr = '';
						} else {
							echo $l . '<br>';
							echo 'OPS: ' . $tp . '<br>';
							exit;
						}
				}
			}
		}
		return $metadata;
	}

	function standard_CSV($data,$sep = ',')
	{
		$sep2 = ';';
		if ($sep == ';') { $sep2 = ','; }
		$th = '';
		foreach ($data[0] as $hd => $vl) {
			if (strlen($th) > 0) {
				$th .= $sep;
			}
			$th .= $hd;
		}
		$tr = '';		
		for ($r = 0; $r < count($data); $r++) {
			$datai = $data[$r];
			$tl = '';
			foreach ($datai as $ln => $value) {
				if (strlen($tl) > 0) { $tl .= $sep; }
				if (is_array($value))
					{
						$tl .= '"';
						for($y=0;$y<count($value);$y++)
							{
								if ($y > 0) { $tl .= $sep2; }
								$vlr2 = $value[$y];
								$vlr2 = troca($vlr2,$sep,$sep2);
								$tl .= trim($vlr2);
							}
						$tl .= '"';
					} else {
						$tl .= $value;
					}
			}
			$tr .= $tl.chr(10);
		}
		$tr = $th.chr(10).$tr;
		return $tr;
	}
}
