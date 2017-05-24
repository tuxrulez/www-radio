<?php

require 'Slim/Slim.php';

include "../config.inc.php";

$path_mp3 = "/usr/local/radio/generos/";

\Slim\Slim::registerAutoloader();


$app = new \Slim\Slim(array(
    'debug'=>true,
    'templates.path' => 'templates'
));

$app->add(new \Slim\Middleware\SessionCookie(array('secret' => 'fdrngrfdgdsfolkkjhdfgd')));


$app->hook('slim.before', function () use ($app) 
{
    
    $app->view()->appendData(array('cacheControl'=>'?v='.rand()));

    $posIndex = strpos( $_SERVER['PHP_SELF'], '/index.php');
    $baseUrl = substr( $_SERVER['PHP_SELF'], 0, $posIndex);
    $app->view()->appendData(array(
                                    'baseUrl' => $baseUrl,
                                    'resourceUri' => $app->request()->getResourceUri()
                                    ));

    $_SESSION['baseUrl'] = $baseUrl;
});

$env = $app->environment();
$env['path_mp3'] = $path_mp3;
$env['rede'] = $rede;
$env['loja'] = $loja;

require 'routes/hora.php';
require 'routes/arquivo.php';
require 'routes/login.php';
require 'routes/usuario.php';
require 'routes/aviso.php';
require 'routes/oferta.php';
require 'routes/lista.php';
require 'routes/historico.php';
require 'routes/programacao.php';

// GET route

$app->get('/teste', function() use ($app)
{
    $env = $app->environment();

    //login2('admin', '');

    print preg_replace("/^[a-z]{3}\s/", "", "eea Bom Dia");

});

$app->get('/', function() use ($app)
{
    if (!isset($_SESSION['user-logado'])) 
    {

        $app->redirect($_SESSION['baseUrl'].'/login');
    }   
    
    $app->render('home.php');
});

function grava_programacao()
{
    $pdo = connect_db_locucao();

    $sql_query = "DELETE FROM programacao WHERE locucao_id=1"; //delete a programação da locução
    $stmt = $pdo->prepare($sql_query);

    if(!$stmt->execute())
    {
        return false;
    }

    
    $sql_query =    "SELECT 
                        programacao.hora_fim, locucao.titulo
                    FROM 
                        programacao JOIN locucao 
                        ON locucao.id=programacao.locucao_id";

    $pdo = connect_db_locucao();
    $stmt = $pdo->prepare($sql_query);

    if($stmt->execute())
    {
        $temp = $stmt->fetchAll(PDO::FETCH_OBJ);

        print_r($temp);
    }

    return true;
}

function get_arquivo($arquivo, $genero, $tempo=false, $input='')
{
    Global $rede;
    Global $path_mp3;


    $nome = str_replace($rede." - locucao ", "", $arquivo);
    $nome = str_replace(".mp3", "", $nome);

    $a = new StdClass();
    $a->nome = str_replace($rede." - ", "", $nome);
    $a->arquivo = $arquivo;
    $a->genero = $genero;

    $sql_query = "SELECT tempo, data_inicio, data_fim, dia_semana, hora_inicio, hora_fim, dia_semana FROM arquivos WHERE arquivo='".$arquivo."' AND genero='".$genero."'";
    $pdo = connect_db_radio();
    $stmt = $pdo->prepare($sql_query);

    if($stmt->execute())
    {
        $temp = $stmt->fetch(PDO::FETCH_OBJ);

        if($temp == null) return null;

        $a->data_inicio = $temp->data_inicio;
        $a->data_fim = $temp->data_fim;
        $a->hora_inicio = $temp->hora_inicio;
        $a->hora_fim = $temp->hora_fim;
        $a->dia_semana = $temp->dia_semana;
    }

    if($tempo)
    {
       $path = $path_mp3."comercial/".$genero."/".$arquivo;
       $time = exec("ffmpeg -i '$path' 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//");

       $arr = explode(":", $time);

       $time = $arr[0]*3600 + $arr[1]*60+$arr[2];

       if(!(is_nan($time)) && $time > 0)
       {
            $a->tempo = $time;
       }
       else
       {
            $a->tempo = $temp->tempo;
       }
    }

    if($input!='')
    {
        $a->input = $input;
    }

    return $a;
}

function lista_arquivos($genero)
{
    $arquivos = array();
    Global $rede;

    $sql_query = "SELECT arquivo, genero FROM arquivos WHERE genero='".$genero."' ORDER BY arquivo";
    try {
        $pdo = connect_db_radio();
        $stmt = $pdo->prepare($sql_query); 
        
        if($stmt->execute())
        {
            $temp = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($temp as $arquivo) 
            {
                $nome = str_replace($rede." - locucao ", "", $arquivo->arquivo);
                $nome = str_replace(".mp3", "", $nome);

                $a = new StdClass();
                $a->nome = str_replace($rede." - ", "", $nome);
                $a->arquivo = $arquivo->arquivo;
                $a->genero = $arquivo->genero;

                $arquivos[] = $a;
                //$arquivos[] = get_arquivo($arquivo->arquivo, $arquivo->genero);
            }
        }
        else
        {
            return "Erro";
        }
    }
    catch(PDOException $e) 
    {
        return '{"error":{"text":'. $e->getMessage() .'}}';
    }    

    return $arquivos;
/*
    $a = new StdClass();
    $a->nome = "Teste";
    $a->arquivo = "teste.mp3";
    $a->genero = "Locucao_Abertura";

    $arquivos[] = $a;

    return $arquivos;*/
}

function trata_nome_arquivo($arquivo)
{
    Global $rede;

    if($arquivo=="") return null;

    if(strpos($arquivo, "/")!==false)
    {
        $arquivo = explode("/", $arquivo);
        $arquivo = $arquivo[1];
    }

    $nome = str_replace($rede." - locucao ", "", $arquivo);
    $nome = str_replace(".mp3", "", $nome);

    $a = new StdClass();
    $a->nome = $nome;
    $a->arquivo = $arquivo;

    $sql_query = "SELECT genero FROM arquivos WHERE arquivo=:arquivo";
    try {
        $pdo = connect_db_radio();
        $stmt = $pdo->prepare($sql_query); 
        $stmt->bindValue('arquivo',$arquivo); 
        
        if($stmt->execute())
        {
            $tmp = $stmt->fetch(PDO::FETCH_OBJ);
            $a->genero = $tmp->genero;
        }
        else
        {
            return "Erro";
        }
    }
    catch(PDOException $e) 
    {
        return '{"error":{"text":'. $e->getMessage() .'}}';
    } 
    
    return $a;
}

function lista_categorias_produto()
{
    $generos = new StdClass();
    Global $rede;

    $sql_query = "SELECT genero FROM arquivos WHERE genero LIKE'zz_locucao_produto%' GROUP BY genero";
    try {
        $pdo = connect_db_radio();
        $stmt = $pdo->prepare($sql_query); 
        
        if($stmt->execute())
        {
            $generos = $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        else
        {
            return "Erro";
        }
    }
    catch(PDOException $e) 
    {
        return '{"error":{"text":'. $e->getMessage() .'}}';
    }    

    return $generos;
}

function segundos2hora($segundos)
{
    return floor($segundos/3600).":".floor(($segundos%3600)/60).":".$segundos%60;
}

function segundos2horaHM($segundos)
{
    return sprintf('%02d', floor($segundos/3600))."h".sprintf('%02d', floor(($segundos%3600)/60))."m";
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function formataData($data)//formato Y-m-d para d/m/Y
{
    $temp = explode('-', $data);
    $temp = array_reverse($temp);

    return implode('/', $temp);

}

function formataValidade($arquivo)
{
    $formatado = "";

    $data_inicio = $arquivo->data_inicio;
    $data_inicio = date("d/m/Y", strtotime($data_inicio));

    $data_fim = $arquivo->data_fim;
    $data_fim = date("d/m/Y", strtotime($data_fim));

    $hora_inicio = segundos2horaHM($arquivo->hora_inicio);
    $hora_fim = segundos2horaHM($arquivo->hora_fim);
    
    $dia_semana = array();
    $dias = explode(',', $arquivo->dia_semana);

    foreach ($dias as $key => $dia) 
    {
        if($dia==1) $dia_semana[]='Do';
        if($dia==2) $dia_semana[]='2ª';
        if($dia==3) $dia_semana[]='3ª';
        if($dia==4) $dia_semana[]='4ª';
        if($dia==5) $dia_semana[]='5ª';
        if($dia==6) $dia_semana[]='6ª';
        if($dia==7) $dia_semana[]='Sa';
    }

    $formatado.= "Data - de ".$data_inicio." até ".$data_fim."<br />";
    $formatado.= "Horario - de ".$hora_inicio." até ".$hora_fim."<br />";
    $formatado.= "Dias da semana - ".implode(', ', $dia_semana);

    return $formatado;
}

function validaArquivo2($arquivo, $data_inicio, $data_fim, $hora_inicio, $hora_fim, $dia_semana)
{
   Global $rede;
   Global $loja;

   $dias = explode(',', $dia_semana);
   $w = "";

   foreach ($dias as $key => $dia) 
   {
       $w.= " AND FIND_IN_SET(".$dia.", dia_semana)";
   }

   $sql_query = "SELECT * FROM arquivos 
                WHERE 
                    rede=:rede AND loja=:loja AND arquivo=:arquivo AND :data_inicio>=data_inicio AND :data_fim<=data_fim 
                    AND :hora_inicio>=hora_inicio AND :hora_fim<=hora_fim".$w;// AND CONCAT(',', dia_semana, ',') REGEXP CONCAT(',(',:dia_semana,'),')";

   $pdo = connect_db_radio();
   $stmt = $pdo->prepare($sql_query);
   $stmt->bindValue('rede',$rede); 
   $stmt->bindValue('loja',$loja); 
   $stmt->bindValue('arquivo',$arquivo); 
   $stmt->bindValue('data_inicio',$data_inicio); 
   $stmt->bindValue('data_fim',$data_fim); 
   $stmt->bindValue('hora_inicio',$hora_inicio); 
   $stmt->bindValue('hora_fim',$hora_fim); 
   //$stmt->bindValue('dia_semana', $dia_semana, PDO::PARAM_STR); 


   if($stmt->execute())
   {
        $temp = $stmt->fetch(PDO::FETCH_OBJ);

        if($temp!=null)
        {

            if(arquivoExiste($temp->genero, $temp->arquivo))
            {
                return true;
            }

        }
   }


   return false;
}

function validaArquivo($arquivo, $data_inicio, $data_fim, $hora_inicio, $hora_fim, $dia_semana)
{
   Global $rede;
   Global $loja;

   $erros = array();

   $sql_query = "SELECT * FROM arquivos WHERE rede=:rede AND loja=:loja AND arquivo=:arquivo";

   $pdo = connect_db_radio();
   $stmt = $pdo->prepare($sql_query);
   $stmt->bindValue('rede',$rede); 
   $stmt->bindValue('loja',$loja); 
   $stmt->bindValue('arquivo',$arquivo); 

   if($stmt->execute())
   {
        $temp = $stmt->fetch(PDO::FETCH_OBJ);

        if($temp!=null)
        {
            if(!arquivoExiste($temp->genero, $temp->arquivo))
            {
                $erros[] = 'Arquivo não existe';
            }

            if(strtotime($data_inicio) < strtotime($temp->data_inicio))
            {
                $erros[] = 'A data incial precisa ser maior ou igual a: '.formataData($temp->data_inicio);
            }
            if($data_fim > $temp->data_fim)
            {
                $erros[] = 'A data final precisa ser menor ou igual a: '.formataData($temp->data_fim);
            }

            if($hora_inicio < $temp->hora_inicio)
            {
                $erros[] = 'O horário incial precisa ser maior ou igual a: '.segundos2horaHM($temp->hora_inicio);
            }
            if($hora_fim > $temp->hora_fim)
            {
                $erros[] = 'O horário final precisa ser menor ou igual a: '.segundos2horaHM($temp->hora_fim);
            }

            $dias = explode(',', $dia_semana);
            $dias_erro = false;

            foreach ($dias as $key => $dia) 
            {
                if(strstr($temp->dia_semana, $dia)===false)
                {
                    $dias_erro = true;
                }
            }

            if($dias_erro)
            {
                $dia_semana = array();
                $dias = explode(',', $temp->dia_semana);

                foreach ($dias as $key => $dia) 
                {
                    if($dia==1) $dia_semana[]='Do';
                    if($dia==2) $dia_semana[]='2ª';
                    if($dia==3) $dia_semana[]='3ª';
                    if($dia==4) $dia_semana[]='4ª';
                    if($dia==5) $dia_semana[]='5ª';
                    if($dia==6) $dia_semana[]='6ª';
                    if($dia==7) $dia_semana[]='Sa';
                }

                $erros[] = 'É válido para os seguintes dias da semana: '.implode(', ', $dia_semana);
            }

        }
        else
        {
            $erros[] = 'Arquivo não cadastrado';
        }
   }

   if(count($erros)>0) return $erros;

   return true;
}

function arquivoExiste($genero, $arquivo)
{
    Global $path_mp3;

    $file = $path_mp3."comercial/".$genero."/".$arquivo;

    if(file_exists($file))
    {
        return true;
    }

    return false;
}

function addLog($tabela, $operacao, $tabela_id, $query="")
{
    if(isset($_SESSION['usuario']) && $_SESSION['usuario']->id > 0)
    {
        $sql_query = "INSERT INTO log(usuario_id, tabela, operacao, tabela_id, query) VALUES(:usuario_id, :tabela, :operacao, :tabela_id, :query)";

        try {
            $pdo = connect_db_locucao();
            $stmt = $pdo->prepare($sql_query); 
            $stmt->bindValue('usuario_id',$_SESSION['usuario']->id); 
            $stmt->bindValue('tabela', $tabela); 
            $stmt->bindValue('operacao', $operacao); 
            $stmt->bindValue('tabela_id', $tabela_id);
            $stmt->bindValue('query', $query);
            
            $stmt->execute();
        }
        catch(PDOException $e) 
        {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }
}

function login($login, $senha)
{

   $sql_query = "SELECT id, nome, tipo FROM usuarios WHERE login=:login AND senha=:senha AND deletado=0";
   $pdo = connect_db_locucao();
   $stmt = $pdo->prepare($sql_query);
   $stmt->bindValue('login',$login); 
   $stmt->bindValue('senha',sha1($senha)); 



   if($stmt->execute())
   {
        $rows = $stmt->fetch(PDO::FETCH_OBJ);

        if($rows!=null) return $rows;
   }


    return false;
}

function loginCodigo($login, $senha)
{
    Global $rede;
    Global $loja;
    $dia = date('j');

    $temp = sha1($dia.$rede.$loja.$login);

    $p1 = substr($senha, 1, 1);
    $s1 = substr($senha, 0, 1);
    $p2 = substr($senha, 3, 1);
    $s2 = substr($senha, 2, 1);
    $p3 = substr($senha, 5, 1);
    $s3 = substr($senha, 4, 1);
    $p4 = substr($senha, 7, 1);
    $s4 = substr($senha, 6, 1);

    if($p1==$p2 || $p1==$p3 || $p1==$p4) return false;
    if($p2==$p3 || $p2==$p4) return false;
    if($p3==$p4) return false;

    if(substr($temp, $p1, 1)==$s1 && substr($temp, $p2, 1)==$s2 && substr($temp, $p3, 1)==$s3 && substr($temp, $p4, 1)==$s4)
    {
       $sql_query = "SELECT id, nome, tipo FROM usuarios WHERE login=:login AND deletado=0";
       $pdo = connect_db_locucao();
       $stmt = $pdo->prepare($sql_query);
       $stmt->bindValue('login',$login); 
       $stmt->bindValue('senha',sha1($senha)); 



       if($stmt->execute())
       {
            $rows = $stmt->fetch(PDO::FETCH_OBJ);

            if($rows!=null) return $rows;
       }
    }

    return false;
}

function showQuery($query, $params)
{
    $keys = array();
    $values = array();
    
    # build a regular expression for each parameter
    foreach ($params as $key=>$value)
    {
        if (is_string($key))
        {
            $keys[] = '/:'.$key.'/';
        }
        else
        {
            $keys[] = '/[?]/';
        }
        
        if(is_numeric($value))
        {
            $values[] = intval($value);
        }
        else
        {
            $values[] = '"'.$value .'"';
        }
    }
    
    $query = preg_replace($keys, $values, $query, 1, $count);
    return $query;
}


function connect_db_radio() 
{
    try {
        $db_username = "root";
        $db_password = "";
        $conn = new PDO('mysql:host=localhost;dbname=radio', $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    return $conn;
}

function connect_db_locucao() 
{
    /*$server = 'localhost'; // this may be an ip address instead
    $user = 'root';
    $pass = '';
    $database = 'radio_locucao';
    $connection = new mysqli($server, $user, $pass, $database);

    return $connection;
*/
    try {
        $db_username = "root";
        $db_password = "";
        $conn = new PDO('mysql:host=localhost;dbname=radio_locucao', $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    return $conn;
}


$app->run();