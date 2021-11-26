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

	function getFullText($id)
		{
			$sql = "
			SELECT d_r2 as idf, c_class 
				FROM brapci.rdf_data 
				INNER JOIN brapci.rdf_class ON d_p = id_c 
				WHERE d_r1 = $id and c_class = 'hasFileStorage'
			";
			$dt = $this->query($sql)->getResult();
			if (count($dt) > 0)
				{
					$line = (array)$dt[0];
					$idt = $line['idf'];
					$url = 'https://brapci.inf.br/index.php/res/txt/'.$idt;
					$txt = file_get_contents($url);
					return $txt;
				}
			return '';
		}	

	function brapci_api($dt)
		{
			$title = trim($dt['title']);
			$title = troca($title,"'","´");
			$idbp = $this->search($title);
			if ($idbp == 0)
				{					
					$idbp = $this->search_word($title);
				}			
			return $idbp;
		}	

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

	function search_word($termo)
	{
		$termo = troca($termo,"'","´");
		$st = array(':',';','!','?','.');
		$termo = troca($termo,$st,' ');
		$bs = explode(' ', $termo);
		$wh = '';
		foreach($bs as $b)
			{
				if (strlen($b) > 2)
				{
					if (strlen($wh) > 0) { $wh .= ' AND '; }
					$wh .= " (n_name like '%$b%')";
				}
			}
		$sql = "select * from ".$this->table." where $wh";
		$rlt = (array)$this->db->query($sql)->getResult();
		$pre = '';
		if (count($rlt) > 0)
			{
				$line = (array)$rlt[0];
				$id = $line['id_n'];
				
				$sql = "SELECT * FROM brapci.rdf_data 
							INNER JOIN brapci.rdf_concept ON d_r1 = id_cc
							where d_p = 17 and d_literal = $id and cc_status <> 99";
				$rst = $this->query($sql)->getresult();
				if ((count($rst) > 0) and (count($rst) == 1))
					{
						$l = (array)$rst[0];
						$id_rdf = $l['d_r1'];	
						return $id_rdf;
					}
			}	
		return 0;	
	}		

	function search($termo)
	{
		$termo = troca($termo,"'","´");
		$rlt = $this->where('n_name',$termo)->findAll();
		if (count($rlt) > 0)
			{
				$line = (array)$rlt[0];
				$id = $line['id_n'];
				$pre = 'and cc_created = d_literal';
				$sql = "SELECT * FROM brapci.rdf_data 
							INNER JOIN brapci.rdf_concept ON d_r1 = id_cc $pre
							where d_p = 17 and d_literal = ".$id;
				$rst = (array)$this->query($sql)->getresult();
				if ((count($rst) > 0) and (count($rst) == 1))
					{
						$l = (array)$rst[0];
						$id_rdf = $l['d_r1'];
						return $id_rdf;
					}

			}	
		return 0;	
	}
}
