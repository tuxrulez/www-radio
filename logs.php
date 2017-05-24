<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Logs</title>
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
				<td> <a href="playlists.php"><b>Playlists</b></a> </td>
			</tr>
			<tr>
				<td><b>Logs</b></a> </td>
			</tr>

		</table>
	  </td>

<form action="logs.php" method="POST" enctype="multipart/form-data">
      <td>
	  	<table cellspacing="0" border="1" cellpadding="0">
			<tr>
				<td align="center">Atualizar log?<br>Sim <input type="radio" checked name="log" value="sim">
	  			Nao <input type="radio" name="log" value="nao"></td>
				<td align="center">Proxima faixa?<br>Sim <input type="radio" name="proxima" value="sim">
	  			Nao <input type="radio" checked name="proxima" value="nao"></td>
				<td align="center"><input type="submit" name="ok" value="Ok"></td>
	  		</tr>
<?php
	if ($_POST['log'] == "sim") {
		exec("cat /var/log/radio/radio.log", $log);
		$result = array_reverse($log);
	}
	
	if ($_POST['proxima'] == "sim") {
		exec("xmms-shell -e next");
	}

	echo "<tr bgcolor='#d3d3d3'>
				<th colspan=3>Hora Inicio e Arquivo</th>
			</tr>";
	$i = 0;
	while ($result[$i]) {
		echo "<tr bgcolor='#d3d3d3'>
				<td colspan=3> $result[$i] </td>";
				$i++;
	}

	echo "</tbody>
		</table>";
?>

</body>
</html>
