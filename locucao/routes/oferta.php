<?php

$app->get('/oferta(/:id)', function($id=0) use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }

    $erros = array();

    $arquivos = new StdClass();
    $arquivos->abertura = lista_arquivos('zz_locucao_abertura');
    $arquivos->fechamento = lista_arquivos('zz_locucao_fechamento');

    $generos_produtos = lista_categorias_produto();
    $produtos = array();

    foreach ($generos_produtos as $key => $value) 
    {
        //echo $value->genero;
        $categoria = new StdClass();
        $categoria->genero = $value->genero;
        $categoria->nome = implode('_', array_slice(explode('_', $value->genero), 3));
        $categoria->arquivos = lista_arquivos($value->genero);

        $produtos[] = $categoria;
    }

    $arquivos->produtos = $produtos;
    $arquivos->parcelamento = lista_arquivos('zz_locucao_parcelamento');
    $arquivos->precos = lista_arquivos('zz_locucao_preco');
    $arquivos->repeticao = lista_arquivos('zz_locucao_repeticao');
    $arquivos->depor = lista_arquivos('zz_locucao_depor');
/*
    echo "<pre>";
    print_r($arquivos->depor);
    echo "</pre>";
    exit();
*/
    $oferta = new StdClass();
    $oferta->id = 0;
    $oferta->titulo = '';
    $oferta->ativo = 1;
    $oferta->dia_semana = '1,2,3,4,5,6,7';
    $oferta->repeticoes_hora = 1;
    $oferta->data_inicio = date("d/m/Y");
    $oferta->data_fim = date("d/m/Y");
    $oferta->hora_inicio = '0';
    $oferta->minuto_inicio = '0';
    $oferta->hora_fim = '23';
    $oferta->minuto_fim = '0';
    $oferta->repetir = 0;

    $oferta->sequencia = new StdClass();
    $oferta->sequencia->produtos = array(new StdClass());

    if($id>0)
    {
        $sql_query = "SELECT id, titulo, ativo, dia_semana, post, sequencia, repeticoes_hora, data_inicio, data_fim, hora_inicio, hora_fim FROM locucao WHERE id=:id AND deletado=0 AND tipo='oferta'";
        try {
            $pdo = connect_db_locucao();
            $stmt = $pdo->prepare($sql_query);
            $stmt->bindValue('id', $id);
            
            if($stmt->execute())
            {
                $oferta = $stmt->fetch(PDO::FETCH_OBJ);

                if($oferta==null)
                {
                    $app->redirect($_SESSION['baseUrl'].'/ofertas');
                }

                $post = json_decode($oferta->post);
                $sequencia = new StdClass();

                foreach ($post as $key => $value) 
                {
                    if($key=='zz_locucao_abertura')
                    {
                        $sequencia->{$key} = trata_nome_arquivo($value);
                    }
                    else if(strstr($key, 'zz_locucao_produto')!== false)
                    {
                        $sequencia->{$key} = trata_nome_arquivo($value);
                    }
                    else if(strstr($key, 'zz_locucao_parcelamento')!== false)
                    {
                        $sequencia->{$key} = trata_nome_arquivo($value);
                    }
                    else if(strstr($key, 'preco-real')!== false)
                    {
                        $sequencia->{$key} = trata_nome_arquivo($value);
                    }
                    else if(strstr($key, 'preco-centavos')!== false)
                    {
                        $sequencia->{$key} = trata_nome_arquivo($value);
                    }
                    else if(strstr($key, 'zz_locucao_repeticao')!== false)
                    {
                        $sequencia->{$key} = trata_nome_arquivo($value);
                    }
                    else if(strstr($key, 'zz_locucao_de-')!== false)
                    {
                        $sequencia->{$key} = trata_nome_arquivo($value);
                    }
                    else if(strstr($key, 'zz_locucao_por-')!== false)
                    {
                        $sequencia->{$key} = trata_nome_arquivo($value);
                    }
                    else if(strstr($key, 'zz_locucao_deporextra')!== false)
                    {
                        $sequencia->{$key} = trata_nome_arquivo($value);
                    }
                    else if($key=='zz_locucao_fechamento')
                    {
                        $sequencia->{$key} = trata_nome_arquivo($value);
                    }
                    else if($key=='repetir')
                    {
                        $oferta->repetir = $value;
                    }
                }

                $oferta->sequencia = $sequencia;
                $oferta->sequencia->produtos = array();
/*
                echo "<pre>";
                print_r($oferta);
                echo "</pre>";
                exit();
*/
                foreach ($sequencia as $key => $value) 
                {
                    if(strstr($key, 'zz_locucao_produto')!== false)
                    {
                        $temp = end(split('-', $key));
                        $sufix = is_numeric($temp)? '-'.$temp: '';

                        $grupo = new StdClass();
                        $grupo->zz_locucao_produto = $value;
                        $grupo->zz_locucao_de = isset($oferta->sequencia->{'zz_locucao_de'.$sufix})? $oferta->sequencia->{'zz_locucao_de'.$sufix}: null;
                        $grupo->zz_locucao_parcelamento = isset($oferta->sequencia->{'zz_locucao_parcelamento'.$sufix})? $oferta->sequencia->{'zz_locucao_parcelamento'.$sufix}: null;
                        //$grupo->{'zz_locucao_preco_real'} = $sequencia->{'preco-real'.$sufix};
                        //$grupo->{'zz_locucao_preco_centavos'} = $sequencia->{'preco-centavos'.$sufix};
                        $grupo->{'zz_locucao_preco_real'} = isset($oferta->sequencia->{'preco-real'.$sufix})? $oferta->sequencia->{'preco-real'.$sufix}: null;
                        $grupo->{'zz_locucao_preco_centavos'} = isset($oferta->sequencia->{'preco-centavos'.$sufix})? $oferta->sequencia->{'preco-centavos'.$sufix}: null;

                        if($grupo->{'zz_locucao_preco_centavos'} !== null)
                        {
                            $centavos = $grupo->{'zz_locucao_preco_centavos'};
                            if(strstr($centavos->arquivo, '_sem_e')!==false)
                            {
                                $centavos->arquivo = str_replace('_sem_e', '', $centavos->arquivo);
                                $centavos->nome = str_replace('_sem_e', '', $centavos->nome);

                                $grupo->{'zz_locucao_preco_centavos'} = $centavos;
                            }
                        }

                        $grupo->zz_locucao_por = isset($oferta->sequencia->{'zz_locucao_por'.$sufix})? $oferta->sequencia->{'zz_locucao_por'.$sufix}: null;
                        $grupo->zz_locucao_parcelamento_depor = isset($oferta->sequencia->{'zz_locucao_parcelamento'.$sufix.'-de_por'})? $oferta->sequencia->{'zz_locucao_parcelamento'.$sufix.'-de_por'}: null;
                        $grupo->{'zz_locucao_preco_real_depor'} = isset($oferta->sequencia->{'preco-real'.$sufix.'-de_por'})? $oferta->sequencia->{'preco-real'.$sufix.'-de_por'}: null;
                        $grupo->{'zz_locucao_preco_centavos_depor'} = isset($oferta->sequencia->{'preco-centavos'.$sufix.'-de_por'})? $oferta->sequencia->{'preco-centavos'.$sufix.'-de_por'}: null;

                        if($grupo->{'zz_locucao_preco_centavos_depor'} !== null)
                        {
                            $centavos = $grupo->{'zz_locucao_preco_centavos_depor'};
                            if(strstr($centavos->arquivo, '_sem_e')!==false)
                            {
                                $centavos->arquivo = str_replace('_sem_e', '', $centavos->arquivo);
                                $centavos->nome = str_replace('_sem_e', '', $centavos->nome);

                                $grupo->{'zz_locucao_preco_centavos_depor'} = $centavos;
                            }
                        }

                        $grupo->zz_locucao_deporextra = isset($oferta->sequencia->{'zz_locucao_deporextra'.$sufix})? $oferta->sequencia->{'zz_locucao_deporextra'.$sufix}: null;

                        $oferta->sequencia->produtos[] = $grupo;
                    }
                }
                /*
                echo "<pre>";
                print_r($oferta->sequencia->produtos);
                echo "</pre>";
                exit();
                */
                $oferta->data_inicio = date("d/m/Y", strtotime($oferta->data_inicio));
                $oferta->data_fim = date("d/m/Y", strtotime($oferta->data_fim));

                $minutos = floor($oferta->hora_inicio / 60) % 60;
                $horas = floor($oferta->hora_inicio / 3600);;

                $oferta->minuto_inicio = $minutos;
                $oferta->hora_inicio = $horas;

                $minutos = floor($oferta->hora_fim / 60) % 60;
                $horas = floor($oferta->hora_fim / 3600);

                $oferta->minuto_fim = $minutos;
                $oferta->hora_fim = $horas;
            }
            else
            {
                echo "Erro";
            }
        }
        catch(PDOException $e) 
        {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }    
    }

    
    //echo "<pre>";
    //print_r($arquivos);
    //echo "</pre>";
    //exit();
    
    $app->render('oferta.php', array('oferta'=>$oferta, 'arquivos'=>$arquivos, 'erros'=>$erros));
});

$app->post('/oferta', function() use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }

    $id = $app->request()->post('id');
    $titulo = $app->request()->post('titulo');
    $ativo = 1;
    $repeticoes_hora = $app->request()->post('repeticoesHora');
    $dataInicio = $app->request()->post('dataInicio');
    $dataInicio = str_replace('/', '-', $dataInicio);
    $dataInicio = date("Y-m-d", strtotime($dataInicio));

    $dataFim = $app->request()->post('dataFim');
    $dataFim = str_replace('/', '-', $dataFim);
    $dataFim = date("Y-m-d", strtotime($dataFim));

    $hInicio = $app->request()->post('horaInicio');
    $mInicio = $app->request()->post('minutoInicio');
    $horaInicio = ($hInicio * 3600) + ($mInicio * 60);

    $hFim = $app->request()->post('horaFim');
    $mFim = $app->request()->post('minutoFim');
    $horaFim = ($hFim * 3600) + ($mFim * 60);

    $diasSemana = $app->request()->post('dias')!=null? join($app->request()->post('dias'), ','): '';

    $repetir = $app->request()->post('repetir');

    $sequencia_produtos = array();

    foreach ($app->request->post() as $key => $value) 
    {
        if(strstr($key, 'zz_locucao_produto')!== false)
        {
            $categoria = explode('/', $app->request()->post($key));
            $genero = implode('_', array('zz_locucao_produto', $categoria[0]));

            $p = new StdClass();
            $p->fild = $key;
            $p->genero = $genero;//$genero;
            $p->sequencia = $genero;
            $p->arquivo = end($categoria);
            $sequencia_produtos[] = $p;
        }
        else if (strstr($key, 'zz_locucao_parcelamento')!== false) 
        {
            $r = new StdClass();
            $r->fild = $key;
            $r->genero = 'zz_locucao_parcelamento';
            $r->sequencia = 'zz_locucao_parcelamento';
            $r->arquivo = $app->request()->post($key);
            $sequencia_produtos[] = $r;
        }
        else if (strstr($key, 'preco-real')!== false) 
        {
            $r = new StdClass();
            $r->fild = $key;
            $r->genero = 'zz_locucao_preco';
            $r->sequencia = 'zz_locucao_preco_real';
            $r->arquivo = $app->request()->post($key);
            $sequencia_produtos[] = $r;
        }
        else if (strstr($key, 'preco-centavos')!== false) 
        {
            $r = new StdClass();
            $r->fild = $key;
            $r->genero = 'zz_locucao_preco';
            $r->sequencia = 'zz_locucao_preco_centavos';
            
            $centavos_sem_e = str_replace('.mp3', '_sem_e.mp3', $app->request()->post($key));

            if(end($sequencia_produtos)->sequencia == 'zz_locucao_preco_real' && end($sequencia_produtos)->arquivo === '' && arquivoExiste($r->genero, $centavos_sem_e))//verica se o preço foi definido
            {
                $r->arquivo = $centavos_sem_e;
            }
            else
            {
                $r->arquivo = $app->request()->post($key);
            }

            
            $sequencia_produtos[] = $r;
        }
        else if (strstr($key, 'zz_locucao_de-')!== false) 
        {
            $r = new StdClass();
            $r->fild = $key;
            $r->genero = 'zz_locucao_depor';
            $r->sequencia = 'zz_locucao_depor';
            $r->arquivo = $app->request()->post($key);
            $sequencia_produtos[] = $r;
        }
        else if (strstr($key, 'zz_locucao_por-')!== false) 
        {
            $r = new StdClass();
            $r->fild = $key;
            $r->genero = 'zz_locucao_depor';
            $r->sequencia = 'zz_locucao_depor';
            $r->arquivo = $app->request()->post($key);
            $sequencia_produtos[] = $r;
        }
        else if (strstr($key, 'zz_locucao_deporextra-')!== false) 
        {
            $r = new StdClass();
            $r->fild = $key;
            $r->genero = 'zz_locucao_depor';
            $r->sequencia = 'zz_locucao_depor';
            $r->arquivo = $app->request()->post($key);
            $sequencia_produtos[] = $r;
        }
    }


    $tempo_locucao = 0;

    $sequencia = array();

    if($app->request()->post('zz_locucao_abertura')!="") $sequencia['zz_locucao_abertura'] = get_arquivo($app->request()->post('zz_locucao_abertura'), 'zz_locucao_abertura', true);
    
    $sequencia_produtos_arquivos = array();
    foreach ($sequencia_produtos as $p)
    {
        if($app->request()->post($p->fild)!="") 
        {
            $sequencia_produtos_arquivos[$p->fild] = get_arquivo($p->arquivo, $p->genero, true);
        }
        else
        {
            $sequencia_produtos_arquivos[$p->fild] = null;
        }
    }

    $sequencia = array_merge($sequencia, $sequencia_produtos_arquivos);

    if($repetir == '1')
    {
        if($app->request()->post('zz_locucao_repeticao')!="") $sequencia['zz_locucao_repeticao'] = get_arquivo($app->request()->post('zz_locucao_repeticao'), 'zz_locucao_repeticao', true);

        foreach ($sequencia_produtos_arquivos as $key => $value) 
        {
            $sequencia[$key.'-repetir'] = $value;
        }
    }

    if($app->request()->post('zz_locucao_fechamento')!="") $sequencia['zz_locucao_fechamento'] = get_arquivo($app->request()->post('zz_locucao_fechamento'), 'zz_locucao_fechamento', true);
/*
    echo "<pre>";
    print_r($sequencia);
    //print_r($app->request()->post());
    echo "</pre>";
    exit();    
*/
    //validação
    $erros = array();

    $oferta = new StdClass();
    $oferta->id = $id;
    $oferta->titulo = $titulo;
    $oferta->ativo = $ativo;
    $oferta->dia_semana = $diasSemana;
    $oferta->repeticoes_hora = $repeticoes_hora;
    $oferta->data_inicio = $app->request()->post('dataInicio');
    $oferta->data_fim = $app->request()->post('dataFim');
    $oferta->hora_inicio = $hInicio;
    $oferta->minuto_inicio = $mInicio;
    $oferta->hora_fim = $hFim;
    $oferta->minuto_fim = $mFim;
    $oferta->repetir = $repetir;

    if($titulo=="")                                     $erros[] = array("campo"=>"titulo", "mensagem"=>"Preencha o título.");
    if(!isset($sequencia['zz_locucao_abertura']))       $erros[] = array("campo"=>"zz_locucao_abertura", "mensagem"=>"Selecione uma mensagem de abertura.");

    foreach ($sequencia_produtos as $key => $p)
    {
        
        if(strstr($p->genero, 'zz_locucao_produto')!==false)
        {
            if($app->request()->post($p->fild)=="")
            {
                $erros[] = array("campo"=>"$p->fild", "mensagem"=>"Selecione o produto corretamente.");
            }

            $temp = end(split('-', $p->fild));
            $sufix = is_numeric($temp)? '-'.$temp: '';

            if($app->request()->post('preco-real'.$sufix)=="" && $app->request()->post('preco-centavos'.$sufix)=="" )
            {
                $erros[] = array("campo"=>"preco-real$sufix", "mensagem"=>"Selecione o preço corretamente.");
            }

            if($app->request()->post('zz_locucao_de'.$sufix)!="")
            {
                if($app->request()->post('preco-real'.$sufix."-de_por")=="" && $app->request()->post('preco-centavos'.$sufix."-de_por")=="" )
                {
                    $erros[] = array("campo"=>"zz_locucao_de$sufix", "mensagem"=>"Selecione o preço De Por corretamente.");
                }
            }
        }
    }

    foreach ($sequencia as $key => $arquivo)
    {
        if($arquivo == null) continue;

        $valido = validaArquivo($arquivo->arquivo, $dataInicio, $dataFim, $horaInicio, $horaFim, $oferta->dia_semana);
        if($valido!==true)
        {
            $erros[] = array("campo"=>$arquivo->genero, "mensagem"=>"A narração '".$arquivo->nome."' apresenta os seguintes erros:<br />".implode('<br />', $valido));
        }
    }

    if(!isset($sequencia['zz_locucao_fechamento']))     $erros[] = array("campo"=>"zz_locucao_fechamento", "mensagem"=>"Selecione uma mensagem de fechamento.");
    if($repeticoes_hora<1)                              $erros[] = array("campo"=>"repeticoesHora", "mensagem"=>"O número de repetições precisa se maior que 0");
    if(!validateDate($oferta->data_inicio, 'd/m/Y'))    $erros[] = array("campo"=>"dataInicio", "mensagem"=>"Erro na data inicial");
    if(!validateDate($oferta->data_fim, 'd/m/Y'))       $erros[] = array("campo"=>"dataInicio", "mensagem"=>"Erro na data final");
    if($oferta->data_inicio > $oferta->data_fim)        $erros[] = array("campo"=>"mensagem", "mensagem"=>"Data inicial precisa ser menor ou igual a final");
    if($horaInicio >= $horaFim)                         $erros[] = array("campo"=>"mensagem", "mensagem"=>"Hora inicial é menor do que a final");

    if($repetir=='1')
    {
        if(!isset($sequencia['zz_locucao_repeticao']))       $erros[] = array("campo"=>"zz_locucao_repeticao", "mensagem"=>"Selecione uma mensagem para anteceder a repetição.");
    }
    
    /*
    echo "<pre>";
        print_r($sequencia);
        echo "</pre>";
    exit();
    */
    if(count($erros)>0)
    {
        $arquivos = new StdClass();
        $arquivos->abertura = lista_arquivos('zz_locucao_abertura');
        $arquivos->fechamento = lista_arquivos('zz_locucao_fechamento');
        //$arquivos->produtos = lista_arquivos('zz_locucao_produto');
        $generos_produtos = lista_categorias_produto();
        $produtos = array();

        foreach ($generos_produtos as $key => $value) 
        {
            //echo $value->genero;
            $categoria = new StdClass();
            $categoria->genero = $value->genero;
            $categoria->nome = implode('_', array_slice(explode('_', $value->genero), 3));
            $categoria->arquivos = lista_arquivos($value->genero);

            $produtos[] = $categoria;
        }

        $arquivos->produtos = $produtos;
        $arquivos->parcelamento = lista_arquivos('zz_locucao_parcelamento');
        $arquivos->precos = lista_arquivos('zz_locucao_preco');
        $arquivos->repeticao = lista_arquivos('zz_locucao_repeticao');
        $arquivos->depor = lista_arquivos('zz_locucao_depor');

        //if(count($sequencia)==0) $sequencia[] = new StdClass();
        $oferta->sequencia = new StdClass();
        
        if(count($sequencia)>0)
        {
            $_sequencia = json_encode($sequencia);
            $oferta->sequencia = json_decode($_sequencia);
        }
        
        $oferta->sequencia->produtos = array(new StdClass());
        //$oferta->sequencia->produtos = array(new StdClass());
        //echo "<pre>";
        //print_r($erros); exit();
        //echo "</pre>";
        $sequencia_temp = array(); //array q armazena os p
        foreach ($sequencia_produtos as $p)
        {
            if($app->request()->post($p->fild)!="") 
            {
                $sequencia[$p->fild] = get_arquivo($p->arquivo, $p->genero, true);
            }
            else
            {
                $sequencia[$p->fild] = null;
            }
        }
        
        foreach ($sequencia_produtos_arquivos as $key => $value) 
        {
            
            if(strstr($key, 'zz_locucao_produto')!== false)
            {
                
                $temp = end(split('-', $key));
                $sufix = is_numeric($temp)? '-'.$temp: '';

                if($temp==0) $oferta->sequencia->produtos = array();

                $grupo = new StdClass();
                $grupo->zz_locucao_produto = $value;
                $grupo->zz_locucao_de = isset($oferta->sequencia->{'zz_locucao_de'.$sufix})? $oferta->sequencia->{'zz_locucao_de'.$sufix}: null;
                $grupo->zz_locucao_parcelamento = isset($oferta->sequencia->{'zz_locucao_parcelamento'.$sufix})? $oferta->sequencia->{'zz_locucao_parcelamento'.$sufix}: null;
                $grupo->{'zz_locucao_preco_real'} = isset($oferta->sequencia->{'preco-real'.$sufix})? $oferta->sequencia->{'preco-real'.$sufix}: null;
                $grupo->{'zz_locucao_preco_centavos'} = isset($oferta->sequencia->{'preco-centavos'.$sufix})? $oferta->sequencia->{'preco-centavos'.$sufix}: null;

                if($grupo->{'zz_locucao_preco_centavos'} !== null)
                {
                    $centavos = $grupo->{'zz_locucao_preco_centavos'};
                    if(strstr($centavos->arquivo, '_sem_e')!==false && arquivoExiste($centavos->genero, $centavos->arquivo))
                    {
                        $centavos->arquivo = str_replace('_sem_e', '', $centavos->arquivo);
                        $centavos->nome = str_replace('_sem_e', '', $centavos->nome);

                        //$grupo->{'zz_locucao_preco_centavos'} = $centavos;

                       
                    }
                }

                $grupo->zz_locucao_por = isset($oferta->sequencia->{'zz_locucao_por'.$sufix})? $oferta->sequencia->{'zz_locucao_por'.$sufix}: null;
                $grupo->zz_locucao_parcelamento_depor = isset($oferta->sequencia->{'zz_locucao_parcelamento'.$sufix.'-de_por'})? $oferta->sequencia->{'zz_locucao_parcelamento'.$sufix.'-de_por'}: null;
                $grupo->{'zz_locucao_preco_real_depor'} = isset($oferta->sequencia->{'preco-real'.$sufix.'-de_por'})? $oferta->sequencia->{'preco-real'.$sufix.'-de_por'}: null;
                $grupo->{'zz_locucao_preco_centavos_depor'} = isset($oferta->sequencia->{'preco-centavos'.$sufix.'-de_por'})? $oferta->sequencia->{'preco-centavos'.$sufix.'-de_por'}: null;

                if($grupo->{'zz_locucao_preco_centavos_depor'} !== null)
                {
                    $centavos = $grupo->{'zz_locucao_preco_centavos_depor'};
                    if(strstr($centavos->arquivo, '_sem_e')!==false)
                    {
                        $centavos->arquivo = str_replace('_sem_e', '', $centavos->arquivo);
                        $centavos->nome = str_replace('_sem_e', '', $centavos->nome);

                        $grupo->{'zz_locucao_preco_centavos_depor'} = $centavos;
                    }
                }

                $grupo->zz_locucao_deporextra = isset($oferta->sequencia->{'zz_locucao_deporextra'.$sufix})? $oferta->sequencia->{'zz_locucao_deporextra'.$sufix}: null;


                $oferta->sequencia->produtos[] = $grupo;

            }
        }
        $app->render('oferta.php', array('oferta'=>$oferta, 'arquivos'=>$arquivos, 'erros'=>$erros));
        /*
        echo "<pre>";
        print_r($oferta->sequencia->produtos);
        echo "</pre>";
        exit();
        */
        //echo date("Y-m-d H:i:s", strtotime($dataInicio));exit();
    }
    else
    {

        foreach ($sequencia as $s)
        {
            //echo exec("ffmpeg -i '$arquivo' 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//");

            if($s!=null)$tempo_locucao += $s->tempo;
        }

        $tempo_locucao = ceil($tempo_locucao) + 2;//arrendoda o tempo e adiciona 1 segundo pra colocar no inicio da locução e 1 no final

        if($id==0)
        {
            $sql_query = "INSERT INTO locucao(titulo, post, sequencia, tempo, repeticoes_hora, ativo, dia_semana, data_inicio, data_fim, hora_inicio, hora_fim, data_criacao, data_edicao, tipo) VALUES(:titulo, :post, :sequencia, :tempo, :repeticoes_hora, :ativo, :dia_semana, :data_inicio, :data_fim, :hora_inicio, :hora_fim, NOW(), NOW(), 'oferta')";
        }
        else
        {
            $sql_query = "UPDATE locucao SET titulo=:titulo, post=:post, sequencia=:sequencia, tempo=:tempo, repeticoes_hora=:repeticoes_hora, ativo=:ativo, dia_semana=:dia_semana, data_inicio=:data_inicio, hora_inicio=:hora_inicio, hora_fim=:hora_fim, data_fim=:data_fim, data_edicao=NOW() WHERE id=:id";
        }
        try {
            $pdo = connect_db_locucao();
            //$stmt = $pdo->prepare("INSERT INTO locucao (titulo) VALUES ('titulos')");
            $stmt = $pdo->prepare($sql_query); 
            $stmt->bindValue('titulo',$titulo); 
            $stmt->bindValue('post',json_encode($app->request->post()) ); 
            $stmt->bindValue('sequencia',json_encode($sequencia)); 
            $stmt->bindValue('tempo', $tempo_locucao); 
            $stmt->bindValue('repeticoes_hora', $repeticoes_hora); 
            $stmt->bindValue('ativo', $ativo);
            $stmt->bindValue('dia_semana', $diasSemana);
            $stmt->bindValue('data_inicio', $dataInicio);
            $stmt->bindValue('data_fim', $dataFim);
            $stmt->bindValue('hora_inicio', $horaInicio);
            $stmt->bindValue('hora_fim', $horaFim);
            if($id>0) $stmt->bindValue('id', $id);
            
            if($stmt->execute())
            {
                if($id==0)
                {
                    addLog('locucao', 'c', $pdo->lastInsertId(), showQuery($sql_query, array("titulo"=>$titulo, 
                                                                                            "post"=>json_encode($app->request->post()), 
                                                                                            "sequencia"=>json_encode($sequencia), 
                                                                                            "tempo"=>$tempo_locucao,
                                                                                            "repeticoes_hora"=>$repeticoes_hora,
                                                                                            "ativo"=>$ativo,
                                                                                            "dia_semana"=>$diasSemana,
                                                                                            "data_inicio"=>$dataInicio,
                                                                                            "data_fim"=>$dataFim,
                                                                                            "hora_inicio"=>$horaInicio,
                                                                                            "hora_fim"=>$horaFim) )  );
                }
                else
                {
                    addLog('locucao', 'u', $id, showQuery($sql_query, array("titulo"=>$titulo, 
                                                                            "post"=>json_encode($app->request->post()), 
                                                                            "sequencia"=>json_encode($sequencia), 
                                                                            "tempo"=>$tempo_locucao,
                                                                            "repeticoes_hora"=>$repeticoes_hora,
                                                                            "ativo"=>$ativo,
                                                                            "dia_semana"=>$diasSemana,
                                                                            "data_inicio"=>$dataInicio,
                                                                            "data_fim"=>$dataFim,
                                                                            "hora_inicio"=>$horaInicio,
                                                                            "hora_fim"=>$horaFim,
                                                                            "id"=>$id) )  );
                }                

                $app->redirect($_SESSION['baseUrl'].'/programacao');
            }
            else
            {
                echo "Erro";
            }
        }
        catch(PDOException $e) 
        {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }    
    }

});

$app->post('/oferta/:id/delete', function($id) use ($app)
{
    if (!isset($_SESSION['user-logado']) || !isset($_SESSION['usuario'])) 
    {

        echo "erro";
    }

    if (isset($_SESSION['usuario']) && $_SESSION['usuario']->tipo!=0) 
    {

        echo "erro";
    }

    //$sql_query = "DELETE FROM locucao WHERE id=:id";
    $sql_query = "UPDATE locucao SET deletado=1 WHERE id=:id";
    try {
        $pdo = connect_db_locucao();
        $stmt = $pdo->prepare($sql_query);
        $stmt->bindValue('id', $id);
        
        if($stmt->execute())
        {
            addLog('locucao', 'd', $id, showQuery($sql_query, array("id"=>$id) )  );
            echo "ok";
        }
        else
        {
            echo "Erro";
        }
    }
    catch(PDOException $e) 
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }    

});

$app->get('/ofertas(/)', function() use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }

    $sql_query = "SELECT id, titulo, ativo, data_inicio, data_fim, hora_inicio, hora_fim FROM locucao WHERE deletado=0 AND tipo='oferta' ORDER By data_edicao DESC";
    try {
        $pdo = connect_db_locucao();
        $stmt = $pdo->prepare($sql_query); 
        
        if($stmt->execute())
        {
            $lista = $stmt->fetchAll(PDO::FETCH_OBJ);

            $app->render('ofertas.php', array('lista'=>$lista) );
        }
        else
        {
            echo "Erro";
        }
    }
    catch(PDOException $e) 
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }    
});