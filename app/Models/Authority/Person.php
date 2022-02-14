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

function viewid($id,$loop=0)
	{
		$AuthorityNames = new \App\Models\Authority\AuthorityNames();
		$Lattes = new \App\Models\Lattes\Lattes();
		$Brapci = new \App\Models\Brapci\Brapci();

		$RDF = new \App\Models\Rdf\RDF();
		$da = $RDF->le($id);
		
		$use = $da['concept']['cc_use'];
		if ($use > 0)
			{
				if ($loop > 4) { echo "OPS - Falhar geral LOOP"; exit;}
				return $this->viewid($use,($loop++));
			}

		$name = $da['concept']['n_name'];
		$idc = $da['concept']['id_cc'];

		$dt = $this->where('a_brapci',$idc)->findAll();
		if (count($dt) == 0)
			{
				$dt['a_uri'] = 'https://brapci.inf.br/v/'.$id;
				$dt['a_use'] = 0;
				$dt['a_prefTerm'] = $name;
				$dt['a_lattes'] = '';
				$dt['a_orcid'] = '';
				$dt['a_master'] = '';
				$dt['a_brapci'] = $id;
				$AuthorityNames->insert($dt);
			} else {
				$dt = $dt[0];
			}

		$tela = bs(bsc(h($dt['a_prefTerm'], 1),12));
		$link0 = $Brapci->link($dt);

		$link1 = '';

		$link1 = $Lattes->link($dt);

		if ($dt['a_brapci'] != 0)
			{			
				$link = base_url(PATH .MODULE . '/index/import_lattes/' . trim($dt['a_lattes']) . '/');
				$link2 = '<a href="' . $link . '" target="_new' . $dt['a_lattes'] . '">';
				$link2 .= '<img src="' . base_url('img/icones/import.png') . '?x=1" style="height: 50px">';
				$link2 .= '</a>';
			} else {
				$link2 = '';
			}
			//brapci_200x200.png
			
		//} else {
//			$tela .= anchor(base_url(PATH . MODULE. '/admin/authority/findid/' . $dt['a_brapci']));

		if ($dt['a_lattes'] == 0)
			{
				$tela .= $Lattes->link($dt);
				return $tela;
			}	
		$tela .= bs(bsc(trim($link0.' '.$link1.' '.$link2),12));

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
