<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Solr extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'solrs';
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
			$sx = $d1;
			switch($d1)
				{
					case 'schema':
						$sx = $this->updateSchema($d2,$d3);
						break;
					default:
						$menu[PATH.MODULE.'dataverse/solr/schema'] = 'dataverse.Solr.Schema';
						$menu[PATH.MODULE.'dataverse/solr'] = 'dataverse.Solr';
						$sx .= menu($menu);
				}
			return $sx;			
		}

	function updateSchema()
		{
			//$url = 'curl "http://localhost:8080/api/admin/index/solr/schema"';
			$url = 'http://localhost/api/admin/index/solr/schema';
			$file = 'D:\Projeto\www\LattesData\_Documentação\PerfilAplicação\schema_dv.xml';
			$txt = file_get_contents($file);
			$txt = troca($txt,'<','&lt;');
			$txt = explode(chr(10),$txt);

			/*************************************************************************/
			$file = 'D:\Projeto\www\LattesData\_Documentação\PerfilAplicação\schema.xml';
			$solr = file_get_contents($file);
			$solr = troca($solr,'<','&lt;');
			$solr = explode(chr(10),$solr);

			/* SUBS */
			$tta = '---';
			$txa = '<!-- Dataverse copyField from http://localhost:8080/api/admin/index/solr/schema -->';
			$txb = '<!-- End: Dataverse-specific -->';

			pre($txt);
		}
}
