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
						$sx .= $this->modPQ($dt,$id);
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
			echo h($titulo,2);
			echo h($processo,6);
			echo '<p>'.$dti.'-'.$dtf.'</p>';
			echo $keys;
			echo '<pre>';
			print_r($dt);
			echo '</pre>';

			$dv = array();
			$dv['datasetVersion'] = array();
			$dv['datasetVersion']['termsOfUse'] = 'CC0 Waiver';
			$dv['datasetVersion']['license'] = 'CC0';
			

			/** Citation */
			$ci = array();
			$ci['fields'] = array();

			$primitive = array();
			$compound = array();
			$primitive['title'] = $titulo;
			$primitive['productionDate'] = $dti;
			$primitive['dsDescription'] = $abs;
			foreach($primitive as $fld => $vlr)
				{
					array_push($ci['fields'],array('typeName'=>$fld,'multiple'=>false,'value'=>$vlr,'typeClass'=>'primitive'));
				}
			
			$mb = array($ci);
			$dv['metadataBlocks'] = $mb;
			echo '<pre>';
			echo json_encode($dv,JSON_PRETTY_PRINT);
			echo '<hr>';
			print_r($dv);
		}
}
