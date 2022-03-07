<?php

namespace App\Models\XXX;

use CodeIgniter\Model;

class Index extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'indices';
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
			$sx = '';
			switch($d1)
				{
					case 'convert_proceeding':
						$sx .= h($d1.'-'.$d2);
						$sx .= $this->convert_issue($d2);
						break;
					default:
						$sx .= '<h2>'.msg('index_'.$d1).'</h2>';
				}
			$sx = bs(bsc($sx));
			return $sx;
		}

	function convert_issue($issue)
		{
			$RDF = new \App\Models\Rdf\RDF();
			$RDFConcept = new \App\Models\Rdf\RDFConcept();
			$RDFData = new \App\Models\Rdf\RDFData();
 
			$sx = '';
			$sx .= h('locate-'.$issue,5);

			$dt = $RDF->le($issue);

			if (($dt['concept']['c_class'] == 'Issue') or ($dt['concept']['c_class'] == 'IssueProceeding'))
				{
					//pre($dt);
					$art = $RDF->recover($dt,'hasIssueOf');
					$class = $RDF->getClass('brapci:Proceeding',0);
					$class_o = $RDF->getClass('brapci:Article',0);

					$issueID = $RDF->getClass('brapci:IssueProceeding',0);
					$issue_o = $RDF->getClass('Issue',0);	
					$dd['cc_class'] = $issueID;
					$RDFConcept->set($dd)->where('id_cc',$issue)->where('cc_class',$issue_o)->update();		

					$hasIssueOf = $RDF->getClass('hasIssueOf',0);
					//$isPubishIn = $RDF->getClass('isPublishIn',0);
					$hasIssueProceedingOf = $RDF->getClass('hasIssueProceedingOf',0);


					/*********************************************/
					$conv = array();
					echo h($class_o.'==>'.$class);
					
					for ($r=0;$r < count($art);$r++)
						{
							$dd['cc_class'] = $class;
							$RDFConcept->set($dd)->where('id_cc',$art[$r])->where('cc_class',$class_o)->update();
							echo $RDFConcept->getlastquery();

							$da = $RDFData
										->where('d_r2',$issue)
										->where('d_p',$hasIssueOf)->FindAll();
							echo h('Total =>'.count($da),5);
							for ($rq=0;$rq < count($da);$rq++)
								{
									$line = $da[$rq];
									$dv['d_r1'] = $line['d_r2'];
									$dv['d_p'] = $hasIssueProceedingOf;
									$dv['d_r2'] = $line['d_r1'];
									$RDFData->set($dv)->where('id_d',$line['id_d'])->update();
								}							
						}
				} else {
					$sx .= h($dt['concept']['c_class'],1);
				}
			
			return $sx;
		}
}
