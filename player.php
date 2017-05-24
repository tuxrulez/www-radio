#!/usr/bin/php -q
<?php
/**
 * player.php: Carrega e executa uma playlist no XMMS
 * Carlos Daniel de Mattos Mercer <daniel@useinet.com.br>
 * 2004-03-23
*/

// Gera a playlist
exec("/var/www/playlist.php > /var/www/mplayer.m3u");

include "config.inc.php";
mysql_connect("localhost", "root", ""); // host, usuario e senha
mysql_select_db("radio");                  // DB

$hora = localtime();
$tempo = $hora[0] + $hora[1] * 60 + $hora[2] * 3600;

$arquivos = mysql_query("SELECT hora_inicio FROM playlists
					   WHERE hora_inicio < '$tempo'");   // iniciar
$proximo = mysql_num_rows($arquivos)+1;	// Quantos arquivos + 1?

$arquivos = mysql_query("SELECT hora_inicio FROM playlists");   // todos
mysql_data_seek($arquivos, $proximo);	// Vai para o proximo arquivo
$arquivo = mysql_fetch_object($arquivos);   // Seleciona um arquivo

// Gera o log, carrega a playlist
exec("/var/www/crialog.php");

/*
exec("/usr/local/bin/xmms-shell -e clear");
exec("/usr/local/bin/xmms-shell -e 'load /srv/www/htdocs/xmms.m3u'");

// Acessa e reproduz o numero da faixa
exec("/usr/local/bin/xmms-shell -e 'jump $proximo'");
exec("/usr/local/bin/xmms-shell -e play");
*/

$in_playlist="/tmp/playlist_in";
$in_locucao="/tmp/locucao_in";

$out_playlist="/tmp/playlist_out";
$out_locucao="/tmp/locucao_out";

$playlist_mute = false;

$mode=0777;

if(!file_exists($in_playlist)) 
{
  umask(0);
  posix_mkfifo($in_playlist,$mode);
}

if(!file_exists($in_locucao)) 
{
  umask(0);
  posix_mkfifo($in_locucao,$mode);
}

if(!file_exists($out_playlist)) exec("touch $out_playlist");
if(!file_exists($out_locucao)) exec("touch $out_locucao");

exec("mplayer -slave -volume 100 -nolirc -softvol -idle -quiet -noautosub -input file=$in_playlist 2>&- 1> $out_playlist &"); //abre mplayer q toca playlist da radio

exec("mplayer -slave -nolirc -softvol -idle -quiet -noautosub -input file=$in_locucao 2>&- 1> $out_locucao &"); //abre mplayer q toca locucao

//carrega playlist
comando("loadlist /var/www/mplayer.m3u", $in_playlist);
usleep(500000);
//avança pra proxima
comando("pt_step $proximo", $in_playlist);

//executa scripts q gera log
exec("/var/www/inicia_log.sh > /dev/null &");

// Seleciona eventos validos para o dia e para o horario
$eventos_sql = mysql_query("SELECT arquivo,hora_inicio,tempo,hora_inicio as inicio FROM
	arquivos WHERE rede='$rede' AND loja='$loja' AND
	tipo='comercial' AND genero='Eventos'
	AND CURDATE() >= data_inicio AND CURDATE() <= data_fim AND
	FIND_IN_SET(DAYOFWEEK(CURDATE()),dia_semana) ORDER BY hora_inicio,arquivo");

$locucoes = array();//lista das locuções
$lista_locucoes = array();//playlist das locuções
$a = 0;
$evento_delay = 0;//tempo q a playlist vai atrazando conforme os eventos são tocados
$aMax = 10;//intervalo entre atualização das locuções
$intervalo = 30;//tempo minimo entre as locuções
$playlist_delay = 300;//tempo maximo pra recarregar a playlist, em segundos
$evento_atual = null;
$playlist_mute = false;

while ($evento = mysql_fetch_object($eventos_sql)) 
{
//print_r($evento);echo "teste";
    //$eventos[] = $evento;
    $lista_locucoes[] = $evento;
}

echo segundos2hora($tempo)."\r\n";

$locucoes = atualizaLoacucao();

echo segundos2hora($tempo)."\r\n";

while (true) 
{
	$hora = localtime();
	$tempo = $hora[0] + $hora[1] * 60 + $hora[2] * 3600;

	if($a >= 10)
	{
		$a = 0;
		$locucoes = atualizaLoacucao();
	}

	$a++;

    echo segundos2hora($tempo)."\r\n";


    //checa se tem envento ou locução
    $evento = checaEvento();

    if($evento !== null)
    {
        $evento_atual = clone $evento;
    }

/*
    if($evento_atual!== null)
    {
        echo "string";
        if( ($evento_atual->inicio + $evento_atual->tempo) > $tempo )
        {
            //echo ($evento_atual->inicio + $evento_atual->tempo) ." ". $tempo."\r\n";
            echo "tocando evento \r\n";
            
        }
        else
        {
            $evento_atual = null;
        }
        //echo ($evento_atual->inicio + $evento_atual->tempo) ." ". $tempo."\r\n";
    }
*/
    if($evento_atual!==null)
    {
        if($evento == $evento_atual)
        {

            $a = 10;//força atulização depois do evento
            echo 'tocando envento '.$evento_atual->tempo." segundos - ".(isset($evento_atual->arquivo)? $evento_atual->arquivo: "locucao id:".$evento_atual->id)." - ".$evento_atual->inicio." - ".segundos2hora($evento_atual->inicio)."\r\n";
            
            if(!$playlist_mute)//verica se playlist esta com volume baixo
            {
                exec("/var/www/volume_menos.php > /dev/null &");//baixa volume
                $playlist_mute = true;
            }

            if(isset($evento_atual->arquivo)) //evento normal
            {
                exec("echo '/usr/local/radio/generos/comercial/Eventos/$evento_atual->arquivo' > /var/www/evento.m3u"); // pl com o evento

                comando("loadlist /var/www/evento.m3u",$in_locucao);// toca evento

                //sleep($evento_atual->tempo);// espera o evento acabar
            }
            else if(isset($evento_atual->sequencia) ) // locucao
            {
                $e = json_decode($evento_atual->sequencia);

                $locucoes_pl = "";

                foreach ($e as $key => $value) 
                {
                    //print_r($value);
                    if($value!=null) $locucoes_pl.= "/usr/local/radio/generos/comercial/".$value->genero."/".$value->arquivo."\n";
                }

                exec("echo '$locucoes_pl' > /var/www/locucao.m3u"); // pl com o evento

                sleep(1); //delay de 1 segundo antes de iniciar a locução

                comando("loadlist /var/www/locucao.m3u",$in_locucao);// toca evento

                //sleep($evento_atual->tempo - 1);// espera o evento acabar, desconta 1 segundo do delay inicial
            }

            $evento_delay += $evento_atual->tempo;
        }

        if( ($evento_atual->inicio + $evento_atual->tempo) > $tempo )
        {
            //echo ($evento_atual->inicio + $evento_atual->tempo) ." ". $tempo."\r\n";
            echo "tocando evento \r\n";
            
        }
        else
        {
            $evento_atual = null;
        }

    }
    else
    {
        if($playlist_mute)//verica se playlist esta com volume baixo
        {
            if($evento_delay > $playlist_delay)
            {
                $evento_delay = 0;//zera a variavel

                //pega a posição q a playlist deveria estar
                $arquivos = mysql_query("SELECT hora_inicio FROM playlists
                       WHERE hora_inicio < '$tempo'");   // iniciar
                $proximo = mysql_num_rows($arquivos)+1; // Quantos arquivos + 1?

                //carrega playlist novamente
                comando("loadlist /var/www/mplayer.m3u", $in_playlist);
                usleep(500000);
                //avança pra proxima
                comando("pt_step $proximo", $in_playlist);
            }
            else
            {
                exec("/var/www/volume_mais.php > /dev/null &");//almenta volume
            
            }

            $playlist_mute = false;
            

            echo $evento_delay." evento delay\r\n";
        }
        
        
    }

    sleep(1);//ciclo de um segundo
}


function checaEvento()
{
    Global $tempo;
    Global $lista_locucoes;

    foreach ($lista_locucoes as $key => $evento) 
    {

        //if($tempo >= $evento->inicio && $tempo <= ($evento->inicio + $evento->tempo))
        if($tempo >= $evento->inicio)
        {
            $_evento = clone $evento;
            //unset($eventos[$key]);
            return $_evento;
        }
    }

    return null;
}

function atualizaLoacucao()
{
	Global $tempo;
    Global $lista_locucoes;
    Global $locucoes;

    $atualizou = false;

	$novo = array();

	$sql_query = 	"SELECT 
						id, titulo, repeticoes_hora, hora_inicio, hora_fim, data_edicao, sequencia, tempo 
					FROM 
						locucao
					WHERE
						 ativo=1 AND deletado=0 AND CURDATE() >= data_inicio AND CURDATE() <= data_fim AND FIND_IN_SET(DAYOFWEEK(CURDATE()),dia_semana)";
    try {
        $pdo = connect_db_locucao();
        $stmt = $pdo->prepare($sql_query); 
        //$stmt->bindValue('rede',$rede); 
        //$stmt->bindValue('loja',$loja); 
        
        if($stmt->execute())
        {
            $temp = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($temp as $locucao) 
            {
                //$novo[] = $locucao;
                $temp2 = comparaLocucao($locucao->id, $locucoes);
                if($temp2 !== null)
                {
                	if($temp2->data_edicao == $locucao->data_edicao)
                	{
                		echo "igual, não mudou nada $locucao->id\r\n";
                		$novo[] = $temp2;
                	}
                	else
                	{
                		echo "igual, mas atualizou $locucao->id\r\n";
                        atualizaLista($locucao);
                        $atualizou = true;
                        $novo[] = $locucao;
                	}
                	
                }
                else
                {
                	echo "diferente $locucao->id\r\n";
                	atualizaLista($locucao);
                    $atualizou = true;
                	$novo[] = $locucao;
                }
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

    $id_locucoes = array();
    foreach ($novo as $key => $value) 
    {
        $id_locucoes[] = $value->id;
    }

    
    //loop pra remover a locução q não existe mais
    foreach ($lista_locucoes as $key => $loc) 
    {
        
        if((isset($loc->id) && !in_array($loc->id, $id_locucoes)) || $loc->inicio < $tempo)
        {
            //echo $loc->inicio."  ".segundos2hora($loc->inicio)." tempo:".segundos2hora($tempo)."\r\n";
            unset($lista_locucoes[$key]);

            $atualizou = true;
            

        }
    }

    $lista_locucoes = array_values($lista_locucoes);
    
    if ($atualizou) updateProgramacaoDB();

	return $novo;
}

function updateProgramacaoDB()
{
    Global $lista_locucoes;

    $sql_query = "TRUNCATE programacao";

    $pdo = connect_db_locucao();
    $stmt = $pdo->prepare($sql_query); 
    
    if(!$stmt->execute())
    {
        return "Erro";
    }

    foreach ($lista_locucoes as $key => $locucao) 
    {
        $sql_query = "INSERT INTO programacao(locucao_id, arquivo, inicio, tempo) VALUES(:locucao_id, :arquivo, :inicio, :tempo)";

        $stmt = $pdo->prepare($sql_query); 
        $stmt->bindValue('locucao_id', (isset($locucao->id)? $locucao->id: null ) );
        $stmt->bindValue('arquivo', (isset($locucao->arquivo)? $locucao->arquivo: null ) );
        $stmt->bindValue('inicio',$locucao->inicio); 
        $stmt->bindValue('tempo',$locucao->tempo);

        if(!$stmt->execute())
        {
            return "Erro";
        }
    }
}

function comparaLocucao($id, $array) 
{
   foreach ($array as $key => $val) 
   {
       if ($val->id === $id) 
       {
           return $val;
       }
   }
   return null;
}

function atualizaLista($_locucao)
{
	Global $lista_locucoes;

	$locucao = $_locucao;

    //loop pra remover a locução se já existir
    foreach ($lista_locucoes as $key => $loc) 
    {
        if(isset($loc->id) && $loc->id == $locucao->id)
        {
            //echo $key.' bla';
            //echo "bla".segundos2hora($lista_locucoes[$key]->inicio)." - ";
            unset($lista_locucoes[$key]);

        }
    }
    //print_r($lista_locucoes);
    //reordena os indices do array
    $lista_locucoes = array_values($lista_locucoes);

    $hora_inicio = $locucao->hora_inicio;
    $hora_fim = $locucao->hora_fim;
    $repeticoes_hora = $locucao->repeticoes_hora;

    $intervalo = $hora_fim - $hora_inicio;//em segundos
    $repeticoes_total = floor($repeticoes_hora * ($intervalo / 3600));
    $intervalo_locucao = round($intervalo / $repeticoes_total);
//echo segundos2hora($hora_inicio)." achou\r\n";
    //echo "aki $repeticoes_total\r\n";
    for ($i=0; $i < $repeticoes_total; $i++) 
    { 
        $temp = true;
        $offset = 0;
        $inicio = $hora_inicio + ($intervalo_locucao * $i);
//echo $i." achou ".$inicio."\r\n";
        while($temp) 
        {
            if(checaHorario($locucao, $inicio + $offset))
            {
                
                $clone = clone $locucao;
                $clone->inicio = $inicio + $offset;
                $lista_locucoes[] = $clone;
                $temp = false;

                usort($lista_locucoes, function($a, $b){return $a->inicio > $b->inicio;});

                //$lista_locucoes = reordernaArray($lista_locucoes);
                //$lista_locucoes = sortArrayofObjectByProperty($lista_locucoes, 'inicio');



                //echo $clone->id."-".$clone->inicio."  ".$offset." inicio:".segundos2hora($hora_inicio)." repeticoes:".$repeticoes_total." intervalo:".segundos2hora($intervalo_locucao)."\r\n";
                //print_r($offset);
            }

            if($offset > 3599) $temp = false;
            $offset ++;
        }
        //echo $offset." offset\r\n";
    }

    //echo "aki2\r\n";
//exit();
    //echo "<pre>";
    //print_r($lista_locucoes);
    //echo "</pre>";
    //usort($lista_locucoes, function($a, $b){return $a->inicio > $b->inicio;});
    //echo $lista_locucoes[1]->inicio.'bla';

    foreach ($lista_locucoes as $key => $value) {
            //echo $value->inicio.' :'.segundos2hora($value->inicio).' - ';
    }

    usort($lista_locucoes, function($a, $b){return $a->inicio > $b->inicio;});

    //echo "\r\n";
}

function checaHorario($_locucao, $_inicio)
{
    Global $lista_locucoes;

    $total = count($lista_locucoes);

    if($total < 1) return true;

    if($total == 1) return true;//analizar essa condição quando der tempo

    //usort($lista_locucoes, function($a, $b){return $a->inicio > $b->inicio;});

//echo count($lista_locucoes).'bla';
    $_fim = $_inicio + $_locucao->tempo;


    //if($_inicio >= ($lista_locucoes[$total-1]->inicio + $lista_locucoes[$total-1]->tempo) && ($_inicio + $_locucao->tempo) <= $_locucao->hora_fim)
    $ultimo = end($lista_locucoes);
    if($_inicio >= ($ultimo->inicio + $ultimo->tempo) && ($_inicio + $_locucao->tempo) <= $_locucao->hora_fim)
    {
        return true;
    }
//echo $_inicio."aki\r\n";
    //print_r($lista_locucoes);
    //echo count($lista_locucoes);
    $inicio = 0;
    /*
    for ($i=1; $i <= $total; $i++) 
    { 
        $ant = $lista_locucoes[$i - 1];
        //echo $_fim." > ".$ant->hora_inicio." ".$i."\r\n";
        //echo $ant->inicio + $ant->tempo." | ".$pos->inicio."\r\n";
        if($_fim < $ant->inicio && $i==1)
        {
            return true;
        }

        if($i==$total) return false;

        $inicio = $lista_locucoes[$i]->inicio;
        $pos = $lista_locucoes[$i];

        if($_inicio >= ($ant->inicio + $ant->tempo) && $_fim < $pos->inicio )
        {
            return true;
        }
    }
    */
    $i = 0;
    foreach ($lista_locucoes as $key => $value)
    { 
        
        
        //echo $ant->inicio + $ant->tempo." | ".$pos->inicio."\r\n";
        if($i>0)
        {
            if($_fim < $ant->inicio && $i==1)
            {
                return true;
            }

            if($i==$total) return false;

            $inicio = $value->inicio;
            $pos = $value;

            if($_inicio >= ($ant->inicio + $ant->tempo) && $_fim <= $pos->inicio )
            {
                return true;
            }
        }

        $ant = $value;

        $i++;

        //echo $_fim." > ".$ant->hora_inicio." ".$i."\r\n";
    }
    //echo "teste testse teste $_fim\r\n";

    return false;
}

function reordernaArray($a)
{
    $sort = array();
    foreach($a as $item) 
    {
        $sort[$item->inicio] = $item;
    }
    ksort($sort, SORT_NUMERIC);
    return array_values($sort);
}

function sortArrayofObjectByProperty( $array, $property )
{
    $cur = 1;
    $stack[1]['l'] = 0;
    $stack[1]['r'] = count($array)-1;

    do
    {
        $l = $stack[$cur]['l'];
        $r = $stack[$cur]['r'];
        $cur--;

        do
        {
            $i = $l;
            $j = $r;
            $tmp = $array[(int)( ($l+$r)/2 )];

            // split the array in to parts
            // first: objects with "smaller" property $property
            // second: objects with "bigger" property $property
            do
            {
                while( $array[$i]->{$property} < $tmp->{$property} ) $i++;
                while( $tmp->{$property} < $array[$j]->{$property} ) $j--;

                // Swap elements of two parts if necesary
                if( $i <= $j)
                {
                    $w = $array[$i];
                    $array[$i] = $array[$j];
                    $array[$j] = $w;

                    $i++;
                    $j--;
                }

            } while ( $i <= $j );

            if( $i < $r ) {
                $cur++;
                $stack[$cur]['l'] = $i;
                $stack[$cur]['r'] = $r;
            }
            $r = $j;

        } while ( $l < $r );

    } while ( $cur != 0 );

    return $array;

}

function segundos2hora($segundos)
{
    return floor($segundos/3600).":".floor(($segundos%3600)/60).":".$segundos%60;
}

function connect_db_locucao() 
{
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

function comando($_cmd, $arquivo)
{
	$cmd = "echo '".$_cmd."' > $arquivo";
	exec($cmd);
}


// Libera variaveis e desconecta do banco de dados
mysql_free_result($eventos);
mysql_close();

?>
