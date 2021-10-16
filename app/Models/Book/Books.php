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
			echo '===>'.$d1;
			$this->path = base_url(PATH.'/index/');
			$this->path_back = base_url(PATH.'/index/');
			switch ($d1)
				{
					case 'self':
						$tela = $this->self($d2,$d3);
						break;
					default:
						$tela = $this->menu();
						break;
				}
			return $tela;
		}	
	function menu()
		{
			$mn = array('book/index/self'=>'book.self_submit');
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
	function self($d1,$d2)
		{
			$tela = '';
			switch($d1)
				{
					case 'upload_ajax':
						$tela .= $this->ajax();
					break;					
					case 'upload':
						$tela .= $this->upload();
					break;
					case 'noagree':
						$tela .= $this->noagree();
					break;
					case 'new':
						$tela .= $this->submit_1();
					break;

					default:
						$tela .= $this->mySubmit();
					break;
				}
			return $tela;
		}

	function upload()
		{
			$url = base_url(PATH.'book/index/self/upload_ajax');
			$tela = upload($url);
			return $tela;
		}

	function ajax()
		{
			$dir = '.tmp/';
			dircheck($dir);
			$dir = '.tmp/.tmp_books/';
			dircheck($dir);

			$arr_file_types = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg','application/pdf'];

			echo '<pre>';
			print_r($_FILES);
			exit;

			$file = $_FILES['file']['name'];
			$type = $_FILES['file']['type'];

			if (ajax($dir,$arr_file_types))
				{
					//print_r($_FILES);
					$tela = bs(bsc(bsmessage(lang('bacpci.saved '.$file. ' - '.$type),1),12));
				} else {
					$tela = bs(bsc(bsmessage(lang('bacpci.save_save_error - '.$type),3),12));
				}
			echo $tela;
			exit;
		}		

	function noagree()
		{
			$tela = '';
			$tela = h('noagree',1);
			$tela .= '<p>'.lang('book.noagree_1').'</p>'.cr();
			$tela .= '<p>'.lang('book.noagree_2').'</p>'.cr();
			$tela .= '<p>'.lang('book.noagree_3').'</p>'.cr();

			$tela .= $this->btn_return();

			$tela = bs(bsc($tela,12));
			return $tela;
		}

	function btn_return()
		{
			$sx = '<a href="'.base_url(PATH.'res').'" class="btn btn-outline-primary">'.
				lang('brapci.return').
				'</a>';
			return $sx;
		}

	function mySubmit()
		{
			$user = 1;
			$BooksSelf = new \App\Models\Book\BooksSelf();
			$tela = h(lang('book.myself_deposit'),1);
			$tela .= $BooksSelf->myTableView($user);
			$tela .= $BooksSelf->btn_newSubmit($user);
			return $tela;
		}		

	function tableview()
		{
			$tela = '';
			return $tela;
		}
	function submit_1()
		{
			$BooksTerms = new \App\Models\Book\BooksTerms();
			$tela = $BooksTerms->showTerm('self');
			$tela .= $BooksTerms->btn_newSubmit();
			return $tela;
		}
}
