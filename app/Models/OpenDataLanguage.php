<?php

namespace App\Models;

use CodeIgniter\Model;

class OpenDataLanguage extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'OA_Language';
	protected $primaryKey           = 'id_lg';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_lg','lg_code','lg_name','lg_lang'
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

	function check($lang)
		{
			
		}

	function inport($url='')
		{
			$url = 'http://cedapdados.ufrgs.br/api/access/datafile/:persistentId?persistentId=hdl:20.500.11959/CedapDados/3/7';
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
									where('lg_code',$dt['lg_code'])->
									where('lg_lang',$dt['lg_lang'])->
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
