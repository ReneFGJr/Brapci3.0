<?php

namespace App\Models\Oaipmh;

use CodeIgniter\Model;

class OaiPMHListRecord extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci.source_listrecords';
	protected $primaryKey           = 'id_ls';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_lr','lr_identifier','lr_datestamp',
		'lr_setSpec','lr_status','lr_jnl',
		'lr_issue'
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

	function resume($id)
		{
			$MOD = df('MOD','/');
			$tela = '';
			$sql = "select count(*) as total, lr_procees 
					from ".$this->table."
					where lr_jnl = $id
					group by lr_procees";

			$dt = $this->query($sql)->getResult();
			$v = array(0,0,0,0);

			for ($r=0;$r < count($dt);$r++)
				{
					$d = (array)$dt[$r];
					if ($d['lr_procees'] == 0) { $v[0] = number_format($d['total'],0,',','.'); }
					if ($d['lr_procees'] == 1) { $v[1] = number_format($d['total'],0,',','.'); }
					if ($d['lr_procees'] == 2) { $v[2] = number_format($d['total'],0,',','.'); }
					if ($d['lr_procees'] == 9) { $v[9] = number_format($d['total'],0,',','.'); }
				}
			$tela .= bsc(lang('brapci.oai_status_0').'<h1><a href="'.(PATH.MODULE.$MOD.'/oai/'.$id.'/0').'">'.$v[0].'</a></h1>',2,'border');
			$tela .= bsc(lang('brapci.oai_status_1').'<h1><a href="'.(PATH.MODULE.$MOD.'/oai/'.$id.'/1').'">'.$v[1].'</a></h1>',2,'border');
			$tela .= bsc(lang('brapci.oai_status_2').'<h1><a href="'.(PATH.MODULE.$MOD.'/oai/'.$id.'/2').'">'.$v[2].'</a></h1>',2,'border');
			$tela .= bsc(lang('brapci.oai_status_9').'<h1><a href="'.(PATH.MODULE.$MOD.'/oai/'.$id.'/9').'">'.$v[3].'</a></h1>',2,'border');
			return $tela;
		}

	function harvesting($dt,$tp='JA')
		{
			$OaipmhListSetSepc = new \App\Models\Oaipmh\OaipmhListSetSepc();

			switch($tp)
				{
					case 'EV':
						$url = trim($dt['is_url_oai']).'?verb=ListIdentifiers';
						$url .= '&metadataPrefix=oai_dc';
						$d['lr_jnl'] = $dt['is_source_rdf'];
					break;

					default:
						$data['li_journal'] = $dt['epi_procceding'];
						$data['li_issue'] = $dt['id_epi'];
						$dt['id_is'] = 0;
						$url = trim($dt['epi_url_oai']).'?verb=ListIdentifiers';
						$url .= '&metadataPrefix=oai_dc';
					break;
				}

			/* Load Url */
			$xml = file_get_contents($url);
			$xml = simplexml_load_string($xml);
			//$xml = simplexml_load_file('d:/lixo/xml.xml');
			$ls = $xml->ListIdentifiers;

			$sx = '';
			$sx .= h(lang('ListIdentifiers'),1);
			$sx .= '<ul>';
			$xsetspec = '';
			$setspec_id = 0;
			foreach($ls->header as $id => $reg)
				{

					$data['lr_identifier'] = (string)$reg->identifier;					
					$data['lr_status'] = 'active';
					$data['lr_datestamp'] = str_replace(array('T','Z'),' ',(string)$reg->datestamp);
					$data['lr_procees'] = 0;
					$data['lr_jnl'] = $dt['is_source_rdf'];
					$data['lr_issue'] = $dt['id_is'];

					$setspec = (string)$reg->setSpec;
					if ($xsetspec != $setspec)
						{
							$dts = $OaipmhListSetSepc
								->where('ls_setSpec',$setspec)
								->where('ls_journal',$data['lr_jnl'])
								->findAll();
							$setspec_id = $dts[0]['id_ls'];
							$xsetspec = $setspec;
						}
					$data['lr_setSpec'] = $setspec_id;				
					$att = (array)$reg;
					if (isset($att['@attributes']))
						{
							$data['lr_status'] = $att['@attributes']['status'];
							if ($data['lr_status'] == 'deleted')
								{
									$data['lr_procees'] = 9;
								}
						}

					if ($this->register($data))
						{
						$sx .= '<li>'.$data['lr_identifier'];
						$sx .= '</li>';
						}
				}			
			$sx .= '</ul>';
			return $sx;
		}	

	function register($data)
		{
			$dt = $this->where('lr_identifier',$data['lr_identifier'])
				->where('lr_jnl',$data['lr_jnl'])
				->where('lr_setSpec',$data['lr_setSpec'])
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
			SELECT li_process, count(*) as total FROM OAI_ListRecords
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