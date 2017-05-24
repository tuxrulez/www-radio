<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Cadastro de Programacoes</title>
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
				<td> <b>Programacoes</b> </td>
			</tr>
			<tr>
      			<td> <a href="playlists.php"><b>Playlists</b></a> </td>
    		</tr>
			<tr>
      			<td> <a href="logs.php"><b>Logs</b></a> </td>
    		</tr>
		</table>
	  </td>

<form action="programacoes.php" method="POST" enctype="multipart/form-data">
      <td>
	  	<table cellspacing="0" border="1" cellpadding="0">
			<tr>
				<td>Rede:<br>
<?php
	include "config.inc.php";
	mysql_connect("localhost", "root", "");
	mysql_select_db("radio");

	echo "<select name=rede>";
	echo "<option>$rede</option>";
	echo "</select></td>";
?>
				<td>Loja:<br>
<?php
	echo "<select name=loja>";
	echo "<option>$loja</option>";
	echo "</select></td>";
?>
      <td>Esquema:<br><select name="esquema">
<?php
	echo "<option></option>";
	$result = mysql_query("SELECT esquema FROM esquemas");

	while ($row = mysql_fetch_object($result)) {
		echo "<option >$row->esquema</option>";
	}

	echo "</select></td>";

	mysql_free_result($result);
?>
		<td colspan="6" align="center">
	  		Dom<input type="checkbox" checked name="dom">
	  		Seg<input type="checkbox" checked name="seg">
			Ter<input type="checkbox" checked name="ter">
	  		Qua<input type="checkbox" checked name="qua">
	  		Qui<input type="checkbox" checked name="qui">
	  		Sex<input type="checkbox" checked name="sex">
	  		Sab<input type="checkbox" checked name="sab">
		</td>
	</tr>
	<tr>
	  <td colspan="3"></td>
	  <td colspan="3">Inicio<br>Dia:<select name="dia_inicio">
            <option selected>1</option>
			<?php
			for ($i = 2; $i <= 31; $i++) {
				echo "<option >$i</option>";
			}
			?>
	  </select> Mes: <select name="mes_inicio">
            <option selected>1</option>
			<?php
			for ($i = 2; $i <= 12; $i++) {
				echo "<option >$i</option>";
			}
			?>
	  </select> Ano: <select name="ano_inicio">
            <option selected>2004</option>
			<?php
			for ($i = 2005; $i <= 2010; $i++) {
				echo "<option >$i</option>";
			}
			?>
	  </select></td>
	  <td colspan="3">Inicio<br>Hora:<select name="hora_inicio">
            <option selected>0</option>
			<?php
			for ($i = 1; $i <= 23; $i++) {
				echo "<option >$i</option>";
			}
			?>
	  </select> Minuto:<select name="minuto_inicio">
            <option selected>0</option>
			<?php
			for ($i = 1; $i <= 59; $i++) {
				echo "<option >$i</option>";
			}
			?>
	  </select></td>
	</tr>
	<tr>
	  <td colspan="3" align="center"> <input type="submit" name="ok" value="Ok"> </td>
	  <td colspan="3">Fim<br>Dia: <select name="dia_fim">
            <option selected>31</option>
			<?php
			for ($i = 1; $i <= 31; $i++) {
				echo "<option >$i</option>";
			}
			?>
	  </select> Mes: <select name="mes_fim">
            <option selected>12</option>
			<?php
			for ($i = 1; $i <= 12; $i++) {
				echo "<option >$i</option>";
			}
			?>
	  </select> Ano: <select name="ano_fim">
            <option selected>2010</option>
			<?php
			for ($i = 2004; $i <= 2010; $i++) {
				echo "<option >$i</option>";
			}
			?>
	  </select></td>
	  <td colspan="3">Fim<br>Hora:<select name="hora_fim">
            <option selected>23</option>
			<?php
			for ($i = 0; $i <= 23; $i++) {
				echo "<option >$i</option>";
			}
			?>
	  </select> Minuto:<select name="minuto_fim">
            <option selected>59</option>
			<?php
			for ($i = 0; $i <= 59; $i++) {
				echo "<option >$i</option>";
			}
			?>
	  </select></td>
	</tr>

<?php
	if ($_POST['esquema'] != "") {
		($_POST['dom'] == "on") ? $dom="1" : $dom="";
		($_POST['seg'] == "on") ? $seg="2" : $seg="";
		($_POST['ter'] == "on") ? $ter="3" : $ter="";
		($_POST['qua'] == "on") ? $qua="4" : $qua="";
		($_POST['qui'] == "on") ? $qui="5" : $qui="";
		($_POST['sex'] == "on") ? $sex="6" : $sex="";
		($_POST['sab'] == "on") ? $sab="7" : $sab="";
		$inicio = $_POST['hora_inicio']*3600 + $_POST['minuto_inicio']*60;
		$fim = $_POST['hora_fim']*3600 + $_POST['minuto_fim']*60 + 59;
		mysql_query("INSERT INTO `programacoes` ( `rede`, `loja`, `esquema`, `data_inicio`, `data_fim`, `dia_semana`,
					`hora_inicio`, hora_fim ) VALUES ( '$_POST[rede]', '$_POST[loja]', '$_POST[esquema]',
					'$_POST[ano_inicio]-$_POST[mes_inicio]-$_POST[dia_inicio]',
					'$_POST[ano_fim]-$_POST[mes_fim]-$_POST[dia_fim]',
					'$dom,$seg,$ter,$qua,$qui,$sex,$sab',
					'$inicio', '$fim' )");
	} else {
		$result = mysql_query("SELECT rede,data_inicio,data_fim,dia_semana,hora_inicio,hora_fim FROM programacoes");
		while ($row = mysql_fetch_object($result)) {
			if
($_POST["$row->rede$row->data_inicio$row->data_fim$row->dia_semana$row->hora_inicio$row->hora_fim"] == "on") {
				mysql_query("DELETE FROM programacoes WHERE rede='$row->rede' AND data_inicio='$row->data_inicio'
				AND data_fim='$row->data_fim' AND dia_semana='$row->dia_semana' AND
				hora_inicio='$row->hora_inicio' AND hora_fim='$row->hora_fim'");
			}
		}
	}

	$result = mysql_query("SELECT * FROM programacoes");
		echo "<tr bgcolor='#d3d3d3'>
					<th>Rede</th>
					<th>Loja</th>
					<th>Esquema</th>
					<th>Data Inicial</th>
					<th>Data Final</th>
					<th>Dia da Semana</th>
					<th>Hora Inicial</th>
					<th>Hora Final</th>
					<th>Excluir?</th>
				</tr>";

	while ($row = mysql_fetch_object($result)) {
		$gm_inicio = gmdate("H:i:s",$row->hora_inicio);
		$gm_fim = gmdate("H:i:s",$row->hora_fim);
		echo "<tr bgcolor='#d3d3d3'>
					<td align=center> $row->rede </td>
					<td align=center> $row->loja </td>
					<td align=center> $row->esquema </td>
					<td align=center> $row->data_inicio </td>
					<td align=center> $row->data_fim </td>
					<td align=center> $row->dia_semana </td>
					<td align=center> $gm_inicio </td>
					<td align=center> $gm_fim </td>
					<td align=center><input type=checkbox
name=$row->rede$row->data_inicio$row->data_fim$row->dia_semana$row->hora_inicio$row->hora_fim></td>
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
