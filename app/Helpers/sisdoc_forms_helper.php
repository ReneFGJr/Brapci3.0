<?php
/**
* CodeIgniter Form Helpers
*
* @package     CodeIgniter
* @subpackage  Forms SisDoc
* @category    Helpers
* @author      Rene F. Gabriel Junior <renefgj@gmail.com>
* @link        http://www.sisdoc.com.br/CodIgniter
* @version     v0.21.06.24
*/
//$sx .= form($url,$dt,$this);

function msg($txt)
    {
        global $msg;
        if (isset($msg[$txt]))
            {
                $txt = $msg[$txt];
            }
        return($txt);
    }

    /* Funcao troca */
    function troca($qutf, $qc, $qt) 
    {
        if (!is_array($qc))
        {
            $qc = array($qc);
        }
        if (!is_array($qt))
        {
            $qt = array($qt);
        }        
        return (str_replace($qc, $qt, $qutf));
    }

    function ascii($d)
    {    //$d = strtoupper($d);
        
        /* acentos agudos */
        $d = (str_replace(array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'), array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'), $d));
        
        /* acentos til */
        $d = (str_replace(array('ã', 'õ', 'Ã', 'Õ'), array('a', 'o', 'A', 'O'), $d));
        
        /* acentos cedilha */
        $d = (str_replace(array('ç', 'Ç', 'ñ', 'Ñ'), array('c', 'C', 'n', 'N'), $d));
        
        /* acentos agudo inverso */
        $d = (str_replace(array('à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù'), array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'), $d));
        
        /* acentos agudo cinconflexo */
        $d = (str_replace(array('â', 'ê', 'î', 'ô', 'û', 'Â', 'Ê', 'Î', 'Ô', 'Û'), array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'), $d));
        
        /* trema */
        $d = (str_replace(array('ä', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü'), array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'), $d));
        
        
        /* Especiais */
        $d = (str_replace(array('Å'), array('A'), $d));
        return $d;
    }

    function UpperCase($d) {
        $d = strtoupper($d);
        return $d;
    }    
    
    function UpperCaseSQL($d) {
        $d = ascii($d);
        $d = strtoupper($d);
        return $d;
    }
    
    function LowerCase($term) {
        $d = mb_strtolower($term);
        return ($d);
    }
    
    function LowerCaseSQL($term) {
        $term = ascii($term);
        $term = mb_strtolower($term);    
        return ($term);
    }    

    function nbr_author($xa, $tp) 
    {
        $xa = troca($xa,'JúNIOR','JÚNIOR');
        $xa = utf8_decode($xa);    
        $xa = troca($xa,', ,',',');
    
        if (strpos($xa, ',') > 0) 
        {
            $xb = trim(substr($xa, strpos($xa, ',') + 1, 100));
            $xa = trim(substr($xa, 0, strpos($xa, ',')));
            $xa = trim(trim($xb) . ' ' . $xa);
        }
        $xa = $xa . ' ';
        $xp = array();
        $xx = "";
        for ($qk = 0; $qk < strlen($xa); $qk++)
        {
            if (substr($xa, $qk, 1) == ' ') 
            {
                if (strlen(trim($xx)) > 0) 
                {
                    array_push($xp, trim($xx));
                    $xx = '';
                }
            } else {
                $xx = $xx . substr($xa, $qk, 1);
            }
        }
        
        $xa = "";    
        /////////////////////////////
        $xp1 = "";
        $xp2 = "";
        $er1 = array(utf8_decode('JÚNIOR'),"JUNIOR", "NETTO", "NETO", "SOBRINHO", "FILHO", "JR.", "JR");
        
        ///////////////////////////// SEPARA NOMES
        $xop = 0;
        for ($qk = count($xp) - 1; $qk >= 0; $qk--) 
        {
            $xa = trim($xa . ' - ' . $xp[$qk]);
    
            /* Primeira operação */
            if ($xop == 0) 
            { 
                $xp1 = trim($xp[$qk] . ' ' . $xp1);
                $xop = -1;
            } else { 
                $xp2 = trim($xp[$qk] . ' ' . $xp2);
            }  
            /* Checa os nomes */ 
            if ($xop == -1) 
                {
                $xop = 1;
                for ($kr = 0; $kr < count($er1); $kr++) 
                {
                    if (trim(UpperCaseSQL($xp[$qk])) == trim($er1[$kr])) 
                    {
                        $xop = 0;
                    }
                }
            }
        }
        
        ////////// 1 e 2
        $xp2a = strtolower($xp2);
        $xa = trim(trim($xp2) . ' ' . trim($xp1));
        if (($tp == 1) or ($tp == 2)) 
        {
            if ($tp == 1) { $xp1 = UpperCase($xp1);
            }
            $xa = trim(trim($xp1) . ', ' . trim($xp2));
            if ($tp == 2) 
            { 
                $xa = UpperCaseSQL(trim(trim($xp1) . ', ' . trim($xp2)));
            }
        }
        if (($tp == 3) or ($tp == 4)) {
            if ($tp == 4) { $xa = UpperCaseSQL($xa);
            }
        }
        
        if (($tp >= 5) or ($tp <= 6)) 
        {
            $xp2a = str_word_count(lowerCaseSQL($xp2), 1);
            $xp2 = '';
            for ($k = 0; $k < count($xp2a); $k++) 
            {
                if ($xp2a[$k] == 'do') 
                { 
                    $xp2a[$k] = '';
                }
                if ($xp2a[$k] == 'da') 
                { 
                    $xp2a[$k] = '';
                }
                if ($xp2a[$k] == 'de') 
                { 
                    $xp2a[$k] = '';
                }
                if (strlen($xp2a[$k]) > 0) 
                { 
                    $xp2 = $xp2 . substr($xp2a[$k], 0, 1) . '. ';
                }
            }
            $xp2 = trim($xp2);
            if ($tp == 6) 
            { 
                $xa = UpperCaseSQL(trim(trim($xp2) . ' ' . trim($xp1)));
            }
            if ($tp == 5) 
            { 
                $xa = UpperCaseSQL(trim(trim($xp1) . ', ' . trim($xp2)));
            }
        }
        
        ////////////////////////////////////////////////////////////////////////////////////
        if (($tp == 7) or ($tp == 8)) 
        {
            $mai = 1;
            $xa = strtolower($xa);
            for ($r = 0; $r < strlen($xa); $r++) 
            {
                if ($mai == 1) 
                { 
                    $xa = substr($xa, 0, $r) . UpperCase(substr($xa, $r, 1)) . substr($xa, $r + 1, strlen($xa));
                    $mai = 0;
                } else {
                    if ((substr($xa, $r, 1) == ' ') or (substr($xa, $r, 1) == '-')) 
                    { 
                        $mai = 1;
                    }
                }
            }
            $xa = troca($xa, 'De ', 'de ');
            $xa = troca($xa, 'Da ', 'da ');
            $xa = troca($xa, 'Do ', 'do ');
            $xa = troca($xa, ' O ', ' o ');
            $xa = troca($xa, ' E ', ' e ');
            $xa = troca($xa, ' Em ', ' em ');
            $xa = troca($xa, ' Para ', ' para ');
            $xa = troca($xa, ' The ', ' the ');
            $xa = troca($xa, ' And ', ' and ');
            $xa = troca($xa, ' Of ', ' of ');
            $xa = troca($xa, ' To ', ' to ');
            $xa = troca($xa, ' For ', ' for ');
        }
        
        ////////////////////////////////////////////////////////////////////////////////////
        if (($tp == 17) or ($tp == 18)) 
        {
            $mai = 1;
            $xa = substr($xa,0,1).strtolower(substr($xa,1,strlen($xa)));
        }  
        $xa = utf8_encode($xa);  
        return $xa;
    }    

function tableview($th)
    {
        $url = base_url($_SERVER['REQUEST_URI']);
        if (strpos($url,'/view')) { $url = substr($url,0,strpos($url,'/view')); }
        $fl = $th->allowedFields;
        if (isset($_POST['action']))
            {
                $search = $_POST["search"];
                $search_field = $_POST["search_field"];
                $th->like($fl[1],$search);
                $_SESSION['srch_'] = $search;
                $_SESSION['srch_tp'] = $search_field;
            } else {
                //
                $search = '';
                $search_field = 0;
                if (isset($_SESSION['srch_']))
                    {
                        $search = $_SESSION['srch_'];
                        $search_field = $_SESSION['srch_tp'];        
                    }
                if (strlen($search) > 0)
                    {
                        $th->like($fl[$search_field],$search);
                    }
            }
        
        $th->orderBy($fl[$search_field]);
	    $v = $th->paginate(15);
        $p = $th->pager;

        $sx = bs(2);
        $sx .= anchor($url.'/edit/','novo','class="btn btn-primary"');
        $sx .= bsdivclose(1);
        $sx .= bscol(10);
        $sx .= '<table width="100%">';
        $sx .= '<tr><td>';
        $sx .= form_open();
        $sx .= '</td><td>';
        $sx .= '<select name="search_field" class="form-control">'.cr();
        for ($r=0;$r < count($fl);$r++)
            {
                $sel = '';
                if ($r==$search_field) { $sel = 'selected'; }
                $sx .= '<option value="'.$r.'" '.$sel.'>'.msg($fl[$r]).'</option>'.cr();
            }
        $sx .= '</select>'.cr();
        $sx .= '</td><td>';
        $sx .= '<input type="text" class="form-control" name="search" value="'.$search.'">';
        $sx .= '</td><td>';
        $sx .= '<input type="submit" class="btn btn-primary" name="action" value="FILTER">';
        $sx .= form_close();
        $sx .= '</td><td align="right">';
        $sx .=  $th->pager->links();
        $sx .= '</td><td align="right">';
        $sx .= $th->pager->GetTotal();
        $sx .= '/'.$th->countAllResults();
        $sx .= '/'.$th->pager->getPageCount();
        /*
        echo '<pre>';
        print_r($th->pager);
        echo '</pre>';
        */

        $sx .= '</td></tr>';
        $sx .= '</table>';
        $sx .= bsdivclose(1);
        //echo '<pre>';
        //print_r($th);
        //echo '</pre>';
        $sx .= '<table class="table">';

        /* Header */
        $sx .= '<tr>';
        $sx .= '</tr>';

        /* Data */
        for ($r=0;$r < count($v);$r++)
            {
                $line = $v[$r];
                $sx .= '<tr>';
                foreach($fl as $field)
                    {
                        $vlr = $line[$field];
                        if (strlen($vlr) == 0) { $vlr = ' '; }
                        $sx .= '<td>'.anchor(base_url($url.'/viewid/'.$line[$fl[0]]),$vlr).'</td>';
                    }   
                $sx .= '<td>'.linked($url.'/edit/'.$line[$fl[0]],'[ed]').'</td>';
                $sx .= '<td>'.linkdel($url.'/delete/'.$line[$fl[0]],'[x]').'</td>';
                $sx .= '</tr>'.cr();
            }
        $sx .= '</table>';
        $sx .=  $th->pager->links();
        $sx .= bsdivclose();
        $sx .= bsdivclose();
        $sx .= bsdivclose();
        return($sx);    
    }

function form($th)
    {
        $sx = '';

        $fl = $th->allowedFields;
        $tp = $th->typeFields;
        $id = round($th->id);
        $url = base_url($_SERVER['REQUEST_URI']);

        $dt = $_POST;
        if ((count($dt) == 0) and ($id > 0))
            {
                $dt = $th->find($id);
            } else {
                if (($th->save($dt)) and (count($dt) > 0))
                    {
                        $url = substr($url,0,strpos($url,'/edit'));
                        $sx .= bsmessage('SALVO');
                        //$sx = redireciona($url);
                        $sx .= anchor($url,'Voltar',['class'=>'btn btn-primary']);
                        return($sx);
                    }
            }
        
        
        $sx .= form_open($url).cr();

        $sx .= '<table class="table">';
        $sx .= '<tr><th width="20%">'.msg('label').'</th>
                    <th width="80%">'.msg('values').'</th></tr>';
        $submit = false;

        /* Formulario */
        for ($r=0;$r < count($fl);$r++)
            {
                $fld = $fl[$r];
                $typ = $tp[$r];
                $vlr = '';
                if (isset($dt[$fld])) { $vlr = $dt[$fld]; }
                $sx .= form_fields($typ,$fld,$vlr);
            }

        /***************************************** BOTAO SUBMIT */
        if (!$submit)
            {
                $sx .= '<tr><td>'.bt_submit().' | '.bt_cancel($url).'</td></tr>'.cr();
            }

        /************************************** FIM DO FORMULARIO */
        $sx .= '</table>'.cr();

        $sx .= form_close().cr();

        return($sx);

    }
/* checa e cria diretorio */
function dircheck($dir) {
    $ok = 0;
    if (is_dir($dir)) { $ok = 1;
    } else {
        mkdir($dir);
        $rlt = fopen($dir . '/index.php', 'w');
        fwrite($rlt, 'acesso restrito');
        fclose($rlt);
    }
    return ($ok);
}

function redireciona($url='/main/service',$time=2)
    {
        $sx = redirect()->to($url);
        return ($sx);
    }

function linkdel($url)
    {
        global $js_del;
        $sx = '';
        $sx .= anchor($url,'&nbsp;X&nbsp;',['class'=>'btn-primary small','onclick'=>'return confirma();','style'=>'border: 1px solid #00000; border-radius: 5px;']);
        if ($js_del == '')
            {
                $sx .= '
                <script>
                function confirma()
                    {
                        if (!confirm("Excluir registro?"))
                            {
                                return false;
                            }
                    }
                </script>';
                $js_del = true;
            }
        return($sx);
    }

function linked($url)
    {
        $sx = anchor($url,'&nbsp;ed&nbsp;',['class'=>'btn-warning small','style'=>'border: 1px solid #00000; border-radius: 5px;']);
        return($sx);        
    }

function form_del($th)
    {
        $sx = '';
        $id = $th->id;

        if ($th->delete($id))
            {
                $sx .= bsmessage('Item excluído',1);
            } else {
                $sx .= bsmessage('Erro de exclusão',2);
            }

        $url = base_url($_SERVER['REQUEST_URI']);
        $url = substr($url,0,strpos($url,'/delete'));
        $sx .= anchor($url,'Voltar',['class'=>'btn btn-danger']);
        $sx = redireciona($url);
        return($sx);
    }

function cr()
    {
        return (chr(13).chr(10));
    }



function form_fields($typ,$fld,$vlr)
    {
        $td = '<td>'; $tdc = '</td>';
        /*********** Mandatory */
        $sub = 0;
        $mandatory = false;        
        $sx = '<tr>';
        $t = substr($typ,0,2);
        switch($t)
                {
                    case 'up':
                        $sx .= '<input type="hidden" id="'.$fld.'" name="'.$fld.'" value="'.date("YmdHi").'">';
                        break;
                    case 'hi':
                        $sx .= '<input type="hidden" id="'.$fld.'" name="'.$fld.'" value="'.$vlr.'">';
                        break;
                    case 'tx':
                        $rows = 5;
                        $sx .= $td.($fld).$tdc;
                        $sx .= $td;
                        $sx .= '<textarea id="'.$fld.'" rows="'.$rows.'" name="'.$fld.'" class="form-control">'.$vlr.'</textarea>';
                        $sx .= $tdc;
                        break;
                    case 'sn':
                        $sx .= $td.($fld).$tdc;
                        $sx .= $td;
                        $op = array(1,0);
                        $opc = array(msg('YES'),msg('NO'));
                        $sg = '<select id="'.$fld.'" name="'.$fld.'" value="'.$vlr.'" class="form-control">'.cr();
                        for ($r=0;$r < count($op);$r++)
                            {
                                $sel = '';
                                $sg .= '<option value="'.$op[$r].'" '.$sel.'>'.$opc[$r].'</option>'.cr();
                            }
                        $sg .= '</select>'.cr();
                        $sx .= $sg;
                        $sx .= $tdc;
                        break;
                    case 'st':
                        $sx .= $td.($fld).$tdc;
                        $sx .= $td;
                        $sx .= '<input type="text" id="'.$fld.'" name="'.$fld.'" value="'.$vlr.'" class="form-control">';
                        $sx .= $tdc;
                        break;
                    default:
                        $sx .= 'OPS - '.$t;
                }
            $sx .= '</tr>';
        return($sx);
    }

    function bt_cancel($url)
        {
            if (strpos($url,'/edit')) { $url = substr($url,0,strpos($url,'/edit')); }
            $sx = anchor($url,msg('return'),['class'=>'btn btn-outline-danger']);
            return $sx;
        }

    function bt_submit($t='save')
        {
            $sx = '<input type="submit" value="'.$t.'" class="btn btn-primary">';        
            return($sx);
        }
?>