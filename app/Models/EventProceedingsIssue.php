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

	function issue($id)
		{

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
