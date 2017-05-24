<?php include('header.php'); ?>

<div class="site-center">
    <div class="site-header">
    </div>
    <div class="site-body">
      <?php include('menu.php'); ?>
        <div class="cell margin-h-25">
          <?php if($_SESSION['usuario']->tipo==0): ?>
          <a href="<?php print $baseUrl; ?>/usuario" class="button icon-button"><span class="icon icon-plus"></span>Adicionar Usuário</a>
          <?php endif; ?>
        </div>
        <div class="cell margin-h-25">
          <h2 style="display:none;">Usuários</h2>
          <table class="horizontal-border usuarios">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th nowrap="nowrap" style="width: 15%;"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data['lista'] as $linha) : ?>
                <tr>
                  <td><?php print $linha->nome; ?></td>
                  <td><a href="<?php print $baseUrl; ?>/usuario/<?php print $linha->id; ?>">Editar</a><?php if($linha->id!=$_SESSION['usuario']->id): ?> | <a class="deletar-usuario" href="<?php print $baseUrl; ?>/usuario/<?php print $linha->id; ?>/delete">Deletar</a><?php endif; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
      </div>
    </div>
    <?php include('footer-contato.php'); ?>
</div>

<?php include('footer.php'); ?>