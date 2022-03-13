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

	function SubjectAuthors($id)
		{
			$RDF = new \App\Models\Rdf\RDF();
			$dt = $RDF->le($id);
			$subject = $RDF->recover($dt,'hasAuthor');
			$data = array();
			$links = array();

			for ($r=0;$r < count($subject);$r++)
				{
					$idx = $subject[$r];

					$dir = $RDF->directory($idx);
					$file = $dir.'keywords.json';
					if (file_exists($file))
						{
							$txt = file_get_contents($file);
							$txt = (array)json_decode($txt);	

							for ($i=0;$i < count($txt);$i++)
								{
									$name = trim((string)$txt[$i]);
									$id = substr($name,strpos($name,'/v/')+3,strlen($name));
									$id = substr($id,0,strpos($id,'"'));
									$name = strip_tags($name);
									$name = troca($name,"'",'');
									$name = troca($name,'"','');
									if (strlen($name) > 4)
									{
										$name = $name;
										if ($name != '')
										{
										if (isset($data[$name]))
											{
												$data[$name]++;
											} else {
												$data[$name] = 1;
												$links[$name] = $id;
											}
										}
									}
								}
						}
				}
				
			$sx = lang('brapci.total').' '.count($subject).' '.
					lang('brapci.subject').' '.
					lang('brapci.with').' '. 
					count($data).' '.lang('brapci.works');				

			$sx .= '<script>';
			foreach($links as $name=>$id)
				{
					//$sx .= '$("#Word_'.$id.'_1'.$id.'").click(function() { alert("'.$id.'"); });' .cr();
				}
			$sx .= '</script>';
			
			$sx .= cloudetags($data);
			$sx = bs(bsc($sx,12));
			return $sx;			
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
