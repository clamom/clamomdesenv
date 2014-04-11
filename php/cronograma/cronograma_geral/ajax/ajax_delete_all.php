<?php
require("cn.php");
//CHAMAR A FUNÇÃO
delete_all($_REQUEST["data1"]);

function delete_all($codigo)
{
	foreach($codigo as $cod)
	{	
		if($cod>0)
		{
			unset($vetor);
			//CHECAR SE TEM FILHOS
			$resu = mysql_query("select * from cro_projeto_fase_e_etapa where id_pai='".$cod."'");
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
			//DELETE TAREFAS
			$sql="delete from cro_projeto_fase_e_etapa_tarefa where id_fase_etapa=".$cod;
			mysql_query($sql);
			//DELETE SUB ITEM
			$sql="delete from cro_projeto_fase_e_etapa where id=".$cod;
			mysql_query($sql);
			//LISTAR AS TABELAS E ITENS
			$resu1 = mysql_query("select * from cro_projeto_etapa_item where id_etapa='".$cod."'");
			while($row1 = mysql_fetch_array($resu1))
			{	//LISTAR SUBITENS
				$resu_sub = mysql_query("select * from tb_projeto_sub_itens where id_itens='".$row1["id_item"]."' ");
				while($row_sub = mysql_fetch_array($resu_sub))
				{
					$resu_deta = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item='".$row_sub["id"]."'");
					while($row_deta = mysql_fetch_array($resu_deta))
					{
						//DELETE EXECUCAO
						$sql="delete from tb_projeto_execucao where id_tarefa=".$row_deta["id"];
						mysql_query($sql);
					}
					if($row_sub["id"] > 0)
					{
						//DELETE TAREFAS
						$sql="delete from tb_projeto_detalhe_tarefa where id_sub_item='".$row_sub["id"]."'";
						mysql_query($sql);
					}
					//DELETE ALERTAS USUARIOS
					$resu11=mysql_query("select * from orc_alerta where id_sub_item='".$row_sub["id"]."' ");
					while($row11=mysql_fetch_array($resu11))
					{
						$sql="delete from orc_alerta_usuarios where id_alerta=".$row11["id_alerta"];
						mysql_query($sql);
					}
					if($row_sub["id"] > 0)
					{
						//DELETAR AS ALERTAS (SUBITENS)
						$sql = "delete from orc_alerta where id_sub_item='".$row_sub["id"]."' ";
						mysql_query($sql);
					}
				}
				if($row1["id_item"] > 0)
				{ 
					//DELETE SUB ITEM
					$sql="delete from tb_projeto_sub_itens where id_itens='".$row1["id_item"]."' ";
					mysql_query($sql);
				}
				if($row1["id_etapa_item"] > 0)
				{
					//DELETE AS TABELAS E ITENS
					$sql="delete from cro_projeto_item_tarefa where id_etapa_item='".$row1["id_etapa_item"]."'";
					mysql_query($sql);
					//DELETAR AS ALERTAS (ITENS)
					$sql = "delete from orc_alerta where id_sub_item='".$row1["id_etapa_item"]."' ";
					mysql_query($sql);
				}
			}
			//DELETE AS TABELAS E ITENS
			$sql = "delete from cro_projeto_etapa_item where id_etapa='".$cod."'";
			mysql_query($sql);
			//DELETAR AS ALERTAS (FASE E ETAPA)
			$sql = "delete from orc_alerta where id_sub_item='".$cod."' ";
			mysql_query($sql);
		}
	}
	return true;
}
?>