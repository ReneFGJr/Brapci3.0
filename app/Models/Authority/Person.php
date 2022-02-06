<?php

namespace App\Models\Authority;

use CodeIgniter\Model;

class Person extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci_authority.AuthorityNames';
	protected $primaryKey           = 'id_a';
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
			if ($dt['a_brapci'] != 0)
			{			
				$link = base_url(PATH .MODULE . '/index/import_lattes/' . trim($dt['a_lattes']) . '/');
				$link2 = '<a href="' . $link . '" target="_new' . $dt['a_lattes'] . '">';
				$link2 .= '<img src="' . base_url('img/icones/import.png') . '?x=1" style="height: 50px">';
				$link2 .= '</a>';
			} else {

			}
			$tela .= bsc('<small>' . lang('Link do Lattes') . '</small><br>' . $link1 . $link2, 12);
		} else {
			$tela .= anchor(base_url(PATH . MODULE. '/index/LattesFindId/' . $dt['id_a']));
		}

		if ($dt['a_lattes'] == 0)
			{
				return $tela.'no lattes';
			}	

		/*************************************************** BRAPCI */
		if (($dt['a_brapci'] == 0) and (strpos($dt['a_uri'],'brapci.inf.br')))
			{
				$txt = $dt['a_uri'];
				while (strpos(' '.$txt,'/') > 0)
					{
						$pos = strpos($txt,'/');
						$txt = substr($txt,$pos+1,strlen($txt));
					}
				$sql = "update ".$this->table." set a_brapci = $txt where id_a = ".$id;
				$this->query($sql);
				$dt['a_brapci'] = $txt;
			}
		$tela .= $this->PersonPublications($dt['a_lattes']);
		return $tela;
	}

	function PersonPublications($id)	
		{
			$LattesProducao = new \App\Models\Lattes\LattesProducao();
			$tela = $LattesProducao->producao($id);
			/*
			$RDF = new \App\Models\Rdf\RDF();
			$dt = $RDF->le($id,0,'brapci');
			$tela .= $RDF->view_data($dt);
			*/
			return $tela;
		}
}
