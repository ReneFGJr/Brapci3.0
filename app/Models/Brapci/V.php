<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class V extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'vs';
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

	function index($th,$id)
		{
			$Checked = new \App\Models\Brapci\Checked();
			$RDF = new \App\Models\Rdf\RDF();

			$tela = $th->cab();			
			$dt = $RDF->le($id,1,'brapci');

			$class = $dt['concept']['c_class'];
			$name = $dt['concept']['n_name'];

			switch ($class)
				{
					case 'Article':
						$Checked->check($id,100);
						$Articles = new \App\Models\Journal\Articles();
						$tela .= $Articles->view_articles($id);
						break;					
					case 'Issue':
						$JournalIssue = new \App\Models\Journal\JournalIssue();
						$tela .= $JournalIssue->view_issue_articles($id);
						break;
					default:
						$sx = h($name,4);
						$sx .= h(lang('rdf.class').': '.$class,6);
						$tela .= bs(bsc($sx,12));
						$tela .= bs(bsc($RDF->view_data($id),12));
					break;
				}

			$tela .= $th->cab('footer');
			return $tela;
		}
}
