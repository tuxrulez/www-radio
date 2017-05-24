<?php include('header.php'); ?>

<div class="site-center">
    <div class="site-header">
    </div>
    <div class="site-body">
      <?php include('menu.php'); ?>
        <div class="cell margin-h-25">
          <h2 style="display:none;">Programação</h2>
          <table class="locucoes">
              <thead>
                <tr>
                    <th>Título</th>
                    <th nowrap="nowrap" style="width: 10%;">Tipo</th>
                    <th nowrap="nowrap" style="width: 10%;">Data Início</th>
                    <th nowrap="nowrap" style="width: 10%;">Data Fim</th>
                    <th nowrap="nowrap" style="width: 10%;">Hora Início</th>
                    <th nowrap="nowrap" style="width: 10%;">Hora Fim</th>
                    <th nowrap="nowrap" style="width: 15%;"></th>
                </tr>
              </thead>
              <tbody class="border">
                <?php $hj = date('Y-m-d'); ?>
                <?php foreach ($data['lista'] as $linha) : ?>
                <?php
                $corLinha = 'verde';
                if($linha->data_inicio > $hj) $corLinha = 'amarelo';
                else if($linha->data_fim < $hj) $corLinha = 'vermelho';
                ?>
                <tr class="<?php print $corLinha; ?>">
                    <td><?php print $linha->titulo; ?></td>
                    <td class="tipo"><?php print $linha->tipo; ?></td>
                    <td><?php print date("d/m/Y", strtotime($linha->data_inicio)); ?></td>
                    <td><?php print date("d/m/Y", strtotime($linha->data_fim)); ?></td>
                    <td><?php print sprintf('%02d', floor($linha->hora_inicio/3600))."h".sprintf('%02d', floor(($linha->hora_inicio%3600)/60))."m"; ?></td>
                    <td><?php print sprintf('%02d', floor($linha->hora_fim/3600))."h".sprintf('%02d', floor(($linha->hora_fim%3600)/60))."m"; ?></td>
                    <td><a href="<?php print $baseUrl; ?>/<?php print $linha->tipo; ?>/<?php print $linha->id; ?>">Editar</a> | <a class="deletar-locucao" href="<?php print $baseUrl; ?>/<?php print $linha->tipo; ?>/<?php print $linha->id; ?>/delete">Deletar</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
          </table>

          <div class="legenda">
            <span><span class="box amarelo"></span>Locução futura</span>
            <span><span class="box verde"></span>Locução válida para hoje</span>
            <span><span class="box vermelho"></span>Locução que já passou</span>
          </div>

      </div>
    </div>
    <?php include('footer-contato.php'); ?>
</div>

<?php include('footer.php'); ?>