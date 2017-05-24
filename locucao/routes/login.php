<?php

$app->get('/login(/)', function() use ($app)
{
    if (isset($_SESSION['user-logado'])) 
    {
        $app->redirect($_SESSION['baseUrl']);
    }
    $app->render('login.php');
});

$app->get('/logout', function() use ($app)
{
   unset($_SESSION['user-logado']);
   unset($_SESSION['usuario']);

   $app->redirect($_SESSION['baseUrl']);
});

// POST route
$app->post('/login', function() use ($app)
{
    $username = $app->request()->post('username');
    $password = $app->request()->post('password');

    $usuario = login($username, $password);

    //if($username=="admin" && $password=="123@")
    if($usuario != false)
    {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['user-logado'] = true;

        $app->redirect($_SESSION['baseUrl']);
    }
    else
    {
        $app->flash('erro-senha', "Dados incorretos!");
        $app->redirect($_SESSION['baseUrl'].'/login');
    }
});

$app->get('/recupera-senha', function() use ($app)
{
    
    $rede = 'Extra';
    $loja = 'AA_Piloto_Locucao';
    $login = 'admin';
    $dia = date('j');


    $temp = sha1($dia.$rede.$loja.$login);

    echo $temp.'<br />';

    $codigo = '';

    $n = array(1,2,3,4,5,6,7,8,9,10);

    for ($i=0; $i < 2; $i++) 
    { 
        $t = mt_rand(1, count($n));

        $codigo.= substr($temp, $n[$t], 1).$n[$t].substr($temp, $n[$t], 1);

        echo $t.'<br />';

        array_splice($n, $t, 1);
    }

    echo $codigo;
});