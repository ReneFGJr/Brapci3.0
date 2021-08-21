<?php

namespace App\Models;

use CodeIgniter\Model;

class OaiPMHListSetSepc extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'oai_setspec';
	protected $primaryKey           = 'id_ss';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'ss_journal','ss_issue','ss_ref','ss_group','ss_name'
	];

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


	function harvesting($dt)
		{
			$data['ss_journal'] = $dt['epi_procceding'];
			$data['ss_issue'] = $dt['id_epi'];
			$url = trim($dt['epi_url_oai']).'?verb=ListSets';
			$xml = file_get_contents($url);
			$xml = simplexml_load_string($xml);

			$ls = $xml->ListSets;
			$sx = '';
			$sx .= h(lang('ListSets'),1);
			$sx .= '<ul>';
			foreach($ls->set as $id => $reg)
				{
					$data['ss_ref'] = (string)$reg->setSpec;
					$data['ss_name'] = (string)$reg->setName;
					$data['ss_description'] = (string)$reg->setDescription;
					
					if ($this->register($data))
						{
						$sx .= '<li>'.$data['ss_ref'];
						$sx .= '</li>';
						}
				}			
			$sx .= '</ul>';
			return $sx;
		}
	function register($data)
		{
			$dt = $this->where('ss_ref',$data['ss_ref'])
				->where('ss_journal',$data['ss_journal'])
				->where('ss_issue',$data['ss_issue'])
				->findAll();
			if (!isset($dt[0]))
				{
					$this->insert($data);
				} else {
					return false;
				}
			return true;
		}
}
