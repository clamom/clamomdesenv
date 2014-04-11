<?php
require("cn.php");

if($_REQUEST["op"]=="detalhe_tarefa")
{	//idsubitem tem tarefas
	$resu   = mysql_query("select * from cro_projeto_fase_e_etapa_tarefa where id_fase_etapa=".$_REQUEST["idsubitem"]);
	$valor1 = mysql_num_rows($resu);
	//fase_etapa 0, 1
	$resu    = mysql_query("select * from cro_projeto_fase_e_etapa where id=".$_REQUEST["idsubitem"]);
	$row     = mysql_fetch_array($resu);
	$valor2 = $row["fase_etapa"];
	echo $valor1."-".$valor2;
}
if($_REQUEST["op"]=="tarefa_item")
{
	$mar = array(171,172,173,174);
	$i=0;
	//pegar id_iten 
	$resu_item = mysql_query("SELECT * FROM cro_projeto_etapa_item where id_etapa_item = '".$_REQUEST["idetapa"]."'");
	$row_item  = mysql_fetch_array($resu_item);
	//existem tarefas 
	$resu_deta = mysql_query("select * from cro_projeto_item_tarefa where id_etapa_item = '".$_REQUEST["idetapa"]."' ");
	$tarefas = mysql_num_rows($resu_deta);
	if($tarefas > 0)
	{
		while($row_deta = mysql_fetch_array($resu_deta))
		{
			$resu_tar = mysql_query("select * from tb_projeto_sub_itens where id_itens='".$row_item["id_item"]."' and id_itens_SubComponente='0' and id_tarefa='".$row_deta["id_tarefa"]."' and ordem_tarefa='".$row_deta["ordem"]."' ");
			if(mysql_num_rows($resu_tar)==0)//sub_itens insertar
			{
				$insertar = "true";
				//PEGAR RESPONSAVEL DO ITEM
				$resu_res = mysql_query("select * from tb_projeto_itens where id='".$row_item["id_item"]."' ");
				$row_res  = mysql_fetch_array($resu_res);
				//PEGAR DADOS DA TAREFA
				$resu_tar = mysql_query("select * from tb_projeto_tarefas where id='".$row_deta["id_tarefa"]."' ");
				$row_tar  = mysql_fetch_array($resu_tar);
				//PEGAR O  PROXIMO SUBITEM
				$resu_sub = mysql_query("select max(num_sub_item) as ultimo from tb_projeto_sub_itens where id_itens='".$row_item["id_item"]."'");
				$row_sub  = mysql_fetch_array($resu_sub);
				$num_sub_item = str_pad($row_sub["ultimo"]+1, 3, "0", STR_PAD_LEFT);
				$IdSubComponente = '0';
				//procurar tarefa da mar01, mar02, mar03, mar04
				$resu_mar = mysql_query("select * from tb_projeto_sub_itens where id_ctr='".$_REQUEST["id_ctr"]."' and id_itens='".$row_item["id_item"]."' and id_itens_SubComponente='0' ");
				while($row_mar = mysql_fetch_array($resu_mar))
				{
					if(in_array($row_mar["id_tarefa"],$mar))//validar tarefa ja cadastrada pertence mar01, mar02, mar03, mar04
					{
						if(in_array($row_deta["id_tarefa"],$mar))//validar nova tarefa pertence mar01, mar02, mar03, mar04
						{
							//PEGAR DADOS DA TAREFA
							$resu_tar1 = mysql_query("select * from tb_projeto_tarefas where id='".$row_mar["id_tarefa"]."' ");
							$row_tar1  = mysql_fetch_array($resu_tar1);
							if($row_tar1["usu_responsavel"]==$row_mar["responsavel"])//responsavel não modicado é atualizado
							{
								$update_responsa = ",responsavel='".$row_tar["usu_responsavel"]."'";
							}
							else $update_responsa = "";
							//update marcenaria nova subitem
							$sql = "update tb_projeto_sub_itens set id_tarefa='".$row_deta["id_tarefa"]."' ".$update_responsa.",login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$row_mar["id"]."' ";
							mysql_query($sql);
							//update detalha do subitem
							$sql = "update tb_projeto_detalhe_tarefa set id_tarefa='".$row_deta["id_tarefa"]."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id_sub_item='".$row_mar["id"]."' ";
							mysql_query($sql);
							$insertar = "false";
							//mudar todos os subitens nova marcenaria
							change_marcenaria($row_mar["id"]);
						}
					}
				}
				if($insertar == "true")
				{
					if($row_deta["id_tarefa"] == "1")
						$responsavel = $row_res["responsavel"];//do item
					else
						$responsavel = $row_tar["usu_responsavel"];//da tarefa
					//SALVAR NOVO SUBITEM																datainicio,datafim call numdias
					$sql="insert into  tb_projeto_sub_itens(id_ctr,id_itens,id_tarefa,num_sub_item,descricao,data_inicio,num_dias,data_final,login,data_cadastro,nr_ctr,id_itens_SubComponente,ordem_tarefa,responsavel) 
					values('".$_REQUEST["id_ctr"]."','".$row_item["id_item"]."','".$row_deta["id_tarefa"]."','".$num_sub_item."','',now(),'0',now(),'".$_REQUEST["usuario"]."',now(),'".$_REQUEST["num_ctr"]."','".$IdSubComponente."','".$row_deta["ordem"]."','".$responsavel."')";					/*".strtoupper($row_res["desc_item"])."*/
					mysql_query($sql);
					$id_sub_item = mysql_insert_id();
					//SALVAR DETALHE DO NOVO SUBITEM
					$num_dias = total_dias($row_deta["data_inicio"],$row_deta["data_final"]);
					$sql="insert into tb_projeto_detalhe_tarefa(id_sub_item,id_tarefa,data_inicio,num_dias,data_final,responsavel,executor,situacao,ordem,login,data_cadastro,hora_final) 
	values('".$id_sub_item."','".$row_deta["id_tarefa"]."','".$row_deta["data_inicio"]."','".$num_dias."','".$row_deta["data_final"]."','".$row_deta["responsavel"]."','".$row_deta["executor"]."','0','1','".$_REQUEST["usuario"]."',now(),'".$row_deta["hora_final"]."')";
					mysql_query($sql);
				}
			}
			else//sub_itens update/******ATUALIZAR RESPONSAVEL
			{
				$row_tar  = mysql_fetch_array($resu_tar);
				if($row_tar["id_tarefa"] == "1")
				{
					$resu_res = mysql_query("select * from tb_projeto_itens where id='".$row_item["id_item"]."' ");
					$row_res  = mysql_fetch_array($resu_res);
					if($row_tar["responsavel"] != $row_res["responsavel"])
					{
						$sql = "update tb_projeto_sub_itens set responsavel='".$row_res["responsavel"]."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$row_tar["id"]."' ";
						mysql_query($sql);
					}
				}
			}
		}
	}
	echo $tarefas;
}
?>