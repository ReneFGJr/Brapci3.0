<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class Main extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'mains';
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

	function index($d1,$d2)
		{
			$menu = array();
			$menu['news']['list'] = 'main/news/';
			$tela = '';
			foreach($menu as $m=>$c)
				{
					if (is_array($c))
						{
							foreach($c as $cm=>$cc)
								{
									$tela .= '<a href="'.base_url($_SERVER['app.baseURL'].'/'.$cc).'">';
									$tela .= $cm.'--->'.$cc.'<br>';		
									$tela .= '</a>';
								}
						} else {
							$tela .= $m.'--->'.$c.'<br>';
						}
					
				}
			return $tela;
		}
}
