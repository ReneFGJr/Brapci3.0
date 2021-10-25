<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class LattesXML extends Model
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

	function xml($id='',$rdf='')
		{
			$dir = '.tmp/lattes/';
			$file = $dir . '/' . $id . '.xml';
			if (file_exists($file))
				{
					$xml = simplexml_load_file($file);
					$this->vinculo($xml,$id);
				} else {
					$this->LattesLoad($id,$rdf);
					$xml = simplexml_load_file($file);
					$this->vinculo($xml,$id);
				}	
			return $xml;
		}
		
	function LattesLoad($id)
		{
		$url = 'https://brapci.inf.br/ws/api/?verb=lattes&q=' . $id;

		$dir = '.tmp';
		dircheck($dir);
		$dir = '.tmp/lattes';
		dircheck($dir);

		$file = $dir . '/' . $id . '.zip';
		$file2 = $dir . '/' . $id . '.xml';

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
		$tela .= $this->xml($id);
		return $tela;
		}

	function vinculo($xml,$id)
		{
			$RDF = new \App\Models\RDF\RDF();
			$xml = (array)$xml;
			$xml = (array)$xml['DADOS-GERAIS'];
			$xml = (array)$xml['ENDERECO'];
			$xml = (array)$xml['ENDERECO-PROFISSIONAL'];
			$dados = $xml['@attributes'];

			$inst_cod = $dados['CODIGO-INSTITUICAO-EMPRESA'];

			echo '===>'.$inst_cod;
			$lang = 'pt-BR';
			$force = 0;
			$class = 'brapci:isCNPqInstCode';

			echo '<br>===>'.$RDF->find($inst_cod,$class,$force);
					echo '<pre>';
					print_r($dados);					
					exit;

		}
}
