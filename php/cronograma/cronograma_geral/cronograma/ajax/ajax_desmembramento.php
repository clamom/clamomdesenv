<?php
require("cn.php");

$mar  = array(171,172,173,174);//mar01, mar02, mar03, mar04     
$mar1 = array(26,27,28,29);//mar01, mar02, mar03, mar04                
if($_REQUEST["op"]=="desmembrar")
{
	//pegar responsavel do subitem pae
	$resu_res = mysql_query("select * from tb_projeto_sub_itens where id='".$_REQUEST["subitem"]."' ");
	$row_res  = mysql_fetch_array($resu_res);
	//tarefas
	$resu_deta   = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item='".$_REQUEST["subitem"]."' ");
	$num_detalhe = mysql_num_rows($resu_deta);
	if($num_detalhe > 0)
	{
		while($row_deta = mysql_fetch_array($resu_deta))
		{
			$resu_tar = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$_REQUEST["subitem"]."' and id_tarefa='".$row_deta["id_tarefa"]."' and ordem_tarefa='".$row_deta["ordem"]."'");
			if(mysql_num_rows($resu_tar)==0)//insertar 
			{
				//PEGAR DADOS DO ITEM
				$resu_item = mysql_query("select * from tb_projeto_sub_itens where id='".$_REQUEST["subitem"]."'");
				$row_item  = mysql_fetch_array($resu_item);
				$resu_desc = mysql_query("select * from tb_projeto_itens where id='".$row_item["id_itens"]."' ");
				$row_desc  = mysql_fetch_array($resu_desc);
				//PEGAR DADOS DA TAREFA
				$resu_tar = mysql_query("select * from tb_projeto_tarefas where id='".$row_deta["id_tarefa"]."' ");
				$row_tar  = mysql_fetch_array($resu_tar);
				//PEGAR O  PROXIMO SUBITEM
				$resu_sub = mysql_query("select max(num_sub_item) as ultimo from tb_projeto_sub_itens where id_itens_SubComponente=".$_REQUEST["subitem"]);
				$row_sub  = mysql_fetch_array($resu_sub);
				$num_sub_item = str_pad($row_sub["ultimo"]+1, 3, "0", STR_PAD_LEFT);
				$IdSubComponente = $_REQUEST["subitem"];
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
				//}
				//SALVAR NOVO SUBITEM												datainicio,datafim call numdias $row_tar["desc_tarefa"] $row_res["responsavel"]
			$sql="insert into tb_projeto_sub_itens(id_ctr,id_itens,id_tarefa,num_sub_item,descricao,data_inicio,num_dias,data_final,login,data_cadastro,nr_ctr,id_itens_SubComponente,ordem_tarefa,responsavel) 
				values('".$_REQUEST["id_ctr"]."','".$row_item["id_itens"]."','".$row_deta["id_tarefa"]."','".$num_sub_item."','',now(),'0',now(),'".$_REQUEST["usuario"]."',now(),'".$_REQUEST["num_ctr"]."','".$IdSubComponente."','".$row_deta["ordem"]."','".$responsavel."')";                                  /*".strtoupper($row_desc["desc_item"])."*/
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
if($_REQUEST["op"]=="desmembrar2")
{	//pegar responsavel do subitem pai
	$resu_res = mysql_query("select * from tb_projeto_sub_itens where id='".$_REQUEST["subitem"]."' ");
	$row_res  = mysql_fetch_array($resu_res);
	if(in_array($row_res["id_tarefa"],$mar))//validar tarefa subitem
	{	//listar os filhos do $_REQUEST["subitem"]
		$resu_tar = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$_REQUEST["subitem"]."' ");
		while($row_tar = mysql_fetch_array($resu_tar))
		{
			if(in_array($row_tar["id_tarefa"],$mar))//validar tarefa filho do subitem
			{
				if($row_tar["id_tarefa"] != $row_res["id_tarefa"])
				{	
					//PEGAR DADOS DA TAREFA
					$resu_tar1 = mysql_query("select * from tb_projeto_tarefas where id='".$row_tar["id_tarefa"]."' ");
					$row_tar1  = mysql_fetch_array($resu_tar1);
					if($row_tar1["usu_responsavel"]==$row_tar["responsavel"])//responsavel não modicado é atualizado
					{
						$update_responsa = ",responsavel='".$row_res["responsavel"]."'";
					}
					//atualizar a nova marcenaria 
					$sql = "update tb_projeto_sub_itens set id_tarefa='".$row_res["id_tarefa"]."' ".$update_responsa.",login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$row_tar["id"]."' ";
					mysql_query($sql);
					//alterar o detalhe tarefa do subitem id='".$row_tar["id_tarefa"]."'
					$resu_deta = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item='".$row_tar["id"]."' and id_tarefa='".$row_tar["id_tarefa"]."' ");
					if(mysql_num_rows($resu_deta)>0)
					{
						$row_deta = mysql_fetch_array($resu_deta);
						$sql = "update tb_projeto_detalhe_tarefa set id_tarefa='".$row_res["id_tarefa"]."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$row_deta["id"]."' ";
						mysql_query($sql);
					}
				}	
			}
		}
	}
	//listar os filhos do $_REQUEST["subitem"]/**********ATUALIZAR RESPONSAVEL
	$resu_tar = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$_REQUEST["subitem"]."' ");
	while($row_tar = mysql_fetch_array($resu_tar))
	{
		if($_REQUEST["countpag"]=="0")//sub_item 
		{
			if($row_tar["responsavel"] != $row_res["responsavel"])//responsaveis diferentes atualiza
			{
				$sql = "update tb_projeto_sub_itens set responsavel='".$row_res["responsavel"]."',login_alteracao='".$_REQUEST["usuario"]."',data_alteracao=now() where id='".$row_tar["id"]."' ";
				mysql_query($sql);
			}
		}
	}
}
?>