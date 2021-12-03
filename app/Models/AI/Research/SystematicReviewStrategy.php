<?php

namespace App\Models\AI\Research;

use CodeIgniter\Model;

class SystematicReviewStrategy extends Model
{
	protected $DBGroup              = 'ai';
	protected $table                = 'SystematicReviews_Strategy';
	protected $primaryKey           = 'id_st';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_st','st_study','st_database','st_strategy',
		'st_justify','st_status'
	];
	protected $typeFields        = [
		'hidden','hidden','string:100','text',
		'text','status'
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

	function index($id,$d2)
		{
			$sx = '';
			switch($d2)
				{
					default:
						$sx = bsc($this->list($id),12);
						$sx .= bsc($this->btn_edit($id),1);
						break;
				}
			$sx = bs($sx);
			return $sx;
		}
	function btn_edit($id,$id2=0)
		{
			if ($id==0)
				{
					$msg = 'main.new';
				} else {
					$msg = 'main.edit';
				}
			$sx = '<a href="'.base_url(PATH.MODULE.'research/systematic_review/strategy_edit/'.$id.'/'.$id2).'" class="btn btn-primary btn-sm">';
			$sx .= lang($msg);
			$sx .= '</a>';
			return $sx;			
		}

	function view($id,$id2)
		{
			$sx = '';
			$this->join('systematicreviews_studies','id_sr = st_study');
			$dt = $this->find($id2);
			print_r($dt);

			$link = '<a href="'.base_url(PATH.MODULE.'research/systematic_review/viewid/'.$id).'">';
			$linka = '</a>';
			$sx .= bsc(h($link.$dt['sr_title'].$linka,2),12);
			$sa = '';
			$sa .= h($dt['st_database'],3);
			$sa .= p($dt['st_strategy'],'xxx'.lang('st_strategy'));
			$sa .= p($dt['st_justify'],lang('st_justify'));

			$sb = '';
			$sb .= $this->btn_edit($id,$id2);

			$sx .= bsc($sa,11);
			$sx .= bsc($sb,1);

			$sx = bs($sx);
			return $sx;
		}
	function list($id)
		{
			$dt = $this->where('id_st',$id)->findAll();
			$sx = '';
			$sx .= '<table class="table">';
			$sx .= '<tr>';
			$sx .= '<th width="20%">'.'st_database'.'</th>';
			$sx .= '<th width="35%">'.'st_strategy'.'</th>';
			$sx .= '<th width="35%">'.'st_justify'.'</th>';
			$sx .= '<th width="10%">'.'st_status'.'</th>';
			$sx .= '</tr>';							

			for ($r=0;$r < count($dt);$r++)
				{
					$ln = $dt[$r];
					$link = '<a href="'.PATH.MODULE.'research/systematic_review/strategy_view/'.$id.'/'.$ln['id_st'].'">';
					$linka = '</a>';
					$sx .= '<tr>';
					$sx .= '<td>'.$link.$ln['st_database'].$linka.'</td>';
					$sx .= '<td>'.$ln['st_strategy'].'</td>';
					$sx .= '<td>'.$ln['st_justify'].'</td>';
					$sx .= '<td>'.$ln['st_status'].'</td>';
					$sx .= '</tr>';					
				}
			$sx .= '</table>';
			return $sx;
		}
	function edit($id,$id2)
		{
			$sx = '';
			$this->id = $id2;
			$_POST['st_study'] = $id;
			$this->path = PATH.MODULE.'research/systematic_review/strategy_edit/'.$id.'/'.$id2;
			$this->path_back = PATH.MODULE.'research/systematic_review/strategy/'.$id.'/';
			$sx = form($this);
			return $sx;
		}
}
