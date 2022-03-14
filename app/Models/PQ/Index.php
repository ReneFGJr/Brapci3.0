<?php

namespace App\Models\PQ;

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


	function subheader()
		{
			$sx = bsc('<img src="'.URL.'img/logo/base_pq.png" class="img-fluid">',2);
			$sx .= bsc('<h1>Base PQ/CNPq</h1>',10);
			$sx = bs($sx);
			return $sx;
		}

	function index($d1,$d2,$d3,$d4)
	{
		$sx = '';

		switch($d1)
			{
				case 'export':
					$Export = new \App\Models\PQ\Export();
					$sx .= $Export->brapci();
					break;
				case 'viewid':
					$sx .= $this->subheader();
					$sx .= $this->viewid($d2);
					break;
				case 'pq_bolsas':
					$sx .= $this->subheader();
					$sx .= $this->pq_bolsas();
					break;
				break;

				case 'pq_ano':
					$sx .= $this->subheader();
					$sx .= $this->pq_ano();
					break;
				break;

				case 'pq_vigentes':
					$sx .= $this->subheader();
					$sx .= $this->pq_vigentes($d2,$d3,$d4);
					break;
				break;				

				default:
				$sx .= $this->subheader();
				$sx .= $this->resume();
			}
		$sx = bs($sx);
		return $sx;
	}	

	function viewid($id)
		{
			$RDF = new \App\Models\Rdf\RDF();
			$Bolsistas = new \App\Models\PQ\Bolsista();
			$dt = $Bolsistas->find($id);
			if ($dt['bs_rdf_id'] == 0)
				{
					$Authority = new \App\Models\Authority\AuthorityNames();
					$nome = $dt['bs_nome'];
					$nome = nbr_author($nome,1);
					$id_brapci = $Authority->getBrapciId($nome);

					if ($id_brapci > 0)
						{							
							$dt['bs_rdf_id'] = $id_brapci;
							$Bolsistas->set($dt)->where('id_bs',$id)->update();							
						}
					$dt = $Bolsistas->find($id);
				}
				if ($id_brapci > 0)
					{
						$link = $RDF->link(array('id_cc'=>$id_brapci),'text-secondary');
						$link = substr($link,strpos($link,'"')+1,strlen($link));
						$link = substr($link,0,strpos($link,'"'));
						echo metarefresh($link);						
						exit;
					}
					echo h('Nome não localizado '.$dt['bs_nome']);
					pre($dt);
		}

	function pq_bolsas()
		{
			$Bolsa = new \App\Models\PQ\Bolsa();
			$sx = '';
			$sx = $Bolsa->bolsista_list();
			return $sx;
		}
	function pq_ano()
		{
			$Bolsa = new \App\Models\PQ\Bolsa();
			$sx = '';
			$sx = $Bolsa->year_list(0);
			return $sx;
		}

	function pq_vigentes($d2,$d3,$d4)
		{
			$Bolsa = new \App\Models\PQ\Bolsa();
			$sx = '';
			$sx = $Bolsa->year_list(1);
			return $sx;
		}		

	function resume()
		{
			$this->Socials = new \App\Models\Socials();
			$Bolsa = new \App\Models\PQ\Bolsa();
			$Bolsista = new \App\Models\PQ\Bolsista();			

			$sx = $Bolsa->resume();

			$sx .= '<ul>';
			$sx .= '<li><a href="' . PATH . MODULE . 'pq/pq_bolsas' . '">' . lang('pq.bolsista_list') . '</a></li>';
			$sx .= '<li><a href="' . PATH . MODULE . 'pq/pq_ano' . '">' . lang('pq.bolsista_ano_list') . '</a></li>';
			$sx .= '<li><a href="' . PATH . MODULE . 'pq/pq_vigentes' . '">' . lang('pq.bolsista_vigentes') . '</a></li>';
			$sx .= '<li><a href="http://memoria2.cnpq.br/bolsistas-vigentes" target="_new">' . lang('pq.bolsista_ativos_cnpq') . '</a></li>';		
			if  ($this->Socials->getAccess("#ADM"))
				{
					$sx .= '<hr>';
					$sx .= '<li><a href="' . PATH . MODULE . 'pq/export' . '">' . lang('pq.exportar') . '</a></li>';
				}
			$sx .= '</ul>';			

			return $sx;
		}
}
