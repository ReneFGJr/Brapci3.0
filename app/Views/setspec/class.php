<?php
$uri = URL;
$type = lang('rdf.class_name');
?>
<div class="row">
    <div class="col-md-12" style="background-color: #CCC; border-top: 3px solid #333;">
        <span style="font-size: 150%;"><?php echo '<b>'.$type.': '.$c_class.'</b>'; ?></span>
    </div>
    <table width="100%" cellpadding=2>
        <tr>
            <td width="15%" align="right">
                <b>URI:</b>
            </td><td>
                <?php echo $uri;?>
            </td>
        </tr>

        <tr>
            <td width="15%" align="right">
                <b>Prefix:</b>
            </td><td>
                <?php echo lang($prefix_ref);?> (<?php echo anchor($prefix_url);?>)
            </td>
        </tr>        

        <tr>
            <td width="15%" align="right">
                <b>Label:</b>
            </td><td>
                <?php echo lang($c_class);?>
            </td>
        </tr>        
    </table>
    
</div>
prefix_ref