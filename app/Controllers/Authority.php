<?php
//https://www.tandfonline.com/doi/full/10.1080/01639374.2021.1881009
//https://www.librarianshipstudies.com/2016/06/authority-control.html
namespace App\Controllers;

use App\Controllers\BaseController;

helper(['url']);

define('PATH','authority/');

class Authority extends BaseController
{

	private function cab($dt=array())
		{
			$view = \Config\Services::renderer();
			return  $view->setVar('title','Brapci - Authority')
							->render('Header/header');
		}
	private function navbar()
		{
			$view = \Config\Services::renderer();
			return  $view->setVar('title','Brapci - Authority')
							->render('Header/navbar_authority');			
		}
	private function footer()
		{
			$view = \Config\Services::renderer();
			return  $view->setVar('title','Brapci - Authority')
							->render('Header/footer');			
		}		
	
	public function index()
	{
		$tela = $this->cab();
		$tela .= $this->navbar();
		$tela .= $this->footer();
		$this->db = \Config\Database::connect('auth', false);
		return $tela;
	}

	public function api()
	{
		$tela = $this->cab();
		$tela .= "<h1>API</h1>";
		return $tela;
	}	

	public function admin()
	{
		$view = \Config\Services::renderer();

		$tela = $this->cab();
		$tela .= "<h1>Admin</h1>";

		$tela .= $view->render('authority/card');
		return $tela;
	}	

	public function doc()
	{
		$tela = $this->cab();
		$tela .= $this->navbar();
		$tela .= "<h1>DOCUMMENTATION</h1>";
		$tela .= $this->footer();		
		
		return $tela;
	}	
}
