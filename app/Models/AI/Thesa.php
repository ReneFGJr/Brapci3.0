<?php

namespace App\Models\AI;

use CodeIgniter\Model;

class Thesa extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'thesas';
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

	function import($id,$force=0)
		{
			dircheck('.tmp');
			dircheck('.tmp/thesa');
			$dir = '.tmp/thesa/';
			$file = $dir.'thesa_txt_'.$id.'.txt';
			if ((!file_exists($file)) or ($force==1))
			{
				$url = 'https://www.ufrgs.br/tesauros/index.php/thesa/terms_from_to/'.$id.'/txt';
				$txt = file_get_contents($url);
				file_put_contents($file,$txt);
			}
			return $this->le($id);			
		}
		
	function le_array($id=243)
		{
			$txt = $this->le($id);
			$txt = mb_strtolower($txt);
			$txt = ascii($txt);
			$txt = troca($txt,chr(10),chr(13));
			$txt = troca($txt,chr(13).chr(13),chr(13));
			$txt = troca($txt,'=>','£');
			$ln = explode(chr(13),$txt);

			$vc = array();
			for ($r=0;$r < count($ln);$r++)
				{
					$l = $ln[$r];
					$terms = explode('£',$l);
					if (count($terms) == 2)
						{
							$t1 = trim($terms[0]);
							$t2 = trim($terms[1]);
							$t1 = strzero(strlen($t1),5).$t1;
							$vc[$t1] = $t2;
						}
				}
			krsort($vc);
			$vca = array();
			foreach($vc as $term=>$value)
				{
					$term = substr($term,5,strlen($term));
					$vca[$term] = $value;
				}
			return $vca;
		}
	function le($id)
		{
			$dir = '.tmp/thesa/';
			$file = $dir.'thesa_txt_'.$id.'.txt';
			if (file_exists($file))
				{
					$txt = file_get_contents($file);
					return $txt;
				} else {
					$txt = $this->import($id);
				}
			return '';
		}
}
