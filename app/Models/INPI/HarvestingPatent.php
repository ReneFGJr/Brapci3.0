<?php

namespace App\Models\INPI;

use CodeIgniter\Model;

class HarvestingPatent extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'harvestingpatents';
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

	protected $path          = '.tmp/inpi/publications/';

	function harvesting()
	{
		$sx = '';
		$next = $this->last();
		if ($next > 0) {
			$sx .= 'Next: '.$next.'<br>';
			$sx .= $this->upload($next);
			$sx .= metarefresh(PATH.MODULE.'inpi/harvesting',1);
		} else {
			$sx .= bsmessage('Nothing to colete',2);
		}
		$sx .= $this->xml();
		return $sx;
	}

	function xml()
	{
		//Patente_2640_10082021
		$sx = '';
		$fl = scandir($this->path . 'txt/');

		for ($r = 0; $r < count($fl); $r++) {
			$file = $fl[$r];
			$file_substr = substr($file, 0, 8);
			if ($file_substr == 'Patente_') {
				$file_xml = $this->path . 'txt/' . $file;
				$sx .= '<br>'.$this->xml_register($file_xml);
			}
		}
		return $sx;
	}

	function xml_register($file_xml)
	{
		$InpiRpi = new \App\Models\INPI\InpiRpi();
		$xml = simplexml_load_file($file_xml);
		$att = (array)$xml->attributes();

		if (isset($att['@attributes']['numero']))
		{
			$data = $att['@attributes']['dataPublicacao'];
			$data2 = substr($data,6,4) . '-' . substr($data,3,2) . '-' . substr($data,0,2);
			$dta['pb_number'] = $att['@attributes']['numero'];
			$dta['pb_date'] = $data2;
			$dta['pb_type'] = 'PATENT';
			$dta['pb_ano'] = substr($data,strlen($data)-4,4);
			$dta['pb_file'] = $file_xml;
			$dta['pb_status'] = 1;
			$sx = $file_xml.' -> '. $InpiRpi->atualiza($dta);
		}
		return $sx;
	}

	function process($sta=1)
		{
			$sx = h('Process '.$sta,4);
			$InpiRpi = new \App\Models\INPI\InpiRpi();
			$dt = $InpiRpi->where('pb_status',$sta)->findAll();
			if (count($dt) > 0)
				{
					$dt = $dt[0];
					$file = $dt['pb_file'];
					$status = $dt['pb_status'];
					switch($status)
						{
							case 1:
								$sx .= $this->xml_process_01($file);
								$sx .= bsmessage(lang('brapci.finish - '.$file),1);
								$InpiRpi->update_file($file,2);
								break;

							default:
								$sx .= bsmessage(lang('brapci.status_not_locete - '.$status),3);
						}
				}
			return $sx;
		}

	function xml_process_01($file_xml)
	{
		$Authority = new \App\Models\INPI\InpiAuthority();
		$sx = '';
		$xml = simplexml_load_file($file_xml);
		$despacho = $xml->despacho;

		for ($r = 0; $r < count($despacho); $r++) {
			$ln = (array)$despacho[$r];
			if (isset($ln['processo-patente'])) {
				$pp = (array)$ln['processo-patente'];
				if (isset($pp['titular-lista'])) {
					$tt = (array)$pp['titular-lista'];
					$tti = (array)$tt['titular'];

					$titular = array();
					if (isset($tti[0])) 
					{
						$titular = (array)$tti;
					} else {
						$titular[0] = $tti;
					}					

					for ($z = 0; $z < count($titular); $z++) {
						$ti = (array)$titular[$z];

						/******************* Endereço */
						if (isset($ti['endereco'])) {
							$end = (array)$ti['endereco'];
							/* Pais */
							if (isset($end['pais'])) {
								$pais = (array)$end['pais'];
								$ti['a_country'] = $pais['sigla'];
							}
							/* Estado */
							if (isset($end['UF'])) {
								$ti['a_UF'] = $end['UF'];
							}
						}
						/*****************************************************************/
						if (isset($ti['nome-completo'])) {
							$dta = $Authority->get_id_by_name($ti['nome-completo'], $ti);
						} else {
							echo "OOOOOOOOOOOOOOOOO";
							print_r($ti);
							echo "XXXXXXXXXXXXXXXXX";
						}
					}
				}
			}
		}
		//$sx .= '<h6>' . $xml->attributes()->autores . '</h6>';
	}

	function upload($id)
	{
		$sx = '';
		if ($id <= 0) {
			return '';
		}
		$file_dest = $this->path . 'P' . $id . '.zip';
		$url = "http://revistas.inpi.gov.br/txt/P$id.zip";
		$txt = file_get_contents($url);
		file_put_contents($file_dest, $txt);

		$zip = new \ZipArchive;
		$res = $zip->open($file_dest);
		if ($res === TRUE) {
			$zip->extractTo($this->path . 'txt/');
			$zip->close();
			$sx = bsmessage('Unzip ' . $file_dest, 1);
			//unlink($file);
		} else {
			unlink($file_dest);
			return bsmessage("ERRO " . $file_dest, 3);
		}
		return $sx;
	}

	function last()
	{
		$dir = '.tmp';
		dircheck($dir);
		$dir = '.tmp/inpi';
		dircheck($dir);
		$dir = '.tmp/inpi/patent';
		dircheck($dir);
		$dir = '.tmp/inpi/publications';
		dircheck($dir);
		$dir = '.tmp/inpi/publications/processed';
		dircheck($dir);
		$dir = '.tmp/inpi/publications/txt';
		dircheck($dir);

		$dir = '.tmp/inpi/publications';
		for ($r = 2550; $r < 2670; $r++) {
			$file = $dir . '/P' . $r . '.zip';
			if (!file_exists($file)) {
				//echo " - Not found";
				return $r;
			}
		}
		return -1;
	}
}
