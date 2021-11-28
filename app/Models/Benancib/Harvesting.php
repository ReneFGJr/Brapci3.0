<?php

namespace App\Models\Benancib;

use CodeIgniter\Model;

class Harvesting extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'harvestings';
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

	function harvesting_auto($offset=0)
	{
		if (strlen($offset)==0) { $offset = 5; }
		$sx = $this->havesting($offset);
		if (strlen($sx) > 0)
			{
				$offset++;
				$sx .= '';
				$sx .= metarefresh(PATH.MODULE.'benancib/harvesting_auto/'.($offset),1);
			} else {
				$sx .= 'Erro na carga do arquivo';
			}
		return $sx;
	}


	function havesting($id = 5)
	{
		if ($id < 5) { return ''; }
		dircheck('.tmp');
		dircheck('.tmp/benancib');
		dircheck('.tmp/benancib/harvesting');
		$file = '.tmp/benancib/harvesting/benancib_' . $id . '.xml';
		if (!file_exists($file)) {
			$url = 'http://repositorios.questoesemrede.uff.br/repositorios/handle/123456789/' . $id . '?show=full';
			$url2 = 'http://repositorios.questoesemrede.uff.br/repositorios/handle/123456789/' . $id;

			$pageDocument = @file_get_contents($url);
			if ($pageDocument === false) {
				if ($id > 5000)
					{
						exit;
					}
				return "Erro 404";
				exit;				
			}			
			$txt = file_get_contents($url);

			$fr = '<table xmlns:i18n="http://apache.org/cocoon/i18n/2.1" xmlns="http://di.tamu.edu/DRI/1.0/" xmlns:oreatom="http://www.openarchives.org/ore/atom/" xmlns:ore="http://www.openarchives.org/ore/terms/" xmlns:atom="http://www.w3.org/2005/Atom" class="ds-includeSet-table detailtable">';
			if ((strlen($txt) > 0) and ($pos = strpos($txt, $fr))) {
				$txt = substr($txt, $pos, strlen($txt));
				//preg_match_all('"|<td class=[^>]+>(.*)</[^>]+>|U"',$txt,$matches, PREG_PATTERN_ORDER);

				preg_match_all('/<tr.*?>(.*?)<\/tr>/si', $txt, $matches);
				$data = $matches[0];

				$simplexml = new \SimpleXMLElement('<?xml version="1.0"?><benancib/>');
				$procceding = $simplexml->addChild('record');
				$procceding->addChild('id', $id);
				$procceding->addChild('url', $url2);
				for ($r = 0; $r < count($data); $r++) {
					$ln = $data[$r];
					$ln = troca($ln, ';', '.,');
					$ln = strip_tags($ln);
					$ln = troca($ln, chr(10), ';');
					$cp = explode(';', $ln);
					$field = trim($cp[1]);
					if ($field == 'dc.referencias') {
						$cited = $procceding->addChild($field);
						$content = htmlspecialchars($cp[2]);
						$content = troca($content, '.,&amp;', '');
						$ref = explode('#13.,', $content);
						$procceding = $simplexml->addChild('citation');
						for ($q = 0; $q < count($ref); $q++) {
							$ref[$q] = troca($ref[$q], '#13', '');
							$ref[$q] = troca($ref[$q], '&amp;', '');
							$cited->addChild('cited', $ref[$q]);
						}
					} else {
						$content = htmlspecialchars($cp[2]);
						$procceding->addChild($cp[1], $content);
					}
				}
				file_put_contents($file, $simplexml->asXML());
			}			
		}
		return $file;
	}
}
