<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class RDFExport extends Model
{
	protected $DBGroup              = 'rdf';
	protected $table                = PREFIX.'rdfexports';
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

	function exportNail($id)
		{
			$RDF = new \App\Models\RDF\RDF();
			$Covers = new \App\Models\Book\Covers();
			$dt = $RDF->le($id);
			$class = $dt['concept']['c_class'];
			switch($class)
				{
					case 'Manifestation':
						$isbn = substr($dt['concept']['n_name'],5,13);
						$cover_nail = $Covers->get_cover($isbn);
						return $cover_nail;
						break;
				}
		}	

	function export($id,$FORCE=false)
		{
			$tela = '';
			$RDF = new \App\Models\RDF\RDF();
			$dir = $RDF->directory($id);
			$file = $dir.'name.nm';
			if (file_exists($file))
				{
					return '';
				}

			$dt = $RDF->le($id,0);
			$prefix = $dt['concept']['prefix_ref'];
			$class = $prefix.':'.$dt['concept']['c_class'];
			$name = ':::: ?'.$class.'? ::::';

			switch($class)
				{
					case 'dc:Issue':
						$vol = $RDF->recovery($dt['data'],'hasPublicationVolume');
						$nr = $RDF->recovery($dt['data'],'hasPublicationNumber');
						$year = $RDF->recovery($dt['data'],'dateOfPublication');
						$year2 = $RDF->recovery($dt['data'],'hasDateTime');
						$place = $RDF->recovery($dt['data'],'hasPlace');
						//$edition = $RDF->c($dt['concept']['id_cc']);
						$edition = '';

						if (isset($vol[0][1])) { $vol = $RDF->c($vol[0][1]); } else { $vol = ''; }
						if (isset($nr[0][1])) { $nr = $RDF->c($nr[0][1]); } else { $nr = ''; }
						if (isset($year[0][1])) { $year = $RDF->c($year[0][1]); } else { $year = ''; }
						if (isset($year2[0][1])) { $year = $RDF->c($year2[0][1]); } else { $year = ''; }
						if (isset($place[0][1])) { $place = $RDF->c($place[0][1]); } else { $place = ''; }

						$issue = $edition;
						if (strlen($nr) > 0) { $issue .= ', '.$nr; }
						if (strlen($place) > 0) { $issue .= ', '.$place; }
						if (strlen($vol) > 0) { $issue .= ', '.$vol; }
						if (strlen($year) > 0) { $issue .= ', '.$year; }
						$issue .= '.';

						$name = $issue;
						$this->saveRDF($id,$name,'name.nm');
						break;
					case 'dc:Journal':
						$name = $dt['concept']['n_name'];
						$name = '<a href="'.(URL.'v/'.$id).'" class="journal">'.$name.'</a>';
						$this->saveRDF($id,$name,'name.nm');
						break;

					case 'IssueProceeding':
						$name = $dt['concept']['n_name'];
						$name = '<a href="'.(URL.'v/'.$id).'" class="journal">'.$name.'</a>';
						$this->saveRDF($id,$name,'name.nm');
						break;						

					case 'brapci:Proceeding':
						$publisher = '';
						$tela .= 'Proceeding';	
						/************************************************** Authors */
						$authors = $RDF->recovery($dt['data'],'hasAuthor');
						$auths = '';
						for ($r=0;$r < count($authors);$r++)
							{
								$idr = $authors[$r][1];
								if (strlen($auths) > 0) { $auths .= '; '; }
								$auths .= $RDF->c($idr);
							}

						/***************************************************** Title */
						$title = $RDF->recovery($dt['data'],'hasTitle');
						if (isset($title[0][2]))
							{
								$title = nbr_title($title[0][2]);
							} else {
								$title = '## FALHA NO TÍTULO ##';
							}


						/***************************************************** Title */
						$issue = $RDF->recovery($dt['data'],'hasIssueProceedingOf');
						if (!isset($issue[0])) 
							{ $issue = 'NoN'; } 
							else 
							{ 
								$id2 = $issue[0][0];
								if ($issue[0][0] == $id)
									{
										$id2 = $issue[0][1];
									}
								$issue = $RDF->c($id2); 
							}
						
						/************************************************** Section */
						//$section = $RDF->recovery($dt['data'],'hasSectionOf');						

						/****************************************************** SAVE */
						$publisher = 'Anais... ';
						$name = strip_tags($auths.'. '.$title.'. $b$'.$publisher. '$/b$'.$issue);
						$name = '<a href="'.(URL.'v/'.$id).'" class="article">'.$name.'</a>';
						$name = troca($name,'$b$','<b>');
						$name = troca($name,'$/b$','</b>');
						$name .= '.';
						$this->saveRDF($id,$name,'name.nm');
						break;

					/**************************************************************************** ARTICLE */
					case 'brapci:Article':
						$publisher = '';
						$tela .= 'Proceeding';	
						/************************************************** Authors */
						$authors = $RDF->recovery($dt['data'],'hasAuthor');
						$auths = '';
						for ($r=0;$r < count($authors);$r++)
							{
								$idr = $authors[$r][1];
								if (strlen($auths) > 0) { $auths .= '; '; }
								$auths .= $RDF->c($idr);
							}
						/************************************************** Authors */
						//echo '<pre>';
						//print_r($dt);
						//$publisher = $RDF->recovery($dt['data'],'isIssue');
						//$publisher = $RDF->c($publisher[0][1]);

						/***************************************************** Title */
						$title = $RDF->recovery($dt['data'],'hasTitle');
						if (isset($title[0][2]))
							{
								$title = nbr_title($title[0][2]);
							} else {
								$title = '## FALHA NO TÍTULO ##';
							}


						/***************************************************** Issue */
						$issue = $RDF->recovery($dt['data'],'hasIssueOf');
						if (!isset($issue[0])) 
							{ $issue = 'NoN'; } 
							else 
							{ 
								$id2 = $issue[0][0];
								if ($issue[0][0] == $id)
									{
										$id2 = $issue[0][1];
									}
								$issue = $RDF->c($id2); 
							}
						
						/************************************************** Section */
						//$section = $RDF->recovery($dt['data'],'hasSectionOf');						

						/****************************************************** SAVE */
						$publisher = '';
						$name = strip_tags($auths.'. '.$title.'. $b$'.$publisher. '$/b$'.$issue);
						$name = '<a href="'.(URL.'v/'.$id).'" class="article">'.$name.'</a>';
						$name = troca($name,'$b$','<b>');
						$name = troca($name,'$/b$','</b>');
						$name .= '.';
						$this->saveRDF($id,$name,'name.nm');
					break;

					case 'foaf:Person':
						$tela .= 'ARTICLE';	
						$name = nbr_author($dt['concept']['n_name'],1);
						$name = '<a href="'.(URL.'v/'.$id).'" class="author">'.$name.'</a>';
						$this->saveRDF($id,$name,'name.nm');
						break;

					default:
						if (!isset($dt['concept']['id_cc'])) { return 'NoN'; }
						
						$name = trim($dt['concept']['n_name']);
						
						if (strlen($name) == 0)
							{
								$name = $RDF->recovery($dt['data'],'prefLabel');
								$name = trim($name[0][2]);
							}

					
						if (strlen($name) > 0)
							{
								$this->saveRDF($id,$name,'name.nm');
								break;
							}
						break;
				}
			$tela .= '<a href="'.(PATH.MODULE.'v/'.$id).'">'.$name.'</a>';
			return $tela;
		}

		function saveRDF($id,$value,$file)
			{
				$RDF = new \App\Models\RDF\RDF();
				$dir = $RDF->directory($id);
				$file = $dir.$file;
				file_put_contents($file,$value);
				return true;
			}
}
