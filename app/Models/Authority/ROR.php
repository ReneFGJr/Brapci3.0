<?php

namespace App\Models\Authority;

use CodeIgniter\Model;

class ROR extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'rors';
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

	var $api_url = 'https://api.ror.org/organizations';

	function search($q)
		{
			$url = $this->api_url.'?query='.'"'.htmlentities(ascii($q)).'"';
			$url = troca($url,' ','%20');
			$txt = file_get_contents($url);
			$txt = json_decode($txt, true);
			//echo h($q,1);
			//echo '<tt>'.$url.'</tt><br/>';
			if (!isset($txt['items']))
				{
					print_r($txt);
					exit;
					return;
				}
			$items = $txt['items'];	
			$d = array();		
			for ($r=0;$r < count($items);$r++)
				{
					$inst = $items[$r];
					$AI = new \App\Models\AI\NLP\Language();
					$lang = $AI->getTextLanguage($inst['name']);
					if ($lang == 'NaN') { $d['n_inst']['en'] = $inst['name']; }
					else { $d['n_inst'][$lang] = $inst['name']; }
					/************************************* ROR */
					$d['ror'] = 'https://ror.org/'.$inst['id'];

					/************************************* Data Criação */
					if (isset($inst['established']))
						{
							$d['established'] = $inst['established'];
						} else {
							$d['established'] = '';
						}
					



					$labels = $inst['labels'];
					$d['acronyms'] = (string)$inst['acronyms'][0];
					for ($y=0;$y < count($labels);$y++)
						{
							$name = $labels[$y];
							$lang = $name['iso639'];
							$d['n_inst'][$lang] = $name['label'];
						}
				}
			return $d;
		}
}
