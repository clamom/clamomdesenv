<?php
require("cn.php");
$mar = array(171,172,173,174);//mar01, mar02, mar03, mar04
//SALVAR ALERTAS
if($_REQUEST["op"]=="SalvarAlerta")
{
	if($_REQUEST["tipo"]=="3")//item
	{
		$resu = mysql_query("select * from cro_projeto_item_tarefa where id_item_tarefa=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//INSERT TABELA ALERTA		
		$sql="insert into orc_alerta(alert_sigla,id_ctr,id_sub_item,id_detalhe,alert_data,alert_hora,alert_mensagem,alert_status,alert_hora_criacao,alert_data_criacao,alert_usuario_criacao) values('CIT','".$_REQUEST["id_ctr"]."','".$row["id_etapa_item"]."','".$_REQUEST["id_detalhe"]."','".database($_REQUEST["dataAlerta"])."','".$_REQUEST["horaAlerta"]."','".strtoupper($_REQUEST["obsAlerta"])."','1',now(),now(),'".$_REQUEST["usuario"]."')";
		mysql_query($sql);
		$id_alerta = mysql_insert_id();
		$lista = explode(';',$_REQUEST["ListaLogin"]);
		foreach($lista as $login)
		{
			if($login != "")
			{
				//INSERT TABELA  ALERTA_USUARIOS
				$sql = "insert into orc_alerta_usuarios(id_alerta,login_usuario) values('".$id_alerta."','".$login."')";
				mysql_query($sql);
			}
		}
		$cor="#FFFFFF";
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CIT' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_etapa_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
		while($row1 = mysql_fetch_array($resu1))
		{	
			$usuarios = '';
			$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
			while($row2 = mysql_fetch_array($resu2))
			{
				$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
			}
			if($cor=="#FFFFFF") $cor="#DDDDDD";
			else $cor = "#FFFFFF";
			if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
			else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
	$tr .= "
		<tr style='background:".$cor.";'>
			<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
			<td valign='top' align='center'>".$row1["alert_hora"]."</td>
			<td valign='top'><ul>".$usuarios."</ul></td>
			<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
			<td valign='top'>".$row1["name"]."<br/>".$hora."</td>
			<td valign='top' align='center'>";
		if($row1["alert_status"]==1)
		{		
	$tr .= 	"<input type='image' id='".$row1["id_alerta"]."' src='images/edit.png' width='20' title='Editar' class='alterar_alerta'/>
			<input type='image' id='".$row1["id_alerta"]."' data_alerta='".$row1["alert_data"]."' src='images/delete.png' width='20' title='Apagar' class='apagar_alerta'/>";
		}
	$tr .=	"</td>
		</tr>";
		}
		echo $tr;
	}
	else//fase e etapa
	{	
		$resu = mysql_query("select * from cro_projeto_fase_e_etapa_tarefa where id=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//INSERT TABELA ALERTA		
		$sql="insert into orc_alerta(alert_sigla,id_ctr,id_sub_item,id_detalhe,alert_data,alert_hora,alert_mensagem,alert_status,alert_hora_criacao,alert_data_criacao,alert_usuario_criacao) values('CFA','".$_REQUEST["id_ctr"]."','".$row["id_fase_etapa"]."','".$_REQUEST["id_detalhe"]."','".database($_REQUEST["dataAlerta"])."','".$_REQUEST["horaAlerta"]."','".strtoupper($_REQUEST["obsAlerta"])."','1',now(),now(),'".$_REQUEST["usuario"]."')";
		mysql_query($sql);
		$id_alerta = mysql_insert_id();
		$lista = explode(';',$_REQUEST["ListaLogin"]);
		foreach($lista as $login)
		{
			if($login != "")
			{
				//INSERT TABELA  ALERTA_USUARIOS
				$sql = "insert into orc_alerta_usuarios(id_alerta,login_usuario) values('".$id_alerta."','".$login."')";
				mysql_query($sql);
			}
		}
		$cor="#FFFFFF";
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CFA' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_fase_etapa"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
		while($row1 = mysql_fetch_array($resu1))
		{	
			$usuarios = '';
			$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
			while($row2 = mysql_fetch_array($resu2))
			{
				$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
			}
			if($cor=="#FFFFFF") $cor="#DDDDDD";
			else $cor = "#FFFFFF";
			if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
			else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
	$tr .= "
		<tr style='background:".$cor.";'>
			<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
			<td valign='top' align='center'>".$row1["alert_hora"]."</td>
			<td valign='top'><ul>".$usuarios."</ul></td>
			<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
			<td valign='top'>".$row1["name"]."<br/>".$hora."</td>
			<td valign='top' align='center'>";
		if($row1["alert_status"]==1)
		{		
	$tr .= 	"<input type='image' id='".$row1["id_alerta"]."' src='images/edit.png' width='20' title='Editar' class='alterar_alerta'/>
			<input type='image' id='".$row1["id_alerta"]."' data_alerta='".$row1["alert_data"]."' src='images/delete.png' width='20' title='Apagar' class='apagar_alerta'/>";
		}
	$tr .=	"</td>
		</tr>";
		}
		echo $tr;
	}
}
//EDITAR ALERTAS
if($_REQUEST["op"]=="EditarAlerta")
{
	if($_REQUEST["tipo"]=="3")//item
	{
		$resu = mysql_query("select * from cro_projeto_item_tarefa where id_item_tarefa=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//UPDATE TABELA ALERTA
		$sql="update orc_alerta set alert_data='".database($_REQUEST["dataAlerta"])."',alert_hora='".$_REQUEST["horaAlerta"]."',alert_mensagem='".strtoupper($_REQUEST["obsAlerta"])."',alert_hora_update=now(),alert_data_update=now() where id_alerta='".$_REQUEST["id_alerta"]."'";
		mysql_query($sql);
		//DELETE orc_alerta_usuarios ANTIGOS
		$sql="delete from orc_alerta_usuarios where id_alerta='".$_REQUEST["id_alerta"]."'";
		mysql_query($sql);
		$lista = explode(';',$_REQUEST["ListaLogin"]);
		foreach($lista as $login)
		{
			if($login != "")
			{
				//INSERT TABELA  ALERTA_USUARIOS
				$sql = "insert into orc_alerta_usuarios(id_alerta,login_usuario) values('".$_REQUEST["id_alerta"]."','".$login."')";
				mysql_query($sql);
			}
		}
		$cor="#FFFFFF";
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CIT' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_etapa_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
		while($row1 = mysql_fetch_array($resu1))
		{	
			$usuarios = '';
			$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
			while($row2 = mysql_fetch_array($resu2))
			{
				$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
			}
			if($cor=="#FFFFFF") $cor="#DDDDDD";
			else $cor = "#FFFFFF";
			if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
			else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
	$tr .= "
		<tr style='background:".$cor.";'>
			<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
			<td valign='top' align='center'>".$row1["alert_hora"]."</td>
			<td valign='top'><ul>".$usuarios."</ul></td>
			<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
			<td valign='top'>".$row1["name"]."<br/>".$hora."</td>
			<td valign='top' align='center'>";
		if($row1["alert_status"]==1)
		{		
	$tr .= 	"<input type='image' id='".$row1["id_alerta"]."' src='images/edit.png' width='20' title='Editar' class='alterar_alerta'/>
			<input type='image' id='".$row1["id_alerta"]."' data_alerta='".$row1["alert_data"]."' src='images/delete.png' width='20' title='Apagar' class='apagar_alerta'/>";
		}
	$tr .=	"</td>
		</tr>";
		}
		echo $tr;
	}
	else//fase e etapa
	{
		$resu = mysql_query("select * from cro_projeto_fase_e_etapa_tarefa where id=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//UPDATE TABELA ALERTA
		$sql="update orc_alerta set alert_data='".database($_REQUEST["dataAlerta"])."',alert_hora='".$_REQUEST["horaAlerta"]."',alert_mensagem='".strtoupper($_REQUEST["obsAlerta"])."',alert_hora_update=now(),alert_data_update=now() where id_alerta='".$_REQUEST["id_alerta"]."'";
		mysql_query($sql);
		//DELETE orc_alerta_usuarios ANTIGOS
		$sql="delete from orc_alerta_usuarios where id_alerta='".$_REQUEST["id_alerta"]."'";
		mysql_query($sql);
		$lista = explode(';',$_REQUEST["ListaLogin"]);
		foreach($lista as $login)
		{
			if($login != "")
			{
				//INSERT TABELA  ALERTA_USUARIOS
				$sql = "insert into orc_alerta_usuarios(id_alerta,login_usuario) values('".$_REQUEST["id_alerta"]."','".$login."')";
				mysql_query($sql);
			}
		}
		$cor="#FFFFFF";
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CFA' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_fase_etapa"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
		while($row1 = mysql_fetch_array($resu1))
		{	
			$usuarios = '';
			$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
			while($row2 = mysql_fetch_array($resu2))
			{
				$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
			}
			if($cor=="#FFFFFF") $cor="#DDDDDD";
			else $cor = "#FFFFFF";
			if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
			else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
	$tr .= "
		<tr style='background:".$cor.";'>
			<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
			<td valign='top' align='center'>".$row1["alert_hora"]."</td>
			<td valign='top'><ul>".$usuarios."</ul></td>
			<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
			<td valign='top'>".$row1["name"]."<br/>".$hora."</td>
			<td valign='top' align='center'>";
		if($row1["alert_status"]==1)
		{		
	$tr .= 	"<input type='image' id='".$row1["id_alerta"]."' src='images/edit.png' width='20' title='Editar' class='alterar_alerta'/>
			<input type='image' id='".$row1["id_alerta"]."' data_alerta='".$row1["alert_data"]."' src='images/delete.png' width='20' title='Apagar' class='apagar_alerta'/>";
		}
	$tr .=	"</td>
		</tr>";
		}
		echo $tr;
	}
}
//LISTAR TODAS AS ALERTAS
if($_REQUEST["op"]=="ListarAlert")
{
	if($_REQUEST["tipo"]=="3")//item
	{
		$resu = mysql_query("select * from cro_projeto_item_tarefa where id_item_tarefa=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//
		$cor="#FFFFFF";
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CIT' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_etapa_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
		while($row1 = mysql_fetch_array($resu1))
		{	
			$usuarios = '';
			$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
			while($row2 = mysql_fetch_array($resu2))
			{
				$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
			}
			if($cor=="#FFFFFF") $cor="#DDDDDD";
			else $cor = "#FFFFFF";
			if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
			else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
	$tr .= "
		<tr style='background:".$cor.";'>
			<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
			<td valign='top' align='center'>".$row1["alert_hora"]."</td>
			<td valign='top'><ul>".$usuarios."</ul></td>
			<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
			<td valign='top'>".$row1["name"]."<br/>".$hora."</td>
			<td valign='top' align='center'>";
		if($row1["alert_status"]==1)
		{		
	$tr .= 	"<input type='image' id='".$row1["id_alerta"]."' src='images/edit.png' width='20' title='Editar' class='alterar_alerta'/>
			<input type='image' id='".$row1["id_alerta"]."' data_alerta='".$row1["alert_data"]."' src='images/delete.png' width='20' title='Apagar' class='apagar_alerta'/>";
		}
	$tr .=	"</td>
		</tr>";
		}
		echo $tr;
	}
	else//fase e etapa
	{
		$resu = mysql_query("select * from cro_projeto_fase_e_etapa_tarefa where id=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//
		$cor="#FFFFFF";
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CFA' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_fase_etapa"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
		while($row1 = mysql_fetch_array($resu1))
		{	
			$usuarios = '';
			$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
			while($row2 = mysql_fetch_array($resu2))
			{
				$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
			}
			if($cor=="#FFFFFF") $cor="#DDDDDD";
			else $cor = "#FFFFFF";
			if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
			else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
	$tr .= "
		<tr style='background:".$cor.";'>
			<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
			<td valign='top' align='center'>".$row1["alert_hora"]."</td>
			<td valign='top'><ul>".$usuarios."</ul></td>
			<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
			<td valign='top'>".$row1["name"]."<br/>".$hora."</td>
			<td valign='top' align='center'>";
		if($row1["alert_status"]==1)
		{		
	$tr .= 	"<input type='image' id='".$row1["id_alerta"]."' src='images/edit.png' width='20' title='Editar' class='alterar_alerta'/>
			<input type='image' id='".$row1["id_alerta"]."' data_alerta='".$row1["alert_data"]."' src='images/delete.png' width='20' title='Apagar' class='apagar_alerta'/>";
		}
	$tr .=	"</td>
		</tr>";
		}
		echo $tr;
	}
}
//LISTAR TODAS AS ALERTAS
if($_REQUEST["op"]=="ListarAlert2")
{
	if($_REQUEST["tipo"]=="3")//item
	{
		$resu = mysql_query("select * from cro_projeto_item_tarefa where id_item_tarefa=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//
		$cor="#FFFFFF";
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CIT' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_etapa_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' and a.alert_data='".$_REQUEST["dataDT"]."' order by a.alert_data, a.alert_hora");
		while($row1 = mysql_fetch_array($resu1))
		{	
			$usuarios = '';
			$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
			while($row2 = mysql_fetch_array($resu2))
			{
				$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
			}
			if($cor=="#FFFFFF") $cor="#DDDDDD";
			else $cor = "#FFFFFF";
			if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
			else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
	$tr .= "
		<tr style='background:".$cor.";'>
			<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
			<td valign='top' align='center'>".$row1["alert_hora"]."</td>
			<td valign='top'><ul>".$usuarios."</ul></td>
			<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
			<td valign='top'>".$row1["name"]."<br/>".$hora."</td>
		</tr>";
		}
		echo $tr;
	}
	else//fase e etapa
	{
		$resu = mysql_query("select * from cro_projeto_fase_e_etapa_tarefa where id=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//
		$cor="#FFFFFF";
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CFA' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_fase_etapa"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' and a.alert_data='".$_REQUEST["dataDT"]."' order by a.alert_data, a.alert_hora");
		while($row1 = mysql_fetch_array($resu1))
		{	
			$usuarios = '';
			$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
			while($row2 = mysql_fetch_array($resu2))
			{
				$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
			}
			if($cor=="#FFFFFF") $cor="#DDDDDD";
			else $cor = "#FFFFFF";
			if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
			else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
	$tr .= "
		<tr style='background:".$cor.";'>
			<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
			<td valign='top' align='center'>".$row1["alert_hora"]."</td>
			<td valign='top'><ul>".$usuarios."</ul></td>
			<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
			<td valign='top'>".$row1["name"]."<br/>".$hora."</td>
		</tr>";
		}
		echo $tr;
	}
}
//TOTAL DE ALERTAS WHERE DATA E DETALHE_TAREFA
if($_REQUEST["op"]=="CountAlert")
{
	if($_REQUEST["tipo"]=="3")//item
	{
		$resu = mysql_query("select * from cro_projeto_item_tarefa where id_item_tarefa=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//
		$resu1 = mysql_query("select * from orc_alerta where alert_sigla='CIT' and id_ctr='".$_REQUEST["id_ctr"]."' and id_sub_item='".$row["id_etapa_item"]."' and id_detalhe='".$_REQUEST["id_detalhe"]."' and alert_data='".$_REQUEST["dataDT"]."' order by alert_data, alert_hora");
		echo mysql_num_rows($resu1);
	}
	else//fase e etapa
	{
		$resu = mysql_query("select * from cro_projeto_fase_e_etapa_tarefa where id=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//
		$resu1 = mysql_query("select * from orc_alerta where alert_sigla='CFA' and id_ctr='".$_REQUEST["id_ctr"]."' and id_sub_item='".$row["id_fase_etapa"]."' and id_detalhe='".$_REQUEST["id_detalhe"]."' and alert_data='".$_REQUEST["dataDT"]."' order by alert_data, alert_hora");
		echo mysql_num_rows($resu1);
	}
}
//LISTAR DATAS DO ALERTA
if($_REQUEST["op"]=="AlertDatas")
{
	if($_REQUEST["tipo"]=="3")//item
	{
		$resu = mysql_query("select * from cro_projeto_item_tarefa where id_item_tarefa=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CIT' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_etapa_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' group by alert_data order by a.alert_data");
		while($row1 = mysql_fetch_array($resu1))
		{
			$vetor[] = $row1["alert_data"];
		}
		echo json_encode($vetor);
	}
	else//fase e etapa
	{
		$resu = mysql_query("select * from cro_projeto_fase_e_etapa_tarefa where id=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CFA' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_fase_etapa"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' group by alert_data order by a.alert_data");
		while($row1 = mysql_fetch_array($resu1))
		{
			$vetor[] = $row1["alert_data"];
		}
		echo json_encode($vetor); 
	}
}
if($_REQUEST["op"]=="ApagarAlerta")
{
	if($_REQUEST["tipo"]=="3")//item
	{
		//delete tabela alerta
		$sql="delete from orc_alerta where id_alerta='".$_REQUEST["id_alerta"]."'";
		mysql_query($sql);
		//delete tabela alerta_usuarios
		$sql="delete from orc_alerta_usuarios where id_alerta='".$_REQUEST["id_alerta"]."'";
		mysql_query($sql);
		//
		$resu = mysql_query("select * from cro_projeto_item_tarefa where id_item_tarefa=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//
		$cor="#FFFFFF";
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CIT' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_etapa_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
		while($row1 = mysql_fetch_array($resu1))
		{	
			$usuarios = '';
			$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
			while($row2 = mysql_fetch_array($resu2))
			{
				$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
			}
			if($cor=="#FFFFFF") $cor="#DDDDDD";
			else $cor = "#FFFFFF";
			if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
			else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
	$tr .= "
		<tr style='background:".$cor.";'>
			<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
			<td valign='top' align='center'>".$row1["alert_hora"]."</td>
			<td valign='top'><ul>".$usuarios."</ul></td>
			<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
			<td valign='top'>".$row1["name"]."<br/>".$hora."</td>
			<td valign='top' align='center'>";
		if($row1["alert_status"]==1)
		{		
	$tr .= 	"<input type='image' id='".$row1["id_alerta"]."' src='images/edit.png' width='20' title='Editar' class='alterar_alerta'/>
			<input type='image' id='".$row1["id_alerta"]."' data_alerta='".$row1["alert_data"]."' src='images/delete.png' width='20' title='Apagar' class='apagar_alerta'/>";
		}
	$tr .=	"</td>
		</tr>";
		}
		echo $tr;
	}
	else//fase e etapa
	{
		//delete tabela alerta
		$sql="delete from orc_alerta where id_alerta='".$_REQUEST["id_alerta"]."'";
		mysql_query($sql);
		//delete tabela alerta_usuarios
		$sql="delete from orc_alerta_usuarios where id_alerta='".$_REQUEST["id_alerta"]."'";
		mysql_query($sql);
		//
		$resu = mysql_query("select * from cro_projeto_fase_e_etapa_tarefa where id=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//
		$cor="#FFFFFF";
		$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CFA' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_fase_etapa"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
		while($row1 = mysql_fetch_array($resu1))
		{	
			$usuarios = '';
			$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
			while($row2 = mysql_fetch_array($resu2))
			{
				$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
			}
			if($cor=="#FFFFFF") $cor="#DDDDDD";
			else $cor = "#FFFFFF";
			if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
			else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
	$tr .= "
		<tr style='background:".$cor.";'>
			<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
			<td valign='top' align='center'>".$row1["alert_hora"]."</td>
			<td valign='top'><ul>".$usuarios."</ul></td>
			<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
			<td valign='top'>".$row1["name"]."<br/>".$hora."</td>
			<td valign='top' align='center'>";
		if($row1["alert_status"]==1)
		{		
	$tr .= 	"<input type='image' id='".$row1["id_alerta"]."' src='images/edit.png' width='20' title='Editar' class='alterar_alerta'/>
			<input type='image' id='".$row1["id_alerta"]."' data_alerta='".$row1["alert_data"]."' src='images/delete.png' width='20' title='Apagar' class='apagar_alerta'/>";
		}
	$tr .=	"</td>
		</tr>";
		}
		echo $tr;
	}
}
if($_REQUEST["op"]=="DadosAlert")
{
	$resu = mysql_query("select * from orc_alerta where id_alerta='".$_REQUEST["id_alerta"]."'");
	$row = mysql_fetch_array($resu);
	$vetor[0] = dataform($row["alert_data"]);//data alerta
	$vetor[1] = $row["alert_hora"];//hora alerta
	$vetor[2] = $row["alert_mensagem"];//observação alerta
	$i = 0;//listrar usuarios para checkbox
	$resu1 = mysql_query("select * from orc_alerta_usuarios where id_alerta='".$_REQUEST["id_alerta"]."' order by login_usuario");
	while($row1 = mysql_fetch_array($resu1))
	{
		if($i == 0)	$lista .= trim($row1["login_usuario"]);
		else $lista .= ";".trim($row1["login_usuario"]);
		$i++;
	}
	$vetor[3] = $lista;
	//listar usuarios para selecUsuario
	$lista2 = "<ul id='selecUsuario'>";
	$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario = u.login where a.id_alerta='".$_REQUEST["id_alerta"]."' order by u.name");
	while($row2 = mysql_fetch_array($resu2))
	{
		$lista2 .= '<li id="'.trim($row2["login_usuario"]).'" style="clear:both;">'.trim($row2["nome"]).'</li>';
	}
	$lista2 .= "</ul>";
	$vetor[4] = $lista2;
	echo json_encode($vetor); 
}
if($_REQUEST["op"]=="ListarResponsavel")
{
	if($_REQUEST["TarefaId"]==0)
	{
		$resu = mysql_query("select *,upper(u.name) as nome from tb_projeto_detalhe_tarefa t inner join sec_users u on t.responsavel = u.login where t.id='".$_REQUEST["id_detalhe"]."'");
		$row = mysql_fetch_array($resu);	
		echo '<option value="'.$row["login"].'">'.$row["nome"].'</option>';
	}
	elseif($_REQUEST["TarefaId"]>0)
	{
		$resu    = mysql_query("select * from tb_projeto_detalhe_tarefa where id='".$_REQUEST["id_detalhe"]."'");
		$row     = mysql_fetch_array($resu);
		$resu_id = mysql_query("select * from tb_projeto_tarefas where id=(select e_terceirizado from tb_projeto_tarefas where id='".$row["id_tarefa"]."')");
		$row_id  = mysql_fetch_array($resu_id);
    	$select  .= '<option value="0">--SELECIONAR--</option>';
$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email FROM sec_users u inner join sec_users_groups p on u.login = p.login inner join sec_groups g on g.group_id = p.group_id where u.active='Y' and p.group_id='".$row_id["user_grupo"]."' order by u.name");
		while($itemUsuario = mysql_fetch_array($listaUsuario))
		{
			$selected = "";
			if($row["executor"]==$itemUsuario["login"]) 
			{
				$selected='selected="selected"';
			}
        	$select .= '<option value="'.$_REQUEST["id_detalhe"]."_".$itemUsuario["login"].'" '.$selected.' >'.$itemUsuario["nome"].'</option>';
	 	}
		echo $select;
	}
}
if($_REQUEST["op"]=="SalvarResponsavel")
{
	$vetor = explode('_',$_REQUEST["responsavel"]);
	$sql="update tb_projeto_detalhe_tarefa set executor='".$vetor["1"]."',login='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$vetor["0"]."' ";
	if(!mysql_query($sql))
	{
		die('Erro no query: '.mysql_error());
	}
	else echo "Salvo com Sucesso!";
}
if($_REQUEST["op"]=="DadosTitulo")
{	
	if($_REQUEST["tipo"]=="3")//item
	{
		//dados do sub item
		$resu = mysql_query("select *,upper(i.desc_item) descricao_item from cro_projeto_etapa_item e inner join tb_projeto_itens i on e.id_item=i.id where e.id_etapa_item='".$_REQUEST["id_subitem"]."'");
		$row = mysql_fetch_array($resu);
		//dados da tarefa
		$resu1 = mysql_query("select *,upper(t.desc_tarefa) as desc_tarefa from cro_projeto_item_tarefa d inner join tb_projeto_tarefas t on d.id_tarefa=t.id where d.id_item_tarefa='".$_REQUEST["id_detalhe"]."'");
		$row1 = mysql_fetch_array($resu1);
		echo $row["descricao_item"]." | ".$row1["desc_tarefa"];
	}
	else//fase e etapa
	{
		//dados do sub item
		$resu = mysql_query("select upper(desc_etapa) as descricao_subitem from cro_projeto_fase_e_etapa where id='".$_REQUEST["id_subitem"]."'");
		$row = mysql_fetch_array($resu);
		//dados da tarefa
		$resu1 = mysql_query("select *,upper(t.desc_tarefa) as desc_tarefa from cro_projeto_fase_e_etapa_tarefa d inner join tb_projeto_tarefas t on d.id_tarefa=t.id where d.id='".$_REQUEST["id_detalhe"]."'");
		$row1 = mysql_fetch_array($resu1);
		echo $row["descricao_subitem"]." | ".$row1["desc_tarefa"];
	}
}
if($_REQUEST["op"]=="ListarDatas")
{
	if($_REQUEST["tipo"]=="3")//item
	{
		$resu = mysql_query("select DAY(data_inicio) dia1,MONTH(data_inicio) mes1,YEAR(data_inicio) ano1, DAY(data_final) dia2,MONTH(data_final) mes2, YEAR(data_final) ano2, data_inicio, data_final from cro_projeto_item_tarefa where id_item_tarefa='".$_REQUEST["id_detalhe"]."'");
		$row = mysql_fetch_array($resu);
		$dia1  = $row["dia1"];
		$mes1  = $row["mes1"];
		$ano1  = $row["ano1"];
		$dia2  = $row["dia2"];
		$mes2  = $row["mes2"];
		$ano2  = $row["ano2"];
		$meses = $mes2;
		$option = "<option value='--'>SELECIONE</option>";
		if($ano1 < $ano2)
		{
			$calulo = $ano2-$ano1;
			$meses = $mes2 + (12*$calulo);
		}
		if($row["data_inicio"]!="" and $row["data_final"]!="")
		{	
			for($k=$mes1; $k<=$meses; $k++)//while($mes <= $meses)
			{
				if($mes1 > 12)
				{
					$mes1 = 1;
					$ano1 ++;
				}
				$total_dias = cal_days_in_month(CAL_GREGORIAN, $mes1, $ano1);
				for ($i=1; $i<=$total_dias; $i++)
				{
					$data=$ano1."-".str_pad($mes1,2,"0",STR_PAD_LEFT)."-".str_pad($i,2,"0",STR_PAD_LEFT);
					if($data >= $row["data_inicio"] and $data <= $row["data_final"])
					{
						$option .= "<option value='".dataform($data)."'>".dataform($data)."</option>"; 					
					}
				}
				$mes1++;
			}
		}
		echo $option;
	}
	else//fase e etapa
	{
		$resu = mysql_query("select DAY(data_inicio) dia1,MONTH(data_inicio) mes1,YEAR(data_inicio) ano1, DAY(data_final) dia2,MONTH(data_final) mes2, YEAR(data_final) ano2, data_inicio, data_final from cro_projeto_fase_e_etapa_tarefa where id='".$_REQUEST["id_detalhe"]."'");
		$row = mysql_fetch_array($resu);
		$dia1  = $row["dia1"];
		$mes1  = $row["mes1"];
		$ano1  = $row["ano1"];
		$dia2  = $row["dia2"];
		$mes2  = $row["mes2"];
		$ano2  = $row["ano2"];
		$meses = $mes2;
		$option = "<option value='--'>SELECIONE</option>";
		if($ano1 < $ano2)
		{
			$calulo = $ano2-$ano1;
			$meses = $mes2 + (12*$calulo);
		}
		if($row["data_inicio"]!="" and $row["data_final"]!="")
		{	
			for($k=$mes1; $k<=$meses; $k++)//while($mes <= $meses)
			{
				if($mes1 > 12)
				{
					$mes1 = 1;
					$ano1 ++;
				}
				$total_dias = cal_days_in_month(CAL_GREGORIAN, $mes1, $ano1);
				for ($i=1; $i<=$total_dias; $i++)
				{
					$data=$ano1."-".str_pad($mes1,2,"0",STR_PAD_LEFT)."-".str_pad($i,2,"0",STR_PAD_LEFT);
					if($data >= $row["data_inicio"] and $data <= $row["data_final"])
					{
						$option .= "<option value='".dataform($data)."'>".diasemana($data)." - ".dataform($data)."</option>"; 					
					}
				}
				$mes1++;
			}
		}
		echo $option;
	}
}
if($_REQUEST["op"]=="retornar_iditem")
{
	$resu = mysql_query("select * from cro_projeto_etapa_item where id_etapa_item = '".$_REQUEST["idetapa"]."' and id_projeto = '".$_REQUEST["projeto"]."'");
	$row  = mysql_fetch_array($resu);
	echo $row["id_item"];
}
if($_REQUEST["op"]=="ListaResponsa")
{
	if($_REQUEST["id_item"]>0)//refente a itens
	{
		$resu    = mysql_query("select * from tb_projeto_itens where id='".$_REQUEST["id_item"]."'");
		$row     = mysql_fetch_array($resu);
	}
	else//referente a fase e etapas
	{
		$resu    = mysql_query("select * from cro_projeto_fase_e_etapa where id='".$_REQUEST["id_subitem"]."'");
		$row     = mysql_fetch_array($resu);//
	}	
	$select .= '<option value="0">--SELECIONAR--</option>';
	if($row["fase_etapa"]=="0")//FASE
	{	//LISTAR SÓ LIDERES M1,M2,M3,M4
		$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email from sec_groups s inner join sec_users_groups g on s.group_id=g.group_id inner join sec_users u on g.login=u.login where s.group_id in (26,27,28,29) and u.classificacao='1' order by u.name");
		while($itemUsuario = mysql_fetch_array($listaUsuario))
		{
			$selected = "";
			if($row["responsavel"]==$itemUsuario["login"]) 
			{
				$selected='selected="selected"';
			}
			$select .= '<option value="'.$_REQUEST["id_item"]."_".$_REQUEST["id_subitem"]."_".$itemUsuario["login"].'" '.$selected.' >'.$itemUsuario["nome"].'</option>';
		}
	}
	else//ETAPA E ITENS
	{	//LISTAR TODOS OS  USUARIOS M1,M2,M3,M4
		$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email,u.classificacao from sec_groups s inner join sec_users_groups g on s.group_id=g.group_id inner join sec_users u on g.login=u.login where s.group_id in (26,27,28,29)  order by s.group_id,u.classificacao,u.name");
		while($itemUsuario = mysql_fetch_array($listaUsuario))
		{
			$selected = "";
			if($row["responsavel"]==$itemUsuario["login"]) 
			{
				$selected='selected="selected"';
			}
			$class = '';
			if($itemUsuario["classificacao"] == '1') $class = 'style="background:#FFC954;"';
			elseif($itemUsuario["classificacao"] == '2') $class = 'style="background:#C8C8C8;"';
			elseif($itemUsuario["classificacao"] == '3') $class = 'style="background:#D59454;"';
			$select .= '<option '.$class.' value="'.$_REQUEST["id_item"]."_".$_REQUEST["id_subitem"]."_".$itemUsuario["login"].'" '.$selected.' >'.$itemUsuario["nome"].'</option>';
		}
	}
	echo $select;
	
}
if($_REQUEST["op"]=="SaveResponsavel")
{
	$vetor = explode('_',$_REQUEST["responsavel"]);
	if($vetor["0"]>0)
	{
		//responsavel do ITEM
		$resu_item = mysql_query("select * from tb_projeto_itens where id='".$vetor["0"]."' ");
		$row_item  = mysql_fetch_array($resu_item);
		if($row_item["responsavel"] != $vetor["2"])//mudança de responsavel
		{
			//listar todos os SUB_ITEM do ITEM
			$resu_sub = mysql_query("select * from tb_projeto_sub_itens where id_itens='".$vetor["0"]."' ");
			while($row_sub  = mysql_fetch_array($resu_sub))
			{
				if(in_array($row_sub["id_tarefa"],$mar))//validar tarefa do subitem
				{
					if($row_item["responsavel"] == $row_sub["responsavel"])//responsaveis = muda
					{
						//atualizar responsavel marcenaria
						$sql = "update tb_projeto_sub_itens set responsavel='".$vetor["2"]."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$row_sub["id"]."' ";
						mysql_query($sql);
					}
				}
			}
		}
		$sql="update tb_projeto_itens set responsavel='".$vetor["2"]."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$vetor["0"]."' ";
		if(!mysql_query($sql))
		{
			die('Erro no query: '.mysql_error());
		}
		else
		{	
			//usuarios
			$resu = mysql_query("select upper(name) as nome from sec_users where login='".$vetor["2"]."' ");
			$row = mysql_fetch_array($resu);
			//tarefas
			$resu1 = mysql_query("select * from tb_projeto_tarefas where user_grupo =(select group_id from sec_users_groups where login='".$vetor["2"]."')");
			$row1 = mysql_fetch_array($resu1);
			echo $row["nome"]."_".$vetor["2"]."_".$row1["id"]."_".$row1["cor_tarefa"]."_".$row1["sigla_tarefa"];
		}
	}
	else
	{
		$sql="update cro_projeto_fase_e_etapa set responsavel='".$vetor["2"]."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$vetor["1"]."' ";
		if(!mysql_query($sql))
		{
			die('Erro no query: '.mysql_error());
		}
		else
		{	//usuarios
			$resu = mysql_query("select upper(name) as nome from sec_users where login='".$vetor["2"]."' ");
			$row = mysql_fetch_array($resu);
			//tarefas
			$resu1 = mysql_query("select * from tb_projeto_tarefas where user_grupo =(select group_id from sec_users_groups where login='".$vetor["2"]."')");
			$row1 = mysql_fetch_array($resu1);
			echo $row["nome"]."_".$vetor["2"]."_".$row1["id"]."_".$row1["cor_tarefa"]."_".$row1["sigla_tarefa"];
		}
	}
}
?>