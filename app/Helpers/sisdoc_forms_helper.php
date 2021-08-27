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

    function get($var)
        {
            $vlr = '';
            if (isset($_GET[$var]))
                {
                    $vlr = $_GET[$var];
                }
            if (isset($_POST[$var]))
                {
                    $vlr = $_POST[$var];
                }
            //$vlr = str_replace($vlr,"'","~");
            return $vlr;
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


function stodbr($dt)
    {
        $rst = substr($dt,6,2).'/'.substr($dt,4,2).'/'.substr($dt,0,4);
        return $rst;
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
                    case 'dt':
                        $sx .= $td.($fld).$tdc;
                        $sx .= $td;
                        $sx .= '<input type="text" id="'.$fld.'" name="'.$fld.'" value="'.$vlr.'" class="form-control" style="width:200px;">';
                        $sx .= $tdc;
                        break;       
                    case 'ur':
                        $sx .= $td.($fld).$tdc;
                        $sx .= $td;
                        $sx .= '<input type="text" id="'.$fld.'" name="'.$fld.'" value="'.$vlr.'" class="form-control">';
                        $sx .= $tdc;
                        break;                         
                    case 'yr':
                        $sx .= $td.($fld).$tdc;
                        $sx .= $td;
                        $op = array();
                        $opc = array();
                        for ($r=date("Y")+1;$r > 1900;$r--)
                            {
                                array_push($op,$r);
                                array_push($opc,$r);
                            }
                        $sg = '<select id="'.$fld.'" name="'.$fld.'" value="'.$vlr.'" class="form-control" style="width: 200px;">'.cr();
                        for ($r=0;$r < count($op);$r++)
                            {
                                $sel = '';
                                $sg .= '<option value="'.$op[$r].'" '.$sel.'>'.$opc[$r].'</option>'.cr();
                            }
                        $sg .= '</select>'.cr();
                        $sx .= $sg;
                        $sx .= $tdc;
                        break;        
                    case 'pl':
                        $sx .= $td.($fld).$tdc;
                        $sx .= $td;
                        //$dt = $this->db->query("select * from oa_country where ct_lang = 'pt-BR'").findAll();

                        $sql = "SELECT * FROM some_table WHERE ct_lang = :ct_lang:";
                        $rlt = $this->db->query($sql, ['ct_lang' => 'pt-BR']);
                        print_r($dt);
                        $op = array();
                        $opc = array();
                        for ($r=date("Y")+1;$r > 1900;$r--)
                            {
                                array_push($op,$r);
                                array_push($opc,$r);
                            }
                        $sg = '<select id="'.$fld.'" name="'.$fld.'" value="'.$vlr.'" class="form-control" style="width: 200px;">'.cr();
                        for ($r=0;$r < count($op);$r++)
                            {
                                $sel = '';
                                $sg .= '<option value="'.$op[$r].'" '.$sel.'>'.$opc[$r].'</option>'.cr();
                            }
                        $sg .= '</select>'.cr();
                        $sx .= $sg;
                        $sx .= $tdc;
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
                    case 'op':
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
                        echo '==>'.$t.'<br>';
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