<?php

namespace App\Models\AI\Research\SystematicReview;

use CodeIgniter\Model;

class Scopus extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci_ai.SystematicReviews_Corpus';
	protected $primaryKey           = 'id_c';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_c', 'c_study', 'id',
		'title', 'author', 'journal',
		'year', 'volume', 'number',
		'pages', 'doi', 'issn',
		'month', 'note', 'eprint',
		'keyword','c_fulltext','c_keywords'
	];

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

	function import($id='',$id2='',$dt=array())
		{
			$sx = '';
			$file = 'd:/lixo/scopus.bib';
			if (file_exists($file))
				{
					$Bibtex = new \App\Models\AI\Research\SystematicReview\Bibtex();
					$SystematicReviewCorpus = new \App\Models\AI\Research\SystematicReviewCorpus();

					$sx .= '<h3>'.lang('st_import_scopus').'</h3>';
					$txt = file_get_contents($file);					
					$dt = $Bibtex->import($txt);
					/**************************************************/
					for ($r=0;$r < count($dt);$r++)
						{
							$data = $dt[$r];
							$SystematicReviewCorpus->registro($id,$id2,$data);
						}
				} else {
					$sx = bsmessage('File not found - '.$file,3);
				}
			return $sx;
		}
}
