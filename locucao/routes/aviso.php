<?php

$app->get('/aviso(/:id)', function($id=0) use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }

    $erros = array();

    $arquivos = new StdClass();
    $arquivos->avisos = lista_arquivos('zz_locucao_avisos');

    $aviso = new StdClass();
    $aviso->id = 0;
    $aviso->titulo = '';
    $aviso->ativo = 1;
    $aviso->dia_semana = '1,2,3,4,5,6,7';
    $aviso->repeticoes_hora = 1;
    $aviso->data_inicio = date("d/m/Y");
    $aviso->data_fim = date("d/m/Y");
    $aviso->hora_inicio = '0';
    $aviso->minuto_inicio = '0';
    $aviso->hora_fim = '23';
    $aviso->minuto_fim = '0';

    $aviso->sequencia = new StdClass();

    if($id>0)
    {
        $sql_query = "SELECT id, titulo, ativo, dia_semana, sequencia, repeticoes_hora, data_inicio, data_fim, hora_inicio, hora_fim FROM locucao WHERE id=:id AND deletado=0 AND tipo='aviso'";
        try {
            $pdo = connect_db_locucao();
            $stmt = $pdo->prepare($sql_query);
            $stmt->bindValue('id', $id);
            
            if($stmt->execute())
            {
                $aviso = $stmt->fetch(PDO::FETCH_OBJ);

                if($aviso==null)
                {
                    $app->redirect($_SESSION['baseUrl'].'/avisos');
                }

                $sequencia = json_decode($aviso->sequencia);

                $aviso->sequencia = json_decode($aviso->sequencia);

                $aviso->data_inicio = date("d/m/Y", strtotime($aviso->data_inicio));
                $aviso->data_fim = date("d/m/Y", strtotime($aviso->data_fim));

                $minutos = floor($aviso->hora_inicio / 60) % 60;
                $horas = floor($aviso->hora_inicio / 3600);;

                $aviso->minuto_inicio = $minutos;
                $aviso->hora_inicio = $horas;

                $minutos = floor($aviso->hora_fim / 60) % 60;
                $horas = floor($aviso->hora_fim / 3600);

                $aviso->minuto_fim = $minutos;
                $aviso->hora_fim = $horas;
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
    //print_r($aviso);
    //echo "</pre>";
    //exit();
    
    $app->render('aviso.php', array('aviso'=>$aviso, 'arquivos'=>$arquivos, 'erros'=>$erros));
});

$app->post('/aviso', function() use ($app)
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

    $tempo_locucao = 0;

    $sequencia = array();

    if($app->request()->post('zz_locucao_avisos')!="") $sequencia['zz_locucao_avisos'] = get_arquivo($app->request()->post('zz_locucao_avisos'), 'zz_locucao_avisos', true);


    //validação
    $erros = array();

    $aviso = new StdClass();
    $aviso->id = $id;
    $aviso->titulo = $titulo;
    $aviso->ativo = $ativo;
    $aviso->dia_semana = $diasSemana;
    $aviso->repeticoes_hora = $repeticoes_hora;
    $aviso->data_inicio = $app->request()->post('dataInicio');
    $aviso->data_fim = $app->request()->post('dataFim');
    $aviso->hora_inicio = $hInicio;
    $aviso->minuto_inicio = $mInicio;
    $aviso->hora_fim = $hFim;
    $aviso->minuto_fim = $mFim;

    if($titulo=="")                                     $erros[] = array("campo"=>"titulo", "mensagem"=>"Preencha o título.");
    if(!isset($sequencia['zz_locucao_avisos']))       $erros[] = array("campo"=>"zz_locucao_avisos", "mensagem"=>"Selecione um aviso.");

    foreach ($sequencia as $key => $arquivo)
    {
        if($arquivo == null) continue;

        $valido = validaArquivo($arquivo->arquivo, $dataInicio, $dataFim, $horaInicio, $horaFim, $aviso->dia_semana);
        if($valido!==true)
        {
            $erros[] = array("campo"=>$arquivo->genero, "mensagem"=>"A narração '".$arquivo->nome."' apresenta os seguintes erros:<br />".implode('<br />', $valido));
        }
    }

    if($repeticoes_hora<1)                              $erros[] = array("campo"=>"repeticoesHora", "mensagem"=>"O número de repetições precisa se maior que 0");
    if(!validateDate($aviso->data_inicio, 'd/m/Y'))    $erros[] = array("campo"=>"dataInicio", "mensagem"=>"Erro na data inicial");
    if(!validateDate($aviso->data_fim, 'd/m/Y'))       $erros[] = array("campo"=>"dataInicio", "mensagem"=>"Erro na data final");
    if($horaInicio >= $horaFim)                         $erros[] = array("campo"=>"mensagem", "mensagem"=>"Hora inicial é menor do que a final");
    

    if(count($erros)>0)
    {
        $arquivos = new StdClass();
        $arquivos->avisos = lista_arquivos('zz_locucao_avisos');

        $aviso->sequencia = new StdClass();
        
        if(count($sequencia)>0)
        {
            $_sequencia = json_encode($sequencia);
            $aviso->sequencia = json_decode($_sequencia);
        }
        
        $app->render('aviso.php', array('aviso'=>$aviso, 'arquivos'=>$arquivos, 'erros'=>$erros));
    }
    else
    {

        foreach ($sequencia as $s)
        {
            if($s!=null)$tempo_locucao += $s->tempo;
        }

        $tempo_locucao = ceil($tempo_locucao) + 2;//arrendoda o tempo e adiciona 1 segundo pra colocar no inicio da locução e 1 no final

        if($id==0)
        {
            $sql_query = "INSERT INTO locucao(titulo, post, sequencia, tempo, repeticoes_hora, ativo, dia_semana, data_inicio, data_fim, hora_inicio, hora_fim, data_criacao, data_edicao, tipo) VALUES(:titulo, :post, :sequencia, :tempo, :repeticoes_hora, :ativo, :dia_semana, :data_inicio, :data_fim, :hora_inicio, :hora_fim, NOW(), NOW(), 'aviso')";
        }
        else
        {
            $sql_query = "UPDATE locucao SET titulo=:titulo, post=:post, sequencia=:sequencia, tempo=:tempo, repeticoes_hora=:repeticoes_hora, ativo=:ativo, dia_semana=:dia_semana, data_inicio=:data_inicio, hora_inicio=:hora_inicio, hora_fim=:hora_fim, data_fim=:data_fim, data_edicao=NOW() WHERE id=:id";
        }
        try {
            $pdo = connect_db_locucao();

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

$app->post('/aviso/:id/delete', function($id) use ($app)
{
    if (!isset($_SESSION['user-logado']) || !isset($_SESSION['usuario']))
    {

        echo "erro";
    }

    if (isset($_SESSION['usuario']) && $_SESSION['usuario']->tipo!=0) 
    {

        echo "erro";
    }

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

$app->get('/avisos(/)', function() use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }

    $sql_query = "SELECT id, titulo, ativo, data_inicio, data_fim, hora_inicio, hora_fim FROM locucao WHERE deletado=0 AND tipo='aviso' ORDER By data_edicao DESC";
    try {
        $pdo = connect_db_locucao();
        $stmt = $pdo->prepare($sql_query); 
        
        if($stmt->execute())
        {
            $lista = $stmt->fetchAll(PDO::FETCH_OBJ);

            $app->render('avisos.php', array('lista'=>$lista) );
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