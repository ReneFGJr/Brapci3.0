<?php

namespace App\Controllers;

use App\Controllers\BaseController;

$this->session = \Config\Services::session();
$language = \Config\Services::language();


helper(['boostrap','url','graphs','sisdoc_forms','form','nbr']);

define("PATH",$_SERVER['app.baseURL']);



class Main extends BaseController
{
	// https://www.richdataservices.com/showcase

	public function __construct()
	{
		$this->Socials = new \App\Models\Socials();
		$this->EventSearch = new \App\Models\EventSearch();
		$this->EventProceedings = new \App\Models\EventProceedings();
		$this->EventProceedingsIssue = new \App\Models\EventProceedingsIssue();

		helper(['boostrap','url','canvas']);
		define("LIBRARY", "3001");
		define("LIBRARY_NAME", "BRAPCI_LABS");
	}	

	

	public function social($d1 = '', $id = '')
	{
		$cab = $this->cab();
		$dt = array();
		$sx = $this->Socials->index($d1,$id,$dt,$cab);
		return $sx;
	}	

	public function index()
	{
		//
		$tela = $this->cab();
		$tela .= $this->navbar();

		#### Logado
		if (isset($_SESSION['user']['id']))
			{
				$login = 'Welcome';
			} else {
				$login = $this->Socials->login(0);
			}
	
        $MENU = bsc('Menu',12);
        $menu = array();
        $menu['authority'] = array('',lang("main.Authority"),lang("main.Authority_desc"));
        $menu['books'] = array('',lang("main.Books"),lang("main.Books_desc"));
        $menu['journals'] = array('',lang("main.Journals"),lang("main.Journals_desc"));
        $menu['proceedings'] = array('',lang("main.Proceedings"),lang("main.Proceedings"));
        $menu['thesis'] = array('',lang("main.Thesis"),lang("main.Thesis"));
        
        foreach($menu as $url => $dt)
            {
                $title = '';
                $title .= $dt[1];
                $link = '<a href="'.base_url(PATH.$url).'">';
                $linka = '</a>';
                $MENU .= bsc($link.bscard($title,$dt[2]).$linka,4,'p-2');
            }
    
        $MENU = bs($MENU);

        $tela .= '<div style="height: 50px;"></div>';

		$tela .= bs(
						bsc($MENU,8).
						bsc($login,4)
					);	

		$tela .= $this->EventProceedings->resume();		
		
		return $tela;
	}

	public function dropall($tp='')
		{
			$sx = $this->cab();
			$sx .= $this->navbar();
			$tela = '';
			$this->RDF = new \App\Models\RDF();
			$tables = array('OAI_SetSpec','OAI_ListRecords','OAI_log','rdf_concept','rdf_data','rdf_literal','');
			for ($r=0;$r < count($tables);$r++)
				{
					$table = $tables[$r];
					if (strlen($table) > 0) { $this->RDF->query('TRUNCATE '.$table); $tela .= bsmessage('Truncate '.$table,1); }
				}

			$dirs = array('.tmp/','.c','../.temp/oai','../.temp/ddi');
			for ($r=0;$r < count($dirs);$r++)
				{
					$dirn = $dirs[$r];		
					delTree($dirn);
					$tela .= bsmessage('Remove '.$dirn,1);
				}
			$tela = $sx.bs($tela);
			return $tela;
		}
    
        public function authority($d1='',$d2='',$d3='',$d4='')
            {
				$this->Authority = new \App\Models\Authority\Index();
				$dt['title'] = 'Authority';
                $tela = $this->cab($dt);
                $tela .= $this->navbar($dt);
				$tela .= $this->Authority->index($d1,$d2,$d3,$d4);
                $tela .= $this->footer();

                return $tela;
            }
}
