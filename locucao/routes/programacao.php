<?php

$app->get('/programacao(/)', function() use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }

    $lista = array();

    $sql_query = "SELECT id, titulo, ativo, data_inicio, data_fim, hora_inicio, hora_fim, tipo FROM locucao WHERE deletado=0 AND data_inicio > CURDATE() ORDER By data_inicio DESC, titulo ASC";

    $pdo = connect_db_locucao();
    $stmt = $pdo->prepare($sql_query); 
    
    if($stmt->execute())
    {
        $lista = array_merge($lista, $stmt->fetchAll(PDO::FETCH_OBJ));        
    }
    else
    {
        echo "Erro";
    }

    $sql_query = "SELECT id, titulo, ativo, data_inicio, data_fim, hora_inicio, hora_fim, tipo FROM locucao WHERE deletado=0 AND (CURDATE() BETWEEN data_inicio AND data_fim) ORDER By data_inicio DESC, titulo ASC";

    $pdo = connect_db_locucao();
    $stmt = $pdo->prepare($sql_query); 
    
    if($stmt->execute())
    {
        $lista = array_merge($lista, $stmt->fetchAll(PDO::FETCH_OBJ));        
    }
    else
    {
        echo "Erro";
    }

    $sql_query = "SELECT id, titulo, ativo, data_inicio, data_fim, hora_inicio, hora_fim, tipo FROM locucao WHERE deletado=0 AND data_fim < CURDATE() ORDER By data_inicio DESC, titulo ASC";

    $pdo = connect_db_locucao();
    $stmt = $pdo->prepare($sql_query); 
    
    if($stmt->execute())
    {
        $lista = array_merge($lista, $stmt->fetchAll(PDO::FETCH_OBJ));        
    }
    else
    {
        echo "Erro";
    }


    $app->render('programacao.php', array('lista'=>$lista) );
});