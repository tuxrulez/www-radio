<?php include('header.php'); ?>

<div class="site-center">
    <div class="site-header">
    </div>
    <div class="site-body">
      <?php include('menu.php'); ?>
        <div class="cell">
          <div class="col width-2of6">
            <img class="banner"src="<?php print $baseUrl; ?>/img/site/img_home.png" />
          </div>
          <div class="col width-fill">
            <div class="texo-home">
              <p>Olá, o EXTRA disponibilizou para você essa nova e tecnológica ferramenta com o objetivo de agilizar a comunicação e promoção de produtos nas lojas da rede.</p>

              <p>Com ela você pode anunciar avisos e ofertas com linguagem promocional quando você desejar de maneira simples e rápida.</p>

              <p>Escolha o setor, o produto e o preço desejado. Depois é só programar para que toque quando e quantas vezes você quiser.</p>

              <p>Este sistema está acoplado ao sistema de Radio da MEGAMIDIA nossa parceira.</p>
              
              <p>Se tiver dúvidas, lembre-se de clicar no sinal de ajuda (?) ao lado de cada item.</p>

              <p>Vamos começar? Clique em um dos botões abaixo para escolher entre criar uma oferta ou um aviso:</p>            
            </div>
            <div class="botoes">
              <a class="button icon-button" href="<?php print $baseUrl; ?>/oferta"><span class="icon icon-plus"></span>Criar Oferta</a>

              <a class="button icon-button" href="<?php print $baseUrl; ?>/aviso"><span class="icon icon-plus"></span>Criar Aviso</a>
            </div>
          </div>

          
      </div>
    </div>
    <?php include('footer-contato.php'); ?>
</div>

<?php include('footer.php'); ?>