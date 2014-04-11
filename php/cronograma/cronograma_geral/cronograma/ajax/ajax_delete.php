<?php
require("cn.php");
foreach($_REQUEST["data"] as $codigo)
{	//CHECAR SE TEM FILHOS
	$resu = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$codigo."'");
	if(mysql_num_rows($resu)>0) $codigo_pae .= ",".$codigo;	
	else
	{
		//LISTAR TAREFAS 
		$resu_deta = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item=".$codigo);
		while($row_deta = mysql_fetch_array($resu_deta))
		{
			//DELETE EXECUCAO
			$sql="delete from tb_projeto_execucao where id_tarefa=".$row_deta["id"];
			mysql_query($sql);
			//DELETE ALERTAS USUARIOS
			$resu1=mysql_query("select * from orc_alerta where id_detalhe=".$row_deta["id"]);
			while($row1=mysql_fetch_array($resu1))
			{
				$sql="delete from orc_alerta_usuarios where id_alerta=".$row1["id_alerta"];
				mysql_query($sql);
			}
			//DELETE ALERTAS
			$sql="delete from orc_alerta where id_detalhe=".$row_deta["id"];
			mysql_query($sql);
		}
		//DELETE TAREFAS
		$sql="delete from tb_projeto_detalhe_tarefa where id_sub_item=".$codigo;
		mysql_query($sql);
		//DELETE SUB ITEM
		$sql="delete from tb_projeto_sub_itens where id=".$codigo;
		mysql_query($sql);
	}
}
echo $codigo_pae;
//mysql_query("delete from orc_alerta_usuarios");
?>