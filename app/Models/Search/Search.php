<?php

namespace App\Models\Search;

use CodeIgniter\Model;

class Search extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'searches';
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

	function formSearch()
		{
			$Elastic = new \App\Models\Search\ElasticSearch();
			$tela = '';
			$tela .= $this->formSearchTitle();
			$tela .= $this->formSearchField();

			$vlr = get("query");
			$collection = get("collection");

			if (strlen($vlr) > 0)
				{
					$tela .= bs($Elastic->Search($vlr,$collection));
				}
			return $tela;
		}

	function Search($vlr,$collection)
		{
			$sx = '';
			$sx .= '<h1>Result</h1>';
			return $sx;
		}

	function formSearchTitle()
		{
			$tela = '';
			$tela .= bsc(h(lang('main.What do you looking?'),3),12,'text-center');
			return $tela;
		}

	function formSearchField()
		{
			$vlr = get("query");
			$collection = get("collection");
			$tela = '';
			$tela .= '
				<form method="get">
				<div class="input-group input-group-lg mb-0 p-3" style="border: 0px solid #0093DD;">					
  					<input type="text" name="query" value="'.$vlr.'" class="form-control shadow" placeholder="'.lang('main.What do you looking?').'">
					<select id="type" name="collection" class="form-control-2 shadow" style="border: 1px solid #ccc; font-size: 130%; line-hight: 150%; width: 250px; margin: 0px 10px;" >
				';
				$coll = array('all','article','procceding','book','authority','thesis');
				for ($r=0;$r < count($coll);$r++)
				{
					$sel = '';
					if ($collection == $coll[$r]) { $sel = 'selected ';}
					$tela .= '<option value="'.$coll[$r].'" '.$sel.'>'.lang('main.'.$coll[$r]).'</option>'.cr();
				}
				$tela .= '
					</select>
  					<input type="submit" class="btn btn-primary shadow p-3 mb-0 text-lg" type="button" value="'.lang('main.Search').'">
				</div>			
				</form>
			';
			$tela = bsc($tela,12);
			$tela = bs($tela);
			return $tela;
		}

}
