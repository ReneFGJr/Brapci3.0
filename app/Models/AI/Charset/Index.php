<?php

namespace App\Models\AI\Charset;

use CodeIgniter\Model;

class Index extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = '*';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id','text','description'
	];
	protected $typeFields        = [
		'hidden','text','none'
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

	function formAIFile($d1,$d2='')
		{
			$url = PATH.MODULE.'ai/charset/'.$d2;
			$sx = form_open_multipart($url);
			$sx .= form_upload('file');
			$sx .= form_submit('submit',lang('ai.Upload'));
			$sx .= form_close();
			$sx = bs(bsc($sx));

			if (isset($_FILES['file']['tmp_name']))
				{
					$tmp = $_FILES['file']['tmp_name'];
					$file_name = $_FILES['file']['name'];
					$type = $_FILES['file']['name'];
					$txt = file_get_contents($tmp);

					$utf8 = new \App\Models\Ai\Charset\Utf8();
					$txt = $utf8->convert($txt,'utf8','iso-8859-1');
					header('Content-Type: '.$type);
					header('Content-Disposition: attachment; filename="'.$file_name.'"');
					echo $txt;
					exit;					
				}
			
			return $sx;
		}

	function formAI($d1,$d2)
		{
			$this->path = PATH.MODULE.'ai/charset/'.$d2;
			$sx = form($this);
			$sx = bs(bsc($sx));
			return $sx;
		}

	function index($d1,$d2,$d3,$d4)
		{
			$tela = '';
			//corpusId
			switch($d2)
				{
					case 'xx':

					break;
					break;

					default: 
					break;
				}
			return $tela;
		}	
}
