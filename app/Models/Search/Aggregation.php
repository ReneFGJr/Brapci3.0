<?php

namespace App\Models\Search;

use CodeIgniter\Model;

class Aggregation extends Model
{
	protected $DBGroup              = 'main';
	protected $table                = 'Aggregation';
	protected $primaryKey           = 'id_source';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_source','source_description','source_url',
		'source_type'
	];
	protected $typeFields        = [
		'hidden','string:100','string:100',
		'string:1'
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
			$sx = '';
			$sx .= bsc(h(lang('main.aggregation'),1),12);			

			switch($d1)
				{
					case 'view':
						$sx .= bsc(h(lang('main.aggregation.edit'),4),12);
						$sx .= $this->view($d2);
					break;
					case 'edit':
						$sx .= bsc(h(lang('main.aggregation.edit'),4),12);
						$sx .= $this->edit($d2);
					break;
					default:
						$sx .= bsc(h(lang('main.aggregation.sources'),4),12);
						$sx .= bsc($this->sources(),12);
						break;
				}

			return bs($sx);
		}

	function view($id)
		{
			$sx = '';
			$dt = $this->find($id);
			$sx = bsc(h($dt['source_description'],4),12);
			$sx .= bsc($this->btn_export($id),1);
			return $sx;
		}

	function edit($id)
		{
			$this->path = PATH.MODULE.'/aggregation';
			$this->path_back = PATH.MODULE.'/aggregation';
			
			$sx = form($this);
			return $sx;
		}

	function sources()
		{
			$sx = '';
			$dt = $this->findAll();
			$sx = '<ul>';
			foreach($dt as $row)
				{
					$sx .= '<li>';
					$link = '<a href="'.PATH.MODULE.'/aggregation/view/'.$row['id_source'].'">';
					$linka = '</a>';
					$sx .= $link.$row['source_description'].$linka;
					$sx .= '</li>';
				}
			$sx .= '</ul>';
			$sx .= $this->btn_edit(0);
			return $sx;
		}
	function btn_edit($id)
		{
			if ($id==0)
				{
					$msg = 'main.new';
				} else {
					$msg = 'main.edit';
				}
			$sx = '<a href="'.PATH.MODULE.'/aggregation/edit/'.$id.'" class="btn btn-primary btn-sm">'.lang($msg).'</a>';
			return $sx;
		}
	function btn_export($id)
		{
			$msg = 'main.export';
			$sx = '<a href="'.PATH.MODULE.'/aggregation/edit/'.$id.'" class="btn btn-outline-primary btn-sm">'.lang($msg).'</a>';
			return $sx;
		}		
}
