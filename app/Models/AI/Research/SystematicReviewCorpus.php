<?php

namespace App\Models\AI\Research;

use CodeIgniter\Model;

class SystematicReviewCorpus extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci_ai.SystematicReviews_Corpus';
	protected $primaryKey           = 'id_c';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_c','c_study','id',
		'author','title','journal',
		'year','volume','number',
		'pages','doi','issn',
		'month','note','eprint',
		'keyword'
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

	function check_duplicate($id)
		{
			$sql = "select * from 
					(
						select title, c_study, count(*) as total, max(id_c) as max
						from ".$this->table."
						where c_study = $id and c_duplicata = 0
						group by title, c_study
					) as tabela
					where total > 1";

			$rlt = $this->query($sql)->getresult();

			for ($r=0;$r < count($rlt);$r++)
				{
					$line = (array)$rlt[$r];
					$sql = "update ".$this->table." set c_duplicata = 1 where id_c = ".$line['max'];
					$this->query($sql);
				}
		}

	function view($id)
		{
			$this->check_duplicate($id);
			$sql = "select count(*) as total, c_duplicata from ".$this->table." where c_study = ".$id." group by c_duplicata";
			$rlt = $this->query($sql)->getresult();
			$dup = 0;
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = (array)$rlt[$r];
					if ($line['c_duplicata'] == 1) { $dup = $line['total']; }
				}

			$sql = "select count(*) as total, c_status from ".$this->table." where c_study = ".$id." group by c_status";
			$rlt = $this->query($sql)->getresult();
			$n = array(0,0,0,0);
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = (array)$rlt[$r];
					if ($line['c_status'] == 0) { $n[0] = $line['total']; }
					if ($line['c_status'] == 1) { $n[1] = $line['total']; }
					if ($line['c_status'] == 2) { $n[2] = $line['total']; }
					if ($line['c_status'] == 9) { $n[3] = $line['total']; }
				}
			$sx = '';
			$sx .= bsc('<span class="supersmall">'.lang('ai.sr_status_dp').'</span>'.h($dup,3),2);
			for ($r=0;$r < count($n);$r++)
				{
					$sx .= bsc('<span class="supersmall">'.lang('ai.sr_status_'.$r).'</span>'.h($n[$r],3),2);
				}
			$sx = bs($sx);
			return $sx;
		}
}
