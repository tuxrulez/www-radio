<?php include('header.php'); ?>

<div class="site-center">
    <div class="site-header">
    </div>
    <div class="site-body">
        <?php include('menu.php'); ?>
        <div class="cell">
          <div class="cell">
            <h2><?php $data['usuario']->id==0? print 'Adicionar ': print 'Editar '; ?>Usuário</h2>
            <form method="post" action="<?php print $baseUrl; ?>/usuario"><input type="hidden" name="id" value="<?php print $data['usuario']->id; ?>">
                <div class="col">
                    <div class="col width-1of4">
                        <div class="cell">
                            <label for="nome">Nome<span class="color-red"> *</span></label>
                        </div>
                    </div>
                    <div class="col width-fill">
                        <div class="cell">
                            <input value="<?php print $data['usuario']->nome; ?>" type="text" data-error-message="Nome é obrigatório" data-required="true" placeholder="Nome" id="nome" name="nome" class="text parsley-validated">
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="col width-1of4">
                        <div class="cell">
                            <label for="login">Login<span class="color-red"> *</span></label>
                        </div>
                    </div>
                    <div class="col width-fill">
                        <div class="cell">
                            <input value="<?php print $data['usuario']->login; ?>" type="text" data-error-message="Login é obrigatório" data-required="true" placeholder="Login" id="login" name="login" class="text parsley-validated">
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="col width-1of4">
                        <div class="cell">
                            <label for="login">Senha<span class="color-red"> *</span></label>
                        </div>
                    </div>
                    <div class="col width-fill">
                        <div class="cell">
                            <input type="password" data-error-message="Senha é obrigatório" data-required="true" placeholder="Senha" id="senha" name="senha" class="text parsley-validated">
                        </div>
                    </div>
                </div>
                <?php if(!($_SESSION['usuario']->id == $data['usuario']->id && $_SESSION['usuario']->tipo==0)): ?>
                <div class="col">
                    <div class="col width-1of4">
                        <div class="cell">
                            <label for="tipo">Tipo</label>
                        </div>
                    </div>
                    <div class="col width-fill">
                        <div class="cell">
                          <?php
                          $tipo = $data['usuario']->tipo;
                          ?>
                          <select id="tipo" name="tipo">
                              <option value="1" <?php if($tipo=="1") print 'selected';?>>Usuário</option>
                              <option value="0" <?php if($tipo=="0") print 'selected';?>>Administrador</option>
                          </select>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="col">
                    <div class="col width-1of4">
                    </div>
                    <div class="col width-fill">
                        <div class="cell">
                            <button type="submit" class="button background-green"><span class="icon icon-ok"></span>Salvar</button>
                            <a href="<?php print $_SESSION['baseUrl']; ?>/usuarios" class="button">Cancelar</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </div>
    <?php include('footer-contato.php'); ?>
</div>

<?php include('footer.php'); ?>