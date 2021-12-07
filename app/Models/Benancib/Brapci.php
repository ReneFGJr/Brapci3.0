<?php

namespace App\Models\Benancib;

use CodeIgniter\Model;

class Brapci extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapcis';
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

	function export($id)
	{
		$RDF = new \App\Models\Rdf\RDF();

		$dir = '.tmp/benancib/harvesting/';
		$file1 = $dir . 'benancib_' . $id . '.xml';
		$file2 = $dir . 'benancib_' . $id . '.pdf';

		$xml = simplexml_load_file($file1);

		$class = "Brapci:Proceeding";
		$reg = (array)$xml->record;
		$year = $reg['dc.ano.evento'];
		$name = "enancib.org.".$year.".".$id;

		$idc = $RDF->conecpt($class,$name);
		/* Properties */
		$lang = 'pt-BR';		
		echo '<h1>'.$name.'</h1>';
		echo '<h2>'.$idc.'</h2>';
		echo '<pre>';
		print_r($reg);
		echo '</pre>';
		$title = (string)$reg['dc.title'];
		$idt = $RDF->literal($title,$lang,$idc,'brapci:hasTitle');
		$link = '<a href="'.URL.'/res/v/'.$idc.'" target="new'.$idc.'">'.$title.'</a>';
		$sx = '';
		$sx .= $link;
		echo $sx;
		return $sx;
	}
}
