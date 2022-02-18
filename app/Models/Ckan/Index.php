<?php

namespace App\Models\Ckan;

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
					case 'dataverse':
						$sx = $this->dataverse($d2,$d3);
						break;
					case 'create_package':
						$CkanPackages = new \App\Models\Ckan\CkanPackages();
						$sx .= $CkanPackages->createPackage();
						break;
					case 'package_list':
						$CkanPackages = new \App\Models\Ckan\CkanPackages();
						$sx .= $CkanPackages->package_list();
						break;						
					case 'list_groups':
						$CkanGroup = new \App\Models\Ckan\CkanGroup();
						$sx .= $CkanGroup->list_group_api();						
						break;
					default:
						$sx .= $this->menu();
				}
			$sx = bs(bsc($sx,12));
			return $sx;
		}
	function menu()
		{
			$sx = h(lang('brapci.menu'));
			$sx .= '<ul>';
			$menu['list_groups'] = 'List Groups';
			$menu['create_package'] = 'Create Package';
			$menu['package_list'] = 'List Packages';
			foreach($menu as $url=>$label)
				{
					$sx .= '<li>'.'<a href="'.PATH.MODULE.'admin/ckan/'.$url.'">'.$label.'</a></li>';
				}
			$sx .= '</ul>';
			return $sx;
		}


	function dataverse()
		{
			
			$file = '.tmp/dataverse.xml';
			if (!file_exists($file))
				{
					echo "Importando Dados do Dataverse";
					exit;
					$uri = 'http://vitrinedadosabertos.inep.rnp.br//api/datasets/export?exporter=ddi&persistentId=doi%3A10.80102/vtn/FVBA0I';
					$txt = file_get_contents($uri);
					file_put_contents($file,$txt);
				}
			$xmlstring = file_get_contents($file);		
			
			$xml = simplexml_load_string($xmlstring);

			echo '<pre>';

			$xml = (array)$xml;
			$dosDesc = (array)$xml['stdyDscr'];
			$citation = (array)$dosDesc['citation'];
			$titlStmt = (array)$citation['titlStmt'];			
			$titulo = (string)$titlStmt['titl'];

			$DOI = (string)$titlStmt['IDNo'][0];

			$author = array();
			$respStmt = (array)$citation['rspStmt'];
			$distStmt = (array)$citation['distStmt'];

			array_push($author,$distStmt['depositr']);
			array_push($author,$respStmt['AuthEnty']);

			/* Files */
			$files = (array)$xml['fileDscr'];
			//print_r($files);

			$resources = array();
			

			for ($r=0;$r < count($files);$r++)
				{
					$file = (array)$files[$r];
					$attr = (array)$file['@attributes'];
					$fileTxt = (array($file['fileTxt']));
					$fileTxt = $fileTxt[0];

					$rs = array();
					$rs['package_id'] = $attr['ID'];
					$rs['url'] = $attr['URI'];
					//$rs['description']='1';

					echo '<hr>';
					$filename = (string)$fileTxt['fileName'];
					$fmt = substr($filename,strlen($filename)-5,5);
					if (strpos($fmt,'.') > 0) 
						{ 
							$pos = strpos($fmt,'.');
							$fmt = substr($fmt,$pos+1,10);
						}
					$rs['format'] = $fmt;
					$rs['name'] = $filename;
					$rs['resource_type']= $rs['format'];
					$rs['mimetype']= (string)$fileTxt['fileType'];
					//$rs['mimetype_inner ']='1';
					//$rs['cache_url ']='1';
					//$rs['size  ']='1';
					//$rs['created  ']='1';
					//$rs['last_modified  ']='1';
					//$rs['last_modified  ']='1';	
					print_r($rs);
					exit;
					array_push($resources,$rs);
				}
			
			$sx = '';
			$API = new \App\Models\Ckan\CkanAPI();
			$dt = array();
			$dt['cmd'] = 'package_create';
			$dt['type'] = 'POST';

			$data = array();
			$data['name'] = troca(ascii(mb_strtolower($titulo)),' ','_');
			$data['notes'] = 'Notes My DataSet';
			$data['owner_org'] = 'ufrgs';
			$data['version'] = '2';
			$data['author'] = $author;
			$data['isopen'] = 'true';
			$data['license_id'] = 'cc-by';
			$data['license_title'] = 'Creative Commons Attribution';
			$data['maintainer'] = 'INEP';
			//$data['maintainer_email'] = array('inep@inep.gov.br');
			//$data['tags'] = array('inep','Censo Educação Basica'); 
			//$data['tags'] = 'INEP; Censo Educação Basica';
			$data['resources'] = $resources;
			$data['url'] = 'http://www.inep.gov.br/';
			$dt['data'] = $data;

			print_r($data);
			//exit;

			$rsp = $API->API($dt);
			$rsp = (array)json_decode($rsp);	
			echo '<hr>';
			print_r($rsp);		
		}
}
