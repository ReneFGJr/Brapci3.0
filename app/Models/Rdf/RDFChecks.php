<?php

namespace App\Models\Rdf;

use CodeIgniter\Model;

class RDFChecks extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = PREFIX.'rdf_concept';
	protected $primaryKey           = 'id_cc';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_cc','cc_use'
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

	function check_html($class)
		{
			$sx = '';
			$sx .= h(lang('brapci.check')).' '.$class;
			$RDF = new \App\Models\Rdf\RDF();
			$RDFLiteral = new \App\Models\Rdf\RDFLiteral();
			$class = $RDF->getClass($class);
			$dt = $this
				->join('rdf_name','rdf_name.id_n = rdf_concept.cc_pref_term')
				->where('cc_class',$class)
				->like('n_name','%<%')
				->FindAll();
			$t = 0;
			$sx .= '<ul>';
			$total = count($dt);

			for($r=0;$r < $total;$r++)
				{
					$t++;
					if ($t > 10) { break; }

					echo '<br>'.$r;
					$line = $dt[$r];

					$sx .= '<li>';
					$nome = $line['n_name'];
					$nome = strip_tags($nome);
					$nome = troca($nome,'"','');
					$nome = troca($nome,"'",'');
					$nome = troca($nome,"  ",' ');
					$nome = troca($nome,"  ",' ');
					$nome = troca($nome,">",'');
					$nome = troca($nome,"<",'');
					$nome = trim($nome);

					$dt['n_name'] = $nome;
					$RDFLiteral->set($dt)->where('id_n',$line['id_n'])->update();
					$sx .= '<li>'.$nome.' <b>Update</b></li>';
				}
				$sx .= '</ul>';
			$sx = 'Update '.$t.' for '.count($dt).'<br>'.$sx;
			if ($t > 0)
				{
					$sx .= metarefresh(PATH.MODULE.'rdf/check_corporate_body',2);
				} else {
					$sx .= bsmessage(lang('brapci.process_finished'));
					$sx .= '<a href="'.PATH.MODULE.'rdf" class="btn btn-outline-primary">'.lang('brapci.return').'</a>';
				}
			return $sx;
		}

	function check_duplicate()
		{
		/********************************************** Check RDF */
		$RDFData = new \App\Models\Rdf\RDFData();
		$sx = '';
		$sx .= '<h2>'.msg('brapci.check_duplicate').'</h2>';
		$sx .= '<ul>';
		$sx .= '<li class="text-success">';
		$tot = $RDFData->check_duplicates();
		$sx .= lang('brapci.processing').' -> '.$tot.' '.lang('brapci.duplicates');
		$sx .= '</li>';
		$sx .= '</ul>';

		if ($tot > 0)
			{
				$sx .= metarefresh(PATH.MODULE.'rdf/check',5);
				$sx .= $this->btn_return();
			} else {
				$sx .= bsmessage(lang('brapci.rdf_check_ok'),1);
				$sx .= $this->btn_return();
			}
		return $sx;
		}	

	function btn_return()
		{
			$sx = '<a href="'.PATH.MODULE.'rdf" class="btn btn-outline-primary bt-2">'.lang('brapci.return').'</a>';
			return $sx;
		}

	function check_loop()
		{
			$RDF = new \App\Models\Rdf\RDF();
			$sx = h('Check Loop');
			$this->select('
				rdf_concept.id_cc as r0idc, rc1.id_cc as r1idc,rc2.id_cc as r2idc,
				rdf_concept.cc_use as r0use, rc1.cc_use as r1use,rc2.cc_use as r2use,
			');
			$this->join('rdf_concept as rc1','rdf_concept.cc_use = rc1.id_cc');
			$this->join('rdf_concept as rc2','rc1.cc_use = rc2.id_cc');
			$this->where('rdf_concept.cc_class > 0');
			$this->limit(1);
			$dt = $this->findAll();

			for ($r=0;$r < count($dt);$r++)
				{
					$ln = $dt[$r];	
					echo '<pre>';
					print_r($ln);
					echo '</pre>';

					/* Method 0 */
					if ($ln['r0idc'] == $ln['r0use'])
						{
							$dd['cc_use'] = 0;
							$this->set($dd)->where('id_cc',$ln['r0idc'])->update();
							$ln['r2use'] = -1;
						}
					if ($ln['r1idc'] == $ln['r1use'])
						{
							$dd['cc_use'] = 0;
							$this->set($dd)->where('id_cc',$ln['r0idc'])->update();
							$ln['r2use'] = -1;
						}
					if ($ln['r2idc'] == $ln['r2use'])
						{
							$dd['cc_use'] = 0;
							$this->set($dd)->where('id_cc',$ln['r0idc'])->update();
							$ln['r2use'] = -1;
						}

					/* Method 1 */
					if ($ln['r2use'] == 0)
						{
							$dd['cc_use'] = $ln['r1use'];
							$this->set($dd)->where('id_cc',$ln['r0idc'])->update();

							$sx .= '<li>';
							$sx .= $RDF->link(array('id_cc'=>$ln['r0idc']));
							$sx .= $ln['r0idc'].' -> '.$ln['r1idc'].'</a></li>';
						}
					if ($ln['r2use'] > 0)
						{
							$dd['cc_use'] = $ln['r2use'];
							$this->set($dd)->where('id_cc',$ln['r0idc'])->update();
							$this->set($dd)->where('id_cc',$ln['r1idc'])->update();							

							$sx .= '<li>Triple: ';
							$sx .= $RDF->link(array('id_cc'=>$ln['r0idc']));
							$sx .= $ln['r0idc'].' -> '.$ln['r1idc'].'</a></li>';							
						}

				}
			if (count($dt) == 0)
				{
					$sx .= $this->btn_return();
				}			
			return $sx;
		}	

	function check_authors()
		{
			$AuthotityRDF = new \App\Models\Authority\AuthotityRDF();
			$sx  = '';
			$ph = get("phase");
			switch ($ph)
				{
					default:
						/*************************************** Etapa I */				
						$sx .= h('Method 1');
						$sx .= $AuthotityRDF->author_check_method_1();
						$sx .= $this->btn_return();
						break;

					case '2':
						$sx .= h('Method 3');
						/*************************************** Etapa I */				
						$sx .= $AuthotityRDF->author_check_method_3();
						$sx .= $this->btn_return();
						break;
				}

			return $sx;
		}					
}
