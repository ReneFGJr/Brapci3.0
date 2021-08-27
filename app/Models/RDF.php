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
