<?php

namespace App\Models\AI;

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

	function index($d1='',$d2='',$d3='')
		{
			$tela = '';
			echo '===>'.$d1.'==>'.$d2;
			switch($d1)
				{
					case 'syllables':
						$AI = new \App\Models\AI\NLP\Syllables();
						$tela .= $this->formAI(1,lang('ai.Sillables'));
						$tela .= $AI->syllables(get("dd1"),get("dd2"));						
						break;
					case 'syllable':
						$AI = new \App\Models\AI\NLP\Syllables();
						$tela .= $this->formAI(1,lang('ai.Sillables'));
						$tela .= $AI->syllable(get("dd1"),get("dd2"));						
						break;
					default:
						$tela .= bsmessage('Service notefound: '.$d1,2);
						$tela .= $this->services();
						break;
				}
			return $tela;		
		}	

	function services()
		{
			$tela = '';
			$s = array();
			$s['ai.syllables'] = 'ai/nlp/syllables';
			$s['ai.wordcount'] = 'ai/nlp/wordcount';
			$tela .= '<ul>';
			foreach($s as $service=>$url)
				{
					$tela .= '<li><a href="'.base_url(PATH.$url).'">'.$service.'</a></li>';
				}
			$tela .= '</ul>';
			return $tela;
		}		
}
