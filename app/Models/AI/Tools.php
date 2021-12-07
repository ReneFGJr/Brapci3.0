<?php

namespace App\Models\AI;

use CodeIgniter\Model;

class Tools extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'tools';
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

	function index($d1='',$d2='',$d3='',$d4='')
		{
			$NLP = new \App\Models\AI\NLP();
			$tela = '';
			switch($d1)
				{
					case 'language_detection':
						$Language = new \App\Models\AI\NLP\Language();
						$tela .= $NLP->formAI(1,lang('ai.Sillables'));
						$tela .= $Language->getTextLanguage(get("dd1"),get("dd2"));						
						break;
					case 'syllables':
						$AI = new \App\Models\AI\NLP\Syllables();
						$tela .= $NLP->formAI(1,lang('ai.Sillables'));
						$tela .= $AI->syllables(get("dd1"),get("dd2"));						
						break;
					case 'syllable':
						$AI = new \App\Models\AI\NLP\Syllables();
						$tela .= $NLP->formAI(1,lang('ai.Sillables'));
						$tela .= $AI->syllable(get("dd1"),get("dd2"));
						break;					
					case 'scraping':
						$Scraping = new \App\Models\AI\NLP\Scraping();
						$tela .= $Scraping->index($d1,$d2,$d3,$d4);
						break;
					case 'systematic_review':
						$tela .= $this->research($d1,$d2,$d3,$d4);
						break;
					case 'contentanalysis':
						$tela .= $this->research($d1,$d2,$d3,$d4);
						break;			
					default:
					$tela .= bsmessage('Service not found (Tools) - '.$d1);

					/********************** Serviços */
					$lst = array(
						'systematic_review',
						'content_analysis',
						'roboti_task',
						'bibliometric',
						'syllables',
						'wordcount',
						'language_detection',
						'scraping'
						);
					$tela .= '<ul>';
					for ($r=0;$r < count($lst);$r++)
						{
							
							$tela .= '<li>';
							$tela .= '<a href="'.PATH.MODULE.'research/'.$lst[$r].'">'.lang('ai.'.$lst[$r]).'</a>';
							$tela .= '</li>';
						}
					$tela .= '</ul>';
				}
			$tela = bs(bsc($tela,12));
			return $tela;
		}

	public function research($d1='',$d2='',$d3='',$d4='')
	{
		$Research = new \App\Models\AI\Research();
		$d2 = trim($d2);
		$tp = '';
		if ($d2 == 'corpusId')
			{
				$tp = 'head';
			}	
		$tela = '';				
		$tela .= $Research->index($d1,$d2,$d3,$d4);
		return $tela;
	}	
}
