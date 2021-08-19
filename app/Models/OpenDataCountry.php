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
