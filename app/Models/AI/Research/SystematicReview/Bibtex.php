<?php

namespace App\Models\AI\Research\SystematicReview;

use CodeIgniter\Model;

class Bibtex extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'bibtices';
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

	function import($txt='')
	{
		$txt = str_replace("\r\n", "\n", $txt);
		$txt = str_replace("\r", "\n", $txt);
		$txt = str_replace("\n", " ", $txt);	

		$tps = array('@ARTICLE','@INPROCEEDINGS','@BOOK','@INCOLLECTION','@CONFERENCE','@MASTERSTHESIS','@PHDTHESIS','@TECHREPORT','@UNPUBLISHED','@MISC');
		for($r=0;$r<count($tps);$r++)
		{
			$tp = $tps[$r];
			$txt = str_replace($tp, "\n".$tp, $txt);
		}

		$ln = explode("\n", $txt);
		$ln = $this->process($ln);
		return $ln;
	}

	function process($dt)
		{
			$rst = array();
			for ($r=0;$r<count($dt);$r++)
			{
				$ln = $dt[$r];
				$ln = trim($ln);
				if (strlen($ln)==0) continue;
				if (substr($ln,0,1)=='@')
				{
					$dta = $this->process_entry($ln);
					array_push($rst, $dta);
				}
			}
			return $rst;
		}
	function process_entry($ln)
	{
		
		preg_match_all('/,(.*)}/U', $ln, $matches);
		$lns = $matches[1];
		$dt = array();
		$dt['type'] = substr($ln, 1, strpos($ln, '{')-1);
		$idart = substr($ln, strpos($ln, '{')+1, strpos($ln, ',')-strpos($ln, '{')-1);
		$dt['id'] = $idart;
		foreach($lns as $id=>$l)
		{
			$l = trim($l);
			$pre = substr($l,0,strpos($l,'={'));
			$txt = substr($l,strpos($l,'={')+2,strlen($l));
			if (strlen($pre) > 0) { $dt[$pre] = $txt; } 
		}
		return $dt;
	}
}