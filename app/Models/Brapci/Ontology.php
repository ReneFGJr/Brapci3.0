<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class Ontology extends Model
{
	protected $DBGroup              = 'brapci';
	protected $table                = PREFIX.'rdf_class';
	protected $primaryKey           = 'id_c';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = ['c_prefix'];

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

	function index($d1, $d2, $d3, $d4)
	{
		$RDF = new \App\Models\Rdf\RDF();
		$sx = '';
		if ($d2 != '') {	
			$this->join(PREFIX.'rdf_prefix', 'c_prefix = id_prefix', STR_PAD_LEFT);
			$dt = $this->find($d2);
			$sx .= view('setspec/class',$dt);
		} else {
			$sx .= view('setspec/header_title');
			$sx .= bsc($this->list('C'), 6);
			$sx .= bsc($this->list('P'), 6);
			$this->publish();
		}
		return $sx;
	}

	function check()
	{
		$this->set('c_prefix', 2)->where('c_prefix', 0)->update();
	}

	function publish()
	{
		//dircheck('setspec');
		$dir = 'setsepc';

		$url_rdf = URL . 'setspec/brapci-core-' . date("Ymd") . '.rdf';
		$head = view('setspec/header');
	}

	function list($type = 'C')
	{
		$RDF = new \App\Models\Rdf\RDF();
		$this->join(PREFIX.'rdf_prefix', 'c_prefix = id_prefix', STR_PAD_LEFT);
		$this->where('c_type', $type)->orderBy('prefix_ref, c_class');
		$dt = $this->findAll();
		//echo $this->getLastQuery();
		$tela1 = '';
		$tela2 = '';

		if ($type == 'C') {
			$tela1 = '<h3>Classes</h3>';
			$pre = 'class';
		} else {
			$tela1 = '<h3>Properties</h3>';
			$pre = 'property';
		}

		$tela1 .= '<ul>';
		for ($r = 0; $r < count($dt); $r++) {
			$tela1 .= '<li>';
			$tela1 .= '<a href="' . PATH . MODULE . ('ontology/' . $pre . '/' . $dt[$r]['id_c']) . '#' . $dt[$r]['c_class'] . '">' .
				$RDF->show_class($dt[$r]) .

				'</a>';
			$tela1 .= '</li>';
		}
		$tela1 .= '</ul>';

		$tela = $tela1;

		return $tela;
	}
}
