<?php

namespace App\Models\AI\NLP;

use CodeIgniter\Model;

class Syllables extends Model
{
	protected $DBGroup              = 'ai';
	protected $table                = 'ai_syllables';
	protected $primaryKey           = 'id_sy';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_sy','sy_syllable','sy_lang'
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

	var $lib = array();

	function syllabes_language($lang)
		{
			$this->select("sy_syllable");
			$dt = $this->where('sy_lang',$lang)->findAll();
			$w = array();
			for ($r=0;$r < count($dt);$r++)
				{
					$wd = trim($dt[$r]['sy_syllable']);
					$w[$wd] = 1;
				}
			return $w;
		}

	function syllables($txt,$lang)
		{
			$tela = '';			
			$txt = troca($txt,array(
							'-','(',')','[',']','&','$','?','"','.','!',
							',','“','”','/','=',':',';','@','$','%','*',
							'0','1','2','3','4','5','6','7','8','9',"'",
							),' ');

			$sy = $this->syllabes_language($lang);

			$txt = mb_strtolower($txt);
			$wd = explode(' ',$txt);

			$tela = h('brapci.result',2);
			$tela .= '<ul>';
			for($r=0;$r < count($wd);$r++)
				{
					if (strlen(trim($wd[$r])) > 0)
						{
							$link = '<a href="'.base_url(PATH.'ai/nlp/syllable/?dd1='.$wd[$r].'&dd2='.$lang).'" target="_new">';
							$linka = '</a>';
							$tela .= '<li>'.$link.$wd[$r].$linka.' => '.$this->syllabe($wd[$r],$lang). '</li>';
						}
					
				}
			$tela .= '</ul>';
			return $tela;
		}


	function syllabe($w,$lang='en')
		{
			$lib = $this->syllabes_language($lang);
			$wo = $w;
			$w = mb_strtolower($w);
			$w = trim('*'.ascii($w));
			$wd = '';
			$sep = '';
			if ($lang=='pt-BR')
			{
				$w = troca($w,'ai','a*i');
				$w = troca($w,'au','a*u');

				$w = troca($w,'ea','e*a');
				$w = troca($w,'eu','e*u');

				$w = troca($w,'io','i*o');
				$w = troca($w,'ie','i*e');			
				$w = troca($w,'ia','i*a');
				$w = troca($w,'iu','i*u');

				$w = troca($w,'oi','o*i');
				$w = troca($w,'ou','o*u');

				$w = troca($w,'ua','u*a');
				
				$w = troca($w,'ss','s*s');
				$w = troca($w,'rr','r*r');
				$w = troca($w,'xc','x*c');
				$w = troca($w,'xs','x*s');
				$w = troca($w,'sc','s*c');			

				//if ($w == '*sao') { $w = '*sa*o'; }
				if ($w == '*ao') { $w = '*a*o'; }
				if ($w == '*oi') { $w = '*o*i'; }
			}

			while(strlen($w) > 0)
				{
					$check = '';
					for ($r=(strlen($w)-1);$r >= 0;$r--)
						{
							$l = substr($w,$r,1);
							$wd = $l.$wd;
							if (isset($lib[$wd]))
								{
									$check = $wd;
								}
						}
					if ($check != '')
						{
							if (strlen($sep) > 0)
								{
									$sep = $check.'-'.$sep;
								} else {
									$sep = $check;
								}
							$wd = '';
							$w = substr($w,0,strlen($w)-strlen($check));
							$check = '';							
						} else {
							if (substr($wd,0,1) == '*')
								{
									if (strlen($sep) > 0)
										{
											$sep = $wd.'-'.$sep;
										} else {
											$sep = $wd;
										}
										$w = '';
										break;
								} else {
									echo "ERRO: ".$wo.'==>'.$wd;
									exit;
								}						
						}
					if ($w=='*') { break; }
				}

			$tela = $sep;
			$tela = troca($tela,'*','');
			
			return $tela;
		}		


	/*****************************************************************************************/
	function syllable($txt,$lang)
		{
			$rx = $this->auto_learn_en($txt);
			$rx = troca($rx,'&nbsp;',' ');
			//$rx = strip_tags($rx);
			$fr = 'into syllables:';
			$id = strpos($rx,$fr);

			$tela = '';
			
			if ($id > 0)
				{
					$rx = trim(substr($rx,$id+strlen($fr),strlen($rx)));
					$fr = '<span class="Answer_Red">';
					$rx = troca($rx,$fr,'');
					$tela .= '<br>Fase I ='.strlen($rx);
					
					$rx = substr($rx,0,strpos($rx,'</span>'));					
					$tela .= '<br>Fase II ='.strlen($rx);
					//$tela .= '<textarea class="form-control" rows=8>'.$rx.'</textarea>';
					$rx = mb_strtolower($rx);
					$syl = explode('-',$rx);
					for ($r=0;$r < count($syl);$r++)
						{
							$syll = $syl[$r];
							$this->syllabe_check($syll,$lang);
						}
				} else {
					$rx = 'OPS';
				}
			return $tela.'<hr>'.$rx;
		}
	function auto_learn_en($txt)
		{
			$url = 'https://www.howmanysyllables.com/words/'.$txt;
			$txt = file_get_contents($url);			
			return $txt;
		}
	function learn_pt_br()
		{
			//https://www.separarensilabas.com/index-pt.php
			$lang = 'pt-PR';
			$w = array(
			'*a',
			
			'ba','be','bi','bo','bu',
			'bas','bra','bril',
			'bar','ber','bir','bor','bur',
			'bol',
			'ca','ce','ci','co','cu',
			'cao','cer','cia','cog','com',
			'cla','cle','cli','clo','clu',
			'can','cen','cin','con','cun',
			'cau','ceu','ciu','cou','cuu',
			'cres',
			'cha','che','chi','cho','chu',

			'*e',
			'*es','em',
			'en',

			'fa','fe','fi','fo','fu',
			'fal','fel','fil','fol','ful',
			'fei',
			'far','fer','fir','for','fur',
			'fas','fes','fis','fos','fus',
			'faz','fez','fiz','foz','fuz',

			

			'*i',
			'in','ins',
			'is',

			'ja','je','ji','jo','ju',
			'jar','jer','jir','jor','jur',
			'jan','jen','jin','jon','jun',
			
			'*o',
			'*os',
			'pa','pe','pi','po','pu',			
			'pan','pas','pes','pis','pos','pus',
			'pneu',

			'sao','sil',
			'sag','seg','sig','sog','sug',
			'*sas','*s*s',
			'trump',
			'trans',
			'*u',
			'*um',
			'i',
			'*a*in',
			'a*is',
			'*u',
			);

			$n = array(
				'$r','$d','$s','$l',
				'b$','b$s','br$','br$d','b$r','b$l','br$l','b$n','bh$','br$n','b$m','bl$',
				'c$','cr$','c$s*','c$*u','$*u','co$s','co*$s',
				'd$','d$r','d$*','d$n','d$s*','dr$s','d$s','d$l','d$m',
				
				'c$l','c$m','c$r','c$r*','c$*',	'c$s',			
				'h$','h$s',
				'fl$x',
				'g$ns', 'g$n', 'g$m','g$','gu$','gu*$','gu$i','gr$','g$r','gr$s*','g$s',
				'k$',
				
				'l$i','l$','l$n','l$r','l$s','l$z','lh$','lh$m','lh$n',
				
				'n$','n$s','n$l','nh$','n$n','n$m','n$r','nh$u','n$s','n$u',
				'$m','m$s','m$s*','m$r','m$n','m$u','mi$','m$o','m$*is',

				'p$n','p$r','pr$','pl$n','pl$s','pl$','p$*','pr$n','ps$','pc$',

				'qu*$n','qu$n','qu$',
				'r$n','r$*','r$s','ri$','r$','r$t','r$m',
				's$s','s$','s$r','s$m','s$n','*s$r','*s$l',	's$l','s$r','s$gn','s$*',

				't$','t$r','tr$','tr$s','t$s','t$z','t$*','t$u','t$m','t$n','t$o','t$ch','t$l',

				'v$d','v$l','v$m','v$r','v$n','vr$s','vr$','v$s','v$z','v$',
				'z$','z$r','z$s','r$z',
				'x$m','x$n','x$','x$s'
				);
			for ($r=0;$r < count($n);$r++)
				{
					$nn = $this->variations($n[$r]);
					$w = array_merge($w,$nn);
				}
			for ($r=0;$r < count($w);$r++)
				{
					$this->syllabe_check($w[$r],$lang);
				}
			return '';
		}
	function variations($t)
		{
			$a = array('a','e','i','o','u');
			$d = array();
			for ($r=0;$r < count($a);$r++)
				{
					$tt = troca($t,'$',$a[$r]);
					array_push($d,$tt);
				}
			return($d);
		}

	function syllabe_check($s,$lang)
		{
			$s = mb_strtolower($s);
			$s = ascii($s);
			$lang = trim($lang);
			$tela = '';
			$data['sy_syllable'] = $s;
			$data['sy_lang'] = $lang;

			$this->where('sy_syllable',$s);
			$this->where('sy_lang',$lang);

			$dt = $this->findAll();

			if (count($dt) == 0)
				{
					$this->insert($data);
					$tela .= 'Inserido '.$s;
				}			
			return $tela;

			//$this->append($data);
		}

}
