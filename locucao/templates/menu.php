        <?php

        //echo $resourceUri;
        ?>

        <div class="menu">
          <div class="tabs">
              <ul class="nav">
                  <li class="<?php if($resourceUri=='/')print 'active'; ?>"><a href="<?php print $baseUrl; ?>"><span class="esq"></span>Início<span class="dir"></span></a></li>
                  <li class="<?php if(strstr($resourceUri, '/oferta')!==false)print 'active'; ?>"><a href="<?php print $baseUrl.'/oferta'; ?>"><span class="esq"></span>Criar Oferta<span class="dir"></span></a></li>
                  <li class="<?php if(strstr($resourceUri, '/aviso')!==false)print 'active'; ?>"><a href="<?php print $baseUrl; ?>/aviso"><span class="esq"></span>Criar Aviso<span class="dir"></span></a></li>
                  <li class="<?php if(strstr($resourceUri, '/programacao')!==false)print 'active'; ?>"><a href="<?php print $baseUrl; ?>/programacao" ><span class="esq"></span>Programação<span class="dir"></span></a></li>
                  <li class="<?php if(strstr($resourceUri, '/lista')!==false)print 'active'; ?>"><a href="<?php print $baseUrl; ?>/lista" ><span class="esq"></span>Reprodução<span class="dir"></span></a></li>
                  <?php if($_SESSION['usuario']->tipo==0): ?>
                  <li class="<?php if(strstr($resourceUri, '/usuario')!==false)print 'active'; ?>"><a href="<?php print $baseUrl; ?>/usuarios" ><span class="esq"></span>Usuários<span class="dir"></span></a></li>
                  <?php endif; ?>
              </ul>
          </div>
          
          <a href="<?php print $baseUrl; ?>/logout" class="sair">Sair</a>
          <span class="hora-sistema tooltip" title="Essa é a hora no seu sistema. Ela deve ser igual ao horário local."><span class="hora"><?php print date('H:i'); ?></span></span>
        </div>
