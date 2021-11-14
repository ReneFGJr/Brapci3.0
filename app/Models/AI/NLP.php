<?php

namespace App\Models\AI;

use CodeIgniter\Model;

class NLP extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'nlps';
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
					/* wordcount */
					case 'wordcount':
						$AI = new \App\Models\AI\NLP\Wordcount();
						$tela .= $this->formAI(1,lang('ai.Wordcount'));
						$tela .= $AI->wordcount(get("dd1"),get("dd2"));						
						break;
					case 'wordmatch':
						$AI = new \App\Models\AI\NLP\WordMatch();
						$tela .= $this->formAI(1,lang('ai.Wordmatch'));
						$tela .= $AI->WordMatch(get("dd1"),get("dd2"));						
						break;						

					default:
						$tela .= bsmessage('Service notefound: '.$d1,2);
						break;
				}
			return $tela;		
		}	

	function formAI($tp,$txt)
		{
			$tela = '';
			$tela .= form_open();
			$tela .= lang('ai.InputTextForm');
			$tela .= '<textarea  class="form-control" name="dd1" rows=5>';
			$tela .= get("dd1");
			$tela .= '</textarea>';			
			
			$tela .= '<hr>';
			$tela .= lang('ai.language').': ';
			$tela .= '<select name="dd2">';
			$tela .= '<option value="'.get("dd2").'">'.lang("ai.".get("dd2")).'</option>';
			$tela .= '<option value="pt-BR">'.lang("ai.pt-BR").'</option>';
			$tela .= '<option value="en">'.lang("ai.en").'</option>';
			$tela .= '</select>';

			$tela .= '<hr>';
			$tela .= '<input type="submit" value="'.lang('ai.Proceess').'" class="btn btn-outline-primary">';

			$tela .= '</form>';
			return $tela;
		}
}
