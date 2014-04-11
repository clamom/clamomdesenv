<?php
require("cn.php");

delete_all($_REQUEST["data1"]);

function delete_all($codigo)
{
	foreach($codigo as $cod)
	{	
		if($cod>0)
		{
			unset($vetor);
			//CHECAR SE TEM FILHOS
			$resu = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$cod."'");
			if(mysql_num_rows($resu)>0)
			{
				while($row = mysql_fetch_array($resu))
				{
					$vetor[] = $row["id"];
				}
			}
			if(count($vetor)>0)
			{
				delete_all($vetor);
			}
			//LISTAR TAREFAS 
			$resu_deta = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item=".$cod);
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
			$sql="delete from tb_projeto_detalhe_tarefa where id_sub_item=".$cod;
			mysql_query($sql);
			//DELETE SUB ITEM
			$sql="delete from tb_projeto_sub_itens where id=".$cod;
			mysql_query($sql);
		}
	}
	return true;
}
?>