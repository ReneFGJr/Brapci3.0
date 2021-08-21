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
		'hi',
		'yr*',

		'st20',
		'st100',
		'tx:5:10',

		'dt',
		'dt',
		'st50',

		'url',
		'url',
		'url',

		'op 1:1&2:2'
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


	function editar($o)
		{
			$sx = 'x';
			$this->id = $o->id;
			$st = form($this);
			return $st;
		}

	function viewIssue($id)
		{
			$this->EventProceedings = new \App\Models\EventProceedings();
			$this->Socials = new \App\Models\Socials();
			$sx = 'OK';
			/* Read data */
			$dt = $this->find($id);
			$di = $this->EventProceedings->find($dt['epi_procceding']);

			/* Show Data */
			$sx = $this->EventProceedings->headProceeding($di);
			$sx .= $this->headProceedingIssue($dt);

			
			print_r($dt);
			echo '<hr>';
			print_r($di);

			return $sx;
		}

	function headProceedingIssue($dt)
		{
			$sx = '';
			$sx .= bsc(bssmall(lang('epi_edition')),1);
			$sx .= bsc(bssmall(lang('epi_edition_name')),8);
			$sx .= bsc(bssmall(lang('epi_year')),1);
			$sx .= bsc(bssmall(lang('epi_date')),2);

			$sx .= bsc(h($dt['epi_edition'],4),1);
			$sx .= bsc(h($dt['epi_edition_name'],4),7);
			if ($this->Socials->perfil("#ADM"))
			{
				$img = '<img src="'.base_url('img/icones/arrow-repeat.svg').'" class="img-responsive" style="height: 32px;" title="'.lang('Proceeding Harvesting').'">';
				$img = '<a href="'.base_url(PATH.'proceedings/harvesting/'.$dt['id_epi']).'">'.$img.'</a>';
				$sx .= bsc($img,1);
			} else {
				$sx .= bsc('',1);
			}
			$sx .= bsc(h($dt['epi_year'],4),1);

			$di = $dt['epi_date_start'];
			$df = $dt['epi_date_end'];

			

			if (substr($di,0,6) == substr($df,0,6))
				{
					$data = substr($di,6,2).' '.lang('à').' '.stodbr($df);
				} else {
					$data = stodbr($dt['epi_date_start']).' '.lang('à').' '.stodbr($dt['epi_date_end']);
				}			
			$sx .= bsc($data,2);

			return $sx;
		}		

	function issues($id=0)
		{
			$sx = "";
			$dt = $this->where('epi_procceding', $id)->findAll();
			echo '==>'.$id;

			$sx .= '<a href="'.base_url(PATH.'proceedings/ed_issue/?jnl='.$id).'" class="btn btn-primary">'.lang('new').'</a>';

			for ($r=0;$r < count($dt);$r++)
				{
					$line = $dt[$r];
					$lk = '<a href="'.base_url(PATH.'proceedings/issue/'.$line['id_epi']).'">';
					$ed = '<a href="'.base_url(PATH.'proceedings/ed_issue/'.$line['id_epi']).'" class="btn-warning p-1">ed</a>';
					$lka = '</a>';
					
					$sx .= bsc($lk.$line['epi_year'].$lka,1);
					$sx .= bsc($lk.$line['epi_edition'].$lka,1);
					$sx .= bsc($lk.$line['epi_edition_name'].$lka,4);
					$sx .= bsc($lk.stodbr($line['epi_date_start'].$lka),1);
					$sx .= bsc($lk.stodbr($line['epi_date_end'].$lka),1);
					$sx .= bsc($lk.$line['epi_place'].$lka,3);
					$sx .= bsc($ed,1);
				}

			return $sx;
		}
}
