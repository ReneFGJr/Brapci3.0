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

	function resume($id,$issue=0)
		{
			$tela = '';
			$wh = 'lr_jnl = '.$id;
			if ($issue > 0)
				{
					$wh = 'lr_issue = '.$issue;
				}

			$sql = "select count(*) as total, lr_procees 
					from ".$this->table."
					where $wh
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
			$tela .= bsc(lang('brapci.oai_status_0').'<h1><a href="'.(PATH.MODULE.'admin/oai/records/'.$id.'/0').'">'.$v[0].'</a></h1>',2,'border');
			$tela .= bsc(lang('brapci.oai_status_1').'<h1><a href="'.(PATH.MODULE.'admin/oai/records/'.$id.'/1').'">'.$v[1].'</a></h1>',2,'border');
			$tela .= bsc(lang('brapci.oai_status_2').'<h1><a href="'.(PATH.MODULE.'admin/oai/records/'.$id.'/2').'">'.$v[2].'</a></h1>',2,'border');
			$tela .= bsc(lang('brapci.oai_status_9').'<h1><a href="'.(PATH.MODULE.'admin/oai/records/'.$id.'/9').'">'.$v[3].'</a></h1>',2,'border');
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
			$sx = h($url,2);
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
					$sx .= '<li>'.$data['lr_identifier'];
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

	function update_journal($id)	
		{
			$OaipmhListRecord = new \App\Models\Oaipmh\OaipmhListRecord();
			$dts = $OaipmhListRecord
					->where('lr_issue',$id)
					->findAll();
			print_r($dts);
			exit;
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
	
	function listrecords($id,$st=-1,$act='')
		{
			$OaipmhRegister = new \App\Models\Oaipmh\OaipmhRegister();
			$sx = '';
			$this->join('source_listsets','lr_setSpec = id_ls');
			$this->where('lr_jnl',$id);
			if ($st != -1)
				{
					$this->where('lr_procees',$st);
				}
			$dt = $this->findAll();

			if  ($this->Socials->getAccess("#ADM"))
				{
					//echo '===>'.$st;
					if ($st == 0)
						{
							$sb = '<a href="'.PATH.MODULE.'admin/oai/get_record/A/'.$id.'" class="btn btn-outline-primary">'.lang('brapci.harvesting_all').'</a>';
						}
					if ($st == 1)
						{
							$sb = '<a href="'.PATH.MODULE.'admin/oai/process_record/A/'.$id.'" class="btn btn-outline-primary">'.lang('brapci.harvesting_all').'</a>';
						}
					if (isset($sb)) { $sx .= bsc($sb,12); }
				}
				
			if ($act=='A')
				{
					$sx ='FIM';
					return $sx;
				}

			$sx .= '<table class="table table-striped">';
			$sx .= '<tr>';
			$sx .= '<th>'.lang('brapci.lr_identifier').'</th>';
			$sx .= '<th>'.lang('brapci.lr_status').'</th>';
			$sx .= '<th>'.lang('brapci.ls_setSpec').'</th>';
			$sx .= '<th>'.lang('brapci.action').'</th>';
			$sx .= '<th>'.lang('brapci.lk').'</th>';
			$sx .= '</tr>';

			for ($r=0;$r < count($dt);$r++)
				{
					$ln = $dt[$r];

					$link = PATH.MODULE.'admin/oai/record/'.$ln['id_lr'];
					$link = '<a href="'.$link.'">';
					$linka = '</a>';
					$sx .= '<tr>';
					$sx .= '<td>';
					$sx .= $link.$ln['lr_identifier'].$linka;
					$sx .= '</td>';

					$sx .= '<td>';
					$sx .= $ln['lr_status'];
					$sx .= '</td>';

					$sx .= '<td>';
					$sx .= $ln['ls_setSpec'];
					$sx .= '</td>';

					$act = $OaipmhRegister->actions($ln);
					$sx .= '<td>';
					$sx .= $act;
					$sx .= '</td>';	

					$sx .= '<td>';
					$sx .= $this->link($ln);
					$sx .= '</td>';										
				}

			if (count($dt) == 0)
				{
					//$sx = $this->getlastquery();
					$sx .= bsmessage(lang('table empty'),3);
					$sx .= '<a href="'.PATH.MODULE.'">'.lang('brapci.return').'</a>';
				}
			$sx .= '</table>';
			$sx = bs(bsc($sx,12));
			return $sx;
		}

	function link($dt)
		{
			$sx = '';
			if ($dt['lr_work'] > 0)
				{
					$sx .= '<a href="'.PATH.MODULE.'v/'.$dt['lr_work'].'">';
					$sx .= bsicone('link');
					$sx .= '</a>';
				}
			return $sx;
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