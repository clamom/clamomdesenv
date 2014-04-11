<?php
require("cn.php");

$mar = array(171,172,173,174);//MAR01, MAR02, MAR03, MAR04

//SALVAR ALERTAS
if($_REQUEST["op"]=="SalvarAlerta")
{	
	$resu = mysql_query("select * from tb_projeto_detalhe_tarefa where id=".$_REQUEST["id_detalhe"]);
	$row = mysql_fetch_array($resu);
	//INSERT TABELA ALERTA		
	$sql="insert into orc_alerta(alert_sigla,id_ctr,id_sub_item,id_detalhe,alert_data,alert_hora,alert_mensagem,alert_status,alert_hora_criacao,alert_data_criacao,alert_usuario_criacao,alert_para) values('CRO','".$_REQUEST["id_ctr"]."','".$row["id_sub_item"]."','".$_REQUEST["id_detalhe"]."','".database($_REQUEST["dataAlerta"])."','".$_REQUEST["horaAlerta"]."','".strtoupper($_REQUEST["obsAlerta"])."','1',now(),now(),'".$_REQUEST["usuario"]."','".$_REQUEST["para_ale"]."')";
	mysql_query($sql);
	$id_alerta = mysql_insert_id();
	$lista = explode(';',$_REQUEST["ListaLogin"]);
	foreach($lista as $login)
	{
		$sql = "insert into orc_alerta_usuarios(id_alerta,login_usuario) values('".$id_alerta."','".$login."')"; 
		if($login != "")
		{
			//INSERT TABELA  ALERTA_USUARIOS
			$sql = "insert into orc_alerta_usuarios(id_alerta,login_usuario) values('".$id_alerta."','".$login."')"; 
			mysql_query($sql);
		}
	}
	$cor="#FFFFFF";
	$resu1 = mysql_query("select *,upper(u.name) nome from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CRO' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_sub_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
	while($row1 = mysql_fetch_array($resu1))
	{	
		$usuarios = '';
		$resu_para = mysql_query("select *,upper(name) nome from sec_users where login='".$row1["alert_para"]."' ");
		$row_para  = mysql_fetch_array($resu_para);
		$resu2 = mysql_query("select *,upper(u.name) nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
		while($row2 = mysql_fetch_array($resu2))
		{
			$usuarios .= '<li style="clear:both; padding:0;">'.$row2["nome"].'</li>';
		}
		if($cor=="#FFFFFF") $cor="#DDDDDD";
		else $cor = "#FFFFFF";
		if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
		else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
$tr .= "
	<tr style='background:".$cor.";'>
		<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
		<td valign='top' align='center'>".$row1["alert_hora"]."</td>
		<td valign='top'>".$row_para["nome"]."</td>
		<td valign='top'><ul>".$usuarios."</ul></td>
		<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
		<td valign='top'>".$row1["nome"]."<br/>".$hora."</td>
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
//EDITAR ALERTAS
if($_REQUEST["op"]=="EditarAlerta")
{
	$resu = mysql_query("select * from tb_projeto_detalhe_tarefa where id=".$_REQUEST["id_detalhe"]);
	$row = mysql_fetch_array($resu);
	//UPDATE TABELA ALERTA
	$sql="update orc_alerta set alert_data='".database($_REQUEST["dataAlerta"])."',alert_hora='".$_REQUEST["horaAlerta"]."',alert_mensagem='".strtoupper($_REQUEST["obsAlerta"])."',alert_para='".$_REQUEST["para_ale"]."',alert_hora_update=now(),alert_data_update=now() where id_alerta='".$_REQUEST["id_alerta"]."'";
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
	$resu1 = mysql_query("select *,upper(u.name) nome from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CRO' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_sub_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
	while($row1 = mysql_fetch_array($resu1))
	{	
		$usuarios = '';
		$resu_para = mysql_query("select *,upper(name) nome from sec_users where login='".$row1["alert_para"]."' ");
		$row_para  = mysql_fetch_array($resu_para);
		$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
		while($row2 = mysql_fetch_array($resu2))
		{
			$usuarios .= '<li style="clear:both; padding:0;">'.$row2["nome"].'</li>';
		}
		if($cor=="#FFFFFF") $cor="#DDDDDD";
		else $cor = "#FFFFFF";
		if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
		else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
$tr .= "
	<tr style='background:".$cor.";'>
		<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
		<td valign='top' align='center'>".$row1["alert_hora"]."</td>
		<td valign='top'>".$row_para["nome"]."</td>
		<td valign='top'><ul>".$usuarios."</ul></td>
		<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
		<td valign='top'>".$row1["nome"]."<br/>".$hora."</td>
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
//LISTAR TODAS AS ALERTAS
if($_REQUEST["op"]=="ListarAlert")
{
	$resu = mysql_query("select * from tb_projeto_detalhe_tarefa where id=".$_REQUEST["id_detalhe"]);
	$row = mysql_fetch_array($resu);
	//
	$cor="#FFFFFF";
	$resu1 = mysql_query("select *,upper(u.name) nome from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CRO' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_sub_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
	while($row1 = mysql_fetch_array($resu1))
	{	
		$usuarios = '';
		$resu_para = mysql_query("select *,upper(name) nome from sec_users where login='".$row1["alert_para"]."' ");
		$row_para  = mysql_fetch_array($resu_para);
		$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
		while($row2 = mysql_fetch_array($resu2))
		{
			$usuarios .= '<li style="clear:both; padding:0;">'.$row2["nome"].'</li>';
		}
		if($cor=="#FFFFFF") $cor="#DDDDDD";
		else $cor = "#FFFFFF";
		if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
		else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
$tr .= "
	<tr style='background:".$cor.";'>
		<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
		<td valign='top' align='center'>".$row1["alert_hora"]."</td>
		<td valign='top'>".$row_para["nome"]."</td>
		<td valign='top'><ul>".$usuarios."</ul></td>
		<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
		<td valign='top'>".$row1["nome"]."<br/>".$hora."</td>
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
//LISTAR TODAS AS ALERTAS
if($_REQUEST["op"]=="ListarAlert2")
{
	$resu = mysql_query("select * from tb_projeto_detalhe_tarefa where id=".$_REQUEST["id_detalhe"]);
	$row = mysql_fetch_array($resu);
	//
	$cor="#FFFFFF";
	$resu1 = mysql_query("select *,upper(u.name) nome from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CRO' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_sub_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' and a.alert_data='".$_REQUEST["dataDT"]."' order by a.alert_data, a.alert_hora");
	while($row1 = mysql_fetch_array($resu1))
	{	
		$usuarios = '';
		$resu_para = mysql_query("select *,upper(name) nome from sec_users where login='".$row1["alert_para"]."' ");
		$row_para  = mysql_fetch_array($resu_para);
		$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
		while($row2 = mysql_fetch_array($resu2))
		{
			$usuarios .= '<li style="clear:both; padding:0;">'.$row2["nome"].'</li>';
		}
		if($cor=="#FFFFFF") $cor="#DDDDDD";
		else $cor = "#FFFFFF";
		if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
		else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
$tr .= "
	<tr style='background:".$cor.";'>
		<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
		<td valign='top' align='center'>".$row1["alert_hora"]."</td>
		<td valign='top'>".$row_para["nome"]."</td>
		<td valign='top'><ul>".$usuarios."</ul></td>
		<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
		<td valign='top'>".$row1["nome"]."<br/>".$hora."</td>
	</tr>";
	}
	echo $tr;
}
//TOTAL DE ALERTAS WHERE DATA E DETALHE_TAREFA
if($_REQUEST["op"]=="CountAlert")
{
	$resu = mysql_query("select * from tb_projeto_detalhe_tarefa where id=".$_REQUEST["id_detalhe"]);
	$row = mysql_fetch_array($resu);
	//
	$resu1 = mysql_query("select * from orc_alerta where alert_sigla='CRO' and id_ctr='".$_REQUEST["id_ctr"]."' and id_sub_item='".$row["id_sub_item"]."' and id_detalhe='".$_REQUEST["id_detalhe"]."' and alert_data='".$_REQUEST["dataDT"]."' order by alert_data, alert_hora");
	echo mysql_num_rows($resu1);
}
//LISTAR DATAS DO ALERTA
if($_REQUEST["op"]=="AlertDatas")
{
	$resu = mysql_query("select * from tb_projeto_detalhe_tarefa where id=".$_REQUEST["id_detalhe"]);
	$row = mysql_fetch_array($resu);
	//
	$resu1 = mysql_query("select * from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CRO' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_sub_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' group by alert_data order by a.alert_data");
	while($row1 = mysql_fetch_array($resu1))
	{
		$vetor[] = $row1["alert_data"];
	}
	echo json_encode($vetor); 
}
if($_REQUEST["op"]=="ApagarAlerta")
{
	//delete tabela alerta
	$sql="delete from orc_alerta where id_alerta='".$_REQUEST["id_alerta"]."'";
	mysql_query($sql);
	//delete tabela alerta_usuarios
	$sql="delete from orc_alerta_usuarios where id_alerta='".$_REQUEST["id_alerta"]."'";
	mysql_query($sql);
	//
	$resu = mysql_query("select * from tb_projeto_detalhe_tarefa where id=".$_REQUEST["id_detalhe"]);
	$row = mysql_fetch_array($resu);
	//
	$cor="#FFFFFF";
	$resu1 = mysql_query("select *,upper(u.name) nome from orc_alerta a left join sec_users u on a.alert_usuario_criacao = u.login where a.alert_sigla='CRO' and a.id_ctr='".$_REQUEST["id_ctr"]."' and a.id_sub_item='".$row["id_sub_item"]."' and a.id_detalhe='".$_REQUEST["id_detalhe"]."' order by a.alert_data, a.alert_hora");
	while($row1 = mysql_fetch_array($resu1))
	{	
		$usuarios = '';
		$resu_para = mysql_query("select *,upper(name) nome from sec_users where login='".$row1["alert_para"]."' ");
		$row_para  = mysql_fetch_array($resu_para);
		$resu2 = mysql_query("select *,upper(u.name) as nome from orc_alerta_usuarios a inner join sec_users u on a.login_usuario=u.login where a.id_alerta='".$row1["id_alerta"]."' order by u.name");
		while($row2 = mysql_fetch_array($resu2))
		{
			$usuarios .= '<li style="clear:both; padding:0;">'.$row2["nome"].'</li>';
		}
		if($cor=="#FFFFFF") $cor="#DDDDDD";
		else $cor = "#FFFFFF";
		if($row1["alert_hora_update"] != "") $hora = dataform(substr($row1["alert_hora_update"],0,10))." ".substr($row1["alert_hora_update"],10);
		else $hora = dataform(substr($row1["alert_hora_criacao"],0,10))." ".substr($row1["alert_hora_criacao"],10);
$tr .= "
	<tr style='background:".$cor.";'>
		<td valign='top' align='center'>".dataform($row1["alert_data"])."</td>
		<td valign='top' align='center'>".$row1["alert_hora"]."</td>
		<td valign='top'>".$row_para["nome"]."</td>
		<td valign='top'><ul>".$usuarios."</ul></td>
		<td valign='top'>".nl2br($row1["alert_mensagem"])."</td>
		<td valign='top'>".$row1["nome"]."<br/>".$hora."</td>
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
		$lista2 .= '<li id="'.trim($row2["login_usuario"]).'" style="clear:both; padding:0;">'.trim($row2["nome"]).'</li>';
	}
	$lista2 .= "</ul>";
	$vetor[4] = $lista2;
	$vetor[5] = $row["alert_para"];
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
	$sql="update tb_projeto_detalhe_tarefa set executor='".$vetor["1"]."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$vetor["0"]."' ";
	if(!mysql_query($sql))
	{
		die('Erro no query: '.mysql_error());
	}
	else echo "Salvo com Sucesso!";
}
if($_REQUEST["op"]=="DadosTitulo")
{	//dados do sub item
	$resu = mysql_query("select upper(descricao) as descricao_subitem from tb_projeto_sub_itens where id='".$_REQUEST["id_subitem"]."'");
	$row = mysql_fetch_array($resu);
	//dados da tarefa
	$resu1 = mysql_query("select *,upper(t.desc_tarefa) as desc_tarefa from tb_projeto_detalhe_tarefa d inner join tb_projeto_tarefas t on d.id_tarefa=t.id where d.id='".$_REQUEST["id_detalhe"]."'");
	$row1 = mysql_fetch_array($resu1);
	echo $row["descricao_subitem"]." | ".$row1["desc_tarefa"];
}
if($_REQUEST["op"]=="ListarDatas")
{
	$resu = mysql_query("select DAY(data_inicio) dia1,MONTH(data_inicio) mes1,YEAR(data_inicio) ano1, DAY(data_final) dia2,MONTH(data_final) mes2, YEAR(data_final) ano2, data_inicio, data_final from tb_projeto_detalhe_tarefa where id='".$_REQUEST["id_detalhe"]."'");
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
if($_REQUEST["op"]=="ListaResponsa")
{
	//pegar responsavel do subitem
	$resu    = mysql_query("select * from tb_projeto_sub_itens where id='".$_REQUEST["id_subitem"]."'");
	$row     = mysql_fetch_array($resu);
	$select .= '<option value="0">--SELECIONAR--</option>';
	if($_REQUEST["task"]>0)//1 NIVEL ID_ITEM > 0
	{
		//validar serviço externo COMPRAS
		$resu_9999 = mysql_query("select * from tb_projeto_tarefas where id='".$_REQUEST["task"]."' ");
		$row_9999  = mysql_fetch_array($resu_9999);
		if($row_9999["user_grupo"]=="9999")
		{
			$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email,u.classificacao from sec_users u inner join sec_users_groups g on u.login=g.login where group_id='5'");
		}
		else//não é compras
		{
			if($_REQUEST["task"]=='170')//montagem
			{
				$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_users u inner join sec_users_groups g on u.login=g.login where g.group_id in (1,5,14,20,22,21,24,31,42,43) order by u.classificacao, u.name");
			}
			elseif(in_array($_REQUEST["task"],$mar))//sÓ marcenaria
			{
				$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_groups s inner join sec_users_groups g on s.group_id=g.group_id inner join sec_users u on g.login=u.login where s.group_id in (26,27,28,29) and (u.classificacao='1' or u.classificacao='2' or u.classificacao='3' or u.classificacao='4') order by u.classificacao, u.name");
			}
			else//outros casos
			{
				if($_REQUEST["task"]=='1')//engenheria
				{
					$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_groups s inner join sec_users_groups g on s.group_id=g.group_id inner join sec_users u on g.login=u.login where s.group_id in (26,27,28,29)  order by s.group_id,u.classificacao, u.name");
					while($itemUsuario = mysql_fetch_array($listaUsuario))
					{
						$selected = "";
						if($row["responsavel"]==$itemUsuario["login"]) 
						{
							$selected='selected="selected"';
						}
						$class = '';
						if($itemUsuario["classificacao"] == '1') $class = 'style="background:#FFC033;"';
						elseif($itemUsuario["classificacao"] == '2') $class = 'style="background:#C8C8C8;"';
						elseif($itemUsuario["classificacao"] == '3') $class = 'style="background:#E0B081;"';
						$select .= '<option '.$class.' value="'.$_REQUEST["id_item"]."_".$_REQUEST["id_subitem"]."_".$itemUsuario["login"].'" '.$selected.' >'.$itemUsuario["nome"].'</option>';
					}
				}
				$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_users_groups g inner join sec_users u on g.login=u.login where g.group_id = (select user_grupo from tb_projeto_tarefas where id='".$_REQUEST["task"]."') order by u.classificacao, u.name");
			}
		}
	}
	if(mysql_num_rows($listaUsuario)==0)//outros
	{
		$resu = mysql_query("select * from tb_projeto_tarefas_filho where id_dependente='".$_REQUEST["task"]."' ");
		if(mysql_num_rows($resu) > 0)
		{
			$row  = mysql_fetch_array($resu);
			$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_users_groups g inner join sec_users u on g.login=u.login where g.group_id = (select user_grupo from tb_projeto_tarefas where id='".$row["id_independente"]."') order by u.classificacao, u.name");
		}
		else
		{
			$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_users u inner join sec_users_groups g on u.login=g.login where g.group_id in (1,5,14,20,22,21,24,31,42,43) order by u.classificacao, u.name");
		}
	}
	while($itemUsuario = mysql_fetch_array($listaUsuario))
	{
		$selected = "";
		if($row["responsavel"]==$itemUsuario["login"]) 
		{
			$selected='selected="selected"';
		}
		$class = '';
		if($itemUsuario["classificacao"] == '1') $class = 'style="background:#FFC033;"';
		elseif($itemUsuario["classificacao"] == '2') $class = 'style="background:#C8C8C8;"';
		elseif($itemUsuario["classificacao"] == '3') $class = 'style="background:#E0B081;"';
		$select .= '<option '.$class.' value="'.$_REQUEST["id_subitem"]."_".$itemUsuario["login"].'" '.$selected.' >'.$itemUsuario["nome"].'</option>';
	}
	
	echo $select;
}
if($_REQUEST["op"]=="ListaExecutor")
{
	//pegar responsavel do subitem
	$resu    = mysql_query("select * from tb_projeto_sub_itens where id='".$_REQUEST["id_subitem"]."'");
	$row     = mysql_fetch_array($resu);
	$select .= '<option value="0">--SELECIONAR--</option>';
	if($_REQUEST["task"]>0)//1 NIVEL ID_ITEM > 0
	{
		//validar serviço externo COMPRAS
		$resu_9999 = mysql_query("select * from tb_projeto_tarefas where id='".$_REQUEST["task"]."' ");
		$row_9999  = mysql_fetch_array($resu_9999);
		if($row_9999["user_grupo"]=="9999")
		{
			$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email,u.classificacao from sec_users u inner join sec_users_groups g on u.login=g.login where group_id='5'");
		}
		else//não é compras
		{
			if($_REQUEST["task"]=='170')//montagem
			{
				$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_users u inner join sec_users_groups g on u.login=g.login where g.group_id in (1,5,14,20,22,21,24,31,42,43) order by u.classificacao, u.name");
			}
			elseif(in_array($_REQUEST["task"],$mar))//sÓ marcenaria
			{
				$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_groups s inner join sec_users_groups g on s.group_id=g.group_id inner join sec_users u on g.login=u.login where s.group_id in (26,27,28,29) and (u.classificacao='1' or u.classificacao='2' or u.classificacao='3' or u.classificacao='4') order by u.classificacao, u.name");
			}
			else//outros casos
			{
				if($_REQUEST["task"]=='1')//engenheria
				{
					$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_groups s inner join sec_users_groups g on s.group_id=g.group_id inner join sec_users u on g.login=u.login where s.group_id in (26,27,28,29)  order by s.group_id,u.classificacao, u.name");
					while($itemUsuario = mysql_fetch_array($listaUsuario))
					{
						$selected = "";
						if($row["executor"]==$itemUsuario["login"]) 
						{
							$selected='selected="selected"';
						}
						$class = '';
						if($itemUsuario["classificacao"] == '1') $class = 'style="background:#FFC033;"';
						elseif($itemUsuario["classificacao"] == '2') $class = 'style="background:#C8C8C8;"';
						elseif($itemUsuario["classificacao"] == '3') $class = 'style="background:#E0B081;"';
						$select .= '<option '.$class.' value="'.$_REQUEST["id_item"]."_".$_REQUEST["id_subitem"]."_".$itemUsuario["login"].'" '.$selected.' >'.$itemUsuario["nome"].'</option>';
					}
				}
				$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_users_groups g inner join sec_users u on g.login=u.login where g.group_id = (select user_grupo from tb_projeto_tarefas where id='".$_REQUEST["task"]."') order by u.classificacao, u.name");
			}
		}
	}
	if(mysql_num_rows($listaUsuario)==0)//outros
	{
		$resu = mysql_query("select * from tb_projeto_tarefas_filho where id_dependente='".$_REQUEST["task"]."' ");
		if(mysql_num_rows($resu) > 0)
		{
			$row  = mysql_fetch_array($resu);
			$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_users_groups g inner join sec_users u on g.login=u.login where g.group_id = (select user_grupo from tb_projeto_tarefas where id='".$row["id_independente"]."') order by u.classificacao, u.name");
		}
		else
		{
			$listaUsuario = mysql_query("select u.login, upper(u.name) as nome, u.email, u.classificacao from sec_users u inner join sec_users_groups g on u.login=g.login where g.group_id in (1,5,14,20,22,21,24,31,42,43) order by u.classificacao, u.name");
		}
	}
	while($itemUsuario = mysql_fetch_array($listaUsuario))
	{
		$selected = "";
		if($row["executor"]==$itemUsuario["login"]) 
		{
			$selected='selected="selected"';
		}
		$class = '';
		if($itemUsuario["classificacao"] == '1') $class = 'style="background:#FFC033;"';
		elseif($itemUsuario["classificacao"] == '2') $class = 'style="background:#C8C8C8;"';
		elseif($itemUsuario["classificacao"] == '3') $class = 'style="background:#E0B081;"';
		$select .= '<option '.$class.' value="'.$_REQUEST["id_subitem"]."_".$itemUsuario["login"].'" '.$selected.' >'.$itemUsuario["nome"].'</option>';
	}
	echo $select;
}
if($_REQUEST["op"]=="SaveResponsavel")
{
	$vetor = explode('_',$_REQUEST["responsavel"]);
	//responsavel principal
	$resu = mysql_query("select * from tb_projeto_sub_itens where id='".$vetor["0"]."' ");
	$row = mysql_fetch_array($resu);
	//responsavel filhos
	$resu1 = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$vetor["0"]."' ");
	while($row1 = mysql_fetch_array($resu1))
	{
		if($_REQUEST["tipor"] == "Res")//tipo responsavel
		{
			if($row["responsavel"] == $row1["responsavel"])
			{
				$sql="update tb_projeto_sub_itens set responsavel='".$vetor["1"]."',executor=NULL,login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$row1["id"]."' ";
				mysql_query($sql);
			}
		}
		else//tipo executor
		{
			if($row["executor"] == $row1["executor"])
			{
				$sql="update tb_projeto_sub_itens set executor='".$vetor["1"]."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$row1["id"]."' ";
				mysql_query($sql);
			} 		
		}
	}
	if($_REQUEST["tipor"] == "Res")//tipo responsavel
	{
		$sql="update tb_projeto_sub_itens set responsavel='".$vetor["1"]."',executor=NULL,login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$vetor["0"]."' ";
	}
	else//tipo executor
	{
		$sql="update tb_projeto_sub_itens set executor='".$vetor["1"]."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$vetor["0"]."' ";
	}
	if(!mysql_query($sql))
	{
		die('Erro no query: '.mysql_error());
	}
	else
	{
		$resu2 = mysql_query("select * from tb_projeto_sub_itens where id='".$vetor["0"]."' ");
		$row2  = mysql_fetch_array($resu2);
		if($row2["responsavel"] != "")
		{
			$resu_res = mysql_query("select upper(name) as nome from sec_users where login='".$row2["responsavel"]."' ");
			$row_res  = mysql_fetch_array($resu_res);
			$result   = "[R. ".$row_res["nome"]."]";
			if($row2["executor"] != "")
			{
				$resu_exe = mysql_query("select upper(name) as nome from sec_users where login='".$row2["executor"]."' ");
				$row_exe  = mysql_fetch_array($resu_exe);
				//$result  .= "[E. ".$row_exe["nome"]."]"; 
				$result = '<img class="img_res_exe" src="images/res_exe.jpg" width="21" height="18" alt="'."[R. ".$row_res["nome"]."]".'" title="'."[R. ".$row_res["nome"]."]".'"/>
			[E. '.$row_exe['nome'].']';
			}
		}
		echo $result;
		/*if($_REQUEST["tipor"] == "Res")
		{
			$resu = mysql_query("select upper(name) as nome from sec_users where login='".$vetor["1"]."' ");
			$row = mysql_fetch_array($resu); 
			echo $row["nome"];
		}*/
	}
}
if($_REQUEST["op"]=="fim_rev")
{
	$i = 0;
	foreach($_REQUEST["data"] as $codigo)
	{
		$resu         = mysql_query("select * from tb_projeto_sub_itens where id='".$codigo."' ");
		$row          = mysql_fetch_array($resu);
		$resu1        = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item='".$codigo."' ");
		if(mysql_num_rows($resu1) > 0)
		{
			$vetor[$i][0] = "1";
		}
		else
		{
			$vetor[$i][0] = "0";
		}
		$vetor[$i][1] = $row["status_finalizar"];
		$vetor[$i][2] = $row["executor"];
		$i++;
	}
	$liberar   = "";
	$finalizar = "";
	foreach($vetor as $v)
	{
		if($v[0]=="0")//sem tarefas
		{ 
			$liberar   = "false";
			$finalizar = "false";
			break;
		}
		elseif($v[0]=="1")//com tarefas
		{	//finalizar 
			if($v[1]=="0" && $v[2]!="")//pendente finalizar e executor diferente null
			{
				$finalizar = "true";
				$liberar   = "false";
			}
			else
			{
				$finalizar = "false";
				$liberar   = "true";
			}
		}
	}
	if($liberar == "false" and $finalizar == "false")
	{
		echo "false";
	}
	elseif($liberar == "true" and $finalizar == "false")
	{
		echo "liberar";
	}
	elseif($liberar == "false" and $finalizar == "true")
	{
		echo "finalizar";
	}
}
if($_REQUEST["op"]=="pegar_status")
{
	$resu    = mysql_query("select * from tb_projeto_sub_itens where id='".$_REQUEST["idsubitem"]."' ");
	$row     = mysql_fetch_array($resu);
	echo $row["status_finalizar"]; 
	/*if($row["status_finalizar"]=="0" and $row["liberacao"]=="0")
	{
		echo "0";
	}
	else
	{
		echo "1";
	}*/
}
?>