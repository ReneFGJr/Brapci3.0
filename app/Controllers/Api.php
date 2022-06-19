<?php

namespace App\Controllers;

define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix']);

define("LIBRARY", "202101");
helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr']);

$this->session = \Config\Services::session();
$language = \Config\Services::language();

use App\Controllers\BaseController;

$hd = new \App\Models\Header\Header();

class Api extends BaseController
{
    function __construct()
    {
        define("MODULE", "api");
    }
	public function index($d1='',$d2='',$d3='',$d4='')
	{
        $API = new \App\Models\Api\Endpoints();
        return $API->index($d1,$d2,$d3,$d4);
	}

    private function cab()
        {
            $hd = new \App\Models\Header\Header();
            $dt['title'] = 'API';
            $sx = $hd->cab($dt);
			$sx .= $hd->navbar($dt);
            return $sx;
        }

    function doc()
        {
            $sx = $this->cab();
            $sx .= '<link rel="preconnect" href="https://fonts.googleapis.com">'.cr();
            $sx .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>'.cr();
            $sx .= '<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet">';
            $sx .= '<style> body { font-family: "Ubuntu", sans-serif; } </style>';            
            $sx .= bs(bsc(h('API',1),12));

            $dd['book'] = 'Busca metadados de livros com ISBN, para acessar utilize o link: <a href="'.PATH.MODULE.'/book/#ISBN#">/api/book/#ISBN</a>';
            $dd['bookcover'] = 'Busca capas de livros com ISBN, para acessar utilize o link: <a href="'.PATH.MODULE.'/bookcover/ISBN#">/api/bookcover/#ISBN</a>';
            $dd['cutter'] = 'Retorna o CUTTER pelo nome do autor: <a href="'.PATH.MODULE.'/cutter/?q=NOME DO AUTOR">/api/cutter/NOME_DO_AUTOR</a>';

            foreach($dd as $title => $text)
                {
                    $sa = h(lang('api.'.$title),4);
                    $sa .= '<p>'.$text.'</p>';
                    $sx .= bs(bsc($sa,12));
                }
            return $sx;
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
            $isbn = troca($isbn,'.','');
            $isbn = troca($isbn,'-','');

            $ISBN = new \App\Models\Book\Isbn();
            $API = new \App\Models\Book\API\Index();
            $dd = $ISBN->isbns($isbn);

            $isbn13 = $dd['isbn13'];
            $isbn10 = $dd['isbn10'];

            $dd = array();
            $dd['query'] = $isbn;

            if (($isbn13 != $isbn) and ($isbn10 != $isbn))
                {
                    $dd['status'] = '500';
                    $dd['error'] = 'ISBN invalid';
                } else {
                    $file = getenv("apiFind").'Find/cover/'.$isbn13.'.jpg';
                    if (file_exists($file))
                        {
                            $dd['status'] = '200';
                            $dd['path'] = $file;
                        } else {
                            $dd['status'] = '404';
                            $dd['error'] = 'ISBN not found';
                        }
                }
            echo json_encode($dd);
            exit;            
        }   

    function cutter($author='')
        {
            http_response_code(200);
            exit;            
        }              
}
