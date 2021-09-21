<?php
  function tableview($th,$dt=array())
        {
            $url = base_url($th->path);

            /********** Campos do formulário */
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

            /**************************************************************** TABLE NAME */
            $sx = bsc('<h1>'.$th->table.'</h1>',12);
    
            $st = '<table width="100%" border=1>';
            $st .= '<tr><td>';
            $st .= form_open();
            $st .= '</td><td>';
            $st .= '<select name="search_field" class="form-control">'.cr();
            for ($r=1;$r < count($fl);$r++)
                {
                    $sel = '';
                    if ($r==$search_field) { $sel = 'selected'; }
                    $st .= '<option value="'.$r.'" '.$sel.'>'.msg($fl[$r]).'</option>'.cr();
                }
            $st .= '</select>'.cr();
            $st .= '</td><td>';
            $st .= '<input type="text" class="form-control" name="search" value="'.$search.'">';
            $st .= '</td><td>';
            $st .= '<input type="submit" class="btn btn-primary" name="action" value="FILTER">';
            $st .= form_close();
            $st .= '</td><td align="right">';
            $st .=  $th->pager->links();
            $st .= '</td><td align="right">';
            $st .= $th->pager->GetTotal();
            $st .= '/'.$th->countAllResults();
            $st .= '/'.$th->pager->getPageCount();    
            $st .= '</td>';

            /*********** NEW */
            $st .= '<td align="right">';
            $st .= anchor($url.'/edit/',lang('new'),'class="btn btn-primary"');
            $st .= '</td></tr>';
            $st .= '</table>';

            $sx .= bs($st,12);

            $sx .= '<table class="table" border="1">';
    
            /* Header */
            $heads = $th->allowedFields;
            $sx .= '<tr>';
            $sx .= '<th>#</th>';
            for($h=1;$h < count($heads);$h++)
                {
                    $sx .= '<th>'.lang($heads[$h]).'</th>';
                }            
            $sx .= '</tr>'.cr();
    
            /* Data */
            for ($r=0;$r < count($v);$r++)
                {
                    $line = $v[$r];
                    $sx .= '<tr>';
                    foreach($fl as $field)
                        {
                            $vlr = $line[$field];
                            if (strlen($vlr) == 0) { $vlr = ' '; }
                            $sx .= '<td>'.anchor(($url.'/viewid/'.$line[$fl[0]]),$vlr).'</td>';
                        }   
                    /* Botoes */
                    $sx .= '<td>';
                    $sx .= linked($url.'/edit/'.$line[$fl[0]],'[ed]').'&nbsp;';
                    $sx .= linkdel($url.'/delete/'.$line[$fl[0]],'[x]');
                    $sx .= '</td>';

                    $sx .= '</tr>'.cr();
                }
            $sx .= '</table>';
            $sx .=  $th->pager->links();
            $sx .= bsdivclose();
            $sx .= bsdivclose();
            $sx .= bsdivclose();
            return($sx);    
        }  
?>