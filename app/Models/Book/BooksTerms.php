<?php

namespace App\Models\Book;

use CodeIgniter\Model;

class BooksTerms extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'none';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'',''
	];
	protected $typeFields        = [
		'hidden','agree_checkbox'
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

	function showTerm($t)
		{
			$sx = 'Term not found - '.$t;
			$file = 'no file';
			switch($t)
				{
					case 'self':
						$file = '../TermoLivro-pt-BR.md';
						break;
				}

			if (file_exists($file))
				{
					$txt = file_get_contents($file);
					$pos = strpos($txt,'</h1>');
					$title = substr($txt,0,$pos+5);
					$txt = troca($txt,$title,'').cr().cr();
					$sx = $title;
					$sx .= '<textarea class="form-control" style="height: 400px" disabled>'.$txt.'</textarea>';
					$sx .= '<br/>';
				}

			/*************************************** FORMULÀRIO */
			$sx = bs(bsc($sx),12);
			return $sx;
		}
	function btn_newSubmit($id=0)
		{
			$btn_agree = '<a href="'.base_url(PATH.'book/index/self/upload').'" class="btn btn-primary">'.lang('book.agree').'</a>';
			$btn_noagree = '<a href="'.base_url(PATH.'book/index/self/noagree').'" class="btn btn-warning">'.lang('book.noagree').'</a>';
			$sx = $btn_agree.' &nbsp; '.$btn_noagree;
			$sx = bs(bsc($sx,12));
			return $sx;
		}
}
