<?php include('header.php'); ?>

<div class="site-center">
    <div class="site-header">
    </div>
    <div class="site-body">
      <?php include('menu.php'); ?>
        <div class="cell margin-h-25">
          <h2 style="display:none;">Lista de reprodução</h2>
          <table class="horizontal-border">
              <thead>
                <tr>
                    <th>Hora Início</th>
                    <th>Titulo</th>
                    <th>Tipo</th>
                    <th>Tempo</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data['programacao'] as $linha) : ?>
                <tr>
                    <td><?php print sprintf('%02d', floor($linha->inicio/3600))."h".sprintf('%02d', floor(($linha->inicio%3600)/60))."m".sprintf('%02d', $linha->inicio%60)."s"; ?></td>
                    <td><?php print $linha->titulo; ?></td>
                    <td><?php print $linha->tipo; ?></td>
                    <td><?php print $linha->tempo; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
          <!--
          <div class="footer pagination">
              <ul class="nav">
                  <li><a href="#">Prev</a></li>
                  <li><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">Next</a></li>
              </ul>
          </div>
        -->
      </div>
    </div>
    <?php include('footer-contato.php'); ?>
</div>

<?php include('footer.php'); ?>