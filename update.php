#!/usr/bin/php -q
<?php
/**
 * update.php: Refaz os comerciais e recadastra o restante
 * Carlos Daniel de Mattos Mercer <daniel@useinet.com.br>
 * 2004-07-26
*/
mysql_connect("localhost", "root", "");	// host, usuario e senha
mysql_select_db("radio");
mysql_query("DELETE FROM arquivos WHERE tipo='comercial'");
mysql_query("DELETE FROM generos  WHERE tipo='comercial'");
mysql_query("DROP TABLE esquemas");
mysql_query("DROP TABLE grades");
mysql_query("DROP TABLE programacoes");

exec ("unzip -d /var/www -o /var/www/radio.zip 2> /dev/null");
exec ("mysql -fu root radio < /var/www/radio.sql 2> /dev/null");

// Libera variavel e desconecta do banco
mysql_close();
?>
