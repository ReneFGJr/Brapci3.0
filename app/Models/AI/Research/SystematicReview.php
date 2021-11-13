<?php

namespace App\Models\AI\Research;

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

	function index($d1,$d2,$d3,$d4='')
		{
			$Fluxo = new \App\Models\IO\Imagem\Fluxo();
			$tela = h(lang('research.systematic_review'),1);
			$tela .= $Fluxo->index('Planejamento','#66ff66');
			$tela .= $Fluxo->index('Execução','#66ff66');
			$tela .= $Fluxo->index('Análise','#66ff66');
			switch($d2)
				{
					case 'autoclass':
						$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
						$tela .= $SystematicReviewCorpus->autoClass_mth1($d3);
						$tela .= $SystematicReviewCorpus->autoClass_mth2($d3);
						break;	
					case 'nlp':
						$tela .= $this->analyse($d3);
						break;										
					case 'corpusId':
						$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
						$tela = $SystematicReviewCorpus->classification($d3);
						break;	
					case 'corpus_edit':
						$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
						$tela .= $SystematicReviewCorpus->edit($d3);
						break;		
					case 'corpus_status':
						$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
						$tela .= $SystematicReviewCorpus->changeStatus($d3,$d4);
						$tela .= wclose();
						break;		
					case 'viewid':
						$tela .= $this->viewid($d3,$d4);
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

		function analyse($id)
			{
				$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
				$Thesa = new \App\Models\AI\Thesa();
				$vc = $Thesa->le_array(243);

				$ArticleBusca = new \App\Models\Brapci\ArticleBusca();
				$txt = $ArticleBusca->txt(149163);

				$WordMatch = new \App\Models\AI\NLP\WordMatch();
				$tela = $WordMatch->analyse($txt,$vc);

				return $tela;
			}

		function authors($id)
		{
			$tela = h('<i>João da Silva Sauro</i>',4);
			return $tela;
		}

		function viewid($id,$d4=0)
		{
			$SystematicReviewData = new \App\Models\AI\Research\SystematicReviewData();
			$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
			$dt = $this->find($id);
			$tela = '';
			$tela .= bsc(h($dt['sr_title'],1),12);
			$tela .= bsc('<small>'.lang('authors').'</small><br>'.$this->authors($id),12);
			$tela .= bsc('<small>'.lang('created').'</small><br>'.$dt['created_at'],6);
			$tela .= bsc('<small>'.lang('update').'</small><br>'.$dt['updated_at'],6);

			$tela .= $SystematicReviewData->view($id);

			$tela .= $SystematicReviewCorpus->view($id,$d4);
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
