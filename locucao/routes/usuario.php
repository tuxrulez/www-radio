<?php

$app->get('/usuarios(/)', function() use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }

    if (isset($_SESSION['usuario']) && $_SESSION['usuario']->tipo != 0) 
    {

        $app->redirect($_SESSION['baseUrl']);
    }

    $sql_query = "SELECT id, nome, tipo FROM usuarios WHERE deletado=0 ORDER By nome DESC";
    try {
        $pdo = connect_db_locucao();
        $stmt = $pdo->prepare($sql_query); 
        
        if($stmt->execute())
        {
            $lista = $stmt->fetchAll(PDO::FETCH_OBJ);

            $app->render('usuarios.php', array('lista'=>$lista) );
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

$app->get('/usuario(/:id)', function($id=0) use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }

    if (isset($_SESSION['usuario']) && $_SESSION['usuario']->tipo != 0) 
    {

        $app->redirect($_SESSION['baseUrl']);
    }

    $usuario = new StdClass();
    $usuario->id = 0;
    $usuario->nome = "";
    $usuario->login = "";
    $usuario->tipo = 1;

    if($id>0)
    {
        $sql_query = "SELECT id, nome, login, tipo FROM usuarios WHERE id=:id";
        try {
            $pdo = connect_db_locucao();
            $stmt = $pdo->prepare($sql_query);
            $stmt->bindValue('id', $id);
            
            if($stmt->execute())
            {
                $usuario = $stmt->fetch(PDO::FETCH_OBJ);
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

    $app->render('usuario.php', array('usuario'=> $usuario));
});

$app->post('/usuario/:id/delete', function($id) use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        echo "erro";
    }

    if (isset($_SESSION['usuario']) && $_SESSION['usuario']->id == $id) 
    {

        echo "erro";
    }

    $sql_query = "UPDATE usuarios SET deletado=1 WHERE id=:id";
    try {
        $pdo = connect_db_locucao();
        $stmt = $pdo->prepare($sql_query);
        $stmt->bindValue('id', $id);
        
        if($stmt->execute())
        {
            addLog('usuarios', 'd', $id, showQuery($sql_query, array("id"=>$id) )  );
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


$app->post('/usuario', function() use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }

    if (isset($_SESSION['usuario']) && $_SESSION['usuario']->tipo != 0) 
    {

        $app->redirect($_SESSION['baseUrl']);
    }

    $id = $app->request()->post('id');
    $nome = $app->request()->post('nome');
    $login = $app->request()->post('login');
    $senha = $app->request()->post('senha');
    $tipo = $app->request()->post('tipo');

    if($id==0)
    {
        $sql_query = "INSERT INTO usuarios(nome, login, senha, tipo) VALUES(:nome, :login, :senha, :tipo)";
    }
    else
    {
        $sql_query = "UPDATE usuarios SET nome=:nome, login=:login, tipo=:tipo";

        if($senha!=null) $sql_query .= ", senha=:senha";

        $sql_query .= " WHERE id=:id";
    }
    try {
        $pdo = connect_db_locucao();
        //$stmt = $pdo->prepare("INSERT INTO locucao (titulo) VALUES ('titulos')");
        $stmt = $pdo->prepare($sql_query); 
        $stmt->bindValue('nome',$nome); 
        $stmt->bindValue('login', $login); 
        if($senha!=null) $stmt->bindValue('senha', sha1($senha)); 
        if($tipo!=null) $stmt->bindValue('tipo', $tipo);
        if($id>0) $stmt->bindValue('id', $id);
        
        if($stmt->execute())
        {
            if($id==0)
            {
                addLog('usuarios', 'c', $pdo->lastInsertId(), showQuery($sql_query, array("nome"=>$nome, 
                                                                                        "login"=>$login,
                                                                                        "senha"=>sha1($senha),
                                                                                        "tipo"=>$tipo) )  );
            }
            else
            {
                $params = array("nome"=>$nome, "login"=>$login);
                if($senha!=null) $params['senha'] = sha1($senha); 
                if($tipo!=null) $params['tipo'] = $tipo;
                if($id>0) $params['id'] = $id;

                addLog('usuarios', 'u', $id, showQuery($sql_query, $params )  );
            }  
            $app->redirect($_SESSION['baseUrl'].'/usuarios');
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