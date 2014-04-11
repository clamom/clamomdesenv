<?php
require("cn.php");

if($_REQUEST["op"]=="detalhe_tarefa")
{	//idsubitens tem tarefas
	$resu = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item=".$_REQUEST["idsubitem"]);
	echo mysql_num_rows($resu);
}
?>