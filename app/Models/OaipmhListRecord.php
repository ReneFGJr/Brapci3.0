<?php

namespace App\Models;

use CodeIgniter\Model;

class OaiPMHListRecord extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'OAI_listrecords';
	protected $primaryKey           = 'id_ls';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_ls','li_journal','li_issue',
		'li_ref','li_datestamp','li_setspec',
		'li_status','li_process','li_local_file'
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
			$this->OaipmhListSetSepc = new \App\Models\OaipmhListSetSepc();

			$data['li_journal'] = $dt['epi_procceding'];
			$data['li_issue'] = $dt['id_epi'];
			$url = trim($dt['epi_url_oai']).'?verb=ListIdentifiers';
			$url .= '&metadataPrefix=oai_dc';

			/* Load Url */
			$xml = file_get_contents($url);
			$xml = simplexml_load_string($xml);
			//$xml = simplexml_load_file('d:/lixo/xml.xml');

			$ls = $xml->ListIdentifiers;

			$sx = '';
			$sx .= h(lang('ListIdentifiers'),1);
			$sx .= '<ul>';
			$xsetspec = '';
			foreach($ls->header as $id => $reg)
				{

					$data['li_ref'] = (string)$reg->identifier;
					$setspec = (string)$reg->setSpec;
					if ($xsetspec != $setspec)
						{
							$dtss = $this->OaipmhListSetSepc
								->where('ss_journal',$data['li_journal'])
								->where('ss_issue',$data['li_issue'])
								->where('ss_ref',$setspec)
								->findAll();
							if (count($dtss) > 0)
							{
								$data['li_setspec'] = $dtss[0]['id_ss'];
							}
						}					
					$data['li_status'] = 'active';
					$data['li_datestamp'] = str_replace(array('T','Z'),' ',(string)$reg->datestamp);
					$data['li_procees'] = 0;
					$data['li_local_file'] = '';
					$att = (array)$reg;
					if (isset($att['@attributes']))
						{
							$data['li_status'] = $att['@attributes']['status'];
							if ($data['li_status'] == 'deleted')
								{
									$data['li_process'] = 9;
								}
						}
					if ($this->register($data))
						{
						$sx .= '<li>'.$data['li_ref'];
						$sx .= '</li>';
						}
				}			
			$sx .= '</ul>';
			return $sx;
		}	
	function register($data)
		{
			$dt = $this->where('li_ref',$data['li_ref'])
				->where('li_journal',$data['li_journal'])
				->where('li_issue',$data['li_issue'])
				->findAll();
			if (!isset($dt[0]))
				{
					$this->insert($data);
				} else {
					return false;
				}
			return true;
		}	

	function status($jnl,$issue)
		{
			$sql = "
			SELECT li_process, count(*) as total FROM `oai_listrecords`
				where li_journal = $jnl and li_issue = $issue
				group by li_process
				order by li_process";

			$query = $this->query($sql);
			$rlt = $query->getResult();
			$sx = '';
			foreach ($rlt as $obj=>$line)				
				{
					$sx .= '<a href="'.base_url(PATH.'proceedings/gets/'.$issue.'?issue='.$issue.'&process='.$line->li_process).'">'.lang('status_'.$line->li_process).' ('.$line->total.')</a><br>';
				}
			return $sx;
		}			
}