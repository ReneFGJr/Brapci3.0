<?php

namespace App\Models;

use CodeIgniter\Model;

class EventProceedingsIssue extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'event_proceedings_issue';
	protected $primaryKey           = 'id_epi';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_epi','epi_procceding','epi_year',
		'epi_edition','epi_edition_name','epi_about',
		'epi_date_start','epi_date_end','epi_place',
		'epi_url','epi_url_oai','epi_source',
		'epi_status'
	];

	protected $typeFields        = [
		'hi',
		'qr:id_ep:ep_nome:event_proceedings',
		'yr*',

		'st:20',
		'st:100',
		'tx:5:10',

		'dt',
		'dt',
		'st:50',

		'url',
		'url',
		'url',

		'op:1&2:2'
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

	var $path 				= 'proceedings_issue';

	function index($d1, $id, $dt=array(),$cab='')
	{	
		$this->path = base_url(PATH.'proceedings_issue');
		if (round($id) > 0)
			{
				$this->path_back = base_url(PATH.'proceedings/viewid/'.$id);
			} else {
				$this->path_back = base_url(PATH.'proceedings/');
			}
		
		switch ($d1) {
			case 'edit':
				$sx = $cab;
				$this->id = $id;
				$st = form($this);
				$sx .= bs(bsc($st,12));
			break;

			case 'issue':
				$sx = $cab;
				$st = $this->viewIssue($id);
				$sx .= bs($st);
				break;			

			default:
				$sx = '';
				$sx .= metarefresh(base_url(PATH.'proceedings'));
				break;
		}
		return $sx;
	}

	function next_events()
		{
			$where = 'epi_date_end >= '.date('Ymd');
			$sx = '';
			$dt = $this
					->join('event_proceedings', 'epi_procceding = id_ep', 'LEFT')
					->where($where)
					->orderBy("epi_date_start")
					->limit(10)
					->findAll();
			$sx .= '<div class="row">';
			for ($r=0;$r < count($dt);$r++)
				{
					$line = $dt[$r];
					
					$st = '<div class="card-body shadow p-3 mb-5 bg-white rounded">';
					/* URL */
					$url = $line['epi_url'];					
					$st .= '<a href="'.$url.'" target="new_'.$line['id_epi'].'">';
					$logo = $line['epi_logo'];
					if (strlen($logo) > 0)
						{
							$st .= '<img src="'.base_url($logo).'" style="float: right; max-width: 200px; max-height: 80px;">';
						}
					$st .= '<h3>';
					$st .= $line['epi_edition'].' ';
					$st .= $line['ep_abrev'];
					$st .= '</h3>';

					$st .= '<h6>';
					$st .= $line['epi_place'].' ';
					$st .= '</h6>';

					$st .= '<p>'.$this->formata_data($line).'</p>';
					$st .= '</a>';
					$st .= '</div>';
					$sx .= bsc($st,6);
				}
			if (count($dt) > 0)
			{
				$sx = '<h3>'.lang('next_events').'</h3>'.'<div class="container">'.$sx.'</div>';
			}
			$sx .= '</div>';
			
			return($sx);
		}	

	function editar($o)
		{
			$sx = 'x';
			$this->id = $o->id;
			$st = form($this);
			return $st;
		}

	function le($id)
		{
			$dt = $this->find($id);
			return $dt;
		}		

	function viewIssue($id)
		{
			$this->EventProceedings = new \App\Models\EventProceedings();
			$this->Socials = new \App\Models\Socials();
			$sx = '';

			/* Read data */			
			$this->where('epi_procceding',$id);
			$this->orderBy('epi_year DESC');
			$dt = $this->findAll();

			/* Show Data */
			$dp = $this->EventProceedings->where('id_ep',$id)->first();
			$sx = $this->EventProceedings->headProceeding($dp);
			$sx .= $this->headProceedingIssue($dt);

			$sx .= bsc('<a href="'.base_url(PATH.'proceedings_issue/edit/?jnl='.$id).'" class="btn btn-primary">'.lang('new').'</a>',1);
			
			return $sx;
		}

	function formata_data($dt)
		{
			$di = $dt['epi_date_start'];
			$df = $dt['epi_date_end'];			

			if (substr($di,0,6) == substr($df,0,6))
				{
					$data = substr($di,6,2).' '.lang('à').' '.stodbr($df);
				} else {
					$data = stodbr($dt['epi_date_start']).' '.lang('à').' '.stodbr($dt['epi_date_end']);
				}
			return($data);
		}

	function headProceedingIssue($dtt)
		{		
			$this->OaipmhListRecord = new \App\Models\OaipmhListRecord();
			$sx = '';
			$sx .= bsc(bssmall(lang('epi_edition')),1);
			$sx .= bsc(bssmall(lang('epi_edition_name')),5);
			$sx .= bsc(bssmall(lang('epi_year')),1);
			$sx .= bsc(bssmall(lang('epi_date')),2);
			$sx .= bsc(bssmall(lang('status')),3);


			/************************************************************** */
			for ($r=0;$r < count($dtt);$r++)
			{
				$dt = $dtt[$r];
				$status = $this->OaipmhListRecord->status($dt['epi_procceding'],$dt['id_epi']);

				$sx .= bsc(h($dt['epi_edition'],4),1);
				$sx .= bsc(h($dt['epi_edition_name'],4),4);

				$sx .= bsc(h($dt['epi_year'],4),1);

				/***************** Formata datas do evento */
				$data = $this->formata_data($dt);	

				$sx .= bsc($data,2);

				if  ($this->Socials->getAccess("#ADM"))
				{
					$btns = '';

					$btn = bsicone('harversting');
					$btns .= '<a href="'.base_url(PATH.'proceedings/harvesting/'.$dt['id_epi']).'" target="Harvesting Metadata">'.$btn.'</a> ';

					$btn = bsicone('edit'); 
					$btns .= '<a href="'.base_url(PATH.'proceedings_issue/edit/'.$dt['id_epi']).'" target="Edit">'.$btn.'</a> ';

					$sx .= bsc($status,3);	
					$sx .= bsc($btns,1);
				} else {
					$sx .= bsc('',1);
					$sx .= bsc('',3);
				}

				
			}

			return $sx;
		}		

	function issuesx_remove($id=0)
		{
			$sx = "";
			$dt = $this->where('epi_procceding', $id)->findAll();			

			for ($r=0;$r < count($dt);$r++)
				{
					$line = $dt[$r];
					$lk = '<a href="'.base_url(PATH.'proceedings_issue/issue/'.$line['id_epi']).'">';
					$ed = '<a href="'.base_url(PATH.'proceedings_issue/edit/'.$line['id_epi']).'" class="btn-warning p-1">ed</a>';
					$lka = '</a>';
					
					$sx .= bsc($lk.$line['epi_year'].$lka,1);
					$sx .= bsc($lk.$line['epi_edition'].$lka,1);
					$sx .= bsc($lk.$line['epi_edition_name'].$lka,4);
					$sx .= bsc($lk.stodbr($line['epi_date_start'].$lka),1);
					$sx .= bsc($lk.stodbr($line['epi_date_end'].$lka),1);
					$sx .= bsc($lk.$line['epi_place'].$lka,3);
					$sx .= bsc($ed,1);
				}

			
			$sx = bs($sx);
			return $sx;
		}
}
