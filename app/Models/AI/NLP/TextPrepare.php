<?php

namespace App\Models\AI\NLP;

use CodeIgniter\Model;

class TextPrepare extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'textprepares';
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

	function removeHttp($txt)
	{
		$t = array('http:','https:');
		for ($r=0;$r < count($t);$r++) {
			$term = $t[$r];
			while ($pos = strpos($txt,$term))
			{
				$http = substr($txt,$pos,strlen($txt));
				$http = substr($http,0,strpos($http,' '));
				$txt = troca($txt,$http,'[URL Hidden]');
			}
		}
		return $txt;
	}

	function removeNumeroEspaco($txt)
	{
		$t = array('a','b','c','d','e','f','g','h','i','j','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		for ($r=0;$r < count($t);$r++) 
			{				
			for ($y=0;$y <= 9;$y++) {
				$term = $t[$r].$y;
				if ($pos = strpos($txt,$term))
					{
						$txt = troca($txt,$term,$t[$r].' '.$y);
					}				
			}
		}
		return $txt;
	}

	function Text($txt)
		{
			$txt = mb_strtolower($txt);
			$txt = ascii($txt);
			$txt = $this->removeHttp($txt);
			$txt = $this->removeNumeroEspaco($txt);
			$txt = troca($txt,'.'.chr(13),'¢¢');
			$txt = troca($txt,'.'.chr(10),'¢¢');
			$txt = troca($txt,chr(13),' ');
			$txt = troca($txt,chr(10),' ');
			$txt = troca($txt,'¢¢¢¢','¢¢');

			$sp = array('.',',','-',';','?','!','/');
			for ($r=0;$r < count($sp);$r++)
				{					
					$txt = troca($txt,$sp[$r],' '.$sp[$r].' ');
				}
			$txt = troca($txt,'¢¢',' '.chr(13));
			return $txt;			
		}
}
