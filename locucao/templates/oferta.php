<?php include('header.php'); ?>
<?php
/*
echo "<pre>";
print_r($oferta);
echo "</pre>";
*/
?>
<div class="site-center">
    <div class="site-header">
    </div>
    <div class="site-body">
      <?php include('menu.php'); ?>
      <div class="cell">
          <form class="oferta form-cadastro" method="post" action="<?php print $baseUrl; ?>/oferta">
            <input type="hidden" name="id" value="<?php print $data['oferta']->id; ?>">
              <div class="col">
                
                <div class="col width-1of2">
                    <div class="cell caixa">
                      <label for="titulo" class="label-titulo"><span class="numeral">1</span>Nomeie sua oferta<span class="color-red"> *</span> <a href="#" class="ajuda tooltip" title="Aqui você especifica qual será o nome da sua oferta, para poder visualizá-la mais rapidamente na aba programação"><img alt="icone" src="<?php print $baseUrl; ?>/img/site/garyblogicon_011.png"></a></label>
                      <input value="<?php print $data['oferta']->titulo; ?>" type="text" data-error-message="Título é obrigatório" data-required="true" placeholder="Título" id="titulo" name="titulo" class="parsley-validated text">
                    </div>
                </div>

                <div class="col width-fill">
                  <div class="cell caixa">
                    <label for="zz_locucao_abertura" class="label-titulo"><span class="numeral">2</span>Saudação inicial da oferta<span class="color-red"> *</span> <a href="#" class="ajuda tooltip" title="Escolha uma das saudações para que suas ofertas tenham uma chamada inicial mais vendedora."><img alt="icone" src="<?php print $baseUrl; ?>/img/site/garyblogicon_011.png"></a></label>
                    <select id="zz_locucao_abertura" name="zz_locucao_abertura" class="select2" data-placeholder="Selecione uma abertura">
                      <option value="" label="nenhum"></option>
                      <?php
                      $abertura = isset($oferta->sequencia->zz_locucao_abertura)? $oferta->sequencia->zz_locucao_abertura->nome: false;
                      ?>
                      <?php foreach ($data['arquivos']->abertura as $arquivo): ?>
                      <option value="<?php print $arquivo->arquivo; ?>" <?php if($abertura!==false && $abertura==$arquivo->nome) print 'selected';?>><?php print ucfirst(preg_replace("/^[a-z]{3}\s/", "", $arquivo->nome)); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col">
                
                <div class="cell caixa">
                    <label class="label-titulo"><span class="numeral">3</span>Produto(s)<span class="color-red"> *</span> <a href="#" class="ajuda tooltip" title="Escolha dentre as várias opões. Primeiro selecione a categoria do produto desejado e depois selecione o produto. Se preferir, digite o nome do produto na barra de busca."><img alt="icone" src="<?php print $baseUrl; ?>/img/site/garyblogicon_011.png"></a></label>

                  <script type="text/javascript">
                    var produtos = <?php print json_encode($data['arquivos']->produtos); ?>;
                  </script>
                  <div id="produtos" class="col bottom-10">
                    <?php
                    $de_por = array();
                    $de = array();
                    $por = array();


                    foreach ($arquivos->depor as $value) 
                    {
                        if($value->nome!="de" && $value->nome!="por")
                        {
                          $value->nome = "de X por Y ".$value->nome;
                          $de_por[] = $value;
                        }
                        elseif ($value->nome=="por") {
                          $value->nome = "de X por Y";
                          $por[] = $value;
                        }
                        elseif ($value->nome=="de") {
                          $de[] = $value;
                        }
                    }

                    if(count($de)>0 && count($por)>0) //valida se existem os arquivos de e por, caso contrario nao mostra nada
                    {
                      $de_por = array_merge($por, $de_por);
                    }
                    else
                    {
                      $de_por = array();
                    }
                    ?>

                    <?php foreach ($oferta->sequencia->produtos as $key => $produto): ?>

                    <?php
                    $locucao_produto = isset($produto->zz_locucao_produto)? $produto->zz_locucao_produto: false;

                    if($locucao_produto!==false)
                    {
                      $genero = $locucao_produto->genero;
                      $genero = str_replace('zz_locucao_produto_', '', $genero);
                    }

                    ?>
                    <div class="produto">
                      <div class="bottom-10">
                        <div class="col categoria width-1of4">

                            <label for="zz_locucao_categoria-<?php print $key; ?>">Selecione a categoria</label>
                            <select id="zz_locucao_categoria-<?php print $key; ?>" name="zz_locucao_categoria-<?php print $key; ?>" class="select2 categoria" data-placeholder="Categoria" data-produtoId="d<?php print $key; ?>">
                              <option value="">Todas as categorias</option>
                              <?php foreach ($data['arquivos']->produtos as $categoria): ?>
                              <option value="<?php print $categoria->nome; ?>" <?php if(isset($genero) && $genero==$categoria->nome) print 'selected';?>><?php print ucfirst($categoria->nome); ?></option>
                              <?php endforeach; ?>
                            </select>

                        </div>

                        <div class="col nome width-3of4">
                          <div class="left-10">
                            <label for="zz_locucao_produto-<?php print $key; ?>">Selecione o produto desejado</label>
                            
                            <select id="zz_locucao_produto-<?php print $key; ?>" name="zz_locucao_produto[<?php print $key; ?>]" class="select2 produtos" data-placeholder="Selecione um produto">
                              <option value="" label="nenhum"></option>
                              <?php
                              $locucao_produto = isset($produto->zz_locucao_produto)? $produto->zz_locucao_produto->nome: false;

                              $arquivosTmp = array();

                              foreach ($data['arquivos']->produtos as $categoria)
                              {
                                if($locucao_produto===false || (isset($genero) && $genero==$categoria->nome))
                                {
                                  foreach ($categoria->arquivos as $arquivo)
                                  {
                                    $arquivosTmp[] = $arquivo;
                                  }
                                }
                              }

                              usort($arquivosTmp, function($a, $b) {
                                  return $a->arquivo > $b->arquivo;
                              });

                              ?>
                              <?php //foreach ($data['arquivos']->produtos as $categoria): ?>
                                <?php //if($locucao_produto===false || (isset($genero) && $genero==$categoria->nome)): ?>
                                  <?php foreach ($arquivosTmp as $arquivo): ?>
                                  <option value="<?php print str_replace('zz_locucao_produto_', '', $arquivo->genero).'/'.$arquivo->arquivo; ?>" <?php if($locucao_produto!==false && $locucao_produto==$arquivo->nome) print 'selected';?>><?php print ucfirst($arquivo->nome); ?></option>
                                  <?php endforeach; ?>
                                <?php //endif; ?>
                              <?php //endforeach; ?>
                            </select>
                          
                          </div>
                        </div>
                      </div>

                      <div class="col preco">

                        <?php
                        

                        $locucao_por = isset($produto->zz_locucao_por)? $produto->zz_locucao_por->arquivo: false;
                        $locucao_deporextra = isset($produto->zz_locucao_deporextra)? $produto->zz_locucao_deporextra->arquivo: false;

                        if($locucao_deporextra === false)
                        {
                          $locucao_deporextra = $locucao_por;
                        }
                        //print($locucao_de);
                        ?>
                        <div class="col width-1of4 de-por">

                            <label for="zz_locucao_de_por-<?php print $key; ?>">De Por</label>
                            <select id="zz_locucao_de_por-<?php print $key; ?>" class="select2 de-por">
                              <option value="">Não</option>
                              <?php foreach ($de_por as $arquivo): ?>
                              <option value="<?php print $arquivo->arquivo; ?>" <?php if($locucao_por!==false && $locucao_por==$arquivo->arquivo) print 'selected';?>><?php print $arquivo->nome; ?></option>
                              <?php endforeach; ?>
                            </select>

                        </div>
                        <input type="hidden" id="zz_locucao_de-<?php print $key; ?>" name="zz_locucao_de-<?php print $key; ?>" value="" data-valor="<?php if(count($de)>0){print $de[0]->arquivo;} ?>" class="de"> 

                        <?php
                        $parcelas = array();

                        foreach ($arquivos->parcelamento as $value) 
                        {
                            $temp = explode(' ', $value->nome);

                            if(is_numeric($temp[0]) && count($temp)>1)
                            {
                              $parcelas[$temp[0]] = $value;
                            }
                        }

                        ksort($parcelas);
                        ?>


                        <div class="col width-1of6 parcelamento">
                          <div class="left-10">
                            <label for="zz_locucao_parcelamento-<?php print $key; ?>">Parcelas</label>
                            <select id="zz_locucao_parcelamento-<?php print $key; ?>" name="zz_locucao_parcelamento-<?php print $key; ?>" class="select2">
                              <option value="">1x de</option>
                              <?php
                              $locucao_parcelamento = isset($produto->zz_locucao_parcelamento)? $produto->zz_locucao_parcelamento->nome: false;
                              ?>
                              <?php foreach ($parcelas as $arquivo): ?>
                              <option value="<?php print $arquivo->arquivo; ?>" <?php if($locucao_parcelamento!==false && $locucao_parcelamento==$arquivo->nome) print 'selected';?>><?php print str_replace(' vezes', 'x', $arquivo->nome); ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <?php
                        $preco_real = array();
                        $preco_centavos = array();

                        foreach ($arquivos->precos as $value) 
                        {
                            $temp = explode(' ', $value->nome);

                            if(is_numeric($temp[0]) && count($temp)>1)
                            {
                                if(stristr($temp[1], 'rea')!==false)
                                {
                                    $preco_real[$temp[0]] = $value;
                                }
                                else if(stristr($value->arquivo, 'centavos.')!==false)
                                {
                                    $preco_centavos[$temp[0]] = $value;
                                }
                                else if(stristr($value->arquivo, 'centavo.')!==false)
                                {
                                    $preco_centavos[$temp[0]] = $value;
                                }
                            }
                        }

                        ksort($preco_real);
                        ksort($preco_centavos);
                        ?>

                        <div class="col width-1of4 real">
                          <div class="left-10">
                            <label for="preco-real-<?php print $key; ?>">Preço</label>
                            <select id="preco-real-<?php print $key; ?>" name="preco-real-<?php print $key; ?>" class="select2">
                              <option value="">Real</option>
                              <?php
                              $real = isset($produto->zz_locucao_preco_real)? $produto->zz_locucao_preco_real->nome: false;
                              ?>
                              <?php foreach ($preco_real as $arquivo): ?>
                              <option value="<?php print $arquivo->arquivo; ?>" <?php if($real!==false && $real==$arquivo->nome) print 'selected';?>><?php print $arquivo->nome; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="col width-1of6 centavos">
                          <div class="left-10">
                            <label for="preco-centavos-<?php print $key; ?>">Centavos</label>
                            <select id="preco-centavos-<?php print $key; ?>" name="preco-centavos-<?php print $key; ?>" class="select2">
                              <option value="">Centavos</option>
                              <?php
                              $centavos = isset($produto->zz_locucao_preco_centavos)? $produto->zz_locucao_preco_centavos->nome: false;
                              ?>
                              <?php foreach ($preco_centavos as $arquivo): ?>
                              <option value="<?php print $arquivo->arquivo; ?>" <?php if($centavos!==false && $centavos==$arquivo->nome) print 'selected';?>><?php print $arquivo->nome; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col row-de-por" style="display: none;">
                        <input type="hidden" id="zz_locucao_por-<?php print $key; ?>" name="zz_locucao_por-<?php print $key; ?>" value="" data-valor="<?php if(count($por)>0){print $por[0]->arquivo;} ?>" class="por"> 
                        <div class="col width-1of4 de-por">
                          <span style="display: block; text-align: right; font-size: 14px; padding-top: 27px;">Por </span>
                        </div>
                        <div class="col width-1of6 parcelamento">
                          <div class="left-10">
                            <label for="zz_locucao_parcelamento-<?php print $key; ?>-de_por">Parcelas</label>
                            <select id="zz_locucao_parcelamento-<?php print $key; ?>-de_por" name="zz_locucao_parcelamento-<?php print $key; ?>-de_por" class="select2">
                              <option value="">1x de</option>
                              <?php
                              $locucao_parcelamento = isset($produto->zz_locucao_parcelamento_depor)? $produto->zz_locucao_parcelamento_depor->nome: false;
                              ?>
                              <?php foreach ($parcelas as $arquivo): ?>
                              <option value="<?php print $arquivo->arquivo; ?>" <?php if($locucao_parcelamento!==false && $locucao_parcelamento==$arquivo->nome) print 'selected';?>><?php print str_replace(' vezes', 'x', $arquivo->nome); ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="col width-1of4 real">
                          <div class="left-10">
                            <label for="preco-real-<?php print $key; ?>-de_por">Preço</label>
                            <select id="preco-real-<?php print $key; ?>-de_por" name="preco-real-<?php print $key; ?>-de_por" class="select2">
                              <option value="">Real</option>
                              <?php
                              $real = isset($produto->zz_locucao_preco_real_depor)? $produto->zz_locucao_preco_real_depor->nome: false;
                              ?>
                              <?php foreach ($preco_real as $arquivo): ?>
                              <option value="<?php print $arquivo->arquivo; ?>" <?php if($real!==false && $real==$arquivo->nome) print 'selected';?>><?php print $arquivo->nome; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="col width-1of6 centavos">
                          <div class="left-10">
                            <label for="preco-centavos-<?php print $key; ?>-de_por">Centavos</label>
                            <select id="preco-centavos-<?php print $key; ?>-de_por" name="preco-centavos-<?php print $key; ?>-de_por" class="select2">
                              <option value="">Centavos</option>
                              <?php
                              $centavos = isset($produto->zz_locucao_preco_centavos_depor)? $produto->zz_locucao_preco_centavos_depor->nome: false;
                              ?>
                              <?php foreach ($preco_centavos as $arquivo): ?>
                              <option value="<?php print $arquivo->arquivo; ?>" <?php if($centavos!==false && $centavos==$arquivo->nome) print 'selected';?>><?php print $arquivo->nome; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <input type="hidden" id="zz_locucao_deporextra-<?php print $key; ?>" name="zz_locucao_deporextra-<?php print $key; ?>" value="" class="extra"> 
                      </div>

                      <a class="remover" href="#">Remover<span class="icon icon-32 icon-remove-sign"></span></a>

                    </div>
                    <?php endforeach; ?>
                    <!--<div class="col width-1of3">
                      <select id="unidade-medida">
                        <option>Unidade</option>
                        <option></option>
                        <option></option>
                      </select>
                      <label for="unidade-medida">Unidade medida</label>
                    </div>-->
                  </div>
                

                  <div class="col">
                    <a href="#" id="adicionar_produto"><span class="icon icon-plus"></span>Adicionar mais produtos</a>
                  </div>

                </div>

              </div>


              <div class="col">
                <div class="col width-1of2">
                  <div class="cell locucao-repetir caixa">
                    <label class="label-titulo"><span class="numeral">4</span>Repetir da oferta<span class="color-red"> *</span> <a href="#" class="ajuda tooltip" title="Marcando essa opção, a locução do produto irá se repetir para dar mais ênfase na promoção."><img alt="icone" src="<?php print $baseUrl; ?>/img/site/garyblogicon_011.png"></a></label>
                    <div class="col">
                      <div class="col width-1of4 radio-repetir">
                        <input type="radio" value="0" name="repetir" id="repetirNao" class="radio" <?php if($data['oferta']->repetir==0) print 'checked'; ?>>
                        <label for="repetirNao">Não</label>
                        <input type="radio" value="1" name="repetir" id="repetirSim" class="radio" <?php if($data['oferta']->repetir==1) print 'checked'; ?>>
                        <label for="repetirSim">Sim</label>
                      </div>
                      <div class="col width-3of4">
                        <div class="cell zz_locucao_repeticao<?php if($data['oferta']->repetir==0) print ' hide'; ?>">
                          <select id="zz_locucao_repeticao" name="zz_locucao_repeticao" class="select2" data-placeholder="Repetição">
                            <option value="" label="nenhum"></option>
                            <?php
                            $repeticao = isset($oferta->sequencia->zz_locucao_repeticao)? $oferta->sequencia->zz_locucao_repeticao->nome: false;
                            ?>
                            <?php foreach ($data['arquivos']->repeticao as $arquivo): ?>
                            <option value="<?php print $arquivo->arquivo; ?>" <?php if($repeticao!==false && $repeticao==$arquivo->nome && $data['oferta']->repetir==1) print 'selected';?>><?php print ucfirst($arquivo->nome); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col width-1of2">
                  <div class="cell locucao-fechamento caixa">
                      <label for="zz_locucao_fechamento" class="label-titulo"><span class="numeral">5</span>Saudação final<span class="color-red"> *</span> <a href="#" class="ajuda tooltip" title="Escolha entre as opções para que a sua oferta tenha um fechamento."><img alt="icone" src="<?php print $baseUrl; ?>/img/site/garyblogicon_011.png"></a></label>
                      <div class="cell">
                        <select id="zz_locucao_fechamento" name="zz_locucao_fechamento" class="select2" data-placeholder="Fechamento">
                          <option value="" label="nenhum"></option>
                          <?php
                          $fechamento = isset($oferta->sequencia->zz_locucao_fechamento)? $oferta->sequencia->zz_locucao_fechamento->nome: false;
                          ?>
                          <?php foreach ($data['arquivos']->fechamento as $arquivo): ?>
                          <option value="<?php print $arquivo->arquivo; ?>" <?php if($fechamento!==false && $fechamento==$arquivo->nome) print 'selected';?>><?php print ucfirst($arquivo->nome); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                  </div>
                </div>
              </div>


              <div class="col">
                <div class="cell caixa">
                  <h4 class="bottom-10"><span class="numeral">6</span>Teste</h4>
                  <div class="col">
                    <a href="#" class="button testar" ><span class="icon icon-play"></span>Testar Locução</a>
                  </div>
                </div>
              </div>

              
              <div class="col">
                <div class="cell caixa">
                  <h4 class="bottom-10"><span class="numeral">7</span>Programação  <a href="#" class="ajuda tooltip" title="Programe a data inicial e final bem como o horário da sua oferta."><img alt="icone" src="<?php print $baseUrl; ?>/img/site/garyblogicon_011.png"></a></h4>

                  <div class="col programacao bottom-20">
                    <div class="col width-1of6">
                      <label for="dataInicio" class="label-titulo">Data Inicial:</label>
                      <input type="text" id="dataInicio" name="dataInicio" class="calendario" value="<?php print $data['oferta']->data_inicio; ?>" >
                    </div>

                    <div class="col width-1of5">
                      <label for="horaInicio" class="bloco label-titulo">Hora inicial:</label>
                      <select id="horaInicio" name="horaInicio" class="hora">
                        <?php for ($i=0; $i < 24; $i++): ?>
                        <option value="<?php print sprintf('%02d', $i); ?>" <?php if($i==$data['oferta']->hora_inicio) print 'selected';?> ><?php print sprintf('%02d', $i); ?></option>
                        <?php endfor; ?>
                      </select>
                      <span>H</span>
                      <select id="minutoInicio" name="minutoInicio" class="hora">
                        <?php for ($i=0; $i < 60; $i++): ?>
                        <option value="<?php print sprintf('%02d', $i); ?>" <?php if($i==$data['oferta']->minuto_inicio) print 'selected';?> ><?php print sprintf('%02d', $i); ?></option>
                        <?php endfor; ?>
                      </select>
                      <span>M</span>
                    </div>

                    <div class="col width-1of6">
                      <label for="dataFim" class="label-titulo">Data Final:</label>
                      <input type="text" id="dataFim" name="dataFim" class="calendario" value="<?php print $data['oferta']->data_fim; ?>" >
                    </div>


                    <div class="col width-1of5">
                      <label for="horaFim" class="bloco label-titulo">Hora final:</label>
                      <select id="horaFim" name="horaFim" class="hora">
                        <?php for ($i=0; $i < 24; $i++): ?>
                        <option value="<?php print sprintf('%02d', $i); ?>" <?php if($i==$data['oferta']->hora_fim) print 'selected';?> ><?php print sprintf('%02d', $i); ?></option>
                        <?php endfor; ?>
                      </select>
                      <span>H</span>
                      <select id="minutoFim" name="minutoFim" class="hora">
                        <?php for ($i=0; $i < 60; $i++): ?>
                        <option value="<?php print sprintf('%02d', $i); ?>" <?php if($i==$data['oferta']->minuto_fim) print 'selected';?> ><?php print sprintf('%02d', $i); ?></option>
                        <?php endfor; ?>
                      </select>
                      <span>M</span>
                    </div>

                    <div class="col width-fill">
                      <label for="repeticoesHora" class="bloco label-titulo">Repetições por hora: <a href="#" class="ajuda tooltip" title="Escolha quantas vezes quer que a oferta toque por hora. "><img alt="icone" src="<?php print $baseUrl; ?>/img/site/garyblogicon_011.png"></a></label>
                      <select id="repeticoesHora" name="repeticoesHora" class="repeticoes-hora">
                        <?php for ($i=1; $i <= 6; $i++): ?>
                        <option value="<?php print $i; ?>" <?php if($i==$data['oferta']->repeticoes_hora) print 'selected';?> ><?php print $i; ?></option>
                        <?php endfor; ?>
                      </select>
                    </div>

                  </div>


                  <div class="col dias-da-semana bottom-20">
                    <h4>Dias da semana</h4>
                    <input type="checkbox" class="checkbox" id="dia1" name="dias[]" value="1" <?php if(strpos($data['oferta']->dia_semana, '1')!==false) print 'checked'; ?>>
                    <label for="dia1">Domingo</label>
                    <input type="checkbox" class="checkbox" id="dia2" name="dias[]" value="2" <?php if(strpos($data['oferta']->dia_semana, '2')!==false) print 'checked'; ?>>
                    <label for="dia2">Segunda</label>
                    <input type="checkbox" class="checkbox" id="dia3" name="dias[]" value="3" <?php if(strpos($data['oferta']->dia_semana, '3')!==false) print 'checked'; ?>>
                    <label for="dia3">Terça</label>
                    <input type="checkbox" class="checkbox" id="dia4" name="dias[]" value="4" <?php if(strpos($data['oferta']->dia_semana, '4')!==false) print 'checked'; ?>>
                    <label for="dia4">Quarta</label>
                    <input type="checkbox" class="checkbox" id="dia5" name="dias[]" value="5" <?php if(strpos($data['oferta']->dia_semana, '5')!==false) print 'checked'; ?>>
                    <label for="dia5">Quinta</label>
                    <input type="checkbox" class="checkbox" id="dia6" name="dias[]" value="6" <?php if(strpos($data['oferta']->dia_semana, '6')!==false) print 'checked'; ?>>
                    <label for="dia6">Sexta</label>
                    <input type="checkbox" class="checkbox" id="dia7" name="dias[]" value="7" <?php if(strpos($data['oferta']->dia_semana, '7')!==false) print 'checked'; ?>>
                    <label for="dia7">Sabado</label>
                  </div>
                  
                  <?php if(false): ?>
                  <div class="col width-2of6">
                    <h4>Oferta ativa</h4>
                    <input type="radio" class="radio" id="ativo" name="ativo" value="1" <?php if($data['oferta']->ativo == 1)print 'checked';?>>
                    <label for="ativo">Sim</label>
                    <input type="radio" class="radio" id="inativo" name="ativo" value="0" <?php if($data['oferta']->ativo == 0)print 'checked';?>>
                    <label for="inativo">Não</label>
                  </div>
                  <?php endif; ?>

                  <div class="col bottom-20">
                    <button type="submit" class="button background-green"><span class="icon icon-ok"></span>Veicular</button>
                    <a href="<?php print $_SESSION['baseUrl'].'/'; ?><?php if($data['oferta']->id>0) print 'programacao'; ?>" class="button cinza">Cancelar</a>
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