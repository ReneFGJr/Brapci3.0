<?php

namespace App\Models\Brapci;

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
			$tela = '';
			$tela .= $this->formSearchTitle();
			$tela .= $this->formSearchField();
			return $tela;
		}

	function getFullText($id)
		{
			$sql = "
			SELECT d_r2 as idf, c_class 
				FROM brapci.rdf_data 
				INNER JOIN brapci.rdf_class ON d_p = id_c 
				WHERE d_r1 = $id and c_class = 'hasFileStorage'
			";
			$dt = $this->query($sql)->getResult();
			if (count($dt) > 0)
				{
					$line = (array)$dt[0];
					$idt = $line['idf'];
					$url = 'https://brapci.inf.br/index.php/res/txt/'.$idt;
					$txt = file_get_contents($url);
					return $txt;
				}
			return '';
		}

	function formSearchTitle()
		{
			$tela = '';
			$tela .= bsc(h(lang('main.What do you looking?'),3),12,'text-center');
			return $tela;
		}

	function formSearchField()
		{
			$tela = '';
			$tela .= '
				<div class="input-group input-group-lg mb-0 p-3" style="border: 0px solid #0093DD;">
  					<input type="text" class="form-control shadow" placeholder="'.lang('main.What do you looking?').'">
					<select id="type" class="form-control-2 shadow" style="border: 1px solid #ccc; font-size: 130%; line-hight: 150%; width: 250px; margin: 0px 10px;" >
						<option value="">'.lang('main.All Collections').'</option>
						<option value="">'.lang('main.Articles').'</option>
						<option value="">'.lang('main.Proceedings').'</option>
						<option value="">'.lang('main.Books').'</option>
						<option value="">'.lang('main.Authorities').'</option>
						<option value="">'.lang('main.Thesis').'</option>
					</select>
  					<button class="btn btn-primary shadow" type="button">'.lang('main.Search').'</button>
  					</div>			
			';
			$tela = bsc($tela,12);
			$tela = bs($tela);
			return $tela;
		}

}
