<?php

namespace App\Models\Header;

use CodeIgniter\Model;

function cr()
	{
		return chr(13).chr(10);
	}

class Header extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'headers';
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

	function menu($dt)
		{
			$nm = $dt['title'];
			$it = array();
			$it['home'] = base_url(PATH);
			switch($nm)
				{
					case 'Authority':
						$it['list'] = base_url(PATH.'/index/list');
						$it['import'] = base_url(PATH.'/index/import');
						break;
				}
			/************* Mostra Menu */
			$tela = '<ul class="nav justify-content-center">'.cr();
			foreach($it as $name=>$url)
				{
					$tela .= '<li class="nav-item">'.cr();
					$tela .= '<a class="nav-link" href="'.$url.'">'.$name.'</a>'.cr();
					$tela .= '</li>'.cr();
				}
				$tela .= '</ul>';
				return $tela;
		}

	function cab($dt = array())
	{
		$thema = '/css/bootstrap.css';
		$thema = '/css/bootstrap-cyborg.min.css';
		$title = 'Brapci Proceedings';
		if (isset($dt['title'])) {
			$title = 'Brapci ' . lang($dt['title']);
		}
		$sx = '<!doctype html>' . cr();
		$sx .= '<html>' . cr();
		$sx .= '<head>' . cr();
		$sx .= '<title>' . $title . '</title>' . cr();
		$sx .= '  <meta charset="utf-8" />' . cr();
		$sx .= '  <link rel="apple-touch-icon" sizes="180x180" href="' . base_url('favicon.ico') . '" />' . cr();
		$sx .= '  <link rel="icon" type="image/png" sizes="32x32" href="' . base_url('favicon.ico') . '" />' . cr();
		$sx .= '  <link rel="icon" type="image/png" sizes="16x16" href="' . base_url('favicon.ico') . '" />' . cr();
		$sx .= '  <!-- CSS -->' . cr();
		$sx .= '  <link rel="stylesheet" href="' . base_url($thema) . '" />' . cr();
		$sx .= '  <link rel="stylesheet" href="' . base_url('/css/style.css?v0.0.20') . '" />' . cr();
		$sx .= ' ' . cr();
		$sx .= '  <!-- CSS -->' . cr();
		$sx .= '  <script src="' . base_url('/js/bootstrap.js?v=5.0.2') . '"></script>' . cr();
		$sx .= '<style>
		@font-face {font-family: "Handel Gothic";
			src: url("' . base_url('css/fonts/HandelGothic/handel_gothic.eot') . '"); /* IE9*/
			src: url("' . base_url('css/fonts/HandelGothic/handel_gothic.eot?#iefix') . '") format("embedded-opentype"), /* IE6-IE8 */
			url("' . base_url('css/fonts/HandelGothic/handel_gothic.woff2') . '") format("woff2"), /* chrome firefox */
			url("' . base_url('css/fonts/HandelGothic/handel_gothic.woff') . '") format("woff"), /* chrome firefox */
			url("' . base_url('css/fonts/HandelGothic/handel_gothic.ttf') . '") format("truetype"), /* chrome firefox opera Safari, Android, iOS 4.2+*/
			url("' . base_url('css/fonts/HandelGothic/handel_gothic.svg#Handel Gothic') . '") format("svg"); /* iOS 4.1- */
			url("' . base_url('css/fonts/Roboto/Roboto-Thin.ttf') . '") format("truetype"), /* chrome firefox opera Safari, Android, iOS 4.2+*/
		}
		@import url(\'https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap\');
		</style>';
		$sx .= '</head>' . cr();

		if (get("debug") != '') {
			$sx .= '<style> div { border: 1px solid #000000;"> </style>';
		}
		return $sx;
	}

	function navbar($dt = array())
	{
		$this->Socials = new \App\Models\Socials();
		$title = 'BRAPCI';
		if (isset($dt['title'])) {
			$title = 'Brapci ' . lang($dt['title']);
		}
		$sx = '<nav class="navbar navbar-expand-lg navbar-dark bc">' . cr();
		$sx .= '  <div class="container-fluid">' . cr();
		$sx .= '  <a class="navbar-brand" href="' . base_url() . '">' . $title . '</a>' . cr();
		$sx .= '    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">' . cr();
		$sx .= '      <span class="navbar-toggler-icon"></span>' . cr();
		$sx .= '    </button>' . cr();
		$sx .= '    <div class="collapse navbar-collapse" id="navbarSupportedContent">' . cr();

		$sx .= $this->Socials->nav_user();

		$sx .= '    </div>' . cr();
		$sx .= '  </div>' . cr();
		$sx .= '</nav>' . cr();
		return $sx;
	}

	function typing($name='',$sub='')
		{
			$sx = '<div class="text-center">';
			$sx .= '<h1 style="font-family: Roboto, Tahoma, Arial; font-size: 400%;">';
			$sx .= $name;
			$sx .= '</h1>';
			$sx .= '</div>';
			return $sx;
		}

	function footer()
	{
		$tela = '<hr>';
		$tela .= '<div style="height: 200px">';
		$tela .= 'Footer';
		$tela .= '</div>';
		return $tela;
	}
}
