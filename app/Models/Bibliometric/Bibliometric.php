<?php

namespace App\Models\Bibliometric;

use CodeIgniter\Model;

class Bibliometric extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'bibliometrics';
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

	function PersonAuthors($id)
		{

		}

	function IssueAuthors($id)
		{
			$RDF = new \App\Models\Rdf\RDF();
			$dt = $RDF->le($id);
			$art1 = $RDF->recover($dt,'hasIssueProceedingOf');
			$art2 = $RDF->recover($dt,'hasIssueOf');
			$auth = array_merge($art1,$art2);
			$auths = array();								

			for ($r=0;$r < count($auth);$r++)
				{
					$idx = $auth[$r];

					$dir = $RDF->directory($idx);
					$file = $dir.'authors.json';
					if (file_exists($file))
						{
							$txt = file_get_contents($file);
							$txt = (array)json_decode($txt);							
							for ($i=0;$i < count($txt);$i++)
								{
									$name = (string)$txt[$i]->name;
									if (isset($auths[$name]))
										{
											$auths[$name]++;
										} else {
											$auths[$name] = 1;
										}
								}
						}
				}
			$sx = lang('brapci.total').' '.count($auths).' '.lang('brapci.authors').' with '.count($auth).' works';
			$sx = bs(bsc($sx,12));
			return $sx;
		}
}
