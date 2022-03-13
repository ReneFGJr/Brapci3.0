<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a href="<?php echo URL.'index.php/res/';?>"><img src="https://brapci.inf.br/img/logo/logo-brapci.png" id="logo" class="logo-lg col-lg-0 navbar-brand" style="height: 50px;"></a>
    <?php 
    if (isset($collection))
        {
            echo '<span class="subtitle h3">'.$collection.'</span>'.cr();
        }
    ?>    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php 
        echo '<li class="nav-item">'.cr();
        echo '    <a class="nav-link active" aria-current="page" href="'.URL.'">';
        echo bsicone('homefill');
        echo '</a>';
        echo '</li>'.cr();

        $menu = array();
        $menu['Home'] = URL.'index.php/res/';
        $menu['Indexes'] = URL.'index.php/res/indexes/';
        $menu['About'] = URL.'index.php/res/about/';
        $menu['Help'] = URL.'index.php/res/help/';
        if ((isset($_SESSION['id'])) and ($_SESSION['id'] > 0))
          {
              $menu['Bibliometric'] = URL.'index.php/res/research/';
          }
        
        foreach($menu as $label=>$url) {
                echo '<li class="nav-item">'.cr();
                echo '    <a class="nav-link active" aria-current="page" href="'.$url.'">'.lang('brapci.'.$label).'</a>'.cr();
                echo '</li>'.cr();
        }

        /********************************************************/
        $socials = new \App\Models\Socials();
        echo $socials->nav_user();
        ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid">
    <style>
    /* https://cssgradient.io/ */
    #bars
    {
        background: rgb(9,9,121);
        background: linear-gradient(90deg, rgba(9,9,121,1) 0%, rgba(9,9,121,0.8799894957983193) 14%, rgba(0,212,255,1) 100%);
        height: 8px;
    }
    </style>
    <?php $class = 'col-1 me-1'; ?>
    <div class="row">
        <div class="col-4" id="bars"></div>     
        <div class="col-8 text-end" style="font-size: 8px;">version 0.21.11.23</div>
    </div>
</div>