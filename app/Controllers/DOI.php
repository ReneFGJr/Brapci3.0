<?php

namespace App\Controllers;

use App\Controllers\BaseController;
helper(['url','sisdoc_forms']);

class DOI extends BaseController
{
	public function index()
	{
		// http://brapci3/doi/metadata
	}

	public function metadata($d1='',$d2='',$d3='')
		{
			$BrapciDoiRequest = new \App\Models\Doi\BrapciDoiRequest();


			$putdata = fopen("php://input", "r");

			/********************************** Recupera arquivo */
			$txt = '';
			while ($data = fread($putdata,1024))
				{
					$txt .= $data;
				}
			fclose($putdata);
			if (strlen($txt) > 0)
				{
					$filename = date("Ymd_His").'.xml';
					$dir = 'doi';
					dircheck($dir);
					$filename = $dir.'/'.$filename;
					file_put_contents($filename,$txt);
				} else {
					$filename = '';
				}
			

			/*********************************** Recupera registros */
			$DOI = $_SERVER['PHP_SELF'];
			$doi = substr($DOI,strpos($DOI,'metadata')+9,strlen($DOI));

			/************************************** USUÁRIO */
			if (isset($_SERVER['PHP_AUTH_USER']))
				{
					$user = $_SERVER['PHP_AUTH_USER'];
				} else {
					$user = 'none';
				}
			/************************************** SENHA */
			if (isset($_SERVER['PHP_AUTH_PW']))
				{
					$pass = $_SERVER['PHP_AUTH_PW'];
				} else {
					$pass = 'none';
				}			
			$meth = $_SERVER['REQUEST_METHOD'];

			$data = array();
			$data['rq_ip'] = ip();
			$data['rq_type'] = $meth;
			$data['rq_filename'] = $filename;
			$data['rq_user'] = $user;
			$data['rq_pass'] = md5($pass);
			$data['rq_doi'] = $doi;
			$BrapciDoiRequest->insert($data);
			echo 'User:'.$user.cr();
			echo 'Pass:'.$pass.cr();
			echo 'Method:'.$meth.cr();
			echo 'DOI:'.$doi;
			exit;			
		}
}
