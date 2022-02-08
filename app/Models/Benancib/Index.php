<?php

namespace App\Models\Benancib;

use CodeIgniter\Model;

class Index extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'indices';
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

	public function __construct()
	{
		$this->Socials = new \App\Models\Socials();
	}	

	function cab()
		{
			$sx = bsc('<img src="'.URL.'img/logo/benancib.png" class="img-fluid"',2);
			$sx = bs($sx);
			return $sx;
		}

	function status()
		{
			
		}

	function index($d1,$d2,$d3,$d4)
	{
		$sx = $this->cab();
		switch($d1)
		{
			case 'harvesting_auto':
				$Harvesting = new \App\Models\Benancib\Harvesting();
				$sx .= $Harvesting->harvesting_auto($d2,$d3,$d4);
				break;
			case 'harvesting_pdf':
				$Harvesting = new \App\Models\Benancib\Harvesting();
				$sx .= $Harvesting->harvesting_auto_pdf($d2,$d3,$d4);
				break;	
			case 'check':
				$Harvesting = new \App\Models\Benancib\Harvesting();
				$sx .= $Harvesting->check_harvesting($d2,$d3,$d4);
				break;			
			case 'harvesting':
				$Harvesting = new \App\Models\Benancib\Harvesting();
				$sx .= $Harvesting->havest($d2,$d3,$d4);
				break;
			case 'export':
				$sx .= $this->export($d2,$d3,$d4);
				break;				
			default:
				$items['home'] = PATH.'res/benancib';
				$sx .= breadcrumbs($items);

				$sx .= $this->painel();
				$sx .= '<a class="btn btn-outline-primary m-3" style="font-size: 150%; width: 100%" href="'.PATH.MODULE.'v/101894">'.lang('brapci.Publicações do Enancib').'</a>';
	
				//$sx .= '<li>'.anchor(PATH.MODULE.'benancib/check','Check Harvesting').'</li>';
				//$sx .= '<li>'.anchor('http://repositorios.questoesemrede.uff.br/repositorios/handle/123456789/2','Repositório BENANCIB').'</li>';
				//$sx .= '<hr>';
				//$sx .= '<li>'.anchor(PATH.MODULE.'benancib/harvesting_auto/5','Auto Harvesting').'</li>';
				//$sx .= '<li>'.anchor(PATH.MODULE.'benancib/harvesting_pdf/5','Auto Harvesting PDF').'</li>';
				if ($this->Socials->perfil('#ADM'))
				{
					$sx .= '<span class="btn btn-outline-primary">'.anchor(PATH.MODULE.'benancib/export','Export to Brapci (RDF)').'</a>';
				}		

		}
		return $sx;
	}

	function painel()
		{
			$data = array();
			$pn1 = view('Benancib/painel_1',$data);
			$pn2 = view('Benancib/painel_2',$data);
			$pn3 = view('Benancib/painel_3',$data);

			$sx = bs(bsc($pn1,4).bsc($pn2,4).bsc($pn3,4));
			return $sx;
		}

	function export($id=0)
		{			
			$offset = round($id);
			$sx = '<h1>Exportação para Brapci</h1>';
			$dir = '.tmp/benancib/harvesting/';
			$file1 = $dir . 'benancib_' . $id . '.xml';
			$file2 = $dir . 'benancib_' . $id . '.pdf';
			$offset++;
			if (file_exists($file1))
				{
					$sx .= bsmessage('Process - '.$file1,1);
					$Brapci = new \App\Models\Benancib\Brapci();
					$sx .= $Brapci->export($id);
					$sx .= metarefresh(PATH . MODULE . 'benancib/export/' . ($offset), 2);
				} else {
					$sx .= bsmessage('File not found - '.$file1,3);
					$sx .= metarefresh(PATH . MODULE . 'benancib/export/' . ($offset), 1);
				}
			return $sx;
		}
}