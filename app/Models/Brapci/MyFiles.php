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
		'id_file','file_own','file_full','file_path_logical','file_name',
		'file_type','file_size','file_cotenttype','file_ext','file_data','file_save'
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
				->where('file_own',$dd['file_own'])
				->where('file_full',$dd['file_full'])
				->findAll();
			if (count($dt) == 0)
				{
					$tp = pathinfo($dd['file_name']);
					$dd['file_type'] = -1;
					$dd['file_size'] = filesize($dd['file_full']);
					$dd['file_cotenttype'] = mime_content_type($dd['file_full']);
					$dd['file_ext'] = $tp['extension'];
					$dd['file_data'] = date("Y-m-d");
					$dd['file_save'] = 1;
					$this->insert($dd);
					return(1);
				} else {
					return(0);
				}
			
			$dd['file_cotenttype'] = '';
		}

	function upload($cab,$d1,$d2,$d3,$d4)
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


			if (isset($_FILES['file']['name']))
				{
				$name = $_FILES['file']['name'];
				$file_tmp = $_FILES['file']['tmp_name'];

				if (file_exists($file_tmp))
					{
						$dir = '../.tmp';
						dircheck($dir);
						$dir = '../.tmp/.user/';
						dircheck($dir);
						$dir = '../.tmp/.user/'.$user.'/';
						dircheck($dir);
						$file_dest = $dir.md5_file($file_tmp);

						if (move_uploaded_file($file_tmp,$file_dest) == true)
							{
								$dd['file_full'] = $file_dest;
								$dd['file_name'] = $name;
								$dd['file_own'] = $user;
								$dd['file_own_gropup'] = 0;
								$dd['file_path_logical'] = '';					
								$this->insert_file($dd);
								$sx = wclose();
							} else {
								$sx .= bsmessage('Save upload faile failed',3);
							}

						echo $dir;
					} else {
						$sx .= bsmessage('Upload failed',3);
					}
				}
				$sx = bs(bsc($sx,12));
			return $sx;
		}

	function list($user=0)
		{
			$sx = '';
			$dt = $this->where("file_own",$user)->findAll();
			$sx .= '<ul>';
			for ($r=0;$r < count($dt);$r++)
				{
					$line = $dt[$r];					
					$sx .= '<li>';
					$sx .= $line['file_name'];
					$sx .= '</li>';
				}
			$sx .= '</ul>';
			$sx .= $this->btn_upload_file($user);
			return $sx;
		}

	function btn_upload_file($user)
		{
			$sx = onclick(PATH.MODULE.'popup/myfiles/upload/'.$user,600,400,'btn btn-primary').bsicone('upload').' '.lang('ai.upload_file').'</span>';
			return $sx;
		}
}
