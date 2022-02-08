<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class SubHeaders extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'subheaders';
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

	function headers($dt)
		{
			$RDF = new \App\Models\Rdf\RDF();

			$issue1 = $RDF->recover($dt,'hasIssue');
			$issue2 = $RDF->recover($dt,'hasIssueProceedingOf');
			$issue = array_merge($issue1,$issue2);

			echo '<pre>';
			print_r($issue);
			echo '</pre>';

			$class = $dt['concept']['c_class'];
			$img1 = 'img/subheads/0001.png';
			$imgx = '';

			for ($r=0;$r < count($issue);$r++)
				{
					$idp = strzero($dt['concept']['id_cc'],10);
					$file = 'img/subheads/'.$idp.'.png';
					if (file_exists($file))
						{
							$img1 = $file;
						}		
				}

			if (($imgx == '') and (round($idp) > 0))	
				{
					$di = $RDF->le(round($idp));
					echo '<pre>';
					print_r($di);
					echo '</pre>';
				}



				if (perfil("#ADM",$this))
				{
					if ($imgx = '')
						{
							print_r($issue);
						}
					
				}

			switch($class)
				{
					case 'Proceeding':
						$img0 = 'img/subheads/collection_proceedings.png';
						break;
					case 'Article':
						$img0 = 'img/subheads/collection_articles.png';
						break;
					default: 
						$img0 = 'img/subheads/0001.png';
						break;		
				}

			$top = '';
			$top .= '<div class="col-12 text-center mb-5" style="position: relative;">';
			$top .= '<img src="'.URL.$img0.'" class="img-fluid" style="width: 100%;">';
			$top .= '<img src="'.URL.$img1.'" class="img-fluid" style="width: 100%;"> ';
			$top .= '<span class="btn-primary pt-2 pb-2 ps-4 pe-4 rounded-pill" 
							style="position: absolute; 
							left: 50%;
							transform: translateX(-50%); 
							bottom: -15px;">'.$dt['section'].'</span>';
			$top .= '</div>';	
			$top .= bsc($idp,12);
		return $top;			
		}
}
