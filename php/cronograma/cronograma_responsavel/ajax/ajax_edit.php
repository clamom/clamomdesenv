<?php
require("cn.php");

$resu = mysql_query("select * from tb_projeto_sub_itens where descricao='".strtoupper(trim($_REQUEST["nome"]))."' and id_ctr='".$_REQUEST["id_ctr"]."' and id_itens_SubComponente='".$_REQUEST["id_subitem"]."' and id<>'".$_REQUEST["valor"]."' ");
if(mysql_num_rows($resu) == 0)
{
	//UPDATE SUB ITEM
	$sql="update tb_projeto_sub_itens set descricao='".strtoupper(trim($_REQUEST["nome"]))."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id=".$_REQUEST["valor"];
	mysql_query($sql);
	echo "true";
}
else
{
	echo "false";
}
?>