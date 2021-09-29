<?php

namespace App\Models\Authority;

use CodeIgniter\Model;

class Index extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = '*';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'','authority.url'
	];

	protected $typeFields        = [
		'hidden',
		'url*'
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

	function index($d1,$d2,$d3,$d4)
		{
			$this->setDatabase('brapci_authority');
			
			$tela = '';
			switch($d1)
				{
					case 'viewid':
						$tela .= $this->viewid($d2);
						break;
					case 'list':
						$tela .= $this->tableview();
						break;
					case 'import':
						$this->id = 0;
						$this->path = base_url(PATH.'/index/import');
						$tela .= form($this);
						$url = get('authority_url');
						$tela .= '';
						if ($url != '')
							{
								$tela = h($url,2);
								$tela .= $this->inport_brapci($url);
							}
						break;

					default:
						$tela .= '==>'.$d1;
						break;
				}
			$tela = bs($tela);
			return $tela;
		}

	function viewid($id)
		{
			$AuthorityNames = new \App\Models\Authority\AuthorityNames();
			$dt = $AuthorityNames->find($id);
			$tela = h($dt['a_prefTerm'],1);
			$tela .= anchor($dt['a_uri']);
			return $tela;
		}
	function tableview()
		{
			$AuthorityNames = new \App\Models\Authority\AuthorityNames();
			$AuthorityNames->path = base_url(PATH.'/index/');
			$tela = tableView($AuthorityNames);

			return $tela;
		}

	/******************************************************************************************/
	function inport_brapci($url)
		{			

			$AuthorityWords = new \App\Models\Authority\AuthorityWords();
			$RDF = new \App\Models\RDF();
			$RDF->DBGroup = 'auth';
			
			$tela = '';
			$URI = '';
			$file = md5($url).'rdf';
			dircheck('.tmp');
			dircheck('.tmp/brapci/');
			$file = '.tmp/brapci/'.$file;

			if (!file_exists($file))
				{
					$rdf = file_get_contents($url.'/rdf');
					file_put_contents($file,$rdf);
				} else {
					$rdf = file_get_contents($file);
				}
			
			/**********************************************************************************/
			//$keywords = preg_split(chr(13),$rdf);
			$ln = explode(chr(13),$rdf);
			$taff = '<ul>';
			for ($r=0;$r < count($ln);$r++)
				{
					$l = explode('	',$ln[$r]);
					$ln0 = trim($l[0]);

					if (substr($ln0,0,5) == '<http')
						{
							$uri = $ln0;
							$URI = str_replace(array('<','>'),'',$uri);							
						}

					if (isset($l[2]))
						{
							//$tela .= $l[1].'=>'.$l[2].'<hr>';
						}

					if (isset($l[1]) and ($l[1] == 'dc:affiliatedWith'))
						{
							$aff = substr($l[2],1,strlen($l[2]));
							$affn = substr($aff,strpos($aff,'#')+1,strlen($aff));
							$aff = substr($aff,0,strpos($aff,'#'));
							$aff = nbr_author($aff,7);
							$AuthorityWords->process($affn);
							$taff .= '<li>'.$affn.'</li>';
						}

					

					if (isset($l[1]) and ($l[1] == 'skos:prefLabel'))
						{
							$name = substr($l[2],1,strlen($l[2]));
							$name = substr($name,0,strpos($name,'"'));
							$name = nbr_author($name,7);
							$AuthorityWords->process($name);
							$RDF->RDP_concept($name,'foad:Person');
						}
				}
				$taff .= '</ul>';

			if (strlen($name))
				{
					$tela .= '<h2>'.$name.'</h2>';
					$AuthorityNames = new \App\Models\Authority\AuthorityNames();
					$AuthorityNames->where('a_uri',$URI);
					$dt = $AuthorityNames->findAll();
					if ((count($dt) == 0) and ($URI != ''))
						{					
							$dt['id_a'] = '';
							$dt['a_uri'] = $URI;
							$dt['a_use'] = '';
							$dt['a_class'] = 'P';
							$dt['a_prefTerm'] = $name;
							$AuthorityNames->insert($dt);								
							$tela.= '<h5>'.lang('authority.appended').'</h5>';
						} else {
							$dt = $dt[0];
							if ($dt['a_prefTerm'] != $name)
								{
									$AuthorityNames->set('a_prefTerm', $name);
									$AuthorityNames->where('id_a', $dt['id_a']);
									$AuthorityNames->update();
									$tela.= '<h5>'.lang('authority.updated').'</h5>';
								} else {
									$tela.= '<h5>'.lang('authority.already_insired').'</h5>';
								}
							
						}
				}
			$tela .= $taff;
			return $tela;
		}
}
