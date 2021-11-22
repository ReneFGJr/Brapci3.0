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

	function cab()
		{
			$tela = view('Book/_header');			
			return $tela;
		}

	function footer()
		{
			$tela = view('Book/_footer');
			return $tela;
		}

	function index($d1,$d2,$d3)
		{
			$this->path = base_url(PATH.'/index/');
			$this->path_back = base_url(PATH.'/index/');
			$tela = $this->cab();
			switch ($d1)
				{
					case 'self':
						$BooksSelf = new \App\Models\Book\BooksSelf();
						$tela .= $BooksSelf->index($d2,$d3);
						break;
					case 'book':
						$dt['btn_download'] = $this->btn_download(1);
						$dt['btn_see_more'] = $this->btn_see_more(1);
						$dt['press_release'] = 'Realiza-se um sobrevoo sobre as principais subáreas dos Estudos Métricos no Brasil, com as primeiras leis bibliométricas, os conceitos e objetos de estudos, questões teóricas e conceituais que surgiram com o desenvolvimento das tecnologias e novos softwares.';
						
						$dt['title'] = 'Estudos métricos da informação no Brasil';
						$dt['subtitle'] = 'indicadores de produção, colaboração, impacto e visibilidade';
						$tela .= view('Book/index',$dt);
						$tela .= $this->menu();
						$tela .= $this->footer();
						break;
					default:
						$dt = array();
						$dt['title'] = 'Brapci Livros';
						$tela .= view('Header/header',$dt);
						$tela .= view('Book/caroussel',$dt);
				}
			return $tela;
		}	

		
	function btn_see_more($id=0)
		{
			$tela = '';
			$tela .= '<a class="btn btn-secondary scrollto w-100" href="'.PATH.'res/book/v/'.$id.'">'.lang('book.Learn_More').'</a>';
			return $tela;
		}


	function btn_download($id=0)
		{
			$tela = '';
			$tela = onclick(PATH.'res/book/download/'.$id,800,400,'btn btn-primary w-100');
			$tela .= lang('book.Download').'</span>';
			//$tela .= '<a class="btn btn-primary w-100" href="'.PATH.'res/book/download/'.$id.'">'.lang('book.Download').'</a>';
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
