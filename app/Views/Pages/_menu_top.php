<footer class="footer pt-3  ">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start active">
                    <a href="<?php echo base_url(PATH.'/');?>" class="font-weight-bold"><?php echo lang('brapci.main');?></a>
                </div>
            </div>
            <div class="col-lg-6">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                    <?php
                    foreach($menu as $link=>$label)
                    {
                        echo '                    
                            <li class="nav-item">
                                <a href="'.base_url(PATH.'/'.$link).'" class="nav-link text-muted">'.
                                lang($label).'</a>
                            </li>
                        ';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</footer>