<?php include('header.php'); ?>
<?php
/*
echo "<pre>";
print_r($arquivos);
echo "</pre>";
*/
?>
<div class="site-center">
    <div class="site-header">
    </div>
    <div class="site-body">
      <?php include('menu.php'); ?>
      <div class="cell">
          <form class="aviso form-cadastro" method="post" action="<?php print $baseUrl; ?>/aviso">
            <input type="hidden" name="id" value="<?php print $data['aviso']->id; ?>">
              <div class="col">
                
                <div class="col width-1of2">
                  <div class="cell caixa">

                    <label for="titulo" class="label-titulo"><span class="numeral">1</span>Nomeie seu aviso<span class="color-red"> *</span> <a href="#" class="ajuda tooltip" title="Aqui você especifica qual será o nome do seu aviso, para poder visualizá-lo mais rapidamente na aba “programação”"><img src="<?php print $baseUrl; ?>/img/site/garyblogicon_011.png"></a></label>
                      <input value="<?php print $data['aviso']->titulo; ?>" type="text" data-error-message="Título é obrigatório" data-required="true" placeholder="Título" id="titulo" name="titulo" class="parsley-validated text">

                  </div>
                </div>

                <div class="col width-1of2">

                  <div class="cell caixa">

                      <label for="zz_locucao_avisos" class="label-titulo"><span class="numeral">2</span>Aviso<span class="color-red"> *</span> <a href="#" class="ajuda tooltip" title="Escolha dentre as várias opões de avisos e selecione o desejado."><img src="<?php print $baseUrl; ?>/img/site/garyblogicon_011.png"></a></label>
                      <select id="zz_locucao_avisos" name="zz_locucao_avisos" class="select2" data-placeholder="Selecione um aviso">
                        <option value=""></option>
                        <?php
                        $abertura = isset($aviso->sequencia->zz_locucao_avisos)? $aviso->sequencia->zz_locucao_avisos->nome: false;
                        ?>
                        <?php foreach ($data['arquivos']->avisos as $arquivo): ?>
                        <option value="<?php print $arquivo->arquivo; ?>" <?php if($abertura!==false && $abertura==$arquivo->nome) print 'selected';?>><?php print ucfirst($arquivo->nome); ?></option>
                        <?php endforeach; ?>
                      </select>

                  </div>
                </div>
              </div>

              <div class="col">

                <div class="cell caixa">
                  <h4 class="bottom-10"><span class="numeral">3</span>Teste</h4>
                  <div class="col">
                    <a href="#" class="button testar" ><span class="icon icon-play"></span>Testar Aviso</a>
                  </div>
                </div>

              </div>

              <div class="col">
                <div class="cell caixa">
                  <h4 class="bottom-10"><span class="numeral">4</span>Programação  <a href="#" class="ajuda tooltip" title="Programe a data inicial e final bem como o horário da sua oferta."><img alt="icone" src="<?php print $baseUrl; ?>/img/site/garyblogicon_011.png"></a></h4>

                  <div class="col programacao bottom-20">
                    <div class="col width-1of6">
                      <label for="dataInicio" class="label-titulo">Data Inicial:</label>
                      <input type="text" id="dataInicio" name="dataInicio" class="calendario" value="<?php print $data['aviso']->data_inicio; ?>" >
                    </div>

                    <div class="col width-1of5">
                      <label for="horaInicio" class="bloco label-titulo">Hora inicial:</label>
                      <select id="horaInicio" name="horaInicio" class="hora">
                        <?php for ($i=0; $i < 24; $i++): ?>
                        <option value="<?php print sprintf('%02d', $i); ?>" <?php if($i==$data['aviso']->hora_inicio) print 'selected';?> ><?php print sprintf('%02d', $i); ?></option>
                        <?php endfor; ?>
                      </select>
                      <span>H</span>
                      <select id="minutoInicio" name="minutoInicio" class="hora">
                        <?php for ($i=0; $i < 60; $i++): ?>
                        <option value="<?php print sprintf('%02d', $i); ?>" <?php if($i==$data['aviso']->minuto_inicio) print 'selected';?> ><?php print sprintf('%02d', $i); ?></option>
                        <?php endfor; ?>
                      </select>
                      <span>M</span>
                    </div>

                    <div class="col width-1of6">
                      <label for="dataFim" class="label-titulo">Data Final:</label>
                      <input type="text" id="dataFim" name="dataFim" class="calendario" value="<?php print $data['aviso']->data_fim; ?>" >
                    </div>


                    <div class="col width-1of5">
                      <label for="horaFim" class="bloco label-titulo">Hora final:</label>
                      <select id="horaFim" name="horaFim" class="hora">
                        <?php for ($i=0; $i < 24; $i++): ?>
                        <option value="<?php print sprintf('%02d', $i); ?>" <?php if($i==$data['aviso']->hora_fim) print 'selected';?> ><?php print sprintf('%02d', $i); ?></option>
                        <?php endfor; ?>
                      </select>
                      <span>H</span>
                      <select id="minutoFim" name="minutoFim" class="hora">
                        <?php for ($i=0; $i < 60; $i++): ?>
                        <option value="<?php print sprintf('%02d', $i); ?>" <?php if($i==$data['aviso']->minuto_fim) print 'selected';?> ><?php print sprintf('%02d', $i); ?></option>
                        <?php endfor; ?>
                      </select>
                      <span>M</span>
                    </div>

                    <div class="col width-fill">
                      <label for="repeticoesHora" class="bloco label-titulo">Repetições por hora: <a href="#" class="ajuda tooltip" title="Escolha quantas vezes quer que a oferta toque por hora. "><img alt="icone" src="<?php print $baseUrl; ?>/img/site/garyblogicon_011.png"></a></label>
                      <select id="repeticoesHora" name="repeticoesHora" class="repeticoes-hora">
                        <?php for ($i=1; $i <= 6; $i++): ?>
                        <option value="<?php print $i; ?>" <?php if($i==$data['aviso']->repeticoes_hora) print 'selected';?> ><?php print $i; ?></option>
                        <?php endfor; ?>
                      </select>
                    </div>

                  </div>


                  <div class="col dias-da-semana bottom-20">
                    <h4>Dias da semana</h4>
                    <input type="checkbox" class="checkbox" id="dia1" name="dias[]" value="1" <?php if(strpos($data['aviso']->dia_semana, '1')!==false) print 'checked'; ?>>
                    <label for="dia1">Domingo</label>
                    <input type="checkbox" class="checkbox" id="dia2" name="dias[]" value="2" <?php if(strpos($data['aviso']->dia_semana, '2')!==false) print 'checked'; ?>>
                    <label for="dia2">Segunda</label>
                    <input type="checkbox" class="checkbox" id="dia3" name="dias[]" value="3" <?php if(strpos($data['aviso']->dia_semana, '3')!==false) print 'checked'; ?>>
                    <label for="dia3">Terça</label>
                    <input type="checkbox" class="checkbox" id="dia4" name="dias[]" value="4" <?php if(strpos($data['aviso']->dia_semana, '4')!==false) print 'checked'; ?>>
                    <label for="dia4">Quarta</label>
                    <input type="checkbox" class="checkbox" id="dia5" name="dias[]" value="5" <?php if(strpos($data['aviso']->dia_semana, '5')!==false) print 'checked'; ?>>
                    <label for="dia5">Quinta</label>
                    <input type="checkbox" class="checkbox" id="dia6" name="dias[]" value="6" <?php if(strpos($data['aviso']->dia_semana, '6')!==false) print 'checked'; ?>>
                    <label for="dia6">Sexta</label>
                    <input type="checkbox" class="checkbox" id="dia7" name="dias[]" value="7" <?php if(strpos($data['aviso']->dia_semana, '7')!==false) print 'checked'; ?>>
                    <label for="dia7">Sabado</label>
                  </div>
                  
                  <?php if(false): ?>
                  <div class="col width-2of6">
                    <h4>Oferta ativa</h4>
                    <input type="radio" class="radio" id="ativo" name="ativo" value="1" <?php if($data['aviso']->ativo == 1)print 'checked';?>>
                    <label for="ativo">Sim</label>
                    <input type="radio" class="radio" id="inativo" name="ativo" value="0" <?php if($data['aviso']->ativo == 0)print 'checked';?>>
                    <label for="inativo">Não</label>
                  </div>
                  <?php endif; ?>

                  <div class="col bottom-20">
                    <button type="submit" class="button background-green"><span class="icon icon-ok"></span>Veicular</button>
                    <a href="<?php print $_SESSION['baseUrl'].'/'; ?>" class="button">Cancelar</a>
                  </div>
                </div>

              </div>
          </form>
      </div>
    </div>
    <?php include('footer-contato.php'); ?>
</div>

<audio id="player" style="display:none;" ></audio>

<?php if(count($erros)>0): ?>
<div id="dialog-message" title="Mensagem" style="display:none;">
  <h4>
    Corrija os seguintes erros antes de salvar a oferta.
  </h4>
  <ul>
  <?php foreach ($erros as $key => $value): ?>
  <li>
    <?php print($value['mensagem']); ?>
  </li>
  <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<?php include('footer.php'); ?>