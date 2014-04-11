<?php
require("cn.php");

if($_REQUEST["op"]=="finalizar")
{	
	$rpt = "true";
	//listar todas as etapas
	$resu_etapa = mysql_query("select * from cro_projeto_fase_e_etapa where id_projeto='".$_REQUEST["id_ctr"]."' and fase_etapa='1' ");
	while($row_etapa  = mysql_fetch_array($resu_etapa))
	{
		//validar todos os itens pertencem as etapas
		$resu_val = mysql_query("select * from cro_projeto_etapa_item where id_projeto='".$_REQUEST["id_ctr"]."' and id_etapa='".$row_etapa["id"]."'");
		if(mysql_num_rows($resu_val)==0)
		{
			$rpt = "false";	
			break;
		}
	}
	if($rpt == "true")//etapas e itens conformidade
	{
		//listar todos os itens
		$resu3 = mysql_query("SELECT * FROM tb_projeto_itens where projeto_id='".$_REQUEST["id_ctr"]."' and num_item<>'000' order by num_item");
		$rpt = "true";
		while($row3 = mysql_fetch_array($resu3))
		{
			//validar todos os itens pertencem ao projeto (id_ctr)
			$resu2 = mysql_query("select * from cro_projeto_etapa_item where id_projeto='".$_REQUEST["id_ctr"]."' and id_item='".$row3["id"]."'");
			if(mysql_num_rows($resu2)==0)
			{
				$rpt = "false";	
				break;
			}
		}
		if($rpt == "true")
		{	
			//atualizar projeto finalizar
			$sql = "update tb_projeto set proj_finalizar_macro='1',proj_login_alteracao='".$_REQUEST["usuario"]."',proj_dataalteracao=now() where id=".$_REQUEST["id_ctr"];
			mysql_query($sql);
			//listar itens
			$resu_itens = mysql_query("SELECT * FROM tb_projeto_itens where projeto_id='".$_REQUEST["id_ctr"]."' and num_item<>'000' order by num_item");
			while($row_itens = mysql_fetch_array($resu_itens))
			{	
				//tabela etapa item
				$resu_etapa_item = mysql_query("select * from cro_projeto_etapa_item where id_item='".$row_itens["id"]."' ");
				$row_etapa_item  = mysql_fetch_array($resu_etapa_item);
				//item tarefa
				$resu_item_tarefa = mysql_query("select * from cro_projeto_item_tarefa where id_etapa_item='".$row_etapa_item["id_etapa_item"]."' ");
				if(mysql_num_rows($resu_item_tarefa)==0)
				{	$i = 1;
					//pegar dados fase_etapa_tarefa
					$resu_fase_e_etapa_tarefa = mysql_query("select * from cro_projeto_fase_e_etapa_tarefa where id_fase_etapa='".$row_etapa_item["id_etapa"]."' and id_tarefa<>'86' ");
					while($row_fase_e_etapa_tarefa = mysql_fetch_array($resu_fase_e_etapa_tarefa))
					{	//pegar dados da tarefa
						$resu = mysql_query("select * from tb_projeto_tarefas where id=".$row_fase_e_etapa_tarefa["id_tarefa"]);
						$row  = mysql_fetch_array($resu);
						if($row["e_terceirizado"]>0)
						{	//pegar dados tarefa pae
							$resu1 = mysql_query("select * from tb_projeto_tarefas where id=".$row["e_terceirizado"]);
							$row1  = mysql_fetch_array($resu1);
							$responsavel = $row1["usu_responsavel"];
							$executor = $row["usu_responsavel"];
						}
						else
						{
							$responsavel = $row["usu_responsavel"];
							$executor = "";	
						}	
						//NOVA TAREFA DE UM DETERMINADO SUBITEM
						if($row_fase_e_etapa_tarefa["hora_final"] != "") $horaFim = $row_fase_e_etapa_tarefa["hora_final"];
						else $horaFim = "18:00";
						$num_dias = total_dias($row_fase_e_etapa_tarefa["data_inicio"],$row_fase_e_etapa_tarefa["data_final"]);
						$sql="insert into cro_projeto_item_tarefa(id_etapa_item,id_tarefa,incremento,data_inicio,num_dias,data_final,hora_final,responsavel,executor,situacao,ordem,login,data_cadastro,hora_cadastro) 
						values('".$row_etapa_item["id_etapa_item"]."','".$row_fase_e_etapa_tarefa["id_tarefa"]."','0','".$row_fase_e_etapa_tarefa["data_inicio"]."','".$num_dias."','".$row_fase_e_etapa_tarefa["data_final"]."','".$horaFim."','".$responsavel."','".$executor."','1','".($i++)."','".$_REQUEST["usuario"]."',now(),now())";
						mysql_query($sql);
					}
				}
			}
			echo "1";//finalizado
		}
		else
		{
			echo "0";//iten(s) sem etapa
		}
	}
	else
	{
		echo "2";//etapa(s) sem itens
	}
}
if($_REQUEST["op"]=="revisao")
{
	$sql = "update tb_projeto set proj_finalizar_macro='0',proj_login_alteracao='".$_REQUEST["usuario"]."',proj_dataalteracao=now() where id=".$_REQUEST["id_ctr"];
	mysql_query($sql);
}
?>