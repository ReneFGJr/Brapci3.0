<?php

namespace App\Models\Oaipmh;

use CodeIgniter\Model;

class OaiPMHListSetSepc extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci.source_listsets';
	protected $primaryKey           = 'id_ss';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_ls','ls_setSpec','ls_description','ls_journal','ls_setName'
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


	function harvesting($dt,$tp='JA')
		{
			switch($tp)
				{
					case 'EV':
						$url = trim($dt['is_url_oai']).'?verb=ListSets';
						break;
					case 'JA':
						$data['ss_journal'] = $dt['epi_procceding'];
						$data['ss_issue'] = $dt['id_epi'];			
						$url = trim($dt['epi_url_oai']).'?verb=ListSets';
						break;
				}
			
			$xml = file_get_contents($url);
			$xml = simplexml_load_string($xml);

			$ls = $xml->ListSets;
			$sx = '';
			$sx .= h(lang('ListSets'),1);
			$sx .= '<ul>';
			foreach($ls->set as $id => $reg)
				{
					$data['ls_setSpec'] = (string)$reg->setSpec;
					$data['ls_setName'] = (string)$reg->setName;
					$data['ls_description'] = (string)$reg->setDescription;
					$data['ls_journal'] = $dt['is_source_rdf'];
					$sx .= '<li>'.$data['ls_setName'] . ' <sup>('.$data['ls_setSpec'].')</sup>';

					if ($this->register($data))
						{
							$sx .= ' - <span class="text-primary">'.lang('brapci.Registered').'</span>';
						} else {
							$sx .= ' - <span class="text-warning">'.lang('brapci.already_Registered').'</span>';
						}
					$sx .= '</li>';
				}			
			$sx .= '</ul>';
			return $sx;
		}
	function register($data)
		{
			//ls_description
			$dt = $this->where('ls_setSpec',$data['ls_setSpec'])
				->where('ls_journal',$data['ls_journal'])
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
