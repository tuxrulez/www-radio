<?php

$app->get('/historico(/)', function() use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }

    $sql_query = "SELECT id, titulo, ativo, data_inicio, data_fim, hora_inicio, hora_fim 
                FROM locucao 
                WHERE deletado=0 AND tipo='oferta' 
                ORDER By data_edicao DESC";
    try {
        $pdo = connect_db_locucao();
        $stmt = $pdo->prepare($sql_query); 
        
        if($stmt->execute())
        {
            $lista = $stmt->fetchAll(PDO::FETCH_OBJ);

            $app->render('historico.php', array('lista'=>$lista) );
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

    
    //$app->render('home.php');
});