<?php

namespace App\Controllers;

use App\Controllers\BaseController;

$this->session = \Config\Services::session();
$language = \Config\Services::language();


helper(['boostrap','url','graphs','sisdoc_forms']);

function cr()
	{
		return chr(13).chr(10);
	}

class Dataverse extends BaseController
{
	// https://www.richdataservices.com/showcase

	public function __construct()
	{
		$this->Socials = new \App\Models\Socials();
		$this->Analysis = new \App\Models\Analysis();

		helper(['boostrap','url','canvas']);
		define("PATH", "index.php/dataverse/");
		define("LIBRARY", "BRAPCI_LABS");
		define("LIBRARY_NAME", "");
	}	

	private function cab($dt=array())
		{
			$title = 'Dataverse - DrashDataBoard';
			if (isset($dt['title'])) { $title = $dt['title']; } 
			$sx = '<!doctype html>'.cr();
			$sx .= '<html>'.cr();
			$sx .= '<head>'.cr();
			$sx .= '<title>'.$title.'</title>'.cr();
			$sx .= '  <meta charset="utf-8" />'.cr();
			$sx .= '  <link rel="apple-touch-icon" sizes="180x180" href="'.base_url('favicon.ico').'" />'.cr();
			$sx .= '  <link rel="icon" type="image/png" sizes="32x32" href="'.base_url('favicon.ico').'" />'.cr();
			$sx .= '  <link rel="icon" type="image/png" sizes="16x16" href="'.base_url('favicon.ico').'" />'.cr();
			$sx .= '  <!-- CSS -->'.cr();
			$sx .= '  <link rel="stylesheet" href="'.base_url('/css/bootstrap.css').'" />'.cr();
			$sx .= '  <link rel="stylesheet" href="'.base_url('/css/style.css?v0.0.8').'" />'.cr();
			$sx .= ' '.cr();
			$sx .= '  <!-- CSS -->'.cr();
			$sx .= '  <script src="'.base_url('/js/bootstrap.js?v=5.0.2').'"></script>'.cr();
			$sx .= '</head>'.cr();
            $sx .= '<body>'.cr();
			return $sx;

		}

    function pdf($id1='',$id2='',$id3='')
        {
            //http://dadospreservados.rnp.br/api/datasets/export?exporter=ddi&persistentId=doi%3A10.80102/FK2/CEAQOW
            $url = 'http://dadospreservados.rnp.br/api/datasets/export?exporter=ddi&persistentId=doi%3A10.80102/FK2/CEAQOW';
            
            $file = md5($url).'.ddi.xml';
            $file = '../_temp/ddi/'.$file;

            if (!file_exists($file))
                {
                    $txt = file_get_contents($url);
                    file_put_contents($file,$txt);
                }

        
            $xml = simplexml_load_file($file);
		    $sx = $this->cab();
		    $sx .= $this->navbar();
            $sx .= $this->show_variables($xml);            
            $sx = bs($sx);
            return $sx;
        }
    function show_variables($xml)
        {
            $var = $xml->dataDscr->var;

            $sx = bsc('<h1>Total de variáveis: '.count($var).'<h1>',12);

            for ($r=0;$r < count($var);$r++)
                {
                    $x = (array)$var[$r];
                    $xo = $var[$r];
                    $op = $x['@attributes'];

                    //$cat = (array)$x->catgry;       
   
                    $lc = (array)$x['location'];
                    $ft = (array)$x['varFormat'];

                    $label = (string)$x['labl'];
                    
                    if (isset($x['catgry']))
                        {
                            $catgrp = (array)$x['catgry'];
                            $tot = 0;
                            $cats = array();
                            for ($t=0;$t < count($catgrp);$t++)
                                {
                                    $c = (array)$catgrp[$t];
                                    $vlr = $c['catStat'];                                    
                                    $vr = $c['catValu'];
                                    $cats[$vr] = array('label'=>$c['labl'],'freq'=>$vlr,'percent'=>0);
                                    $tot = $tot + $vlr;
                                }

                            $st = '<table class="tablex" style="width: 100%; border: 1px solid #000000;">';
                            foreach($cats as $ct => $dc)                            
                            {
                                $st .= '<tr>';
                                $st .= '<td class="p-1">'.$dc['label'].'</td>';
                                $st .= '<td class="p-1" style="text-align: right;">'.number_format($dc['freq'],0,',','.').'</td>';
                                if ($tot > 0)
                                {
                                    $st .= '<td class="p-1" style="text-align: right;">'.number_format($dc['freq']/$tot*100,1,',','.').'%</td>';
                                } else {
                                    $st .= '<td></td>';
                                }
                                $st .= '</tr>';
                            }
                            $st .= '</table>';                                                        
                        } else {
                            $st = '';
                        }
                    
                    $notes = (array)$x['notes'];
                    $fileid = (string)$lc['@attributes']['fileid'];

                    /*********************************************** */
                    if (isset($var->catgry[0]))
                        {
                            $cat = (array)$var->catgry;
                            print_r($cat);
                        }
                    
                    $sx .= bsc('<h4>'.$label.'</h4>',12);
                    $sx .= bsc($op['ID'],1);
                    $sx .= bsc('<b>'.$op['name'].'</b>',4);
                    $sx .= bsc($op['intrvl'],1);
                    $sx .= bsc($fileid,1);
                    $sx .= bsc('<small>Type</small>: '.$ft['@attributes']['type'],2);
                    $sx .= bsc('x',3);
                    $sx .= bsc('&nbsp;',1);
                    $sx .= bsc($st,8);

                    /************************************************************************** */
                    $vls = array('max'=>0,'min'=>'','mean'=>''
                                    ,'medn'=>'','mode'=>'','stdev'=>''
                                    ,'invd'=>'','vald'=>'');

                    if (isset($xo->sumStat[0]))
                    {
                        for ($t=0;$t < 8;$t++)
                            {
                                $sum = (array)$xo->sumStat[$t];                            
                                $vlr = $sum[0];
                                $ind = $sum['@attributes']['type'];
                                $vls[$ind] = $vlr;
                            }

                            $cat = array('Mínimo','Máximo','Média',
                                        'Mediana','Moda','Desvio padrão',
                                        'Inverso?','Valido');                        
                            $sv = '<table>';
                            $n = 0;
                            foreach($vls as $vn => $vl)
                                {
                                    $sv .= '<tr>';
                                    $sv .= '<td style="text-align: right;">'.$vn.'</td>';
                                    $sv .= '<td style="text-align: right;">'.$vl.'</td>';
                                    $sv .= '</tr>';
                                    $n++;
                                }
                            $sv .= '</table>';
                            $sx .= bsc($sv,3);
                        $sx .= bsc('',8);
                    }

                }
            $sx = bs($sx);
            return $sx;
        }

	private function navbar($dt=array())	
		{
			$title = 'DataViewer';
			if (isset($dt['title'])) { $title = $dt['title']; } 
			$sx = '<nav class="navbar navbar-expand-lg navbar-dark bc">'.cr();
			$sx .= '  <div class="container-fluid">'.cr();
			$sx .= '    <a class="navbar-brand" href="'.base_url().'">'.$title.'</a>'.cr();
			$sx .= '    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">'.cr();
			$sx .= '      <span class="navbar-toggler-icon"></span>'.cr();
			$sx .= '    </button>'.cr();
			$sx .= '    <div class="collapse navbar-collapse" id="navbarSupportedContent">'.cr();
			$sx .= '      <ul class="navbar-nav me-auto mb-2 mb-lg-0">'.cr();
			/*
			$sx .= '        <li class="nav-item">'.cr();
			$sx .= '          <a class="nav-link active" aria-current="page" href="#">Home</a>'.cr();
			$sx .= '        </li>'.cr();
			$sx .= '        <li class="nav-item">'.cr();
			$sx .= '          <a class="nav-link" href="#">Link</a>'.cr();
    		$sx .= '		</li>'.cr();
			*/
			$sx .= '        <li class="nav-item dropdown">'.cr();
			$sx .= '          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">'.cr();
			$sx .= '            '.lang('dataview.views').cr();
			$sx .= '          </a>'.cr();
			$sx .= '          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">'.cr();
			$sx .= '            <li><a class="dropdown-item" href="'.base_url('dataverse/pdf').'">'.lang('dataview.Labs.pdf').'</a></li>'.cr();
			$sx .= '            <li><a class="dropdown-item" href="'.base_url('dataverse/ontology').'">'.lang('dataview.Labs.Ontology').'</a></li>'.cr();
			$sx .= '            <li><a class="dropdown-item" href="'.base_url('dataverse/analysis').'">'.lang('dataview.Labs.Analysis').'</a></li>'.cr();
			$sx .= '          </ul>'.cr();
			$sx .= '        </li>'.cr();

			$sx .= '      </ul>'.cr();

			/*
			$sx .= '        <li class="nav-item">'.cr();
			$sx .= '          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>'.cr();
			$sx .= '        </li>'.cr();
			$sx .= '      </ul>'.cr();
			*/

			/*
			$sx .= '      <form class="d-flex">'.cr();
			$sx .= '        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">'.cr();
			$sx .= '        <button class="btn btn-outline-success" type="submit">Search</button>'.cr();
			$sx .= '      </form>'.cr();
			*/

			$sx .= $this->Socials->nav_user();

			$sx .= '    </div>'.cr();
			$sx .= '  </div>'.cr();
			$sx .= '</nav>'.cr();
			return $sx;
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
		$dt = array();
		$d[0] = array('image'=>'http://images.unsplash.com/photo-1492305175278-3b3afaa2f31f?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=1080&fit=max','link'=>'');
		$d[1] = array('image'=>'https://images3.alphacoders.com/102/102609.jpg','link'=>'');
		$d[2] = array('image'=>'https://static.escolakids.uol.com.br/2019/07/paisagem-natural-e-paisagem-cultural.jpg','link'=>'');
		
		$tela .= bscarousel($d);
		
		$tela .= bs(h('Drashboard',1),array('fluid'=>0,'g'=>5));
		$tela .= bs(
						bsc(bscard('Hello'),4).
						bsc(bscard('Hello'),4).
                        bsc(bscard('Hello'),4)
					);

		//$tela .= bs(bsc(graph_demo(),12));
		
		return $tela;
	}

	public function analysis($d1 = '', $id = '')
	{
		$tela = $this->cab();
		$tela .= $this->navbar();
		$tela .= $this->Analysis->index($d1,$id);
		return $tela;
	}
	
}
