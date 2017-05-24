<?php include('header.php'); ?>

<div class="site-center">
    <div class="site-header">
    </div>
    <div class="site-body">
      <?php include('menu.php'); ?>
        <div class="cell">
          <a href="<?php print $baseUrl; ?>/aviso" class="button icon-button"><span class="icon icon-plus"></span>Criar Aviso</a>
        </div>
        <div class="cell">
          <h2>Avisos</h2>
          <table class="horizontal-border locucoes">
              <thead>
                <tr>
                    <th>Título</th>
                    <th nowrap="nowrap" style="width: 10%;">Data Início</th>
                    <th nowrap="nowrap" style="width: 10%;">Data Fim</th>
                    <th nowrap="nowrap" style="width: 10%;">Hora Início</th>
                    <th nowrap="nowrap" style="width: 10%;">Hora Fim</th>
                    <th nowrap="nowrap" style="width: 11%;"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data['lista'] as $linha) : ?>
                <tr>
                    <td><?php print $linha->titulo; ?></td>
                    <td><?php print date("d/m/Y", strtotime($linha->data_inicio)); ?></td>
                    <td><?php print date("d/m/Y", strtotime($linha->data_fim)); ?></td>
                    <td><?php print sprintf('%02d', floor($linha->hora_inicio/3600))."h".sprintf('%02d', floor(($linha->hora_inicio%3600)/60))."m"; ?></td>
                    <td><?php print sprintf('%02d', floor($linha->hora_fim/3600))."h".sprintf('%02d', floor(($linha->hora_fim%3600)/60))."m"; ?></td>
                    <td><a href="<?php print $baseUrl; ?>/aviso/<?php print $linha->id; ?>">Editar</a> | <a class="deletar-locucao" href="<?php print $baseUrl; ?>/aviso/<?php print $linha->id; ?>/delete">Deletar</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
      </div>
    </div>
    <?php include('footer-contato.php'); ?>
</div>

<?php include('footer.php'); ?>