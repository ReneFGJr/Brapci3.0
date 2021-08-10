<?php

namespace App\Models;

use CodeIgniter\Model;

class Analysis extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'analyses';
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


	function url_inport()
		{
			$sx = '<form method="post">';
			$sx .= lang('analysis.input_url');
			$sx .= '<input type="text" name="url" class="form-control" id="url" value="'.get("url").'">';
			$sx .= 'Ex: '.'https://brapci.inf.br/index.php/res/basket/active/361';
			$sx .= '<br/><input type="submit" name="action" class="btn btn-primary" value="'.lang("submit").'">';
			$sx .= '</form>';
			$sx = bs(bsc($sx,12));			
			return $sx;
		}

	function index($a1,$a2)
		{
			$sx = '';
			switch ($a1)
				{
					case 'teste':
						$url = get("url");
						if (strlen($url) == 0)
						{
							$sx .= $this->url_inport();
						} else {
							$sx .= $this->analysis02();	
						}		
						break;			
					default:
						$sx .= $this->analysis03();
						
				}
			return $sx;
		}
	function analysis01()
		{
			$sx = 'OK';
			$file = 'D:/GoogleDrive/Artigos/2021/AnáliseDeDomínio - Leilah/ad.txt';
			$txt = file_get_contents($file);
			$txt = troca($txt,chr(10),'');
			$ln = explode(chr(13),$txt);
			$mtx = array();
			$auth = array();
			for ($r=1972;$r <= date("Y");$r++)
				{
					$mtx[$r] = 0;
				}
			for ($r=0;$r < count($ln);$r++)
				{
					$la = explode(';',$ln[$r]);
					$name = $la[0];
					$auth[$name] = $mtx;
					for ($q=3;$q < count($la);$q++)
						{
							$idm = round($la[$q]);
							if ($idm > 0)
							{
								$ano = $this->api_metadata($idm,'PY');

								for ($z=0;$z < count($ano);$z++)
									{

										$anox = round($ano[$z]);
										if ($anox >= 1972)
										{
											$auth[$name][$anox] = $auth[$name][$anox] + 1 ;
										}
									}
							}
						}					
				}		

			$sx = '';
			$m = 5;
			$x = 10;
			$y = 100;
			$sp = 10;
			$st = 20*$m;

			$sx .= canvas_open("bc",$m*635,$m*400);	
			$n = 0;
			for ($r=1972;$r <= date("Y");$r++)
				{
					$xx = $x + 650 + $n*$m*10;
					$yy = 10 * $m;
					$sx .= canvas_line($xx,$yy+$st,$xx,$yy+430*$m+$st);
					if (round($r/2) == ($r/2))
					{
						$sx .= canvas_text($xx-$m*2,$yy+$st,$r,'black',6*$m);
					}
					$n++;
				}		
			foreach($auth as $name=>$datas)
				{
					$sx .= canvas_text($x+600,$y+$st,$name,'black',6*$m,'right');
					$sx .= canvas_line($x+610,$y-8+$st,$x+730*$m,$y-8+$st);
					$y = $y + $sp * $m;
					$n = 0;
					$xx = 630;
					$yy = 90;

					foreach($datas as $ano => $v)
						{							
							if ($v > 0)
							{
								$yy = $y-8*$m-8;
								$xx = $x+640+$sp*$n*$m;
								$t = log($v+1)*$m*3;
								
								$sx .= canvas_circle($xx+10,$yy-8+$st,$t,'green');
								//echo '<br>'.$name.'='.$ano.'-'.$v.'--'.$xx.'x'.$yy.'==>'.$t;
							}
							$n++;
						}
				}
			$sx .= canvas_close();
			$sx = bs(bsc($sx,12));
			return($sx);
		}		
	function analysis04()
		{
			/**************** Arquivo */
			$file = 'D:/GoogleDrive/Artigos/2021/AnáliseDeDomínio - Leilah/GRT.txt';
			$gr1x = array();
			$gr1y = array();
			$txt = file_get_contents($file);
			$txt = troca($txt,chr(10),'');
			$xln = explode(chr(13),$txt);

			$docs = array();
			$doxs = array();
			$tab = array();
			for ($r=0;$r < 98;$r++)
				{
					array_push($tab,0);
				}

			for ($r=1;$r < count($xln);$r++)
				{
					$l = $xln[$r];
					$la = explode(';',$l);

					$aut = trim($la[0]);

					if (strlen($aut) > 0)
					{
						for ($q=1;$q < count($la);$q++)
							{
								$doc = trim($la[$q]);

								if (strlen($doc) > 0)
								{
									if (!isset($doxs[$doc]))
										{
											$doxs[$doc] = count($doxs);
										}

									$pos = $doxs[$doc];

									if (strlen($doc) > 0)
									{				
									if (isset($docs[$aut][$pos]))
										{
											$docs[$aut][$pos] = ($docs[$aut][$pos]+1);
										} else {
											$docs[$aut][$pos] = 1;
										}
									}
								}
							}
						}
					}
				$rs = 'Citado;';
				foreach($doxs as $name => $n)
					{
						$rs .= $name.';';
					}
				$rs .= '<br>';
				foreach($docs as $name => $tt)
					{
						$rs .= $name.';';
						$rt = $tab;
						foreach($tt as $at => $ad)
							{
								$rt[$at] = $rt[$at] + $ad;
							}
						for ($q=0;$q < count($rt);$q++)
							{
								$rs .= $rt[$q].';';
							}
						$rs .= chr(13);

					}
				echo '<pre>';
				echo $rs;

	

					exit;
		}

	function analysis03()
		{
			$basepq = array();
			for ($q=1;$q <= 3;$q++)
			{
			$file = 'D:/GoogleDrive/Artigos/2021/AnáliseDeDomínio - Leilah/gr'.$q.'.txt';
			$gr1x = array();
			$gr1y = array();
			$txt = file_get_contents($file);
			$txt = troca($txt,chr(10),'');
			$ln = explode(chr(13),$txt.chr(13));			
			for ($r=0;$r < count($ln);$r++)
				{
					$l = explode(';',$ln[$r]);
					for ($y=1;$y < count($l);$y++)
						{
							$a = ascii($l[$y]);
							$a = trim(troca($a,'. ','.'));
																					
							if (strlen($a) > 4)
							{								
								if (!isset($basep[$a]))
									{
										$basepq[$a] = 1;
									} else {
										$basepq[$a] = $basepq[$a] + 1;
									}								
							}
						}
				}
			}
			$ag = deg2rad(266/ (count($basepq)));
			$nx = 0.36;
			$side = 0;
			$aux = array();
			$auy = array();	

			$sx = '';
			$m = 4;
			$sx .= canvas_open("bc",$m*768,$m*1024);
			/********/
			$xi = $m*400;
			$yi = $m*400;
			ksort($basepq);

			foreach($basepq as $name => $q)
				{
					$rr = 300;
					$x = $m*sin($nx)*$rr+$xi;
					$y = $m*cos($nx)*$rr*1.4+$yi;
					$nome = nbr_author($name,5);
					$nome = troca($nome,'. ','.');
					$nome = trim($nome);
					//$sx .= canvas_rect($x,$y,150,15,'#00ff00');
					if ($side == 0)
					{
						$sx .= canvas_text($x+$m*8,$y+3,$nome,'black',$m*6);
					} else {
						$sx .= canvas_text($x-$m*8,$y+3,$nome,'black',$m*6,'right');
					}
					$aux[$nome] = $x;
					$auy[$nome] = $y;
					
					$sx .= canvas_circle($x,$y,$m*3,'red');
					//$y = $y + 12;
					$nx = $nx + $ag;
					if (($nx > 2.65) and ($side == 0)) { $nx = 3.62; $side = 1; }
				}

			$lns = 0.8;
			/**************** Arquivo */
			$cn = 0;
			$p = array(0,0,$m*400,$m*20*$cn + $m*50,
							$m*400,$m*20*$cn + $m*50,
							$m*440,$m*20*$cn + $m*60);
			$cor = array('','blue','green','red');
			
			for ($q=1;$q <= 3;$q++)
			{			
			$file = 'D:/GoogleDrive/Artigos/2021/AnáliseDeDomínio - Leilah/gr'.$q.'.txt';
			$gr1x = array();
			$gr1y = array();
			$txt = file_get_contents($file);
			$txt = troca($txt,chr(10),'');
			$ln = explode(chr(13),$txt.chr(13));

			for ($r=0;$r < count($ln);$r++)
				{
					$l = explode(';',$ln[$r]);
					$cite = trim($l[0]);
					if (!isset($gri1x[$cite]))
						{
							$idqx = ($q*2);
							$idqy = ($q*2)+1;
							$x = $p[$idqx];
							$y = $p[$idqy]+$cn*20*$m;
							$sx .= canvas_text($x-$m*20,$y,$cite,'black',$m*12).cr();
							$gri1x[$cite] = $x;
							$gri1y[$cite] = $y;							
							$cn++;
						}

					for ($z=1;$z < count($l);$z++)
						{
							$a = trim(nbr_author(trim($l[$z]),5));
							if (strlen($a) > 4)
							{
								$a = ascii($a);
								$a = troca($a,'. ','.');
								$a = trim($a);
								if ((strlen($a) > 0) and (isset($aux[$a])))
									{
											$sx .= canvas_line($gri1x[$cite],$gri1y[$cite],$aux[$a],$auy[$a],$cor[$q],$m*$lns);
											$sx .= canvas_circle($gri1x[$cite],$gri1y[$cite],$m*8,$cor[$q]);
									}
							}
						}
				}	
			}

			$sx .= canvas_close();				
			return $sx;


		}

	function analysis03a()
		{





			foreach($basepq as $id => $name)
				{
					$rr = 300;
					$x = $m*sin($nx)*$rr+$xi;
					$y = $m*cos($nx)*$rr*1.4+$yi;
					$nome = nbr_author($name,5);
					$nome = troca($nome,'. ','.');
					$nome = trim($nome);
					//$sx .= canvas_rect($x,$y,150,15,'#00ff00');
					if ($side == 0)
					{
						$sx .= canvas_text($x+$m*8,$y+3,$nome,'black',$m*6);
					} else {
						$sx .= canvas_text($x-$m*8,$y+3,$nome,'black',$m*6,'right');
					}
					$aux[$nome] = $x;
					$auy[$nome] = $y;
					
					$sx .= canvas_circle($x,$y,$m*3,'red');
					//$y = $y + 12;
					$nx = $nx + $ag;
					if (($nx > 2.65) and ($side == 0)) { $nx = 3.62; $side = 1; }
				}

			$lns = 0.8;
			/**************** Arquivo */
			$file = 'D:/GoogleDrive/Artigos/2021/AnáliseDeDomínio - Leilah/gr1.txt';
			$gr1x = array();
			$gr1y = array();
			$txt = file_get_contents($file);
			$txt = troca($txt,chr(10),'');
			$ln = explode(chr(13),$txt);

			$cn = 0;
			for ($r=0;$r < count($ln);$r++)
				{
					$l = explode(';',$ln[$r]);
					$cite = trim($l[0]);
					if (!isset($gri1x[$cite]))
						{
							$x = $m*400;
							$y = $m*20*$cn + $m*50;							
							$sx .= canvas_text($x-$m*20,$y,$cite,'black',$m*12).cr();
							$gri1x[$cite] = $x;
							$gri1y[$cite] = $y;							
							$cn++;
						}
					for ($z=1;$z < count($l);$z++)
						{
							$a = trim($l[$z]);
							if ((strlen($a) > 0) and (isset($aux[$a])))
								{
										$sx .= canvas_line($gri1x[$cite],$gri1y[$cite],$aux[$a],$auy[$a],'green',$m*$lns);
										$sx .= canvas_circle($gri1x[$cite],$gri1y[$cite],$m*8,'green');
								} else {
									
								}
						}
				}

			/**************** Arquivo */
			$lns = 0.8;
			$file = 'D:/GoogleDrive/Artigos/2021/AnáliseDeDomínio - Leilah/gr2.txt';
			$txt = file_get_contents($file);
			$txt = troca($txt,chr(10),'');
			$ln = explode(chr(13),$txt);

			$cn = 0;
			for ($r=0;$r < count($ln);$r++)
				{
					$l = explode(';',$ln[$r]);
					$cite = trim($l[0]);
					if (!isset($gri1x[$cite]))
						{
							$x = $m*400;
							$y = $m*20*$cn + $m*230;
							$sx .= canvas_text($x-$m*20,$y,$cite,'black',$m*12).cr();
							//$sx .= canvas_circle($x,$y,8,'blue');
							$gri1x[$cite] = $x;
							$gri1y[$cite] = $y;							
							$cn++;
						}
					for ($z=1;$z < count($l);$z++)
						{
							$a = trim($l[$z]);
							if ((strlen($a) > 0) and (isset($aux[$a])))
								{
										$sx .= canvas_line($gri1x[$cite],$gri1y[$cite],$aux[$a],$auy[$a],'blue',$m*$lns);
										$sx .= canvas_circle($gri1x[$cite],$gri1y[$cite],$m*8,'blue');
								} else {
									
								}
						}
				}	

			/**************** Arquivo */
			$lns = 0.3;
			$file = 'D:/GoogleDrive/Artigos/2021/AnáliseDeDomínio - Leilah/gr3.txt';
			$txt = file_get_contents($file);
			$txt = troca($txt,chr(10),'');
			$ln = explode(chr(13),$txt);

			$cn = 0;
			for ($r=0;$r < count($ln);$r++)
				{
					$l = explode(';',$ln[$r]);
					$cite = trim($l[0]);
					if (!isset($gri1x[$cite]))
						{
							$x = $m*440;
							$y = $m*20*$cn + $m*290;
							$sx .= canvas_text($x-$m*20,$y,$cite,'black',$m*12).cr();
							//$sx .= canvas_circle($x,$y,3,'red');
							$gri1x[$cite] = $x;
							$gri1y[$cite] = $y;							
							$cn++;
						}
					for ($z=1;$z < count($l);$z++)
						{
							$a = trim($l[$z]);
							if ((strlen($a) > 0) and (isset($aux[$a])))
								{
										$sx .= canvas_line($gri1x[$cite],$gri1y[$cite],$aux[$a],$auy[$a],'red',$m*$lns);
										$sx .= canvas_circle($gri1x[$cite],$gri1y[$cite],$m*5,'red');
								} else {
									
								}
						}
				}							
			/********/
			$sx .= canvas_close();
			$st = '<h1>teste</h1>';
			$sx = bs(bsc($st.$sx,12));
			return $sx;
		}


	function analysis02()
		{
			$sx = 'OK';
			$file = 'D:/GoogleDrive/Artigos/2021/AnáliseDeDomínio - Leilah/ad.txt';
			$txt = file_get_contents($file);
			$ln = explode(chr(13),$txt);
			$basepq = $this->api_basepq();
			for ($r=0;$r < count($ln);$r++)
				{
					$l = $ln[$r];
					$ll = explode(';',$l);					
					for ($z=3;$z < count($ll);$z++)
						{
							$id = round($ll[$z]);
							if ($id > 0)
							{
								$la = $ll[0].';';
								$authors = $this->authors(round($ll[$z]));
								foreach($authors as $ida => $x)
									{
										if (isset($basepq[$ida]))
											{
												$la .= nbr_author($basepq[$ida],5).';';
											}
									}
								echo $la.'<br>';
							}
						}
				}			
			return(bs(bsc($sx,12)));
		}
	function api_basepq()
		{
			$url = "https://brapci.inf.br/ws/api/?verb=basepq";
			$rlt = file_get_contents($url);
			$rlt = (array)json_decode($rlt);
			$auth = array();
			for ($r=0;$r < count($rlt);$r++)
				{
					if (isset($rlt[$r]))
					{
						$ln = (array)$rlt[$r];
						$id = $ln['bs_rdf_id'];
						$name = $ln['bs_nome'];
						if ($id > 0)
						{
							$auth[$id] = $name;
						}
					}
				}
			return($auth);
		}
	function authors($n)
		{
			$url = 'https://brapci.inf.br/c/'.$n.'/author.json';
			$file = '../_temp/cache/autors_'.$n.'.json';
			if (file_exists($file))
				{
					$url = $file;
				}			
			//$url = 'https://brapci.inf.br/c/'.$n.'/name.ABNT';
			
			$rlt = file_get_contents($url);
			file_put_contents($file,$rlt);
			$authors = (array)json_decode($rlt);
			return($authors);
		}

	function api_metadata($n,$cat='')
		{
			$url = 'https://brapci.inf.br/c/'.$n.'/name.ABNT';
			$file = '../_temp/cache/metadata_'.$n.'.name.ABNT';
			if (file_exists($file))
				{
					$url = $file;
				}						
			
			$rlt = file_get_contents($url);
			file_put_contents($file,$rlt);

			$rsp = array();
			$rlt = troca($rlt,chr(13),'');
			$ln = explode(chr(10),$rlt);
			for ($r=0;$r < count($ln);$r++)
				{
					$pre = substr(trim($ln[$r]),0,4);
					if (substr(trim($ln[$r]),0,2) == $cat)
						{
							$vlr = substr($ln[$r],5,strlen($ln[$r]));
							array_push($rsp,$vlr);
						}
				}
			return($rsp);
		}		

}
