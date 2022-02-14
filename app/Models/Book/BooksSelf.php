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
		'id_bs','bs_title','bs_rdf','bs_status','bs_user','bs_agree'
	];

	protected $typeFields        = [
		'hidden','text:100','hidden',
		'set:0','session:user','hidden'
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

	function index($d1,$d2)
		{
			$tela = '';
			switch($d2)
				{
					case 'upload_ajax':
						$tela .= $this->ajax();
					break;					
					case 'noagree':
						$tela .= $this->noagree();
					break;
					case 'new':
						$tela .= $this->submit_1();
					break;
					case 'upload':
						$this->submissao_id();
						$tela .= $this->submit_2();
					break;
					case 'metadata':
						$tela .= $this->submit_3();
					break;					

					default:
						$tela .= $this->mySubmit();
					break;
				}
			return $tela;
		}

	function submissao_id()
		{
			$tela = '';
			$Social = new \App\Models\Socials();
			$user = $Social->loged();
			if ($user > 0)
				{
					$this->where('bs_user',$user);
					$this->where('bs_status',0);
					$dt = $this->findAll();
					if (count($dt) == 0)
						{
							$data['bs_user'] = $user;
							$data['bs_status'] = 0;
							$data['bs_title'] = 0;
							$data['bs_agree'] = date("Ymd");
							$id = $this->insert($data);							
						} else {
							$id = $dt[0]['id_bs'];
						}
					$_SESSION['book_self_id'] = $id;
				} else {
					$tela = homepage();
				}
			return $tela;
		}

	function submit_2()
		{
			$BooksSelf = new \App\Models\Book\BooksSelf();
			$url = base_url(PATH.MODULE.'book/index/self/upload_ajax');
			$dir = $_SESSION['book_self_id'];
			$tela = upload($url);
			$tela .= '<br>'.$BooksSelf->btn_metadata();
			return $tela;
		}

	function ajax()
		{
			$Social = new \App\Models\Socials();
			$ids = $_SESSION['book_self_id'];
			$user = $Social->loged();
			$dir = '.tmp/';
			dircheck($dir);
			$dir = '.tmp/.tmp_books/';
			dircheck($dir);
			$dir = '.tmp/.tmp_books/'.$user.'/';
			dircheck($dir);
			$dir = '.tmp/.tmp_books/'.$user.'/'.$ids.'/';			
			dircheck($dir);

			$arr_file_types = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg','application/pdf'];

			if (!isset($_FILES['file']))
				{
					$tela = '<div class="alert alert-danger">';
					$tela = 'ERRO BOOKSELF';
					$tela .= '</div>';
					print_r($_FILES);
					return $tela;
				}

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
			$Social = new \App\Models\Socials();
			$user = $Social->loged();
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
			$BooksSelf = new \App\Models\Book\BooksSelf();	
			$Social = new \App\Models\Socials();		
			$user = $Social->loged();
			if ($user > 0)
				{
					$tela = '';
					$tela .= $BooksTerms->showTerm('self');					
					$tela .= $BooksTerms->btn_newSubmit();
				} else {
					$tela .= homepage();
				}
			return $tela;
		}

	function submit_3()
		{
			$Social = new \App\Models\Socials();
			$user = $Social->loged();
			if ($user > 0)
				{
					$BooksSelf = new \App\Models\Book\BooksSelf();
					$tela = $BooksSelf->metadata($user);
				} else {
					$tela .= homepage();
				}			
			return $tela;
		}		


	function metadata($user)
		{
			$tela = '';
			$this->path_back = base_url(PATH.MODULE.'book/index/self/end');
			$this->path = base_url(PATH.MODULE.'book/index/self/metadata');
			$tela = form($this);
			return $tela;
		}

	function myTableView($user)
		{
			$tela = '';
			$dt = $this->where('bs_user',$user)->findAll();
			if (count($dt) == 0)
				{
					$tela .= bsmessage(lang('book.submission_not_found'),3);
				} else {
					$tela = '<table class="table">';
					$tela .= '<tr class="small">';
					$tela .= '<th>'.lang('book.bs_title').'</th>';
					$tela .= '<th>'.lang('book.bs_status').'</th>';
					$tela .= '<th>'.lang('brapci.action').'</th>';
					$tela .= '</tr>';
				for ($r=0;$r < count($dt);$r++)
					{
						$d = $dt[$r];
						$tela .= '<tr>';
						$tela .= '<td>';
						$tela .= $d['bs_title'];
						$tela .= '</td>';
						$tela .= '<td>';
						$tela .= lang('book.submit_status_'.$d['bs_status']);
						$tela .= '</td>';
						$tela .= '<td>';
						$tela .= stodbr($d['updated_at']);
						$tela .= '</td>';
						
						if ($d['bs_status'] == 0)
							{
								$link = '<a href="'.base_url(PATH.MODULE.'book/index/self/metadata/'.$d['id_bs']).'" class="btn btn-outline-primary">'.lang('book.selft_action_'.$d['bs_status']).'</a>';
							} else {
								$link ='-';
							}
						

						$tela .= '<td>';
						$tela .= $link;
						$tela .= '</td>';

						$tela .= '</tr>';
					}
					$tela .= '</table>';
			}
			return $tela;
		}

	function btn_newSubmit($user)
		{
			$tela = '';
			$tela .= '<a href="'.base_url(PATH.MODULE.'book/index/self/new').'" class="btn btn-primary">';
			$tela .= lang('book.submit_new');
			$tela .= '</a>';
			return $tela;
		}	

	function btn_metadata()
		{
			$tela = '';
			$tela .= '<a href="'.base_url(PATH.MODULE.'book/index/self/metadata').'" class="btn btn-primary">';
			$tela .= lang('book.submit_metadata');
			$tela .= '</a>';
			return $tela;
		}			
}
