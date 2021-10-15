<?php

namespace App\Models\Pdf;

use CodeIgniter\Model;

class Pdf extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'pdfs';
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

	function index($d1,$d2)
		{
			switch($d1)
				{
					case 'get':
						$d2 = get("issue");
						$sx = $this->harvesting_proceedings($d2);
					break;

					case 'inport':
						$sx = $this->inport($d2);
						break;

					case 'harvesting':
						$sx = $this->harvesting($d1,$d2);
						break;

					default:
						$sx = 'PDF=>'.$d1;
						break;
				}
			return $sx;
		}

	function download($id)
		{
			$sx = '';
			$pdf = $this->pdf_file($id);

			if (file_exists($pdf))
				{
					$img = '<img src="'.base_url(URL.'img/icones/pdf.png').'" class="img-fluid">';
					$sx .= $img;
				} else {
					$img = '<img src="'.base_url(URL.'img/icones/pdf_off.png').'" class="img-fluid">';
					$sx .= '<a href="#">'.$img.'</a>';
					$sx .= $this->btn_inport($id);
				}						
			return $sx;
		}
	function btn_inport($id)
		{
			$sx = '';
			$url = base_url(PATH.'main/pdf/inport/'.$id);
			$sx .= '<a href="'.$url.'">o</a>';
			return $sx;
		}

	function pdf_file($id)
		{
			$IO = new \App\Models\Io\Files();
			$dir = $IO->directory($id);
			$file = $dir.strzero($id,8).'.pdf';
			return $file;
		}

	function inport($id)
		{			
			$RDF = new \App\Models\RDF\RDF();			
			$Pdf_ojs = new \App\Models\Pdf\Pdf_ojs();
			$dt = $RDF->le($id,0,'brapci');
			$urls = $Pdf_ojs->urls($dt);



			/* Identifica método do PDF */			
			for ($r=0;$r < count($urls);$r++)
				{
					$url = $Pdf_ojs->method($urls[$r]);
					for ($z=0;$z < count($url);$z++)
						{
							if (strlen($url[$z] != ''))
								{
									$this->save_file($id,$url[$z]);
								}
						}
				}
			exit;			

			return $sx;
		}

		function save_file($id,$url)
			{
				$Files = new \App\Models\IO\Files();

				/*************************************************** ARQUIVO */
				$file = $this->pdf_file($id);				

				$rsp = $Files->load($url,'curl');
				$txt = $rsp['content'];
				$type = $rsp['content_type'];

				switch($type)
					{
						case 'application/pdf':
							file_put_contents($file,$txt);
							return true;
							break;
						default:
							echo "OPS FILE TYPE = ".$type;
							exit;
					}
			}

}
