<?php

namespace App\Models\Authority;

use CodeIgniter\Model;

class AuthotityRDF extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'rdf_concept';
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

	function author_check_method_2($class = "Person")
		{
				$sql = "SELECT 
						R1.cc_use as use1, R2.cc_use as use2, R3.cc_use as use3,
						R1.id_cc as id1, R2.id_cc as id2, R3.id_cc as id3
						FROM rdf_concept as R1	
						INNER JOIN rdf_class ON cc_class = id_c
						INNER JOIN rdf_concept as R2 ON R1.cc_use = R2.id_cc
						INNER JOIN rdf_concept as R3 ON R2.cc_use = R3.id_cc
						where R1.cc_use > 0 and c_class = '$class' 
						and R2.cc_use > 0 and R3.cc_use > 0";
				$rlt = $this -> db -> query($sql)->getResultArray();

				for ($r=0;$r < count($rlt);$r++)
					{
						$ln = $rlt[$r];
						echo '<pre>';
						print_r($ln);
						echo '<hr>';

						$n = array();
						$n[0] = $ln['use1'];
						$n[1] = $ln['use2'];
						$n[2] = $ln['use2'];

						if (($n[0] == $n[1]) or 
							($n[1] == $n[2]) or
							($n[2] == $n[0]))
						{
							echo "LOOP";
							$min = 9*9*9*9*9*9;
							for ($r=1;$r <= 3;$r++)
								{
									if ($ln['id'.$r] < $min)
										{
											$min = $ln['id'.$r];
										}									
								}
							/**************************** UPDATE */
							for ($r=1;$r <= 3;$r++)
								{
									$dt['cc_use'] = $min;
									if ($ln['id'.$r] == $min)
										{
											$dt['cc_use'] = 0;
										}
									$this->set($dt);
									$this->where('id_cc',$ln['id'.$r])->update();

									echo '<hr>'.$this->getlastquery();
								}
						}
						exit;

					}
				echo '<pre>';
				print_r($rlt);
				exit;
		}

	function author_check_method_3($p = 0, $class = "Person") {
        $sql = "SELECT * FROM rdf_concept as R1
        			INNER JOIN rdf_name ON cc_pref_term = id_n
        			INNER JOIN rdf_class ON cc_class = id_c
        			INNER JOIN rdf_data ON R1.id_cc = d_r2
        			where R1.cc_use > 0 and c_class = '$class' ";

	    $rlt = $this -> db -> query($sql)->getResultArray();
        $sx = '';
        $m = 0;
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $ida = $line['id_cc'];
            $idt = $line['cc_use'];
            $sx .= '<li>' . $line['n_name'].' => '.$idt.'</li>';
            
            $sql = "update rdf_data set 
            		d_o = " . $line['id_d'] . ",
            		d_r2 = $idt,
            		d_update = 1
            	where id_d = " . $line['id_d'];
            $this -> db -> query($sql);
            $m++;
        }
        if ($m == 0) {
            $sx = msg('No_changes');
        }
        return ($sx);
    }	

    function author_check_method_1() {
		$RDF = new \App\Models\RDF\RDF();
		$f = $RDF->getClass('Person');

        $sql = "
		select N1.n_name as n_name, N1.n_lang as n_lang, C1.id_cc as id_cc,
        N2.n_name as n_name_use, N2.n_lang as n_lang_use, C2.id_cc as id_cc_use         
        FROM rdf_concept as C1
        INNER JOIN rdf_name as N1 ON C1.cc_pref_term = N1.id_n
        LEFT JOIN rdf_concept as C2 ON C1.cc_use = C2.id_cc
        LEFT JOIN rdf_name as N2 ON C2.cc_pref_term = N2.id_n
        where C1.cc_class = " . $f . " and C1.cc_use = 0
        ORDER BY N1.n_name";
        $rlt = $this -> db -> query($sql)->getResultArray();
        
        $n2 = '';
        $n0 = '';
        $i2 = 0;
        $sx = '';
        $m = 0;
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $n0 = trim($line['n_name']);
            $n1 = trim($line['n_name']);
            $n1 = troca($n1, ' de ', ' ');
            $n1 = troca($n1, ' da ', ' ');
            $n1 = troca($n1, ' do ', ' ');
            $n1 = troca($n1, ' dos ', ' ');
            $nf = substr($n1, strlen($n1) - 3, 3);
            if (($nf == ' de') or ($nf == ' da') or ($nf == ' do') or ($nf == ' dos')) {
                $n1 = trim(substr($n1, 0, strlen($n1) - 3));
            }
            $n1 = trim($n1);
            $i1 = $line['id_cc'];
            
            if ($n1 == $n2) {
                $m++;
                $sx .= '<li>' . $n1 . '(' . $i1 . ')';
                $sx .= '--' . $n2 . '(' . $i2 . ')';
                $sx .= ' --> <b><font color="green">Igual</font></b>';
				$sx .= '</li>';
                $sql = "update rdf_concept set cc_use = $i2 where id_cc = $i1";
                $rrr = $this -> db -> query($sql);
            }
            $n2 = $n1;
            $i2 = $i1;
        }
        
        if ($m == 0) {
            $sx = msg('No_changes');
			$sx .= metarefresh(PATH.MODULE.'rdf/check_authors?phase=2');
		}
        return ($sx);
    }
}
