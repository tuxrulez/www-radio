#!/usr/bin/php -q
<?php
/**
 * musical.php: Cadastra no banco de dados todos os arquivos musicais
 * Carlos Daniel de Mattos Mercer <daniel@useinet.com.br>
 * 2004-02-18
*/
include "config.inc.php";
mysql_connect("localhost", "root", "");	// host, usuario e senha
mysql_select_db("radio");			// DB

$result = mysql_query("SELECT tipo FROM tipos WHERE tipo='musical'");		// musical

// Cadastro de generos
while ($row = mysql_fetch_object($result)) {
	$i = 0;
	exec ("ls /usr/local/radio/generos/$row->tipo", $genero);
	
	while ($genero[$i]) {
		mysql_query("INSERT INTO generos (tipo, genero)
		VALUES ('$row->tipo', '$genero[$i]')");
		$i++;
	}
}

$result = mysql_query("SELECT tipo,genero FROM generos WHERE tipo='musical'");		// varre todos os generos musicais

// Cadastro de arquivos com tempo
while ($row = mysql_fetch_object($result)) {
	$i = 0;
	exec ("ls /usr/local/radio/generos/$row->tipo/$row->genero", $arquivo);
	while ($arquivo[$i]) {
		if (mysql_query("INSERT INTO arquivos (tipo, genero, arquivo,
						 rede, loja)
		VALUES ( '$row->tipo', '$row->genero', '$arquivo[$i]',
				 '$rede', '$loja' )")) {
			//$tempo = system("gettime '/usr/local/radio/generos/$row->tipo/$row->genero/$arquivo[$i]'");
			$tempo = system("mp3info -p '%S' '/usr/local/radio/generos/$row->tipo/$row->genero/$arquivo[$i]'");
			mysql_query("UPDATE arquivos SET tempo='$tempo'
						 WHERE arquivo='$arquivo[$i]'");
		}
		$i++;
	}
	unset($arquivo);
}

// Libera variavel e desconecta do banco
mysql_free_result($result);
mysql_close();
?>
