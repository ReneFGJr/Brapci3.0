<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class News extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci_news';
	protected $primaryKey           = 'id_news';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_news','news_title','news_content',
		'n_lock','n_lang','n_version',
	];
	protected $typeFields        = [	
		'hidden','string:100*','text',
		'sn','string:5','version'
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

	function edit($id=0)
		{
			$this->id = $id;
			$this->path = base_url(PATH.'main/news');
			$tela = form($this);

			return $tela;
		}
	function list($cmd='',$id='')
		{
			$this->path = base_url(PATH.MODULE.'/news/');
			$tela = tableView($this);
			return $tela;
		}
	
}
