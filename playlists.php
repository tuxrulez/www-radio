<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Cadastro de Playlists</title>
  <meta name="GENERATOR" content="Quanta Plus">
</head>
<body bgcolor="#f5f5f5">
<table cellspacing="0" border="0" cellpadding="0" align="center">
  <tbody>
    <tr>
      <td colspan="2" align="center"> <h2><img src="radiobusiness.gif" width="104" height="100" align="middle" border="0">XMMS-RADIO</h2> </td>
    </tr>
    <tr valign="top">
      <td>
	  	<table>
			<tr>
      			<td> <a href="arquivos.php"><b>Arquivos</b></a> </td>
    		</tr>
			<tr>
      			<td> <a href="musicas.php"><b>Musicas</b></a> </td>
    		</tr>
			<tr>
      			<td> <a href="esquemas.php"><b>Esquemas</b></a> </td>
    		</tr>
			<tr>
				<td> <a href="grades.php"><b>Grades</b></a> </td>
			</tr>
			<tr>
				<td> <a href="programacoes.php"><b>Programacoes</b></a> </td>
			</tr>
			<tr>
				<td><b>Playlists</b></td>
			</tr>
			<tr>
      			<td> <a href="logs.php"><b>Logs</b></a> </td>
    		</tr>
		</table>
	  </td>

<form action="playlists.php" method="POST" enctype="multipart/form-data">
      <td>
	  	<table cellspacing="0" border="1" cellpadding="0">
			<tr>
				<td colspan="2" align="center">Gerar nova playlist?<br>Sim <input type="radio" name="gerar" value="sim">
	  			Nao <input type="radio" checked name="gerar" value="nao"></td>
				<td align="center"><input type="submit" name="ok" value="Ok"></td>
				<td colspan="2" align="center">Reiniciar player?<br>Sim <input type="radio" name="player" value="sim">
	  			Nao <input type="radio" checked name="player" value="nao"></td>
	  		</tr>
<?php
	include "config.inc.php";
	if ($_POST['gerar'] == "sim") {
		exec("./playlist.php > /srv/www/htdocs/xmms.m3u");
	}

	if ($_POST['player'] == "sim") {
  		exec("killall log.sh");
	}

	mysql_connect("localhost", "root", "");
	mysql_select_db("radio");


	$result = mysql_query("SELECT * FROM playlists");
		echo "<tr bgcolor='#d3d3d3'>
					<th>Tipo</th>
					<th>Genero</th>
					<th>Arquivo</th>
					<th>Hora Inicial</th>
					<th>Hora Final</th>
				</tr>";

	while ($row = mysql_fetch_object($result)) {
		$inicio = gmdate("H:i:s",$row->hora_inicio);
		$fim = gmdate("H:i:s",$row->hora_fim);
		echo "<tr bgcolor='#d3d3d3'>
					<td align=center> $row->tipo </td>
					<td align=center> $row->genero </td>
					<td align=center> $row->arquivo </td>
					<td align=center> $inicio </td>
					<td align=center> $fim </td>
				</tr>";
	}

	echo "</tbody>
		</table>";

	mysql_free_result($result);
    mysql_close();
?>

</body>
</html>
