<?php

namespace App\Models\INPI;

use CodeIgniter\Model;

class HarvestingPatent extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'harvestingpatents';
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

	protected $path          = '.tmp/inpi/publications/';

	function harvesting()
		{
			//
			$next = $this->last();
			$sx = $this->upload($next);

			return $sx;
		}
	function upload($id)
		{
			$file_dest = $this->path.'P'.$id.'.zip';
			$url = "http://revistas.inpi.gov.br/txt/P$id.zip";
			$txt = file_get_contents($url);
			file_put_contents($file_dest,$txt);
			
			$zip = new ZipArchive;
			$res = $zip->open($file_dest);
			if ($res === TRUE) {
			  $zip->extractTo($this->path);
			  $zip->close();
			  echo 'woot!';
			} else {
			  echo 'doh!';
			}			
		}
	function last()
		{
			$dir = '.tmp';
			dircheck($dir);
			$dir = '.tmp/inpi';
			dircheck($dir);
			$dir = '.tmp/inpi/patent';
			dircheck($dir);
			$dir = '.tmp/inpi/publications';
			dircheck($dir);

			for ($r=2650;$r < 2670;$r++)
				{
					$file = $dir.'/P'.$r.'.zip';
					if (!file_exists($file))
						{
							return $r;
						}
					return 0;
				}
		}
}
