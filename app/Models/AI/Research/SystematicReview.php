<?php

namespace App\Models\Ai\Research;

use CodeIgniter\Model;

class SystematicReview extends Model
{
	protected $DBGroup              = 'ai';
	protected $table                = 'SystematicReviews_Studies';
	protected $primaryKey           = 'id_sr';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_sr','sr_title','sr_status','sr_user'
	];
	protected $typeFields        = [
		'hidden','text','[0-9]','user'
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

	function index($d1,$d2,$d3)
		{
			$Fluxo = new \App\Models\Io\Imagem\Fluxo();
			$tela = h(lang('research.systematic_review'),1);
			$tela .= $Fluxo->index('Planejamento','#66ff66');
			$tela .= $Fluxo->index('Execução','#66ff66');
			$tela .= $Fluxo->index('Análise','#66ff66');
			ECHO '===>'.$d2;
			switch($d2)
				{
					case 'viewid':
						$tela .= $this->viewid($d3);
						break;
					case 'edit':
						$d3 = round($d3);
						$tela .= $this->edit($d3);
						break;
					default:
						$tela = $this->tableview();
						break;
				}
			return $tela;
		}
		function authors($id)
		{
			$tela = h('<i>João da Silva Sauro</i>',4);
			return $tela;
		}

		function viewid($id)
		{
			$SystematicReviewData = new \App\Models\Ai\Research\SystematicReviewData();
			$dt = $this->find($id);
			$tela = '';
			$tela .= bsc(h($dt['sr_title'],1),12);
			$tela .= bsc('<small>'.lang('authors').'</small><br>'.$this->authors($id),12);
			$tela .= bsc('<small>'.lang('created').'</small><br>'.$dt['created_at'],6);
			$tela .= bsc('<small>'.lang('update').'</small><br>'.$dt['updated_at'],6);

			$tela .= $SystematicReviewData->view($id);
			return bs($tela);
		}

		function edit($id)
			{
				$this->path = PATH.MODULE.'research/systematic_review';
				$this->path_back = PATH.MODULE.'research/systematic_review';
				$tela = form($this);
				return $tela;
			}

		function tableview()
			{
				$this->path = PATH.MODULE.'research/systematic_review';
				$tela = tableview($this);
				return $tela;
			}
}
