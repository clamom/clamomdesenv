<?php
require("cn.php");

$resu = mysql_query("select * from cro_projeto_fase_e_etapa where desc_etapa ='".strtoupper(trim($_REQUEST["nome"]))."' and id_projeto='".$_REQUEST["id_ctr"]."' and id_pai='".$_REQUEST["id_pai"]."' and id<>'".$_REQUEST["valor"]."' ");
if(mysql_num_rows($resu) == 0)
{
	//UPDATE SUB ITEM
	$sql="update cro_projeto_fase_e_etapa set desc_etapa ='".strtoupper(trim($_REQUEST["nome"]))."',data_alteracao=now(),login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id=".$_REQUEST["valor"];
	mysql_query($sql);
	echo "true";
}
else
{
	echo "false";
}
?>