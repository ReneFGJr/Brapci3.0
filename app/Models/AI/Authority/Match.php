<?php

namespace App\Models\AI\Authority;

use CodeIgniter\Model;

class Match extends Model
{
	protected $DBGroup              = 'default';
	var $table                = 'matches';
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

	function check($txt)
		{
			$TextPrepare = new \App\Models\AI\NLP\TextPrepare();
			$txt = $TextPrepare->removeSimbols($txt);
			$txt = explode(' ', $txt);

			$sql = 'select * from '.$this->table.' where ';
			$sql .= "a_use = 0 ";
			for ($r=0;$r < count($txt);$r++)
				{
					if (strlen($txt[$r]) > 1)
					{
						$sql .= " and (a_prefTerm LIKE '%".$txt[$r]."%')";
					}
				}
			$dt = (array)$this->query($sql)->getResult();	

			/**********************************************************************/	
			$pref = 0;
			
			for ($r=0;$r < count($dt);$r++)
				{
					$line = (array)$dt[$r];
					if ($pref == 0) 
					{ 
						$pref = $line['id_a']; 
					} else {
						$this->remissiveUse($line['id_a'],$pref);
					}
				}
			return 1;
		}
		function remissiveUse($id,$use)
			{
				$sql = "update ".$this->table." set a_use = ".$use." where id_a = ".$id;
				$this->query($sql);
				return 1;
			}
}
