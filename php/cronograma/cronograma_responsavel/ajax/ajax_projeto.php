<?php
require("cn.php");

$mar  = array(171,172,173,174);//mar01, mar02, mar03, mar04   
$mar1 = array(26,27,28,29);//mar01, mar02, mar03, mar04 

if($_REQUEST["op"]=="finalizar")
{
	foreach($_REQUEST["data"] as $codigo)
	{               
		/*if($_REQUEST["op"]=="desmembrar")
		{*/
			//pegar responsavel do subitem pae
		$resu_res = mysql_query("select * from tb_projeto_sub_itens where id='".$codigo."' ");
		$row_res  = mysql_fetch_array($resu_res);
		if($row_res["executor"] != "")//validar executor diferente de NULL
 		{
			//tarefas
			$resu_deta   = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item='".$codigo."' ");
			$num_detalhe = mysql_num_rows($resu_deta);
			if($num_detalhe > 0)
			{
				while($row_deta = mysql_fetch_array($resu_deta))
				{
					$resu_tar = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$codigo."' and id_tarefa='".$row_deta["id_tarefa"]."' and ordem_tarefa='".$row_deta["ordem"]."'");
					if(mysql_num_rows($resu_tar)==0)//insertar 
					{
						//PEGAR DADOS DO ITEM
						$resu_item = mysql_query("select * from tb_projeto_sub_itens where id='".$codigo."'");
						$row_item  = mysql_fetch_array($resu_item);
						$resu_desc = mysql_query("select * from tb_projeto_itens where id='".$row_item["id_itens"]."' ");
						$row_desc  = mysql_fetch_array($resu_desc);
						//PEGAR DADOS DA TAREFA
						$resu_tar = mysql_query("select * from tb_projeto_tarefas where id='".$row_deta["id_tarefa"]."' ");
						$row_tar  = mysql_fetch_array($resu_tar);
						//PEGAR O  PROXIMO SUBITEM
						$resu_sub = mysql_query("select max(num_sub_item) as ultimo from tb_projeto_sub_itens where id_itens_SubComponente=".$codigo);
						$row_sub  = mysql_fetch_array($resu_sub);
						$num_sub_item = str_pad($row_sub["ultimo"]+1, 3, "0", STR_PAD_LEFT);
						$IdSubComponente = $codigo;
						/*if(in_array($row_deta["id_tarefa"],$mar))//validar tarefa ja cadastrada pertence mar01, mar02, mar03, mar04
						{
							$responsavel = $row_res["responsavel"];
						}
						else
						{*/
						if($num_detalhe == 1)//engenheria
							$responsavel = $row_res["responsavel"];
						else
						{
							if(in_array($row_tar["user_grupo"],$mar1))
							{
								$responsavel = $row_res["responsavel"];
							}
							else
							{
								$responsavel = $row_tar["usu_responsavel"];
							}
						}
						if($_REQUEST["tipo"]=="1")//
						{
							if(in_array($row_deta["id_tarefa"],$mar))
							{
								$responsavel = $row_desc["responsavel"];
							}
						}
						$url = "http://192.168.0.190/erpclamom/cronograma_executor/index.php?projeto=".$_REQUEST["id_ctr"]."&item=0&subitem=".$codigo."&tipo=".$_REQUEST["tipo"]."&executor=".$row_res["executor"]."&usuario=";
						//}
						//SALVAR NOVO SUBITEM												datainicio,datafim call numdias $row_tar["desc_tarefa"] $row_res["responsavel"]
						$sql="insert into tb_projeto_sub_itens(id_ctr,id_itens,id_tarefa,num_sub_item,descricao,data_inicio,num_dias,data_final,login,data_cadastro,nr_ctr,id_itens_SubComponente,ordem_tarefa,responsavel,liberacao,usu_gerente,status_finalizar,executor,usu_coordenador,url_cronograma) 
						values('".$_REQUEST["id_ctr"]."','".$row_item["id_itens"]."','".$row_deta["id_tarefa"]."','".$num_sub_item."','',now(),'0',now(),'".$_REQUEST["usuario"]."',now(),'".$_REQUEST["num_ctr"]."','".$IdSubComponente."','".$row_deta["ordem"]."','".$responsavel."','1','".$row_res["usu_gerente"]."','1','".$row_res["executor"]."','".$row_res["responsavel"]."','".$url."')";                   /*".strtoupper($row_desc["desc_item"])."*/
						mysql_query($sql);
						$id_sub_item = mysql_insert_id();
						if($num_detalhe > 1)//ADICIONAR TAREFAS MAIOR 1 DESMEMBRAMENTO
						{	
							//SALVAR DETALHE DO NOVO SUBITEM
							$num_dias = total_dias($row_deta["data_inicio"],$row_deta["data_final"]);											//
							$sql="insert into tb_projeto_detalhe_tarefa(id_sub_item,id_tarefa,data_inicio,num_dias,data_final,responsavel,executor,situacao,ordem,login,data_cadastro,hora_final) 
			values('".$id_sub_item."','".$row_deta["id_tarefa"]."','".$row_deta["data_inicio"]."','".$num_dias."','".$row_deta["data_final"]."','".$row_deta["responsavel"]."','".$row_deta["executor"]."','0','1','".$_REQUEST["usuario"]."',now(),'".$row_deta["hora_final"]."')";
							mysql_query($sql);
						}
					}
				}
			}
																													//,executor=NULL
			$sql = "update tb_projeto_sub_itens set status_finalizar='1',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$codigo."' ";
			mysql_query($sql);
			//atualizar sub_item
			finalizar_sub_iten_filhos($codigo,$_REQUEST["usuario"]);
			$id_subitem = $codigo;
			if($id_subitem > 0)
			{
				finalizar_sub_itens($id_subitem,$_REQUEST["usuario"]);
			}			
		}
		//}																						
	}
}
if($_REQUEST["op"]=="revisao")
{
	foreach($_REQUEST["data"] as $codigo)
	{	
		//atualizar sub_item
		$sql = "update tb_projeto_sub_itens set status_finalizar='0',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$codigo."' ";
		mysql_query($sql);
		revisao_sub_itens($codigo,$_REQUEST["usuario"]);
	}
	$resu = mysql_query("select * from tb_projeto_sub_itens where id='".$codigo."' ");
	$row  = mysql_fetch_array($resu);
	//
	if($row["id_itens_SubComponente"] > 0)
	{
		$sql = "update tb_projeto_sub_itens set status_finalizar='0',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$row["id_itens_SubComponente"]."' ";
		mysql_query($sql);
	}
}
if($_REQUEST["op"]=="liberacao")
{
	foreach($_REQUEST["data"] as $codigo)
	{
		//atualizar sub_item
		$sql = "update tb_projeto_sub_itens set liberacao='1',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$codigo."' ";
		mysql_query($sql);         
		/*if($_REQUEST["op"]=="desmembrar")
		{*/
			//pegar responsavel do subitem pae
		$resu_res = mysql_query("select * from tb_projeto_sub_itens where id='".$codigo."' ");
		$row_res  = mysql_fetch_array($resu_res);
		if($row_res["executor"] == "")//validar executor diferente igual NULL
 		{
			//tarefas
			$resu_deta   = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item='".$codigo."' ");
			$num_detalhe = mysql_num_rows($resu_deta);
			if($num_detalhe > 0)
			{
				while($row_deta = mysql_fetch_array($resu_deta))
				{
					$resu_tar = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$codigo."' and id_tarefa='".$row_deta["id_tarefa"]."' and ordem_tarefa='".$row_deta["ordem"]."'");
					if(mysql_num_rows($resu_tar)==0)//insertar 
					{
						//PEGAR DADOS DO ITEM
						$resu_item = mysql_query("select * from tb_projeto_sub_itens where id='".$codigo."'");
						$row_item  = mysql_fetch_array($resu_item);
						$resu_desc = mysql_query("select * from tb_projeto_itens where id='".$row_item["id_itens"]."' ");
						$row_desc  = mysql_fetch_array($resu_desc);
						//PEGAR DADOS DA TAREFA
						$resu_tar = mysql_query("select * from tb_projeto_tarefas where id='".$row_deta["id_tarefa"]."' ");
						$row_tar  = mysql_fetch_array($resu_tar);
						//PEGAR O  PROXIMO SUBITEM
						$resu_sub = mysql_query("select max(num_sub_item) as ultimo from tb_projeto_sub_itens where id_itens_SubComponente=".$codigo);
						$row_sub  = mysql_fetch_array($resu_sub);
						$num_sub_item = str_pad($row_sub["ultimo"]+1, 3, "0", STR_PAD_LEFT);
						$IdSubComponente = $codigo;
						/*if(in_array($row_deta["id_tarefa"],$mar))//validar tarefa ja cadastrada pertence mar01, mar02, mar03, mar04
						{
							$responsavel = $row_res["responsavel"];
						}
						else
						{*/
						if($num_detalhe == 1)//engenheria
							$responsavel = $row_res["responsavel"];
						else
						{
							if(in_array($row_tar["user_grupo"],$mar1))
							{
								$responsavel = $row_res["responsavel"];
							}
							else
							{
								$responsavel = $row_tar["usu_responsavel"];
							}
						}
						if($_REQUEST["tipo"]=="1")//
						{
							if(in_array($row_deta["id_tarefa"],$mar))
							{
								$responsavel = $row_desc["responsavel"];
							}
						}
$url = "http://192.168.0.190/erpclamom/cronograma_responsavel/index.php?projeto=".$_REQUEST["id_ctr"]."&item=0&subitem=".$codigo."&tipo=".$_REQUEST["tipo"]."&tipo2=coo&gere_coor=".$row_res["responsavel"]."&usuario=";				
//$url = "http://192.168.0.190/erpclamom/cronograma_responsavel/index.php?projeto=".$_REQUEST["id_ctr"]."&item=0&subitem=".$codigo."&tipo=".$_REQUEST["tipo"]."&usuario=".$row_res["responsavel"]."&tipo2=coo";
						//}
						//SALVAR NOVO SUBITEM												datainicio,datafim call numdias $row_tar["desc_tarefa"] $row_res["responsavel"]
						$sql="insert into tb_projeto_sub_itens(id_ctr,id_itens,id_tarefa,num_sub_item,descricao,data_inicio,num_dias,data_final,login,data_cadastro,nr_ctr,id_itens_SubComponente,ordem_tarefa,responsavel,liberacao,usu_gerente,usu_coordenador,executor,url_cronograma) 
						values('".$_REQUEST["id_ctr"]."','".$row_item["id_itens"]."','".$row_deta["id_tarefa"]."','".$num_sub_item."','',now(),'0',now(),'".$_REQUEST["usuario"]."',now(),'".$_REQUEST["num_ctr"]."','".$IdSubComponente."','".$row_deta["ordem"]."','".$responsavel."','1','".$row_res["usu_gerente"]."','".$row_res["responsavel"]."','".$row_res["responsavel"]."','".$url."')";                   /*".strtoupper($row_desc["desc_item"])."*/
						mysql_query($sql);
						$id_sub_item = mysql_insert_id();
						if($num_detalhe > 1)//ADICIONAR TAREFAS MAIOR 1 DESMEMBRAMENTO
						{	
							//SALVAR DETALHE DO NOVO SUBITEM
							$num_dias = total_dias($row_deta["data_inicio"],$row_deta["data_final"]);											//
							$sql="insert into tb_projeto_detalhe_tarefa(id_sub_item,id_tarefa,data_inicio,num_dias,data_final,responsavel,executor,situacao,ordem,login,data_cadastro,hora_final) 
			values('".$id_sub_item."','".$row_deta["id_tarefa"]."','".$row_deta["data_inicio"]."','".$num_dias."','".$row_deta["data_final"]."','".$row_deta["responsavel"]."','".$row_deta["executor"]."','0','1','".$_REQUEST["usuario"]."',now(),'".$row_deta["hora_final"]."')";
							mysql_query($sql);
						}
					}
				}
			}	
		}
		//}
	}
}
?>