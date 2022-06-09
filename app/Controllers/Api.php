<?php

namespace App\Controllers;

define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix'].'authority');

define("LIBRARY", "202101");
helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr']);

$this->session = \Config\Services::session();
$language = \Config\Services::language();

use App\Controllers\BaseController;

$hd = new \App\Models\Header\Header();

class Api extends BaseController
{
	public function index($d1='',$d2='',$d3='',$d4='')
	{
        $API = new \App\Models\Api\Endpoints();
        return $API->index($d1,$d2,$d3,$d4);
	}

    private function cab()
        {
            $hd = new \App\Models\Header\Header();
            $dt['title'] = 'API';
            $tela = $hd->cab($dt);
			$tela .= $hd->navbar($dt);
            return $tela;
        }

    function doc()
        {
            $tela = $this->cab();
            $tela .= bs(bsc(h('API',1),12));
            return $tela;
        }

    function book($id='')
        {
            $ISBN = new \App\Models\Book\Isbn();
            $API = new \App\Models\Book\API\Index();
            $dd = $ISBN->isbns($id);
            
            $isbn = $dd['isbn13'];
            $dd['status'] = '200';
            $dd['error'] = '';

            /************************ Consulta */
            $dm = $API->index($isbn);
            $dd = array_merge($dd,$dm);
            
            header("Content-Type: application/json");
            http_response_code(200);
            echo json_encode($dd);
            exit;            
        }

    function bookcover($isbn='')
        {
            $Covers = new \App\Models\Book\API\Covers();

            /************************ Busca */
            $Covers->index($isbn);            
            http_response_code(404);
            exit;            
        }        
}
