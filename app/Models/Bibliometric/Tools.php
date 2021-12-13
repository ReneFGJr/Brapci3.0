<?php

namespace App\Models\Bibliometric;

use CodeIgniter\Model;

class Tools extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'tools';
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

	function index($d1,$d2,$d3)
		{
			$sx = $this->form($d1,$d2,$d3);
			return $sx;
		}
	function form($d1,$d2,$d3)
		{
			$utf8 = false;
			$tps = array('RIS','CSV1','CSV2');

			$sf = h(lang('ai.from_tools'),5);
			$sf .= '<select id="from" name="from" style="width: 100%; font-size:40px;">';
			for ($r=0;$r < count($tps);$r++)
				{
					$chk = '';
					$tp = $tps[$r];
					$sf .= '<option value="'.$tp.'" '.$chk.'>'.lang('ai.'.$tp).'</option>';
				}
			$sf .= '</select>';

			$st = h(lang('ai.to_tools'),5);
			$st .= '<select id="to" name="to" style="width: 100%; font-size:40px;">';
			for ($r=0;$r < count($tps);$r++)
				{
					$chk = '';
					$tp = $tps[$r];
					$st .= '<option value="'.$tp.'" '.$chk.'>'.lang('ai.'.$tp).'</option>';
				}
			$st .= '</select>';			

			$file ='<hr>';
			$file .= '<input type="file" name="file" id="fileToUpload" style="font-size: 30px;">';

			$ss = h(lang('ai.action'),5);
			$ss .= '<input type="submit" value="'.lang('submit').'" class="btn btn-outline-primary" style="font-size: 30px;">';
			
			$sx = bsc(h(lang('ai.bibliographic_convert_tools'),1).'<hr>',12);
			$sx .= bsc($sf,4);
			$sx .= bsc($st,4);
			$sx .= bsc($ss,4);
			$sx .= bsc($file,12);

			$sx = bs($sx);
			$sx = '<form method="post" enctype="multipart/form-data">'.$sx.'</form>';

			$file = '';	
			$txt = '';			
			if (isset($_FILES['file']['tmp_name']))
				{
					$file = $_FILES['file']['tmp_name'];
					if (file_exists($file))
					{
						$txt = file_get_contents($file);
					} else {
						if ($file=='')
							{
								$sx .= bs(bsc(bsmessage(lang('ai.converted_empty'),3),12,'mt-5'));
							} else {
								$sx .= bs(bsc(bsmessage(lang('ai.converted_erro'),3),12,'mt-5'));
							}
						
					}
				}
			$from = get("from");
			$to   = get("to");
			if (($from != '') and (isset($txt)) and (strlen($txt) > 0))
				{
					$Convert = new \App\Models\Bibliometric\Convert();
					switch($from)
						{
							case 'RIS':
								$data_from = $Convert->RIS_to_standard($txt);
								break;
						}
					switch($to)
						{
							case 'CSV1':
								$sep = ';';
								$converted = $Convert->standard_CSV($data_from,$sep);
								$ext = 'csv';
								break;
							case 'CSV2':
								$sep = ';';
								$converted = $Convert->standard_CSV($data_from,$sep);
								$ext = 'csv';
								break;								
						}	
					/******************************************* Iniciando conversão */
					if (strlen($converted) > 0)
						{
							if (!$utf8) { $converted = utf8_decode($converted); }
							dircheck('.tmp');
							dircheck('.tmp/tools/');
							$file = '.tmp/tools/brapci_'.$from.'_'.$to.'_'.date("YmdHi").'.'.$ext;
							file_put_contents($file,$converted);
							$sxf = URL.$file;
							$sx .= bs(bsc(bsmessage(lang('ai.converted').' '.anchor($sxf),1),12,'mt-5'));
						}
					
				}
			return $sx;
		}
}
