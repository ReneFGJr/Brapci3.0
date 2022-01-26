<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class LattesData extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'lattesdatas';
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

	function API_getFileCnpq($id)
		{
			$file = '.tmp/LattesData/'.$id.'.json';
			$token = getenv("token_lattes");
			$data = urlencode('Authorization').'='.urlencode('Bearer '.$token);
			$ch = curl_init("https://api.cnpq.br/lattes-data/v1/processos/".$id);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,  $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);      
			curl_close($ch);
			echo $output;
			/************* FAZER */
			exit;
			return $file;
		}

	function cachedAPI($id)
		{
			$file = '.tmp/LattesData/'.$id.'.json';
			if (file_exists($file))
				{
					return $file;
				}
			$file = '../../Datasets/processos_pq1a/'.$id.'.json';
			if (file_exists($file))
				{
					return $file;
				}
			$file = '../../Datasets/processos_incts/'.$id.'.json';
			if (file_exists($file))
				{
					return $file;
				}
			return '';
		}

	function Process($id='20113023806')
		{
			$sx = '';			
			$file = $this->cachedAPI($id);
			/************************************ GET API CNPq */
			if ($file == '')
				{
					$file = $this->API_getFileCnpq($id);
				}
			
			/*********************** read metadata */
			$dt = file_get_contents($file);
			$dt = (array)json_decode($dt);


			/********************************************** MODALIDADE */
			$MOD = 'X';
			if (isset($dt['modalidade']))
				{
				$MOD = (array)$dt['modalidade'];
				$MOD = (string)$MOD['codigo'];
				}
			
			switch($MOD)
				{
					case 'PQ':
						$Dataset = new \App\Models\Dataverse\Datasets();
						//$sx .= $this->modPQ($dt,$id);
						$dd = $this->modPQ($dt,$id);
						$sx .= $Dataset->CreateDatasets($dd);
						$msg = 'Dataset processado '.$id;
						$sx .= bsmessage($msg,1);
						break;
					case 'AI':
						$sx .= $this->modAI($dt,$id);
						break;
					default:
						$sx .= 'OPS '.$MOD.' not implemented';
						return $sx;
				}
			return $sx;
		}
	function filename($process='')
		{
			$file = ".tmp/datasets/dataset_".$process.'.json';
			return $file;
		}
	function modPQ($dt,$id)
		{
			$projeto = (array)$dt['projeto'];
			$titulo = (string)$projeto['titulo'];
			$titulo = nbr_author($titulo,7);
			$dti = brtos($dt['dataInicioVigencia']);
			$dtf = brtos($dt['dataTerminoVigencia']);

			$processo = (string)$dt['numeroProcesso'];

			$abs = (string)$projeto['resumo'];

			/**************************************************/
			$key = (string)$dt['palavrasChave'];
			$key = troca($key,', ',';');
			$key = troca($key,'. ',';');
			$key = explode(';',$key);
			$keys = '<ul>';
			foreach($key as $word)
				{
					$word = nbr_author($word,7);
					$keys .= '<li>'.$word.'</li>';
				}
			$keys .= '</ul>';

			$dv = array();
			$dv['datasetVersion'] = array();
			$dv['datasetVersion']['termsOfUse'] = 'CC0 Waiver';
			$dv['datasetVersion']['license'] = 'CC0';			

			/********************** metadataBlocks */			

			/********************************************** Citation */
			$ci = array();
			array_push($ci,$this->primitive('title',$titulo));
			array_push($ci,$this->primitive('productionDate',$this->date($dti)));

			/********************************************** Description */
			$desc = array();
			array_push($desc,$this->primitive('dsDescriptionValue',$abs));
			/* CITATION */
			array_push($ci,$this->compound('dsDescription',$desc,'dsDescriptionValue'));		


			/** Subject */
			array_push($ci,$this->controlledVocabulary('subject',array('Genetica')));	

			$mb['citation']['fields'] = $ci;

			/* Display Name */
			$mb['citation']['displayName'] = "Display Name Metadata";

			/** Author */
			$auth = array();
			array_push($auth,$this->primitive('authorAffiliation','CNPq'));
			array_push($auth,$this->primitive('authorName','Fulando de Tal'));
			/* CITATION */
			array_push($ci,$this->compound('author',$auth));		

			/* Metada Block */
			$dv['datasetVersion']['metadataBlocks'] = $mb;
			$dv['id'] = $id;
			if ((!isset($_ENV['DATAVERSE_URL'])) or (!isset($_ENV['DATAVERSE_APIKEY'])))
				{
					echo "ERRO: defina a variavel DATAVERSE_URL e DATAVERSE_APIKEY no .env";
					exit;
				}
			$dv['url'] = $_ENV['DATAVERSE_URL'];
			$dv['apikey'] = $_ENV['DATAVERSE_APIKEY'];
			$dv['api'] = 'api/dataverses/produtividadePQ1A/datasets';

			return $dv;

			//$json = json_encode($dv,JSON_PRETTY_PRINT);
			//$file = $this->filename($id);
			//file_put_contents($file,$json);
		}

		function primitive($field,$value)
			{
				$primitive = array('typeName'=>$field,'multiple'=>false,'value'=>$value,'typeClass'=>'primitive');
				return $primitive;
			}
		function controlledVocabulary($field,$value)
			{
				if (is_array($value))
					{
						$primitive = array('typeName'=>$field,'multiple'=>true,'value'=>$value,'typeClass'=>'controlledVocabulary');
					} else {
						$primitive = array('typeName'=>$field,'multiple'=>false,'value'=>$value,'typeClass'=>'controlledVocabulary');
					}
				return $primitive;
			}			
		function compound($field,$value,$subfield='')
			{
				$dt = array();
				if (strlen($subfield) > 0)
					{
						$dt[$subfield] = $value[0];
						$dt = array($dt);
					} else {
						$dt = $value;
					}

				
				$compound = array('typeName'=>$field,'multiple'=>true,'value'=>$dt,'typeClass'=>'compound');

//				echo '<pre>';
				//print_r($compound);
				//exit;

				return $compound;
			}
		function date($dt)
			{
				$dt = sonumero($dt);
				$dt = substr($dt,0,4).'-'.substr($dt,4,2).'-'.substr($dt,6,2);
				return $dt;
			}			
}
