<?php

namespace App\Models\Book;

use CodeIgniter\Model;

class BooksSelf extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'book_self';
	protected $primaryKey           = 'id_bs';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_bs','bs_title','bs_rdf','bs_status','bs_user','bs_agree',''
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

	function myTableView($user)
		{
			$tela = '';
			$dt = $this->where('bs_user',$user)->findAll();
			if (count($dt) == 0)
				{
					$tela .= bsmessage(lang('book.submission_not_found'),3);
				}
			return $tela;
		}
	function btn_newSubmit($user)
		{
			$tela = '';
			$tela .= '<a href="'.base_url(PATH.'book/index/self/new').'" class="btn btn-primary">';
			$tela .= lang('book.submit_new');
			$tela .= '</a>';
			return $tela;
		}		
}
