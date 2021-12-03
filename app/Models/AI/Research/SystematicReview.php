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
		'hidden','string:100','string:1','user'
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
					case 'brapci_api':
						$ArticleBusca = new \App\Models\Brapci\ArticleBusca();
						$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
						$dt = $SystematicReviewCorpus->find($d3);
						$tela .= $ArticleBusca->brapci_api($dt);
						break;
					case 'autoclass':
						$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
						$tela .= $SystematicReviewCorpus->autoClass_mth1($d3);
						$tela .= $SystematicReviewCorpus->autoClass_mth2($d3);
						break;	
					case 'strategy':
						$SystematicReviewStrategy = new \App\Models\AI\Research\SystematicReviewStrategy();
						$tela .= $SystematicReviewStrategy->index($d3,$d4);
						break;
					case 'strategy_edit':
						$SystematicReviewStrategy = new \App\Models\AI\Research\SystematicReviewStrategy();
						$tela .= $SystematicReviewStrategy->edit($d3,$d4);
						break;
					case 'strategy_view':
						$SystematicReviewStrategy = new \App\Models\AI\Research\SystematicReviewStrategy();
						$tela .= $SystematicReviewStrategy->view($d3,$d4);
						break;						
					case 'autoBrapci':
						$tela .= $this->autoBrapci($d3);
						break;	
					case 'autoKeywords':
						$tela .= $this->autoKeywords($d3,1);
						break;											
					case 'nlp':
						$tela .= $this->analyse($d3);
						break;	
					case 'criterieEd':
						$SystematicReviewValue = new \App\Models\AI\Research\SystematicReviewValue();
						$tela = $SystematicReviewValue->edit($d3);
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
						$tela .= wclose('no_refresh');
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

		function autoKeywords($ini,$st=0)
			{
				$tela = '';
				$ini = round($ini);
				$ArticleBusca = new \App\Models\Brapci\ArticleBusca();
				$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
				$ContentAnalysis = new \App\Models\AI\Research\ContentAnalysis();
				$sql = "select * from ".$SystematicReviewCorpus->table." 
							where c_status = $st
							and c_keywords = ''
							order by id_c
							limit 1 offset $ini
							";
							echo $sql;
				$rlt = (array)$this->query($sql)->getResult();
				$rst = '';
				if (count($rlt) > 0)
					{						
						$dt = (array)$rlt[0];
						$id = $dt['id_c'];
						$tela .= $SystematicReviewCorpus->show($dt,'ABNT');
						$rst = $ContentAnalysis->corpusId('',$id,'');
						if (strlen($rst) == 0)
							{
								$ini++;
							} else {
								
							}
						$tela .= $rst;
						$tela .= 'Redirecionando ->'.$ini;
						$tela .= '<br>'.date("d/m/Y H:i:s").'<br>';
						$tela .= metarefresh(PATH.MODULE.'research/systematic_review/autoKeywords/'.$ini,1);
					} else {
						$tela .= bsmessage('Fim do processamento');
					}
				
				return $tela;
			}		

		function autoBrapci($ini,$st=0)
			{
				$tela = '';
				$ini = round($ini);
				$ArticleBusca = new \App\Models\Brapci\ArticleBusca();
				$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
				$sql = "select * from ".$SystematicReviewCorpus->table." 
							where c_status = $st
							order by id_c
							limit 1 offset $ini
							";
				$rlt = (array)$this->query($sql)->getResult();
				if (count($rlt) > 0)
					{						
						$dt = (array)$rlt[0];
						$id = $dt['id_c'];
						$tela .= $SystematicReviewCorpus->show($dt);
						$rst = $SystematicReviewCorpus->class_status_0($id,$dt);
						if ($rst == 0)
							{
								$ini++;
							} else {

							}
						$tela .= 'Redirecionando ->'.$ini;
						$tela .= '<br>'.date("d/m/Y H:i:s").'<br>';
						$tela .= metarefresh(PATH.MODULE.'research/systematic_review/autoBrapci/'.$ini,1);
					} else {
						$tela .= bsmessage('Fim do processamento');
					}
				
				return $tela;
			}

		function analyse($id)
			{
				$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
				$Thesa = new \App\Models\AI\Thesa();
				$vc = $Thesa->le_array(243);

				$ArticleBusca = new \App\Models\Brapci\ArticleBusca();
				$txt = $ArticleBusca->txt($id);

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
				$this->where('sr_user',user_id());
				$tela = tableview($this);
				return $tela;
			}
}
