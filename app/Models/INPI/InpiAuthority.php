<?php

namespace App\Models\INPI;

use CodeIgniter\Model;

class InpiAuthority extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'inpiauthorities';
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

	function get_id_by_name($name,$dta=array())
		{
			$name = mb_strtolower($name);
			while (strpos($name,'  '))
			{
				$name = str_replace('  ',' ',$name);
			}
			$name = troca($name,', ltd',' ltd');
			$name = troca($name,',ltd',' ltd');
			$name = troca($name,', inc',' inc');
			$name = troca($name,',inc',' inc');
			$name = troca($name,', lp',' lp');
			$name = troca($name,', co',' co');
			$name = troca($name,', llc',' llc');
			$name = troca($name,', s.l.',' s.l.');
			$name = troca($name,', limited',' limited');
			$name = troca($name,', naamloze vennootschap',' n. v');
			$name = troca($name,',naamloze vennootschap',' n. v');
			$name = substr($name,0,120);
			if (substr($name,strlen($name)-1,1) == '.')
				{
					$name = substr($name,0,strlen($name)-1);
				}
			$name = nbr_author($name,7);

			$AuthorityNames = new \App\Models\Authority\AuthorityNames();
			$AuthorityNames->table = 'brapci_inpi.AuthorityNames';
			$dt = $AuthorityNames->get_id_by_name($name);
			
			if (count($dt) == 0)
				{
					$dta['a_class'] = 'P';
					$inst = array('ltd','inc','ltda','sa','limited','s.a.','s. a.',
						'company','s.r', 'srl','electronics','n. v','b.v','b. v','ag ','se ','kg ',
						'gmbh','laboratoir','ab ','a/s',
						'faculdade','co,','universidade','university','universite',
						'lp ','s.p','automotive','technolog','informatica','n.v.',
						'mbh', 
						'corporation','foundation','government','lp','s.l','llc',
						'mv','corp','industria','industries','industria','industrie',
						'military','medicine','pharmaceutical','pharmaceuticals','cie',
						);
					$namel = ' '.ascii(mb_strtolower($name)).' ';
					for ($q=0;$q < count($inst);$q++)
						{
							if (strpos($namel,' '.$inst[$q]))
								{									
									$dta['a_class'] = 'O';
									break;
								}
						}							

					if (strpos($name,','))
						{							
							echo '<hr style="font-color: red">OPS '.$name.'<hr>';							
						}

					$dt['a_prefTerm'] = trim($name);
					$dt['a_class'] = $dta['a_class'];
					if (isset($dta['a_country']))
						{
							$dt['a_country'] = $dta['a_country'];
						}
					if (isset($dta['a_UF']))
						{
							$dt['a_UF'] = $dta['a_UF'];
						}		
					$AuthorityNames->insert($dt);
					$dt = $AuthorityNames->get_id_by_name($name);
				}
			return $dt;
		}
}
