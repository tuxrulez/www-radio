<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Cadastro de Grades</title>
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
				<td> <b>Grades</b> </td>
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

<form action="grades.php" method="POST" enctype="multipart/form-data">
      <td>
	  	<table cellspacing="0" border="1" cellpadding="0">
			<tr>
				<td> Esquema:
<?php
	mysql_connect("localhost", "root", "");
	mysql_select_db("radio");

	$result = mysql_query("SELECT esquema FROM esquemas");
		echo "<select name=esquema>";
		echo "<option></option>";
	while ($row = mysql_fetch_object($result)) {
		echo "<option>$row->esquema</option>";
	}

	echo "</select></td>";

	mysql_free_result($result);
?>
      <td> Sequencia: <input type="text" name="sequencia" value="<?php echo $_POST['sequencia'] + 1; ?>" size="3" maxlength="3"> </td>
	  <td> Tipo:
<?php
	$result = mysql_query("SELECT tipo FROM tipos");
	echo "<select name=tipo>";
	echo "<option></option>";
	while ($row = mysql_fetch_object($result)) {
		echo "<option>$row->tipo</option>";
	}

	echo "</select></td>";
	mysql_free_result($result);
?>
	  <td> Genero:
<?php
	$result = mysql_query("SELECT genero FROM generos");
	echo "<select name=genero>";
	echo "<option></option>";
	while ($row = mysql_fetch_object($result)) {
		echo "<option>$row->genero</option>";
	}

	echo "</select></td>";
	mysql_free_result($result);
?>
	  <td align="center"> <input type="submit" name="ok" value="Ok"> </td>
    </tr>

<?php
	if (($_POST['esquema'] != "") && ($_POST['sequencia'] != "") && ($_POST['tipo'] != "") && ($_POST['genero'] != "")) {
		mysql_query("INSERT INTO `grades` ( `esquema`, `sequencia`, `tipo`, `genero` )
		VALUES ( '$_POST[esquema]', '$_POST[sequencia]', '$_POST[tipo]', '$_POST[genero]' )");
	} else {
		$result = mysql_query("SELECT esquema,sequencia FROM grades");
		while ($row = mysql_fetch_object($result)) {
			if ($_POST["$row->esquema$row->sequencia"] == "on") {
				mysql_query("DELETE FROM grades WHERE esquema='$row->esquema' AND sequencia='$row->sequencia'");
			}
		}
	}

	$result = mysql_query("SELECT * FROM grades ORDER BY esquema,sequencia");
		echo "<tr bgcolor='#d3d3d3'>
					<th>Esquema</th>
					<th>Sequencia</th>
					<th>Tipo</th>
					<th>Genero</th>
					<th>Excluir?</th>
				</tr>";

	while ($row = mysql_fetch_object($result)) {
		echo "<tr bgcolor='#d3d3d3'>
					<td align=center> $row->esquema </td>
					<td align=center> $row->sequencia </td>
					<td align=center> $row->tipo </td>
					<td align=center> $row->genero </td>
					<td align=center><input type=checkbox name=$row->esquema$row->sequencia></td>
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
