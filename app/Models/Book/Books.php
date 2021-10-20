<?php

namespace App\Models\Book;

use CodeIgniter\Model;

class Books extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'books';
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

	function index($d1,$d2,$d3)
		{
			$this->path = base_url(PATH.'/index/');
			$this->path_back = base_url(PATH.'/index/');
			switch ($d1)
				{
					case 'self':
						$BooksSelf = new \App\Models\Book\BooksSelf();
						$tela = $BooksSelf->index($d2,$d3);
						break;
					default:
						$tela = $this->menu();
						break;
				}
			return $tela;
		}	
	function menu()
		{
			$mn = array('/index/self'=>'book.self_submit');
			$tela ='<h1>MENU</h1>';
			$tela .= '<ul>';
			foreach($mn as $url=>$label)
				{
					$link = '<a href="'.base_url(PATH.$url).'">';
					$linka = '</a>';
					$tela .= '<li>'.$link.lang($label).$linka.'</li>';
				}
			$tela .= '</ul>';
			return $tela;
		}

}
