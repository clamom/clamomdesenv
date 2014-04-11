<?php
require("cn.php");
//for para limpar as aterfas
for($i=0; $i<count($_REQUEST["VetorSubItem"]); $i++)
{	//pegar dados do subitem
	$resu_sub = mysql_query("select * from tb_projeto_sub_itens where id=".$_REQUEST["VetorSubItem"][$i]);
	$row_sub = mysql_fetch_array($resu_sub);
	if($row_sub["id_itens"]>0)
	{
		//PEGAR O  PROXIMO SUBITEM
		$resu_num = mysql_query("select max(num_sub_item) as ultimo from tb_projeto_sub_itens where id_itens=".$row_sub["id_itens"]);
		$row_num = mysql_fetch_array($resu_num);
		$num_sub_item = str_pad($row_num["ultimo"]+1, 3, "0", STR_PAD_LEFT);
		$IdSubComponente = 0;
	}
	elseif($row_sub["id_itens_SubComponente"]>0)
	{
		//PEGAR O  PROXIMO SUBITEM
		$resu_num = mysql_query("select max(num_sub_item) as ultimo from tb_projeto_sub_itens where id_itens_SubComponente=".$row_sub["id_itens_SubComponente"]);
		$row_num = mysql_fetch_array($resu_num);
		$num_sub_item = str_pad($row_num["ultimo"]+1, 3, "0", STR_PAD_LEFT);
		$IdSubComponente = $row_sub["id_itens_SubComponente"];
	}
	//SALVAR NOVO SUBITEM
	$sql="insert into tb_projeto_sub_itens(id_ctr,id_itens,num_sub_item,descricao,data_inicio,num_dias,data_final,login,data_cadastro,nr_ctr,id_itens_SubComponente) 
	values('".$row_sub["id_ctr"]."','".$row_sub["id_itens"]."','".$num_sub_item."','".$row_sub["descricao"]."',now(),'1',now(),'eduardoz',now(),'".$row_sub["num_ctr"]."','".$IdSubComponente."')";
	mysql_query($sql);
	$id_subitem = mysql_insert_id();
	//pegar dados da tarefa detalhe do subitem
	/*$resu_deta = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item=".$_REQUEST["VetorSubItem"][$i]);
	while($row_deta = mysql_fetch_array($resu_deta))
	{
	$sql="insert into tb_projeto_detalhe_tarefa(id_sub_item,id_tarefa,data_inicio,data_final,responsavel,situacao,ordem,login,data_cadastro,hora_final) 
values('".$id_subitem."','".$row_deta["id_tarefa"]."','".$row_deta["data_inicio"]."','".$row_deta["data_final"]."','".$row_deta["responsavel"]."','0','".$row_deta["ordem"]."','eduardoz',now(),'".$row_deta["hora_final"]."')";
	mysql_query($sql);
	}*/
}
?>