<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class Xml extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'xmls';
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

	function xml($id='')
		{
			$dir = '.tmp/lattes/';
			$file = $dir . '/' . $id . '.xml';
			if (file_exists($file))
				{
					$xml = simplexml_load_file($file);
					$this->vinculo($xml,$id);
				}			
		}
		
	function Lattes($id)
		{
		$Lattes = new \App\Models\Lattes\Xml();
		$url = 'https://brapci.inf.br/ws/api/?verb=lattes&q=' . $d1;

		$dir = '.tmp';
		dircheck($dir);
		$dir = '.tmp/lattes';
		dircheck($dir);

		$file = $dir . '/' . $d1 . '.zip';
		$file2 = $dir . '/' . $d1 . '.xml';

		if (!file_exists(($file2))) {
			$txt = file_get_contents($url);
			file_put_contents($file, $txt);

			$zip = new \ZipArchive;
			$res = $zip->open($file);
			if ($res === TRUE) {
				$zip->extractTo($dir);
				$zip->close();
				unlink($file);
			}			
		}
		$tela .= $Lattes->xml($d1);
		return $tela;
		}
	function vinculo($xml,$id)
		{
			$xml = (array)$xml;
			$xml = (array)$xml['DADOS-GERAIS'];
			$xml = (array)$xml['ENDERECO'];
			$xml = (array)$xml['ENDERECO-PROFISSIONAL'];
			$dados = $xml['@attributes'];

					echo '<pre>';
					print_r($dados);					
					exit;

		}
}
