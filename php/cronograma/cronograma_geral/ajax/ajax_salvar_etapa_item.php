<?php
require("cn.php");

//deletar todas cro_projeto_etapa_item
$sql = "delete from cro_projeto_etapa_item where id_etapa = '".$_REQUEST["id_etapa"]."'";
mysql_query($sql);
//pegar dados id_projeto nctr
$resu = mysql_query("SELECT * FROM cro_projeto_fase_e_etapa where id='".$_REQUEST["id_etapa"]."'");
$row  = mysql_fetch_array($resu);
//insertar os dados
foreach($_REQUEST["data"] as $codigo)
{
	//insertar
	$sql = "insert into cro_projeto_etapa_item(id_etapa,id_item,id_projeto,nctr,num_item,num_dias,data_inicio,data_final,usuario_cadastro,hora_cadastro,data_cadastro) 
	values ('".$_REQUEST["id_etapa"]."','".$codigo."','".$row["id_projeto"]."','".$row["nctr"]."','1','1',now(),now(),'".$_REQUEST["usuario"]."',now(),now())";
	mysql_query($sql);
	//update
	$sql = "update tb_projeto_itens set responsavel='".$row["responsavel"]."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$codigo."'";
	mysql_query($sql);
}
?>