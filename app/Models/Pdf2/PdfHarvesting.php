<?php

namespace App\Models\PDF;

use CodeIgniter\Model;

class Harvesting extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'harvestings';
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

	function next()
		{
			
		}

	function harvesting_pdf_curl($id)
	{
		$links = array();
		$links2 = array();
		$dd = $this->frbr_core->le($id);
		if ($dd['c_class'] == 'Journal') {
			return ("JOURNAL - " . $id);
		}
		$data = $this->frbr_core->le_data($id);

		for ($r = 0; $r < count($data); $r++) {
			$attr = trim($data[$r]['c_class']);
			$vlr = trim($data[$r]['n_name']);

			if ($attr === 'isPubishIn') {
				$jnl = $data[$r]['d_r2'];
			}

			if ($attr == 'prefLabel') {
				$file = trim($vlr);
				$file = troca($file, '/', '_');
				$file = troca($file, '.', '_');
				$file = troca($file, ':', '_');
			}

			if ($attr == 'hasUrl') {
				if (strpos(' ' . $vlr, 'http') > 0) {
					$vlr = substr($vlr, strpos($vlr, 'http'), strlen($vlr));
					array_push($links, $vlr);
				}
				if (substr($vlr, 0, 2) == '//') {
					$vlr = 'https:' . $vlr;
					echo $vlr;
					array_push($links, $vlr);
				}
			}
			if ($attr == 'hasRegisterId') {
				if (substr($vlr, 0, 4) == 'http') {
					array_push($links2, $vlr);
				}
			}
		}

		echo '<h1>' . $id . '</h1>';

		if ((count($links) == 0) and (count($links2) > 0)) {
			echo "OK";
			for ($r = 0; $r < count($links2); $r++) {
				$link = $links2[$r];
				$rsp = load_page($link);
				$txt = $rsp['content'];

				if (strpos($txt, 'citation_pdf_url') > 0) {
					/*****************************/
					$d = 'citation_pdf_url';
					$pos = strpos($txt, $d) + strlen($d);
					$txt = substr($txt, $pos, 1000);

					/*****************************/
					$d = 'content="';
					$pos = strpos($txt, $d) + strlen($d);
					$txt = substr($txt, $pos, 1000);
					$txt = substr($txt, 0, strpos($txt, '"'));
					if (strlen($txt) > 0) {
						array_push($links, $txt);
					}
				}
				if (strpos($txt, 'frame src="') > 0) {
					/*****************************/
					$d = 'frame src="';
					$pos = strpos($txt, $d) + strlen($d);
					$txt = substr($txt, $pos, 1000);

					/*****************************/
					$d = '" frameborder';
					$pos = strpos($txt, $d) + strlen($d);
					$txt = substr($txt, 0, $pos);
					$txt = substr($txt, 0, strpos($txt, '"'));
					if ((strlen($txt) > 0) and (substr($txt, 0, 4) == 'http')) {
						array_push($links, $txt);
					}
				}
			}
		}

		/************************ IDENTIFICAÇÃO DOS MÉTODOS *************/
		$method = 0;
		$link = '';
		for ($r = 0; $r < count($links); $r++) {
			$method == 0;
			$link = $links[$r];
			if ((strpos($link, '/view/')) or (strpos($link, '/viewFile/')) or (strpos($link, '/viewArticle/')) or (strpos($link, '/download/'))) {
				$method = 1;
			}

			/*************** ENANCIB e SCIELO */

			if ((strpos($link, 'scielo.php') > 0) or (strpos($link, 'enancib')) > 0) {
				$method = 1;
				/* Base do Scielo */
				$txt = file_get_contents($link);
				$txt = substr($txt, strpos($txt, 'citation_pdf_url'), 1024);
				$txt = substr($txt, strpos($txt, 'http'), strlen($txt));
				$link = substr($txt, 0, strpos($txt, '"'));
			}

			switch ($method) {
				case '1':
					$link = $this->method_1($link, $file, $id);
					echo '<br>' . ($r + 1) . '. ' . $link;
					try {
						$rsp = load_page($link);
						$txt = $rsp['content'];
						$type = $rsp['content_type'];
						/* save pdf */

						/**************** Correções de regras de download ************************/
						if (strpos($link, 'revista.arquivonacional.gov.br') > 0) {
							$type = 'application/pdf';
						}

						if (strpos($type, ';') > 0) {
							$type = substr($type, 0, strpos($type, ';'));
						}
						/******************** Tipos de arquivos recebidos ************************/
						switch ($type) {
							case 'application/pdf':
								$this->file_pdf($file, $txt, $id, $jnl);
								return ("pdf");
								//echo ' - ' . msg('save_pdf');
								break;
							case 'application/octet-stream':
								$this->file_pdf($file, $txt, $id, $jnl);
								return ("pdf");
								break;
							case 'application/save-as':
								$this->file_pdf($file, $txt, $id, $jnl);
								return ("pdf");
								break;
							case 'application/zip':
								$this->file_save($file, $txt, $id, 'ZIP', $jnl);
								//echo ' - ' . msg('save_pdf');
								break;
							case 'application/word':
								$this->file_save($file, $txt, $id, 'WRD', $jnl);
								break;
							case 'application/word':
								$this->file_save($file, $txt, $id, 'WRD', $jnl);
								break;
							case 'text/save':
								$this->file_save($file, $txt, $id, 'SAV', $jnl);
								break;
							case 'text/html':
								$this->file_save($file, $txt, $id, 'HTM', $jnl);
								break;
							case 'image/gif':
								$this->file_save($file, $txt, $id, 'GIF', $jnl);
								break;
							case 'image/jpeg':
								$this->file_save($file, $txt, $id, 'JPG', $jnl);
								break;
							case 'application/msword':
								$this->file_save($file, $txt, $id, 'DOC', $jnl);
								break;
							default:
								echo $link . '<br>';
								echo 'ID:' . $id . '<br>';
								echo '===><pre>[' . $type . ']</pre>';
								echo '<hr><pre>';
								print_r($rsp);
								echo '</pre>';
								exit;
						}
					} catch (Exception $e) {
						echo 'Caught exception: ', $e->getMessage(), "\n";
					}
					break;
				default:
					echo '<br>===Erro de método ';
					echo '<br>' . $links[$r];
					break;
			}
		}
		exit;
		return (msg("Harvesting"));
	}

	function harvesting_next($p = '', $rs = '1')
	{
		$s = '';
		if ($p == '') {
			$p = 0;
		}
		$prop1 = $this->frbr_core->find_class('hasUrl');
		$prop3 = $this->frbr_core->find_class('hasRegisterId');
		$prop2 = $this->frbr_core->find_class('hasFileStorage');
		$sql = "
			select count(*) as total from rdf_data AS R1
			left JOIN rdf_data AS R2 ON R1.d_r1 = R2.d_r1 and R2.d_p = $prop2
			where (R1.d_p = $prop1 OR R1.d_p = $prop3) and R2.d_p is null ";
		$s = 'P1:' . date("d/m/Y H:i:s") . '<br>';
		$rlt = $this->db->query($sql);
		$s .= 'P2:' . date("d/m/Y H:i:s") . '<br>';
		$rlt = $rlt->result_array();
		$total = $rlt[0]['total'];
		$date = date("Y-m-d");
		$sql = "
			select R1.d_r1 as d_r1 from rdf_data AS R1
			left JOIN __file_temp on d_r1 = fl_id
			left JOIN rdf_data AS R2 ON R1.d_r1 = R2.d_r1 and R2.d_p = $prop2
			where R1.d_p = $prop1 and R2.d_p is null and R1.d_r1 > $p
			and fl_id is null
			order by R1.d_r1 desc
			limit 1			
			";

		$rlt = $this->db->query($sql);
		$rlt = $rlt->result_array();
		$s .= 'P3:' . date("d/m/Y H:i:s") . '<br>';
		/***************************/
		if (count($rlt) == 0) {
			$sql = "TRUNCATE __file_temp";
			$rrr = $this->db->query($sql);
		}
		if (count($rlt) > 0) {
			$line = $rlt[0];
			$id = $line['d_r1'];

			/* */
			$sql = "insert into __file_temp (fl_id, fl_data) values ($id,'" . $date . "');";
			$rrr = $this->db->query($sql);

			$sx = msg('Article') . ' ' . $id;
			$sx .= ', ' . msg('left') . ' ' . $total . ' files';
			if ($rs == '1') {
				//echo '<meta http-equiv="refresh" content="1;' . base_url(PATH . 'tools/pdf_import/' . (round($id))) . '">';
				echo '<meta http-equiv="refresh" content="30;' . base_url(PATH . 'tools/pdf_import/') . '">';
			}
			$s .= 'CURL: ' . date("d/m/Y H:i:s") . '<br>';
			$sx .= ' ' . $this->harvesting_pdf_curl($id);
			$s .= 'CURL-STOP: ' . date("d/m/Y H:i:s") . '<br>';
			return ($sx . '<hr>' . $s);
		} else {
			return ("Fim da coleta");
		}
	}
}
