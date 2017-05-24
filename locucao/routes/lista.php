<?php

$app->get('/lista(/)', function() use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }

    $programacao = array();

    $sql_query = "SELECT *, IF(arquivo IS NULL,(SELECT titulo FROM locucao WHERE locucao.id=programacao.locucao_id),arquivo) as titulo, 
                            IF(arquivo IS NULL,(SELECT tipo FROM locucao WHERE locucao.id=programacao.locucao_id),'evento') as tipo 
                    FROM programacao WHERE locucao_id IS NOT NULL AND  DATE(data)=DATE(NOW())";
    try {
        $pdo = connect_db_locucao();
        $stmt = $pdo->prepare($sql_query);
        
        if($stmt->execute())
        {
            $programacao = $stmt->fetchAll(PDO::FETCH_OBJ);
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

    $app->render('lista.php', array('programacao'=>$programacao, 'reload'=>59));  
});
