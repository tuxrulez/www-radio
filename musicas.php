<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Cadastro de Musicas</title>
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
				<td> <b>Musicas</b> </td>
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
      			<td> <a href="logs.php"><b>Logs</b></a> </td>
    		</tr>
		</table>
	  </td>

<form action="musicas.php" method="POST" enctype="multipart/form-data">
      <td>
	  	<table cellspacing="0" border="1" cellpadding="0">
			<tr>
				<td>Musica: <br>
<?php
	include "config.inc.php";
	mysql_connect("localhost", "root", "");
	mysql_select_db("radio");

	if ($_POST['excluir'] == "sim") {
			$result = mysql_query("SELECT genero FROM arquivos WHERE arquivo='$_POST[arquivo]'");
			$row = mysql_fetch_object($result);
			exec("rm -f '/usr/local/radio/generos/musical/$row->genero/$_POST[arquivo]'");
			mysql_query("DELETE FROM arquivos WHERE arquivo='$_POST[arquivo]'");
	} elseif ($_POST['atualiza'] == "sim") {
		exec("./musical.php");
	} elseif ($_POST['refazer'] == "sim") {
		mysql_query("DELETE FROM arquivos WHERE tipo='musical'");
		mysql_query("DELETE FROM generos WHERE tipo='musical'");
		exec("./musical.php");
	}

	$result = mysql_query("SELECT arquivo FROM arquivos WHERE tipo='musical' ORDER BY arquivo");
		echo "<select name=arquivo>";
		echo "<option></option>";
	while ($row = mysql_fetch_object($result)) {
		echo "<option >$row->arquivo</option>";
	}

	echo "</select></td>";
	mysql_free_result($result);
?>
				<td>Rede:<br>
<?php
		echo "<select name=rede>";
		echo "<option>$rede</option>";
		echo "</select></td>";
?>
      <td>Loja:<br><select name="loja">
<?php
	echo "<option>$loja</option>";
	echo "</select></td>";
?>	</tr>
	<tr>
	  <td align="center"> Recadastrar musicas?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
	  Sim <input type="radio" name="refazer" value="sim">
	  Nao <input type="radio" checked name="refazer" value="nao">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <input type="submit" name="ok" value="Ok" align="right"></td>
	  <td>Incluir musicas?<br>Sim <input type="radio" name="atualiza" value="sim">
	  Nao <input type="radio" checked name="atualiza" value="nao"></td>
	  <td align="center">Excluir musica?<br>Sim <input type="radio" name="excluir" value="sim">
	  Nao <input type="radio" checked name="excluir" value="nao"></td>
	</tr>
</form>

<?php
	$result = mysql_query("SELECT * FROM arquivos WHERE tipo='musical' ORDER BY genero,arquivo");
		echo "<tr bgcolor='#d3d3d3'>
					<th>Musica</th>
					<th colspan=2>Genero</th>
				</tr>";
	while ($row = mysql_fetch_object($result)) {
		echo "<tr bgcolor='#d3d3d3'>
					<td align=center> $row->arquivo </td>
					<td colspan=2 align=center> $row->genero </td>
				</tr>";
	}
	echo "</tbody>
		</table>";

	mysql_free_result($result);
    mysql_close();
?>

</body>
</html>
