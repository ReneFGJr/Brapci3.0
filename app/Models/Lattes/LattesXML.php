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

	function xml($id='',$rdf=0)
		{
			clog('Harvesting XML');
			$dir = '.tmp/lattes/';
			$file = $dir . '/' . $id . '.xml';			
			if (file_exists($file))
				{
					clog('Harvesting XML - Load File');
					$xml = simplexml_load_file($file);
				} else {
					clog('Harvesting XML - Import from CNPq');
					$this->LattesLoad($id,$rdf);
					$xml = simplexml_load_file($file);
				}	
			clog('Harvesting XML - End');
			$this->vinculo($xml,$rdf);	
			return $xml;
		}
		
	function LattesLoad($id)
		{
		$tela = '';
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
		clog('Lattes Load - End');
		return $tela;
		}

	function vinculo($xml,$id)
		{
			$RDF = new \App\Models\RDF\RDF();
			$RDFData = new \App\Models\RDF\RDFData();
			$RDFClass = new \App\Models\RDF\RDFClass();

			$xml = (array)$xml;
			$xml = (array)$xml['DADOS-GERAIS'];
			$xml = (array)$xml['ENDERECO'];
			$xml = (array)$xml['ENDERECO-PROFISSIONAL'];
			$dados = $xml['@attributes'];
			$class = 'brapci:isCNPqInstCode';
			

			/* Instituição */
			$inst = $dados['NOME-INSTITUICAO-EMPRESA'];			
			$class = 'frbr:CorporateBody';			
			$idc = $RDF->RDP_concept($inst,$class);
			clog('Lattes - Instrituicao');

			/* Codigo */
			$prop = 'brapci:isCNPqInstCode';			
			$codo = $dados['CODIGO-INSTITUICAO-EMPRESA'];
			$idl = $RDFData->literal($idc,$prop,$codo);

			/* Instituição */
			clog('Lattes - Lugar');

			$inst = $dados['PAIS'];			
			if (strlen($inst) > 0) {
			$class = 'brapci:Place';			
			$id_country = $RDF->RDP_concept($inst,$class);
			}

			$inst = $dados['UF'];	
			if (strlen($inst) > 0) {
			$class = 'frbr:Place';			
			$id_state = $RDF->RDP_concept($inst,$class);
			$RDF->propriety($id_country,'brapci:haveState',$id_state);
			}

			$inst = $dados['CIDADE'];			
			if (strlen($inst) > 0) {
			$class = 'frbr:Place';			
			$id_city = $RDF->RDP_concept($inst,$class);
			$RDF->propriety($id_state,'brapci:haveCity',$id_city);
			}
			
			/* Instituição */
			if ($id_city > 0)
			{
				$RDF->propriety($idc,'brapci:isPlace',$id_city);
			}

			$inst = $dados['NOME-ORGAO'];			
			$class = 'frbr:CorporateBodyDep';			
			$idcd = $RDF->RDP_concept($inst,$class);
			//$RDF->propriety($id_country,'brapci:haveCity',$id_state);

			/* Affiliation */
			$inst = 'Affiliation:'.strzero($idc,8).'.'.strzero($id,8);
			$class = 'frbr:Affiliation';			
			$id_aff = $RDF->RDP_concept($inst,$class);

			if ($id_aff > 0)
			{
				$RDF->propriety($id,'brapci:affiliatedWith',$id_aff);
			}			
		}
}
