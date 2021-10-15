<?php

namespace App\Models;

use CodeIgniter\Model;

class OpenDataCountry extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'OA_Country';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_ct','ct_code','ct_name','ct_lang'
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

	/* Source Data: https://github.com/umpirsky/country-list */

	function export()
		{
			/* Checar se já nao foi coletado */
			$dir = '../.temp/';
			if (!is_dir($dir)) { mkdir($dir); }
			$dir .= 'oa/';
			if (!is_dir($dir)) { mkdir($dir); }

			$file = '../.temp/oa/country-$lang.php';
			$dt = $this->FindAll();
			$p = array();
			foreach($dt as $id=>$line)
				{
					$lang = $line['ct_lang'];
					$name = $line['ct_name'];
					if (!isset($p[$lang])) { $p[$lang] = array(); }
					array_push($p[$lang],$name);
				}
			foreach($p as $dt=>$idx)
				{
					$file_save = str_replace('$lang',$dt,$file);
					$txt = '$country = array(';
					for ($r=0;$r < count($idx);$r++)
						{
							if ($r > 0) { $txt .= ', ';}
							$txt .= "'".mb_strtolower($idx[$r])."'";
						}
					$txt .= ');';
					echo $file_save;
					file_put_contents($file_save,$txt);
				}
				return TRUE;
		}	

	function inport($url='')
		{
			$url = 'http://cedapdados.ufrgs.br/api/access/datafile/:persistentId?persistentId=hdl:20.500.11959/CedapDados/3/8';
			$lang = 'pt-BR';
			$dir = '.tmp';
			$file = md5($url);
			$filename = $dir.'/'.$file;

			if (!is_dir($dir))
				{
					mkdir($dir);
				}
			if (file_exists($filename))
			{
				$txt = file_get_contents($filename);
			} else {
			/************************************* */
				$txt = file_get_contents($url);
				file_put_contents($filename,$txt);
			}
			$txt = str_replace(array('"'),array(''),$txt);
			$lns = explode(chr(10),$txt);
			$hd = explode(chr(9),$lns[0]);
			
			for ($r=01;$r < count($lns);$r++)
				{
					$ln = explode(chr(9),$lns[$r]);
					if (count($ln) > 1)
					{
						for ($y=0;$y < count($hd);$y++)
							{
								$dt[$hd[$y]] = $ln[$y];
							}

						$dz = $this->
									where('ct_code',$dt[$hd[0]])->
									where('ct_lang',$dt[$hd[2]])->
									findAll();
						
						if (isset($dz[0]))
							{

							} else {
								$this->insert($dt);
							}					
					}
				}
		}
}
