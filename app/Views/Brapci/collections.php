    <div class="col-2">
        <ul class="list-group list-group-horizontal">
            <?php
            $menu = array();
            $menu['Books'] = URL . 'index.php/res/book/';
            $menu['Authoriry'] = URL . 'index.php/res/authoriry/';
            $menu['Benancib'] = URL . 'index.php/res/benancib/';
            $menu['Ontology'] = URL . 'index.php/res/ontology/';
            $menu['Patent'] = URL . 'index.php/res/Patent/';
            if (isset($_SESSION['id']))
            { 
                $menu['Painel'] = URL . 'index.php/res/painel/';
            }
            
            foreach ($menu as $label => $url) {
                echo '<li class="nav-item">' . cr();
                echo '    <a class="list-group-item" aria-current="page" href="' . $url . '">' . lang('brapci.' . $label) . '</a>' . cr();
                echo '</li>' . cr();
            }
            ?>
        </ul>
    </div>

    <div class="tab-content">
  <div class="tab-pane fade show active" id="home" role="tabpanel">...</div>
  <div class="tab-pane fade" id="profile" role="tabpanel">...</div>
  <div class="tab-pane fade" id="messages" role="tabpanel">...</div>
  <div class="tab-pane fade" id="settings" role="tabpanel">...</div>
</div>