<?php

namespace App\Models\AI\Research;

use CodeIgniter\Model;

class ContentAnalysis extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'contentanalyses';
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

	function index($d1,$d2,$d3,$d4)
		{
			$tela = '';
			//corpusId
			switch($d2)
				{
					case 'corpusId':
						$tela = $this->corpusId($d1,$d3,$d4);
						break;
					break;
				}
			return $tela;
		}

	function BrapciFullText($dt)
		{
			$Search = new \App\Models\Brapci\ArticleBusca();			
			$idb = $dt['c_brapci'];
			$txt = $Search->getFullText($idb);
			return $txt;
		}

	function corpusId($d1,$d2,$d3)
		{
			$th = 243;
			$WordMatch = new \App\Models\AI\NLP\WordMatch();
			$Thesa = new \App\Models\AI\Thesa();
			$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();
			$dt = $SystematicReviewCorpus->find($d2);	
			$vc = $Thesa->le_array($th);
			$txt = $dt['c_fulltext'];
			if (strlen($dt['c_fulltext']) == 0)
				{
					$txt = $this->BrapciFullText($dt);
					$dt['c_fulltext'] = $txt;
					if (strlen($txt > 100))
					{
						$SystematicReviewCorpus->update_textfull($d2,$txt);
					}
				}
			$rst = $WordMatch->analyse($dt['c_fulltext'],$vc);
			
			/*************************************** KEYS */
			$keys = $rst['keys'];
			$tkey = '';
			echo '<pre>';
			print_r($keys);
			echo '<hr>';
			echo $txt;
			foreach ($keys as $key => $value)
				{
					$tkey .= $key.'; ';
				}
			echo $tkey;
			$tela = '';

			return $tela;
		}

	function btn_ContentAnalysis($id)
		{
			$sx = '<a href="'.PATH.MODULE.'research/contentanalysis/corpusId/'.$id.
					'/1" class="btn btn-primary btn-sm">
						Content Analysis
					</a> ';
			return $sx;
		}		
}
