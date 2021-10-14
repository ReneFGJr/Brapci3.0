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
							case 'hasAuthor':
							$txt = $RDF->le_content($line['d_r2']);
								$d['author'][$txt] = $line['d_r2'];
								break;
							case 'hasTitle':
								$d['title'][$lang] = $txt;
								break;
							default:
								$tela .= $class.'--<br>';
								break;
						}
				}
			/* CHECK */
			if (isset($d['title']))
			{
				$IA_title = new \App\Models\AI\Title();

				if (isset($d['title']['pt-BR'])) { $title = $d['title']['pt-BR']; }				
				$IA_title->check($d['title'],$id);
			}

			/********************************************************************* TITLE *****/
			$pref = array('pt-BR','es','en');
			$cl = 'h3';
			for ($r=0;$r < count($pref);$r++)
				{
					$lg = $pref[$r];
					if (isset($d['title'][$lg]))
						{
							$tela .= '<div class="title text-center '.$cl.' p-1 m-1">'.$d['title'][$lg].'</div>';
							$cl = 'h4 fst-italic';
						}
				}

			/********************************************************************* ABSTRACT **/
			$abs[$lang] = '';
		
			/********************************************************************* AUTHOR ****/
			$auth = '';
			foreach($d['author'] as $name => $id)
				{
					$link = '<a href="'.base_url(PATH.'res/v/'.$id).'" class="form-control h5 link-primary">';
					$linka = '<a>';
					$auth .= $link.$name.$linka;
				}
			$tela .= bsc($auth,12,'text-end');
			$tela .= '</div>';


			$tela .= $dados;

			return $tela;
		}
}
