<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class ArticleBusca extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci.rdf_name';
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

	function txt($id=0)
		{
			$url = 'https://brapci.inf.br/index.php/res/download/'.$id;
			$url = 'https://brapci.inf.br/index.php/res/txt/'.$id;
			$txt = file_get_contents($url);
			$txt = troca($txt,chr(13),'#');
			$txt = troca($txt,chr(10),'#');
			$txt = troca($txt,'.#','.'.chr(10));
			$txt = troca($txt,'#',' ');
			while (strpos($txt,'  '))
				{
					$txt = troca($txt,'  ',' ');
				}
			return $txt;
		}

	function search($termo)
	{
		$rlt = $this->where('n_name',$termo)->findAll();
		if (count($rlt) > 0)
			{
				$line = (array)$rlt[0];
				$id = $line['id_n'];
				$sql = "SELECT * FROM brapci.rdf_data where d_p = 17 and d_literal = ".$id;
				$rst = $this->query($sql)->getresult();
				if (count($rst) > 0)
					{
						$l = (array)$rst[0];
						$id_rdf = $l['d_r1'];
						return $id_rdf;
					}
			}	
		return 0;	
	}
}
