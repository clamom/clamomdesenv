<?php
require("cn.php");
//for para limpar as aterfas
for($i=0; $i<count($_REQUEST["VetorSubItem"]); $i++)
{
	if($_REQUEST["VetorSubItem"][$i]>0)
	{
		/*//LISTAR TAREFAS 
		$resu_deta = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item=".$_REQUEST["VetorSubItem"][$i]);
		while($row_deta = mysql_fetch_array($resu_deta))
		{
			//DELETE EXECUCAO
			$sql="delete from tb_projeto_execucao where id_tarefa=".$row_deta["id"];
			mysql_query($sql);
			//DELETE ALERTAS
			$resu1=mysql_query("select * from orc_alerta where id_detalhe=".$row_deta["id"]);
			while($row1=mysql_fetch_array($resu1))
			{
				$sql="delete from orc_alerta_usuarios where id_alerta=".$row1["id_alerta"];
				mysql_query($sql);
			}
			$sql="delete from orc_alerta where id_detalhe=".$row_deta["id"];
			mysql_query($sql);
		}*/
		$sql="delete from tb_projeto_detalhe_tarefa where id_sub_item=".$_REQUEST["VetorSubItem"][$i];
		mysql_query($sql);
	}
}
/*legenda
IdSubItem: ".$_REQUEST["ArrayTarefa"][$i][0]
Tarefa:    ".$_REQUEST["ArrayTarefa"][$i][1]
Ordem:     ".$_REQUEST["ArrayTarefa"][$i][2]
Data1:     ".$_REQUEST["ArrayTarefa"][$i][3]
Detalhe:   ".$_REQUEST["ArrayTarefa"][$i][4]
Data2:     ".$_REQUEST["ArrayTarefa"][$i][5]
Hora Fim:  ".$_REQUEST["ArrayTarefa"][$i][6]*/
for ($i=0; $i<count($_REQUEST["ArrayTarefa"]); $i++) 
{
	//pegar dados da tarefa
	$resu = mysql_query("select * from tb_projeto_tarefas where id=".$_REQUEST["ArrayTarefa"][$i][1]);
	$row = mysql_fetch_array($resu);
	if($row["e_terceirizado"]>0)
	{	//pegar dados tarefa pae
		$resu1 = mysql_query("select * from tb_projeto_tarefas where id=".$row["e_terceirizado"]);
		$row1  = mysql_fetch_array($resu1);
		$responsavel = $row1["usu_responsavel"];;
		$executor = $row["usu_responsavel"];
	}
	else
	{
		$responsavel = $row["usu_responsavel"];
		$executor = "";	
	}	
	//NOVA TAREFA DE UM DETERMINADO SUBITEM
	if($_REQUEST["ArrayTarefa"][$i][6] != "") $horaFim = $_REQUEST["ArrayTarefa"][$i][6];
	else $horaFim = "18:00";
	$num_dias = total_dias($_REQUEST["ArrayTarefa"][$i][3],$_REQUEST["ArrayTarefa"][$i][5]);
	$sql="insert into tb_projeto_detalhe_tarefa(id_sub_item,id_tarefa,data_inicio,num_dias,data_final,responsavel,executor,situacao,ordem,login,data_cadastro,hora_final) 
	values('".$_REQUEST["ArrayTarefa"][$i][0]."','".$_REQUEST["ArrayTarefa"][$i][1]."','".$_REQUEST["ArrayTarefa"][$i][3]."','".$num_dias."','".$_REQUEST["ArrayTarefa"][$i][5]."','".$responsavel."','".$executor."','0','".($_REQUEST["ArrayTarefa"][$i][2]+1)."','".$_REQUEST["usuario"]."',now(),'".$horaFim."')";
	mysql_query($sql);
	$detalhe = mysql_insert_id();
	//UPDATE EXECUCAO DA TAREFA /*falta arrumar duplicidade*/
	$sql = "update tb_projeto_execucao set id_tarefa='".$detalhe."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id_tarefa='".$_REQUEST["ArrayTarefa"][$i][4]."'";
	mysql_query($sql);
	//UPDATE ALERTAS 
	$sql = "update orc_alerta set id_detalhe='".$detalhe."' where id_detalhe='".$_REQUEST["ArrayTarefa"][$i][4]."'";
	mysql_query($sql);

	if($_REQUEST["ArrayTarefa"][$i][6]!="18:00" and $_REQUEST["ArrayTarefa"][$i][6]!="")
	{
	$id_item = retornar_iditem($_REQUEST["ArrayTarefa"][$i][0]);
	//LISTAR DADOS DO ITEM
	$resu_item = mysql_query("select * from tb_projeto_itens where id=".$id_item);
	$row_item = mysql_fetch_array($resu_item);
	//INSRETAR PENDENCIAS 
	$sql = "insert into tb_projeto_item_pendencia(id_item,tipo,pendencia,cadastrado_por,quem_responde,data_cadastro,errata,situacao) 
	values('".$id_item."','D','ALTERAÇÃO DA ENTREGA DA TAREFA ".strtoupper($row["desc_tarefa"])." DO ITEM ".strtoupper($row_item["desc_item"])." PARA A HORA: ".$_REQUEST["ArrayTarefa"][$i][6]."','eduardoz','".$_REQUEST["usuario"]."',now(),'0','1')";
	mysql_query($sql);
	}
}
?>