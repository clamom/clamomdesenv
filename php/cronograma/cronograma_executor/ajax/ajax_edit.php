<?php
require("cn.php");
//UPDATE SUB ITEM
$sql="update tb_projeto_sub_itens set descricao='".strtoupper($_REQUEST["nome"])."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id=".$_REQUEST["valor"];
mysql_query($sql);
?>