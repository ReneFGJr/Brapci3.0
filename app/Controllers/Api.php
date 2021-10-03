<?php

namespace App\Controllers;

//define("PATH", 'Authoriry');
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
}
