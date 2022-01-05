<?php

namespace App\Models\Authority;

use CodeIgniter\Model;

class AuthorityNames extends Model
{
	protected $DBGroup              = 'default';
	public $table                		= 'brapci_authority.AuthorityNames';
	protected $primaryKey           = 'id_a';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_a','a_prefTerm','a_class','a_lattes','a_brapci','a_orcid','a_uri','a_use','a_country','a_UF'
	];

	protected $typeFields        = [
		'hidden',
		'string:100',
		'string:100',
		'string:100',
		'string:100',
		'string:100',
		'string:100',
		'string:1',
		'string:20',
		'string:2'
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

	function summaryCreate()
		{
			$this->select('count(*) as total');
			$dt = $this->findAll();
			print_r($dt);
		}

	function get_id_by_name($name,$dt=array())
		{
			$name = trim($name);
			$this->where('a_prefTerm',$name);
			$dt = $this->findAll();
			return $dt;
		}

	function match($id)
		{
			$this->where('id_a',$id);
			$dt = $this->findAll();
			if (count($dt) > 0)
				{
					$line = $dt[0];
					if ($line['a_use'] > 0)
						{
							$id = $line['a_use'];
							$this->where('id_a',$id);
							$dt = $this->findAll();
						}

					$name = $dt[0]['a_prefTerm'];
					$Match = new \App\Models\AI\Authority\Match();
					$Match->table = $this->table;
					$Match->check($name);					
				}	
			return '';
		}
	function remissive($id)	
		{
			$dt = $this->le($id);
			$id = $dt['id_a'];
			
			$this->where('a_use',$id);
			$this->orderBy('a_prefTerm','asc');
			$dt = $this->findAll();
			$sx = h('Remissivas',4);
			$sx .= '<ul class="list-remissive-authority">';
			for ($r=0;$r < count($dt);$r++)
				{
					$sx .= '<li>'.$dt[$r]['a_prefTerm'].'</li>';
				}
			$sx .= '</ul>';
			return $sx;
		}
	function viewid($id)
		{
			$Country = new \App\Models\Authority\Country();
			$dt = $this->le($id);

			/******************************************** Instituição */
			$sx = '';
			$sx .= bsc(lang('brapci.prefTerm'),11,'small');
			$sx .= bsc(lang('brapci.Country'),1,'small');
			$sx .= bsc(h($dt['a_prefTerm'],3),11);
			$country = $dt['a_country'].$dt['a_UF'];
			$img = $Country->flag($dt['a_country']);
			$sx .= bsc($img,1);
			$sx .= bsc('<hr>',12);
			$sx .= bsc($this->remissive($id),12);

			$sx = bs($sx);

			
			return $sx;
		}

	function le($id)
		{
			$this->where('id_a',$id);
			$dt = $this->findAll();
			$dt = $dt[0];
			if ($dt['a_use'] > 0)
				{
					$id = $dt['a_use'];
					$this->where('id_a',$id);
					$dt = $this->findAll();
					$dt = $dt[0];
				}
			return $dt;
		}

	function edit($id)
		{
			$this->id = $id;
			$this->path = base_url(PATH . MODULE.  '/index/edit/' . $id);
			IF ($id > 0)
				{
					$this->path_back = base_url(PATH . MODULE.  '/index/viewid/' . $id);
				} else {
					$this->path_back = base_url(PATH . MODULE.  '/index/');
				}
			
			$tela = form($this);
			return $tela;
		}

}
