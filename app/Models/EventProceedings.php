<?php

namespace App\Models;

use CodeIgniter\Model;

class EventProceedings extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'event_proceedings';
	protected $primaryKey           = 'id_ep';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = 
	[
		'id_ep', 'ep_nome', 'ep_abrev',
		'ep_url', 'ep_url_oai'
	];

	protected $typeFields        = [
		'hidden',
		'st:100*',
		'st:50*',
		'st:100*',
		'st:100*',
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

	// Edit Model
	protected $path 				= 'proceedings';


	function resume()
		{
			$file = '../.temp/_resume.json';
			$sx = '';

			if (file_exists($file))
			{
				$dt = (array)json_decode(file_get_contents($file));

				foreach($dt as $value=>$total)
					{
					$sx .= bsc('
						<div style="border-right: 2px #888 solid; width: 100%" class="p-3">
						<h1 class="resume_h1">'.$total.'</h1>
						<h5 class="resume_h5">'.lang($value).'</h5>
						</div>'
						,2);
					}
			}
			
			$sx = bs($sx);

			$sx .= '
			<link rel="preconnect" href="https://fonts.googleapis.com">
			<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			<link href="https://fonts.googleapis.com/css2?family=Hina+Mincho&display=swap" rel="stylesheet">
			<style>
			 .resume_h1 { font-family: \'Hina Mincho\', serif; font-size: 250%; font-weight: bold; }
			 .resume_h5 { font-family: \'Hina Mincho\', serif; font-size: 120%;  }
			</style>
			';
			return($sx);
		}

	function index($d1, $id, $id2, $dt, $cab='')
	{	
		$this->path = base_url(PATH.'proceedings');
		$this->path_back = base_url(PATH.'proceedings');
		switch ($d1) {
			case 'gets':
				$sx = $cab;
				$st = get("process");
				$this->Oaipmh = new \App\Models\Oaipmh();
				switch($st)
					{
						case '0':
							$this->OaipmhRegister = new \App\Models\OaipmhRegister();
							$st = $this->OaipmhRegister->process_00($id);
						break;

						case '1':
							$this->OaipmhRegister = new \App\Models\OaipmhRegister();
							$st = $this->OaipmhRegister->process_01($id);
						break;

						default:
							$this->OaipmhRegister = new \App\Models\OaipmhRegister();
							$st = $this->OaipmhRegister->process_01($id);
						break;

					}
				$sx .= bs($st);
				break;			
			break;

			case 'export':
				$sx = $this->export($id,$id2);
				break;

			case 'edit':
				$sx = $cab;
				$this->id = $id;
				$st = form($this);
				$sx .= bs(bsc($st,12));
			break;

			case 'viewid':
				$EventProceedingsIssue = new \App\Models\EventProceedingsIssue();
				$sx = $cab;
				$st = $EventProceedingsIssue->viewIssue($id);
				$sx .= bs($st);
				break;

			case 'harvesting':
				$sx = $cab;
				$this->Oaipmh = new \App\Models\Oaipmh();
				$st = $this->Oaipmh->index($id,$dt);
				$sx .= bs($st);
				break;					

			default:
				$sx = $cab;
				$st = h("Proceedings - View", 1);
				$this->id = $id;				
				$dt =
					[
						'services' => $this->paginate(3),
						'pages' => $this->pager,
					];
				$sx .= tableview($this,$dt);
				$sx = bs($sx);
				break;
		}
		return $sx;
	}

	function export_resume()	
		{
			$RDFData = new \App\Models\RDFData();
			$RDFClass = new \App\Models\RDFClass();		
			$RDFConcept = new \App\Models\RDFConcept();
			$EventProceedingsIssue = new \App\Models\EventProceedingsIssue();

			dircheck('../.temp');
			$file = '../.temp/_resume.json';
	
			$class = $RDFClass->Class('brapci:Event',False);
			$d['proceedings'] = $RDFConcept->where('cc_class',$class)->where('cc_use',0)->countAllResults();	

			$class = $RDFClass->Class('brapci:EventIssue',False);
			$d['issues'] = $RDFConcept->where('cc_class',$class)->where('cc_use',0)->countAllResults();							

			$class = $RDFClass->Class('frbr:Work',False);
			$d['works'] = $RDFConcept->where('cc_class',$class)->where('cc_use',0)->countAllResults();		

			$class = $RDFClass->Class('foaf:Person',False);
			$d['authors'] = $RDFConcept->where('cc_class',$class)->where('cc_use',0)->countAllResults();				

			file_put_contents($file,json_encode($d));
			return True;
		}

	function next_events()
		{
			$EventProceedingsIssue = new \App\Models\EventProceedingsIssue();
			$sx = $EventProceedingsIssue->next_events();
			return $sx;
		}
	function export_authors()
		{
			$RDF = new \App\Models\RDF();
			$dir = '.tmp/index/';
			if (!is_dir($dir)) { mkdir($dir); }
			$RDF->export_index('foaf:Person',$dir.'authors.php');
		}

	function export_events()
		{
			$RDF = new \App\Models\RDF();
			$dir = '.tmp/index/';
			if (!is_dir($dir)) { mkdir($dir); }
			$RDF->export_index('brapci:EventIssue',$dir.'events.php');
		}		

	function export($tp,$id='')
		{
			dircheck('.tmp');
			dircheck('.tmp/index');			
			switch($tp)
				{
					case 'resume':
						$sx = $this->export_resume();
						$sx .= bsmessage('Exported successful',1);
						break;

					case 'events':
						$sx = $this->export_events($id);
						$sx .= bsmessage('Exported successful',1);
						break;	

					case 'authors':
						$sx = $this->export_authors($id);
						$sx .= bsmessage('Exported successful',1);
						break;						

					case 'concepts':
						$sx = $this->export_all($id);
						$sx .= bsmessage('Exported successful',1);
						break;						
					default:
						$sx = bsmessage('OPS '.$tp,2);
						break;
				}
			return $sx;
		}

	function export_all($id='')
		{
			$id = round($id);			

			$sx = '';
			$this->RDF = new \App\Models\RDF();
			$RDFConcept = new \App\Models\RDFConcept();

			$limit = 200;
			$offset = $limit * $id;

			$dt = $RDFConcept->select('id_cc')->orderBy('id_cc')->limit($limit,$offset)->findAll($limit,$offset);
			for ($q=0;$q < count($dt);$q++)
				{
					$line = $dt[$q];
					$sx .= $line['id_cc'].' - ';
					//echo '1.'.$line['id_cc'].'<br>';
					$sx .= $this->RDF->export_id($line['id_cc']);
				}
			if ($q > 0)
				{
					$sx .= metarefresh(base_url(PATH.'/proceedings/export/concepts/'.($id+1)),5);
				}			
			$sx = bs(bsc($sx,12));
			return $sx;
		}

	function imports()
		{
			/*************************************** */
			$this->OpenDataCountry = new \App\Models\OpenDataCountry();
			$this->OpenDataCountry->inport();
			$this->OpenDataLanguage = new \App\Models\OpenDataLanguage();
			$this->OpenDataLanguage->inport();	
		}

	function le($id)
		{
			$dt = $this->find($id);
			return $dt;
		}

	function headProceeding($dt)
		{
			$sx = '';
			$sx .= bsc(bssmall(lang('ep_nome')),12);
			$sx .= bsc('<h4>'.$dt['ep_nome'].'</h4>',12);

			return $sx;
		}

	function viewid($id)
		{
			$this->EventProceedingsIssue = new \App\Models\EventProceedingsIssue();

			$dt = $this->where('id_ep', $id)->findAll();
			$dt = $dt[0];

			$sx = bsc(h($dt['ep_nome'],2));

			$sx .= $this->EventProceedingsIssue->issues($id);

			return $sx;
		}
}
