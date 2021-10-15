<?php

namespace App\Models\Pdf;

use CodeIgniter\Model;

class Pdf_ojs extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'pdfojs';
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

	function urls($dd)
		{
			$links = array();
			
			if ($dd['concept']['c_class'] == 'Journal')
			{
				return("JOURNAL - ".$id);
			}
			$data = $dd['data'];
			
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
					if (strpos(' '.$vlr,'http') > 0) {
						$vlr = substr($vlr,strpos($vlr,'http'),strlen($vlr));
						array_push($links, $vlr);
					}
					if (substr($vlr,0,2) == '//') {
						$vlr = 'https:'.$vlr;
						array_push($links, $vlr);
					}					
				}
				if ($attr == 'hasRegisterId'){
					if (substr($vlr, 0, 4) == 'http') {
						array_push($links, $vlr);
					}			    
				}				
			}		
			return $links;
		}

	function method($url)
		{
			$Files = new \App\Models\IO\Files();
			$rsp = $Files->load($url);
			$txt = $rsp['content'];

			$links = array();
			
			if (strpos($txt,'citation_pdf_url') > 0)
			{
				/*****************************/
				$d = 'citation_pdf_url';
				$pos = strpos($txt,$d)+strlen($d);
				$txt = substr($txt,$pos,1000);
				
				/*****************************/
				$d = 'content="';
				$pos = strpos($txt,$d)+strlen($d);
				$txt = substr($txt,$pos,1000);
				$txt = substr($txt,0,strpos($txt,'"'));                                
				if (strlen($txt) > 0)
				{					
					array_push($links, $txt);
				}
			}
			if (strpos($txt,'frame src="') > 0)
			{
				/*****************************/
				$d = 'frame src="';
				$pos = strpos($txt,$d)+strlen($d);
				$txt = substr($txt,$pos,1000);
				
				/*****************************/
				$d = '" frameborder';
				$pos = strpos($txt,$d)+strlen($d);
				$txt = substr($txt,0,$pos);
				$txt = substr($txt,0,strpos($txt,'"')); 
				if ((strlen($txt) > 0) and (substr($txt,0,4) == 'http'))
				{
					array_push($links, $txt);
				}
			}

			return($links);
			
		}

}
