<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Cadastro de Esquemas</title>
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
				<td> <b>Esquemas</b> </td>
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
      			<td> <a href="logs.php"><b>Logs</b></a> </td>
    		</tr>
		</table>
	  </td>

<form action="esquemas.php" method="POST" enctype="multipart/form-data">
      <td>
	  	<table cellspacing="0" border="1" cellpadding="0">
			<tr>
				<td> Esquema: <input type="text" name="esquema" size="20" maxlength="30"> </td>
	  <td align="center"> <input type="submit" name="ok" value="Ok"> </td>
    </tr>

<?php
	mysql_connect("localhost", "root", "");
	mysql_select_db("radio");

	if ($_POST['esquema'] != "") {
		mysql_query("INSERT INTO `esquemas` ( `esquema` )
		VALUES ( '$_POST[esquema]' )");
	} else {
		$result = mysql_query("SELECT esquema FROM esquemas");
		while ($row = mysql_fetch_object($result)) {
			if ($_POST["$row->esquema"] == "on") {
				mysql_query("DELETE FROM esquemas WHERE esquema='$row->esquema'");
				mysql_query("DELETE FROM grades WHERE esquema='$row->esquema'");
				mysql_query("DELETE FROM programacoes WHERE esquema='$row->esquema'");
			}
		}
	}

	$result = mysql_query("SELECT esquema FROM esquemas");
		echo "<tr bgcolor='#d3d3d3'>
					<th>Esquema</th>
					<th>Excluir?</th>
				</tr>";

	while ($row = mysql_fetch_object($result)) {
		echo "<tr bgcolor='#d3d3d3'>
					<td align=center> $row->esquema </td>
					<td align=center><input type=checkbox name=$row->esquema></td>
				</tr>";
	}

	echo "</tbody>
		</table>";

	mysql_free_result($result);
    mysql_close();
?>

</form>
</body>
</html>
