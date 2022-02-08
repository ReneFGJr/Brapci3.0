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
		$link1a = '';
		$link2a = '';
		$link1b = '';
		$link2b = '';
		$class = $dt['concept']['c_class'];
		switch ($class) {
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

		$RDF = new \App\Models\Rdf\RDF();

		$issue1 = $RDF->recover($dt, 'hasIssue');
		$issue2 = $RDF->recover($dt, 'hasIssueProceedingOf');
		$issue = array_merge($issue1, $issue2);

		$img1 = 'img/subheads/0001.png';
		$imgx = '';

		for ($r = 0; $r < count($issue); $r++) {
			$idp = strzero($issue[$r], 10);
			$file = 'img/subheads/' . $idp . '.png';
			if (file_exists($file)) {
				$img1 = $file;
			}
			$link1a = '<a href="' . PATH . 'res/v/' . $issue[0] . '" border=0>';
			$link1b = '</a>';
		}

		if (isset($idp)) {
			$di = $RDF->le(round($idp));
			$issue1 = $RDF->recover($di, 'hasIssue');
			$issue2 = $RDF->recover($di, 'hasIssueProceeding');
			$issue = array_merge($issue1, $issue2);

			for ($r = 0; $r < count($issue); $r++) {
				$idp = strzero($issue[$r], 10);
				$file = 'img/subheads/' . $idp . '.png';
				if (file_exists($file)) {
					$img0 = $file;
					$link2a = '<a href="' . PATH . 'res/v/' . $issue[$r] . '" border=0>';
					$link2b = '</a>';
				}
			}
		}

		$top = '';
		$top .= '<div class="col-12 text-center mb-5" style="position: relative;">';
		$top .= $link2a . '<img src="' . URL . $img0 . '" class="img-fluid" style="width: 100%;">' . $link2b;
		$top .= $link1a . '<img src="' . URL . $img1 . '" class="img-fluid" style="width: 100%;">' . $link1b;
		$top .= '<span class="btn-primary pt-2 pb-2 ps-4 pe-4 rounded-pill" 
							style="position: absolute; 
							left: 50%;
							transform: translateX(-50%); 
							bottom: -15px;">' . $dt['section'] . '</span>';
		$top .= '</div>';
		return $top;
	}
}
