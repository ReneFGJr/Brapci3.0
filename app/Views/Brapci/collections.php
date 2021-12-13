    <div class="col-2">
        <ul class="list-group list-group-horizontal">
            <?php
            $menu = array();
            $menu['Books'] = URL . 'index.php/res/book/';
            $menu['Authoriry'] = URL . 'index.php/res/authoriry/';
            $menu['Benancib'] = URL . 'index.php/res/benancib/';
            $menu['Ontology'] = URL . 'index.php/res/ontology/';
            foreach ($menu as $label => $url) {
                echo '<li class="nav-item">' . cr();
                echo '    <a class="list-group-item" aria-current="page" href="' . $url . '">' . lang('brapci.' . $label) . '</a>' . cr();
                echo '</li>' . cr();
            }
            ?>
        </ul>
    </div>

    <div class="col-2">
    <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Cras justo odio
            <span class="badge badge-primary badge-pill">14</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Dapibus ac facilisis in
            <span class="badge badge-primary badge-pill">2</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Morbi leo risus
            <span class="badge badge-primary badge-pill">1</span>
        </li>
    </ul>
    </div>

    <div class="tab-content">
  <div class="tab-pane fade show active" id="home" role="tabpanel">...</div>
  <div class="tab-pane fade" id="profile" role="tabpanel">...</div>
  <div class="tab-pane fade" id="messages" role="tabpanel">...</div>
  <div class="tab-pane fade" id="settings" role="tabpanel">...</div>
</div>