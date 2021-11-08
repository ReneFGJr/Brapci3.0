<?php

namespace App\Models\Ai\Research;

use CodeIgniter\Model;

class SystematicReviewData extends Model
{
	protected $DBGroup              = 'ai';
	protected $table                = 'systematicreviews_group';
	protected $primaryKey           = 'id_fs';
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

	function view($id)
		{
			$sql = "select * from systematicreviews_group
						LEFT JOIN systematicreviews_fields ON fr_field = id_fs
						LEFT JOIN systematicreviews_protocol ON sp_field = id_fs AND sr_study = $id
						ORDER BY fr_order
					";
			$dt = $this->query($sql)->getResult();
			$tela = '';
			$grx = '';
			for ($r=0;$r < count($dt);$r++)
				{
					$line = (array)($dt[$r]);
					$gr = $line['fr_group'];
					if ($gr != $grx)
						{
							$tela .= bsc($line['fr_alinea'].' - '.h(lang($gr),5),12);
							$grx = $gr;
						}
					$tela .= bsc(''.$line['fr_alinea'],1);					
					$tela .= bsc('<span class="small">'.$line['fs_field'].'</span>',2);
					$tela .= bsc(''.$line['sp_context'].'&nbsp;',9);
				}
			return $tela;
		}
}
