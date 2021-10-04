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

	function formSearchTitle()
		{
			$tela = '';
			$tela .= bsc(h(lang('What_are_you_looking?'),3),12,'text-center');
			return $tela;
		}

	function formSearchField()
		{
			$tela = '';
			$tela .= '
				<div class="input-group">
  					<input type="text" class="form-control" placeholder="Recipient\'s username" aria-label="Recipient\'s username with two button addons">
					<select id="type" class="form-control" style="width: 40px; margin: 0px 10px;" >
						<option value="">All Collections</option>
					</select>
  					<button class="btn btn-primary" type="button">Button</button>
  					</div>			
			';
			$tela = bsc($tela,12);
			$tela = bs($tela);
			return $tela;
		}

}
