<?php

namespace App\Models;

use CodeIgniter\Model;

class RDF extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'rdfs';
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

	function le($id)
		{
			$RDFConcept = new \App\Models\RDFConcept();
			$dt['concept'] = $RDFConcept->le($id);
						
			$RDFData = new \App\Models\RDFData();
			$dt['data'] = $RDFData->le($id);

			return($dt);
		}

	function info($id)
		{
			$sx = '';
			$id = round($id);
			$file = '.c/'.round($id).'/.name';
			
			if (file_exists(($file)))
				{
					return file_get_contents($file);
				}
			return '';
		}

	function export($id)
		{
			$sx = '';
			$id = round($id);
			if ($id > 0)
			{
				$dir = '.c/';
				if (!is_dir($dir)) { mkdir($dir); }
				$dir = '.c/'.round($id).'/';
				if (!is_dir($dir)) { mkdir($dir); }
			} else {
				$sx .= 'ID ['.$id.'] inválido<br>';
			}

			/*************************************************************** EXPORT */
			$RDFData = new \App\Models\RDFData();
			$RDFConcept = new \App\Models\RDFConcept();

			$dt = $this->le($id);

			$class = $dt['concept']['c_class'];
			$txt_name = $dt['concept']['n_name'];

			/******************************************************* ARQUIVOS ********/
			$file_name = $dir.'.name';

			/********************************************************** VARIÁVEIS ****/
			$txt_journal = '';
			$txt_author ='';

			/********************************************************** WORK *********/
			switch($class)
				{
					case 'Work':
						for ($w=0;$w < count($dt['data']);$w++)
							{
								$dd = $dt['data'][$w];
								$dclass = $dd['c_class'];
								switch($dclass)
									{
										case 'title':
										$txt_title = $dd['n_name'];
										break;

										case 'isWorkOf':
										$x = $this->le($dd['d_r2']);
										$txt_journal = $x['concept']['n_name'];
										break;

										case 'creator':
										$x = $this->le($dd['d_r2']);
										if (strlen($txt_author) > 0) { $txt_author .= '; '; }
										$txt_author .= $x['concept']['n_name'];
										break;										
									}
							}
						/*
						echo '<pre>';
						print_r($dt);
						echo '<hr>';
						exit;		
						*/
						break;	
				}		
				

			/*************************************************************** HTTP ****/
			if (substr($txt_name,0,4) == 'http')
				{
					$txt_name = '<a href="'.$txt_name.'" target="_new">'.$txt_name.'</a>';
				}				

			/******************************************************** JOURNAL NAME  */
			if (strlen($txt_author) > 0)
				{
					$txt_name = $txt_author . $txt_title . '. <b>[Anais...]</b> '.$txt_journal.'.';
				}
			
			/******************************************************* SALVAR ARQUIVOS */
			if (strlen($txt_name) > 0) { file_put_contents($file_name,$txt_name); }

			$sx = $txt_name.' exported<br>';
			return $sx;
		}

	function view_data($dt)
		{
			$concept = $dt['concept'];
			$class = $dt['concept']['prefix_ref'].':'.$dt['concept']['c_class'];

			$sx = '';
			$sx .= '<h2>'.$dt['concept']['n_name'];
			$sx .= '<h5>'.$class.'</h5>';

			$sx .= bsc(lang('created_at'),2);
			$sx .= bsc(lang('updated_at'),2);
			$sx .= bsc(lang('cc_library'),1);
			$sx .= bsc(lang('prefix_url'),7);

			$url = $dt['concept']['prefix_url'].'#'.$dt['concept']['c_url'];
			$url = '<a href="'.$url.'" target="_new">'.$url.'</a>';
			$sx .= bsc($dt['concept']['created_at'],2);
			$sx .= bsc($dt['concept']['updated_at'],2);
			$sx .= bsc($dt['concept']['cc_library'],1);
			$sx .= bsc($url,7);

			$sx .= var_dump($dt,false);

			if (isset($dt['data']))
				{
					$dts = $dt['data'];
					print_r($dts);
				}
			

			$sx = bs($sx);
			return $sx;
		}

	function index($d1,$d2,$d3='',$cab='')
		{
			$sx = '';
			$type = get("type");
			switch($d1)
				{					
					case 'inport':
						$sx = $cab;
						switch($type)
							{
								case 'prefix':
								$this->RDFPrefix = new \App\Models\RDFPrefix();
								$sx .= $this->RDFPrefix->inport();
								break;

								case 'class':
								$this->RDFClass = new \App\Models\RDFClass();
								$sx .= $this->RDFClass->inport();
								break;
							}
					break;
					/************* Default */
					default:
						$sx = $cab;
						$sx .= lang('command not found').': '.$d1;
						$sx .= '<ul>';
						$sx .= '<li><a href="'.base_url(PATH.'rdf/inport?type=prefix').'">'.lang('Inport Prefix').'</a></li>';
						$sx .= '<li><a href="'.base_url(PATH.'rdf/inport?type=class').'">'.lang('Inport Class').'</a></li>';
						$sx .= '</ul>';
				}
			$sx = bs($sx);
			return $sx;
		}
}
