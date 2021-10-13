<?php

namespace App\Models\Journal;

use CodeIgniter\Model;

class Articles extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'articles';
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

	function view_articles($id)
		{
			$RDF = new \App\Models\RDF\RDF();
			$RDFData = new \App\Models\RDF\RDFData();			
			$dt = $RDF->le($id);
			$dados = $RDFData->view_data($dt);



			$tela = '';
			$data = $dt['data'];
			$d = array();
			for ($r=0;$r < count($data);$r++)
				{
					$line = $data[$r];
					$class = $line['c_class'];
					$txt = $line['n_name'];
					$lang = $line['n_lang'];
					switch($class)
						{
							case 'hasTitle':
								$d['title'][$lang] = $txt;
								break;
							default:
								$tela .= $class.'<br>';
								break;
						}
				}
			/* CHECK */
			if (isset($d['title']))
			{
				$IA_title = new \App\Models\AI\Title();
				$IA_title->check($d['title'],$id);
			}
			$tela .= $dados;

			return $tela;
		}
}
