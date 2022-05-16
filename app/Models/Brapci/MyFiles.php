<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class MyFiles extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci_my_area.my_files';
	protected $primaryKey           = 'id_file';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_file', 'file_own', 'file_full', 'file_path_logical', 'file_name',
		'file_type', 'file_size', 'file_cotenttype', 'file_ext', 'file_data', 'file_save'
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

	function index()
	{
	}

	function insert_file($dd)
	{
		$dd['file_type'] = '';
		$dd['file_size'] = '';

		$dt = $this
			->where('file_own', $dd['file_own'])
			->where('file_full', $dd['file_full'])
			->findAll();
		if (count($dt) == 0) {
			$tp = pathinfo($dd['file_name']);
			$dd['file_type'] = -1;
			$dd['file_size'] = filesize($dd['file_full']);
			$dd['file_cotenttype'] = mime_content_type($dd['file_full']);
			$dd['file_ext'] = $tp['extension'];
			$dd['file_data'] = date("Y-m-d");
			$dd['file_save'] = 1;
			$this->insert($dd);
			return (1);
		} else {
			return (0);
		}

		$dd['file_cotenttype'] = '';
	}

	function upload($cab, $d1, $d2, $d3, $d4)
	{
		$Socials = new \App\Models\Socials();
		$user = $Socials->getID();
		$sx = $cab;

		/************************** FORM */
		$sx .= form_open_multipart();
		$sx .= form_upload('file');
		$sx .= form_submit(array('name' => 'submit', 'class' => 'btn btn-primary', 'value' => 'Upload'));
		$sx .= form_close();

		print_r($_FILES);


		if (isset($_FILES['file']['name'])) {
			$name = $_FILES['file']['name'];
			$file_tmp = $_FILES['file']['tmp_name'];

			if (file_exists($file_tmp)) {
				$dir = '../.tmp';
				dircheck($dir);
				$dir = '../.tmp/.user/';
				dircheck($dir);
				$dir = '../.tmp/.user/' . $user . '/';
				dircheck($dir);
				$file_dest = $dir . md5_file($file_tmp);

				if (move_uploaded_file($file_tmp, $file_dest) == true) {
					$dd['file_full'] = $file_dest;
					$dd['file_name'] = $name;
					$dd['file_own'] = $user;
					$dd['file_own_gropup'] = 0;
					$dd['file_path_logical'] = '';
					$this->insert_file($dd);
					$sx = wclose();
				} else {
					$sx .= bsmessage('Save upload faile failed', 3);
				}

				echo $dir;
			} else {
				$sx .= bsmessage('Upload failed', 3);
			}
		}
		$sx = bs(bsc($sx, 12));
		return $sx;
	}

	function SO()
	{
		$so = php_uname();
		if (strpos($so, 'Windows') !== false) {
			$os = 'Windows';
		} else {
			$os = 'Linux';
		}
		jslog($so);
		return $os;
	}

	function ajax()
		{
			$Social = new \App\Models\Socials();
			$user = $Social->getID();
			$dir = '.tmp/';
			dircheck($dir);
			$dir = '.tmp/.user/';
			dircheck($dir);
			$dir = '.tmp/.user/'.$user.'/';
			dircheck($dir);

			$arr_file_types = ['application/octet-stream'];

			$file = $_FILES['file']['name'];
			$type = $_FILES['file']['type'];

			if (ajax($dir,$arr_file_types))
				{
					$tela = '';
					$tela .= $this->process_file($dir.$file);
					$tela = bs(bsc(bsmessage(lang('bacpci.saved '.$file. ' - '.$type),1),12));
				} else {
					$tela = bs(bsc(bsmessage(lang('bacpci.save_save_error - '.$type),3),12));
				}
			echo $tela;
			exit;
		}	

	function tools($d1,$d2,$d3,$d4)
		{			
			$sx = '';
			switch($d1)
				{
					case 'upload_ajax':
						print_r($$_FILES);
						exit;
						$this->ajax();
						exit;
					break;

					case 'uft8':
						$sa = h(lang('brapci.convert_utf8'),3);
						$url = base_url(PATH.MODULE.'tools/upload_ajax');
						//$sa .= upload($url);
						$sa .= upload_form();	
						if (isset($_FILES['file']))
						{				
							if (isset($_FILES['file']['tmp_name']))
								{
									$file = $_FILES['file']['name'];
									$filename = $_FILES['file']['tmp_name'];
									$txt = file_get_contents($filename);

									header('Content-Description: File Transfer');
									header('Content-Disposition: attachment; filename='.basename($file));
									header('Expires: 0');
									header('Cache-Control: must-revalidate');
									header('Pragma: public');
									header('Content-Length: ' . strlen($filename));
									header("Content-Type: text/plain");	
									echo utf8_decode($txt);
									exit;								
								} else {
									$sa .= bs_alert('danger','ERRO');
								}
						}
						$sx = bs(bsc($sa,12));
					break;

					default:
						$sx .= bs(bsc(h(lang('brapci.my_files'),3),12));
						$dv[PATH.MODULE.'tools/uft8'] = lang('brapci.file_convert_utf8');

						$sa = '';
						$sb = '';
						$sc = menu($dv);
						$sx .= bs(bsc($sa,4).bsc($sb,4).bsc($sc,4));
						break;
				}
			return $sx;
		}

	function preview($id, $ac, $tp)
	{
		$dt = $this->find($id);
		$sx = '';

		switch ($ac) {
			case 'utf8':
				$action = get("action");
				$txt = file_get_contents($dt['file_full']);
				$txt = utf8_encode($txt);
				if ($action == 'save') {
					file_put_contents($dt['file_full'].'.txt', $txt);
					echo "==>SAVED ".$dt['file_full'].'.txt';
					exit;
				}
				
				$sx .= 'Encoding:'. mb_detect_encoding($txt);
				$sx .= '<hr>';
				$txt = utf8_encode($txt);
				$sx .= '<a href="'.PATH.MODULE.'file/'.$id.'/'.$ac.'?action=save" class="btn btn-primary">'.lang('ai.save').'</a>';
				$sx .= '<pre>'.$txt.'</pre>';
				break;
			case 'txt':
				echo '<pre>';
				readfile($dt['file_full'].'.txt');
				echo '</pre>';
				break;
				
			case 'actions':
				$sx .= $this->actions($id);
				break;

			case 'pdf2txt':
				$ori = $dt['file_full'];
				$dest = $dt['file_full'] . '.txt';
				$sx .= $this->pdf2txt($ori, $dest);
				break;

			default:
				switch ($dt['file_ext']) {
					case 'txt':
						$filename = $dt['file_full'];
						$file = $dt['file_name'];
						header("Content-type:text/plain");
						header("Content-Disposition:inline;filename='$file");
						readfile($filename);
						exit;
						break;
					case 'pdf':
						$filename = $dt['file_full'];
						$file = $dt['file_name'];
						header("Content-type:application/pdf");
						header("Content-Disposition:inline;filename='$file");
						readfile($filename);
						exit;
						break;
					default:
						$sx = $dt['file_full'];
						break;
				}
				break;
		}
		echo $sx;
		return '';
	}

	function pdf2txt($ori = '', $dest = '')
	{
		// https://pypi.org/project/pdftotext/pdf
		// Windows: pip3 install pdftotext
		// Linux: pip3 install pdftotext
		// pip3 install pypdf2
		$os = $this->so();

		switch ($os) {
			case 'Windows':
				$cmd = getenv('pdf2txt') . ' ' . $ori . ' ' . $dest;
				$rst = shell_exec($cmd);
				echo '<pre>' . $cmd . '</pre>';
				echo $rst;
				exit;
			case 'Linux':
				$cmd = 'pdftotext ' . $ori . ' ' . $dest;
				$rst = shell_exec($cmd);
				echo $rst;
				exit;
			default:
				$rst = 'OS not supported';
				echo $rst;
				break;
		}
	}

	function actions($id = '')
	{
		$so = (string)$this->SO();
		switch ($so) {
			case 'Windows':
				$mn[PATH . MODULE . 'file/' . $id . '/pdf2txt'] = lang('ai.convert_pdf_to_txt');
				$mn[PATH . MODULE . 'file/' . $id . '/utf8'] = lang('ai.convert_utf8');
				break;
			default:
				$mn[PATH . MODULE] = lang('ai.convert_pdf_to_txt');
				$mn[PATH . MODULE . 'file/' . $id . '/utf8'] = lang('ai.convert_utf8');
				break;
		}
		$sx = menu($mn);
		return $sx;
	}

	function list($user = 0, $path = '')
	{
		$sx = '';
		$dt = $this->where("file_own", $user)->findAll();
		$sx .= '<ul>';
		for ($r = 0; $r < count($dt); $r++) {
			$line = $dt[$r];
			$file = $line['file_full'];
			if (file_exists($file)) {
				$link = '<a href="' . PATH . MODULE . $path . '/file/' . $line['id_file'] . '">';
				$linka = '</a>';

				$sx .= '<li>';
				$sx .= $link . $line['file_name'] . $linka;

				$tp = array('txt');
				for ($z = 0; $z < count($tp); $z++) {
					if (file_exists($file . '.'.$tp[$z])) {
						$sx .= ' <span class="btn-primary small rounded ps-1 pe-1">' . lang($tp[$z]) . '</span>';
					}
				}
				$sx .= '</li>';
			}
		}
		$sx .= '</ul>';
		$sx .= $this->btn_upload_file($user);
		return $sx;
	}

	function btn_upload_file($user)
	{
		$sx = onclick(PATH . MODULE . 'popup/myfiles/upload/' . $user, 600, 400, 'btn btn-primary') . bsicone('upload') . ' ' . lang('ai.upload_file') . '</span>';
		return $sx;
	}
}
