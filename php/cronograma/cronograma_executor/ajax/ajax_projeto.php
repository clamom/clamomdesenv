<?php
require("cn.php");

if($_REQUEST["op"]=="finalizar")
{
	foreach($_REQUEST["data"] as $codigo)
	{
		//atualizar sub_item
		$sql = "update tb_projeto_sub_itens set status_finalizar='1',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$codigo."' ";
		mysql_query($sql);
		finalizar_sub_iten_filhos($codigo,$_REQUEST["usuario"]);
		$id_subitem = $codigo;
	}
	if($id_subitem > 0)
	{
		finalizar_sub_itens($id_subitem,$_REQUEST["usuario"]);
	}
}
if($_REQUEST["op"]=="revisao")
{
	foreach($_REQUEST["data"] as $codigo)
	{	
		//atualizar sub_item
		$sql = "update tb_projeto_sub_itens set status_finalizar='0',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$codigo."' ";
		mysql_query($sql);
		revisao_sub_itens($codigo,$_REQUEST["usuario"]);
	}
	$resu = mysql_query("select * from tb_projeto_sub_itens where id='".$codigo."' ");
	$row  = mysql_fetch_array($resu);
	//
	if($row["id_itens_SubComponente"] > 0)
	{
		$sql = "update tb_projeto_sub_itens set status_finalizar='0',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$row["id_itens_SubComponente"]."' ";
		mysql_query($sql);
	}
}
if($_REQUEST["op"]=="liberacao")
{
	foreach($_REQUEST["data"] as $codigo)
	{
		//atualizar sub_item
		$sql = "update tb_projeto_sub_itens set liberacao='1',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$codigo."' ";
		mysql_query($sql);
	}
}
if($_REQUEST["op"]=="gerar_arquivo")
{
	foreach($_REQUEST["data"] as $codigo)
	{
		//atualizar sub_item
		$sql = "update tb_projeto_sub_itens set gerar_arquivo='1',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$codigo."' ";
		mysql_query($sql);
	}
}
?>