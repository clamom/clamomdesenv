<?php
require("cn.php");

if($_REQUEST["op"] == "tarefa_geral")
{
	for($i=0; $i<count($_REQUEST["VetorSubItem"]); $i++)
	{
		if($_REQUEST["VetorSubItem"][$i]>0)
		{
			$sql="delete from cro_projeto_fase_e_etapa_tarefa where id_fase_etapa=".$_REQUEST["VetorSubItem"][$i];
			mysql_query($sql);
		}
	}
	for ($i=0; $i<count($_REQUEST["ArrayTarefa"]); $i++) 
	{
		$check_sql = "select * from cro_projeto_fase_e_etapa where id=".$_REQUEST["ArrayTarefa"][$i][0];
		$resu = mysql_query($check_sql);
		$row = mysql_fetch_array($resu);
		//NOVA TAREFA DE UM DETERMINADO SUBITEM
		$sql="insert into cro_projeto_fase_e_etapa_tarefa(hora_final,id_fase_etapa,id_tarefa,data_inicio,data_final,responsavel,situacao,ordem,login,data_cadastro) 
	values('".$_REQUEST["ArrayTarefa"][$i][5]."','".$_REQUEST["ArrayTarefa"][$i][0]."','".$_REQUEST["ArrayTarefa"][$i][1]."','".$_REQUEST["ArrayTarefa"][$i][3]."','".$_REQUEST["ArrayTarefa"][$i][4]."','".$row["responsavel"]."','0','".($_REQUEST["ArrayTarefa"][$i][2]+1)."','".$_REQUEST["usuario"]."',now())";
		mysql_query($sql);	
		
		if($_REQUEST["ArrayTarefa"][$i][5]!='18:00')
		{
			$codigo_item = procurar_item($row['id_projeto']);
			$nome_usuario = procurar_nome($_REQUEST["usr_login"]);
			if($codigo_item == 0)
			{
				$nctr = find_ctr($row['id_projeto']);
				//$codigo_projeto = find_stage($row['id_projeto']);
				$insert_sql = "insert into tb_projeto_itens(desc_completa, desc_item, num_item,projeto_ctr,projeto_id, login, data_cadastro) 
				values('HISTÓRICOS E PENDÊNCIAS DO PROJETO','HISTÓRICOS E PENDÊNCIAS DO PROJETO', '0','".$nctr."','".$row['id_projeto']."','SYSTEM','". date('Y-m-d h:m:s')."')";
				//$param_message .= "<br>insert item".$insert_sql."<BR><BR>";
				mysql_query($insert_sql);	
				$codigo_item = procurar_item($row['id_projeto']);
			
			}
			$sql = "insert into tb_projeto_item_pendencia(data_cadastro, id_item,tipo,pendencia,situacao, cadastrado_por, quem_responde, ERRATA)
				values('".date('Y-m-d h:m:s')."','".$codigo_item."','D',
	'Alteração de entrega da tarefa ".find_task($_REQUEST["ArrayTarefa"][$i][1])." da etapa ".$row['desc_etapa']." para a hora ".$_REQUEST["ArrayTarefa"][$i][5]."',
	'1','".$nome_usuario."', '".$nome_usuario."',0)";	
			mysql_query($sql);
		}
	}
	for ($i=0; $i<count($_REQUEST["ArrayTarefa"]); $i++) 
	{
		$check_sql = mysql_query("SELECT min(data_inicio) data_inicio, max(data_final) data_final FROM cro_projeto_fase_e_etapa_tarefa where id_fase_etapa =". $_REQUEST["ArrayTarefa"][$i][0]);
		if (mysql_num_rows($check_sql)>0)    
		{
			$rs = mysql_fetch_array($check_sql);
			$data_ini = $rs["data_inicio"];
			$data_fin = $rs["data_final"];
		}
		$sql="update cro_projeto_fase_e_etapa set data_inicio = '".$data_ini."', data_final='".$data_fin."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id = ".$_REQUEST["ArrayTarefa"][$i][0]; 
		mysql_query($sql);
	}
}
elseif($_REQUEST["op"] == "tarefa_item")
{
	for($i=0; $i<count($_REQUEST["VetorSubItem"]); $i++)
	{
		if($_REQUEST["VetorSubItem"][$i]>0)
		{
			$sql="delete from cro_projeto_item_tarefa where id_etapa_item=".$_REQUEST["VetorSubItem"][$i];
			mysql_query($sql);
		}
	}
	
	for ($i=0; $i<count($_REQUEST["ArrayTarefa"]); $i++) 
	{
		//pegar dados da tarefa
		$resu = mysql_query("select * from tb_projeto_tarefas where id=".$_REQUEST["ArrayTarefa"][$i][1]);
		$row  = mysql_fetch_array($resu);
		if($row["e_terceirizado"]>0)
		{	//pegar dados tarefa pae
			$resu1 = mysql_query("select * from tb_projeto_tarefas where id=".$row["e_terceirizado"]);
			$row1  = mysql_fetch_array($resu1);
			$responsavel = $row1["usu_responsavel"];
			$executor = $row["usu_responsavel"];
		}
		else
		{
			$responsavel = $row["usu_responsavel"];
			$executor = "";	
		}	
		//NOVA TAREFA DE UM DETERMINADO SUBITEM
		if($_REQUEST["ArrayTarefa"][$i][5] != "") $horaFim = $_REQUEST["ArrayTarefa"][$i][5];
		else $horaFim = "18:00";
		$num_dias = total_dias($_REQUEST["ArrayTarefa"][$i][3],$_REQUEST["ArrayTarefa"][$i][4]);
		$sql="insert into cro_projeto_item_tarefa(id_etapa_item,id_tarefa,incremento,data_inicio,num_dias,data_final,hora_final,responsavel,executor,situacao,ordem,login,data_cadastro,hora_cadastro) 
		values('".$_REQUEST["ArrayTarefa"][$i][0]."','".$_REQUEST["ArrayTarefa"][$i][1]."','0','".$_REQUEST["ArrayTarefa"][$i][3]."','".$num_dias."','".$_REQUEST["ArrayTarefa"][$i][4]."','".$horaFim."','".$responsavel."','".$executor."','".$_REQUEST["ArrayTarefa"][$i][6]."','".($_REQUEST["ArrayTarefa"][$i][2]+1)."','".$_REQUEST["usuario"]."',now(),now())";
		mysql_query($sql);
		$detalhe = mysql_insert_id();
	}
}
?>