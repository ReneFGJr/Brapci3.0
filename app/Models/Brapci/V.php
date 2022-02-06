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

	function index($th,$id,$act='')
		{			
			$Checked = new \App\Models\Brapci\Checked();
			$RDF = new \App\Models\Rdf\RDF();

			if ($act == 'export')
				{
					$RDF->c($id,true);
				}

			$tela = $th->cab();			
			$dt = $RDF->le($id,1);

			$class = $dt['concept']['c_class'];
			$name = $dt['concept']['n_name'];
			
			switch ($class)
				{
					case 'Article':
						$Checked->check($id,100);
						$Articles = new \App\Models\Journal\Articles();
						$tela .= $Articles->view_articles($id);
						break;
					case 'Proceeding':
						$Checked->check($id,100);
						$Articles = new \App\Models\Journal\Articles();
						$tela .= $Articles->view_articles($id);
						$tela .= bs(bsc($RDF->view_data($id),12));
						break;											
					case 'Issue':
						$JournalIssue = new \App\Models\Journal\JournalIssue();
						$tela .= bs(bsc(h('Class: '.$class,2),12));
						$tela .= $JournalIssue->view_issue_articles($id);
						$tela .= bs(bsc($RDF->view_data($id),12));
						break;
					default:
						$sx = h($name,4);
						echo $sx;
						$sx .= h(lang('rdf.class').': '.$class,6);
						$tela .= bs(bsc($sx,12));
						$tela .= bs(bsc($RDF->view_data($id),12));
					break;
				}
			$tela .= bs(bsc($this->bt_export($id),12));
			$tela .= $th->cab('footer');
			return $tela;
		}
		function bt_export($id)
			{
				$link = URL.'/res/v/'.$id.'/export/';
				$sx = '<a href="'.$link.'" class="btn btn-outline-primary btn-sm" onclick="export_rdf('.$id.');">'.lang('rdf.export').'</a>';
				return $sx;
			}
}
