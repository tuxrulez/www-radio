#!/usr/bin/php -q
<?php
/**
 * crialog.php: Gera log da playlist gerada para o XMMS
 * Carlos Daniel de Mattos Mercer <daniel@useinet.com.br>
 * 2004-03-01
*/
include "config.inc.php";

mysql_connect("localhost", "root", "");	// host, usuario, senha e DB
mysql_select_db("radio");

// Arquiva e apaga o log
$data = date("Ymd");
exec ("zip -m /var/log/radio/'$rede'_'$loja'_'$data'.zip -j /var/log/radio/{playlist,radio}.log");

// Captura da tabela o playlist com os horarios
$playlists=mysql_query("SELECT genero,arquivo,hora_inicio,hora_fim FROM playlists");

while ($playlist = mysql_fetch_object($playlists)) {
	$inicio = gmdate("H:i:s",$playlist->hora_inicio);
	$fim = gmdate("H:i:s",$playlist->hora_fim);
	// Gera log da playlist
	exec ("echo '$inicio $playlist->genero/$playlist->arquivo' >> /var/log/radio/playlist.log");
}

// Libera variaveis e desconecta do banco
mysql_free_result($playlists);
mysql_close();
?>