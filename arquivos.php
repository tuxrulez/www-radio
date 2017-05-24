<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Cadastro de Arquivos</title>
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
				<td> <b>Arquivos</b> </td>
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
      			<td> <a href="logs.php"><b>Logs</b></a> </td>
    		</tr>
		</table>
	  </td>

<form action="arquivos.php" method="POST" enctype="multipart/form-data">
      <td>
	  	<table cellspacing="0" border="1" cellpadding="0">
			<tr>
				<td>Arquivo: <br>
<?php
	include "config.inc.php";
	mysql_connect("localhost", "root", "");
	mysql_select_db("radio");

	if ($_POST['arquivo'] != "") {
		($_POST['dom'] == "on") ? $dom="1" : $dom="";
		($_POST['seg'] == "on") ? $seg="2" : $seg="";
		($_POST['ter'] == "on") ? $ter="3" : $ter="";
		($_POST['qua'] == "on") ? $qua="4" : $qua="";
		($_POST['qui'] == "on") ? $qui="5" : $qui="";
		($_POST['sex'] == "on") ? $sex="6" : $sex="";
		($_POST['sab'] == "on") ? $sab="7" : $sab="";

		$inicio = $_POST['hora_inicio']*3600 + $_POST['minuto_inicio']*60;
		$fim = $_POST['hora_fim']*3600 + $_POST['minuto_fim']*60 + 59;

		mysql_query("UPDATE arquivos SET data_inicio='$_POST[ano_inicio]-$_POST[mes_inicio]-$_POST[dia_inicio]',
				data_fim='$_POST[ano_fim]-$_POST[mes_fim]-$_POST[dia_fim]',
				dia_semana='$dom,$seg,$ter,$qua,$qui,$sex,$sab',
				hora_inicio='$inicio',
				hora_fim='$fim',
				rede='$_POST[rede]',
				loja='$_POST[loja]'
				WHERE arquivo='$_POST[arquivo]'");
		if ($_POST['excluir'] == "sim") {
			$result = mysql_query("SELECT genero FROM arquivos WHERE arquivo='$_POST[arquivo]'");
			$row = mysql_fetch_object($result);
			exec("rm -f '/usr/local/radio/generos/comercial/$row->genero/$_POST[arquivo]'");
			exec("rm -f '/mnt/update/$_POST[rede]/generos/$_POST[loja]/$row->genero/$_POST[arquivo]'");
			mysql_query("DELETE FROM arquivos WHERE arquivo='$_POST[arquivo]'");
		}
	} elseif ($_POST['atualiza'] == "sim") {
		exec("./comercial.php");
	} elseif ($_POST['refazer'] == "sim") {
		mysql_query("DELETE FROM arquivos WHERE tipo='comercial'");
		mysql_query("DELETE FROM generos WHERE tipo='comercial'");
		exec("./comercial.php");
	}

	$result = mysql_query("SELECT arquivo FROM arquivos WHERE tipo='comercial'");
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
?>
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
	<tr>
		<td align="center">
	  		Dom<input type="checkbox" checked name="dom">
	  		Seg<input type="checkbox" checked name="seg">
			Ter<input type="checkbox" checked name="ter">
	  		Qua<input type="checkbox" checked name="qua">
	  		Qui<input type="checkbox" checked name="qui">
	  		Sex<input type="checkbox" checked name="sex">
	  		Sab<input type="checkbox" checked name="sab">
		</td>
	  <td colspan="2">Inicio<br>Hora:<select name="hora_inicio">
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
	</tr>
	<tr>
	  <td align="center"> Recadastrar arquivos?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
	  Sim <input type="radio" name="refazer" value="sim">
	  Nao <input type="radio" checked name="refazer" value="nao">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <input type="submit" name="ok" value="Ok" align="right"></td>
	  <td colspan="2">Fim<br>Hora:<select name="hora_fim">
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
	  <td>Incluir arquivos?<br>Sim <input type="radio" name="atualiza" value="sim">
	  Nao <input type="radio" checked name="atualiza" value="nao"></td>
	  <td colspan="2" align="center">Excluir arquivo?<br>Sim <input type="radio" name="excluir" value="sim">
	  Nao <input type="radio" checked name="excluir" value="nao"></td>
	</tr>
</form>

<?php
	$result = mysql_query("SELECT * FROM arquivos WHERE tipo='comercial' ORDER BY genero");
		echo "<tr bgcolor='#d3d3d3'>
					<th>Arquivo</th>
					<th>Data Inicial</th>
					<th>Data Final</th>
					<th>Dia da Semana</th>
					<th>Hora Inicial</th>
					<th>Hora Final</th>
				</tr>";

	while ($row = mysql_fetch_object($result)) {
		$gm_inicio = gmdate("H:i:s",$row->hora_inicio);
		$gm_fim = gmdate("H:i:s",$row->hora_fim);
		echo "<tr bgcolor='#d3d3d3'>
					<td align=center> $row->arquivo </td>
					<td align=center> $row->data_inicio </td>
					<td align=center> $row->data_fim </td>
					<td align=center> $row->dia_semana </td>
					<td align=center> $gm_inicio </td>
					<td align=center> $gm_fim </td>
				</tr>";
	}

	echo "</tbody>
		</table>";

	mysql_free_result($result);
    mysql_close();
?>

</body>
</html>
