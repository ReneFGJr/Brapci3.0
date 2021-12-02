<?php

namespace App\Models\Benancib;

use CodeIgniter\Model;

class Harvesting extends Model
{
	var $status = 0;
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

	function check_harvesting()
	{
		$total = 3654;
		$dir = '.tmp/benancib/harvesting/';
		$sx = '<table class="table">';
		$err1 = 0;
		$err2 = 0;
		for ($r = 5; $r < $total; $r++) {
			$id = $r;
			$file1 = $dir . 'benancib_' . $id . '.xml';
			$file2 = $dir . 'benancib_' . $id . '.pdf';
			$ok = array(0,0);

			$sl = '<tr>';
			$sl .= '<td>' . $id . '</td>';
			if (!file_exists($file1)) {
				$sl .= '<td>' . 'not harveted' . '</td>';
				$err1++;
			} else {
				$sl .= '<td>' . 'OK' . '</td>';
				$ok[0] = 1;
			}
			if (!file_exists($file2)) {
				$sl .= '<td>' . 'not harveted' . '</td>';
				$err2++;
			} else {
				$sl .= '<td>' . 'OK' . '</td>';
				$ok[1] = 1;
			}
			$sl .= '</tr>';
			if ($ok[0]+$ok[1] == 2) 
			{
				$sx .= $sl;				
			}
		}
		$sx .= '</table>';
		$sa = '<table class="table">';
		$sa .= '<tr><th>Erros Metadata</th><th>Erros PDF</th></tr>';
		$sa .= '<tr><td class="h1">'.$err1.'</td><td class="h1">'.$err2.'</td></tr>';
		$sa .= '</table>';
		return $sa . $sx;
	}

	function harvesting_auto($offset = 0)
	{
		if (strlen($offset) == 0) {
			$offset = 5;
		}
		$sx = $this->havesting($offset);
		if (strlen($sx) > 0) {
			$offset++;
			$sx .= '';
			if ($this->status > 0) {
				$sx .= metarefresh(PATH . MODULE . 'benancib/harvesting_auto/' . ($offset), 5);
				$sx .= bsmessage('Harvesting Success!');
			} else {
				$sx .= metarefresh(PATH . MODULE . 'benancib/harvesting_auto/' . ($offset), 1);
				$sx .= bsmessage('Already harvested!');
			}
		} else {
			$sx .= 'Erro na carga do arquivo';
		}
		return $sx;
	}

	function harvesting_auto_pdf($offset = 0)
	{
		if (strlen($offset) == 0) {
			$offset = 5;
		}
		$sx = $this->harvesting_pdf($offset);
		if (strlen($sx) > 0) {
			$offset++;
			$sx .= '';
			if ($this->status > 0) {
				$sx .= metarefresh(PATH . MODULE . 'benancib/harvesting_pdf/' . ($offset), 5);
				$sx .= bsmessage('Harvesting PDF Success!');
			} else {
				$sx .= metarefresh(PATH . MODULE . 'benancib/harvesting_pdf/' . ($offset), 1);
				$sx .= bsmessage('Already PDF harvested!');
			}
		} else {
			$sx .= 'Erro na carga do arquivo';
		}
		return $sx;
	}

	function harvesting_pdf($id = 5)
	{
		dircheck('.tmp');
		dircheck('.tmp/benancib');
		dircheck('.tmp/benancib/harvesting');

		$http = 'http://repositorios.questoesemrede.uff.br';
		$url = 'http://repositorios.questoesemrede.uff.br/repositorios/handle/123456789/' . $id;
		$pageDocument = @file_get_contents($url);
		if ($pageDocument === false) {
			if ($id > 5000) {
				exit;
			}
			return "Erro 404";
			exit;
		}
		$fl = '.tmp/benancib/harvesting/benancib_' . $id . '.pdf';
		$file = '.tmp/benancib/harvesting/benancib_' . $id . '.pdf';
		if (!file_exists($fl)) {
			$txt = file_get_contents($url);
			file_put_contents($fl, $txt);
		} else {
			$txt = file_get_contents($fl);
		}
		/** <a class="image-link" */
		preg_match_all('/<a class="image-link".*?>(.*?)<\/a>/si', $txt, $matches);
		$data = $matches[0];

		# Remove HTML Tags
		if (isset($data[0])) {
			$txt = $data[0];
			$txt = substr($txt, strpos($txt, 'href="') + 6, strlen($txt));
			$txt = substr($txt, 0, strpos($txt, '"'));
			$url = $http . $txt;

			/******************************** Arquivo */
			if (!file_exists($file)) {
				$pageDocument = @file_get_contents($url);
				if ($pageDocument === false) {
					if ($id > 5000) {
						exit;
					}
					return "Erro 404";
					exit;
				}
			}

			$txt = file_get_contents($url);
			file_put_contents($file, $txt);
			$this->status = 1;
		} else {
			$url .= 'Not locate';
		}
		return $url;
	}

	function havesting($id = 5)
	{
		if ($id < 5) {
			return '';
		}
		dircheck('.tmp');
		dircheck('.tmp/benancib');
		dircheck('.tmp/benancib/harvesting');
		$file = '.tmp/benancib/harvesting/benancib_' . $id . '.xml';
		if (!file_exists($file)) {
			$this->status = 1;
			$url = 'http://repositorios.questoesemrede.uff.br/repositorios/handle/123456789/' . $id . '?show=full';
			$url2 = 'http://repositorios.questoesemrede.uff.br/repositorios/handle/123456789/' . $id;

			$pageDocument = @file_get_contents($url);
			if ($pageDocument === false) {
				if ($id > 5000) {
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
		} else {
			$this->status = -1;
		}
		return $file;
	}
}
