<?php

namespace App\Models\Authority;

use CodeIgniter\Model;

class Person extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'people';
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

function viewid($id)
	{
		$AuthorityNames = new \App\Models\Authority\AuthorityNames();
		$dt = $AuthorityNames->find($id);
		$tela = h($dt['a_prefTerm'], 1);
		$tela .= anchor($dt['a_uri']);

		if (strlen($dt['a_lattes']) > 0) {
			$link = 'http://lattes.cnpq.br/' . trim($dt['a_lattes']);
			$link1 = '<a href="' . $link . '" target="_new' . $dt['a_lattes'] . '">';
			$link1 .= '<img src="' . base_url('img/icones/lattes.png') . '" style="height: 50px">';
			$link1 .= '</a>';

			$link = base_url(PATH . '/index/import_lattes/' . trim($dt['a_lattes']) . '/' . $dt['id_a'] . '/');
			$link2 = '<a href="' . $link . '" target="_new' . $dt['a_lattes'] . '">';
			$link2 .= '<img src="' . base_url('img/icones/import.png') . '?x=1" style="height: 50px">';
			$link2 .= '</a>';

			$tela .= bsc('<small>' . lang('Link do Lattes') . '</small><br>' . $link1 . $link2, 12);
		} else {
			$tela .= anchor(base_url(PATH . '/index/LattedFindId/' . $dt['id_a']));
		}
		return $tela;
	}	
}
