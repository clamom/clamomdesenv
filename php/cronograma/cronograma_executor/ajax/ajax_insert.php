<?php
require("cn.php");
if($_REQUEST["op"]=="left")
{
	/*if($_REQUEST["id_item"] > 0)//ITEM
	{
		//PEGAR RESPONSAVEL DO ITEM
		$resu_res = mysql_query("select * from tb_projeto_itens where id='".$_REQUEST["id_item"]."' ");
		$row_res  = mysql_fetch_array($resu_res);
		//PEGAR O  PROXIMO SUBITEM
		$resu_sub = mysql_query("select max(num_sub_item) as ultimo from tb_projeto_sub_itens where id_itens=".$_REQUEST["id_item"]);
		$row_sub  = mysql_fetch_array($resu_sub);
		$num_sub_item = str_pad($row_sub["ultimo"]+1, 3, "0", STR_PAD_LEFT);
		$IdSubComponente = 0;
		$id_item  = $_REQUEST["id_item"];
	}
	else*/
	if($_REQUEST["id_subitem"] > 0)//SUBITEM 
	{
		//pegar responsavel do subitem pai
		$resu_res = mysql_query("select * from tb_projeto_sub_itens where id='".$_REQUEST["id_subitem"]."' ");
		$row_res  = mysql_fetch_array($resu_res);
		$id_item  = $row_res["id_itens"];
		//PEGAR DADOS DO ITEM
		$resuRI = mysql_query("select upper(u.name) nome,upper(i.desc_item) desc_nome,i.num_item from sec_users u inner join tb_projeto_itens i on u.login=i.responsavel where i.id='".$row_res["id_itens"]."' ");
		$rowRI  = mysql_fetch_array($resuRI);
		$nomeItem  = "Nº ".$rowRI["num_item"]." ITEM ".$rowRI["desc_nome"];
		//pegar detalhe da tarefa do subitem pai
		$resu_deta = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item='".$_REQUEST["id_subitem"]."' ");
		$row_deta  = mysql_fetch_array($resu_deta);
		//$resu_item = mysql_query("select * from tb_projeto_sub_itens where id='".$_REQUEST["id_subitem"]."'");
		//$row_item  = mysql_fetch_array($resu_item);
		//PEGAR O  PROXIMO SUBITEM
		$resu_sub = mysql_query("select max(num_sub_item) as ultimo from tb_projeto_sub_itens where id_itens_SubComponente=".$_REQUEST["id_subitem"]);
		$row_sub  = mysql_fetch_array($resu_sub);
		$num_sub_item = str_pad($row_sub["ultimo"]+1, 3, "0", STR_PAD_LEFT);
		$IdSubComponente = $_REQUEST["id_subitem"];
		//SUBITEM
		if($_REQUEST["id_subitem"] > 0)
		{
			if($IdSubComponente > 0)
			{
				$listanome = retorna_desc($IdSubComponente,$listanome);
			}
		}
		$concatenar = "";
		if(count($listanome)>0)
		{
			//listar os nomes subitem
			foreach(array_reverse($listanome) as $lis)
			{
				if(trim($lis) != "")
				$concatenar .= $lis." | ";
			}
		}
		if($concatenar != "") $concatenar = "<img src='images/msn.png' width='15' title='".$concatenar."' class='img_title' />";
		$TarefaId   = $row_deta["id_tarefa"]; 
		$task       = $row_deta["id_tarefa"];
		$resu_task  = mysql_query("select * from tb_projeto_tarefas where id='".$task."' ");
		$row_task   = mysql_fetch_array($resu_task);
		//if(count($array_link) >= 4)
		$task_task  = "[".$row_task["sigla_tarefa"]."]";
		if($_REQUEST["countpag"] >= 3)/*contatena sigla subitem*/
		{
			$resutar = mysql_query("select *,t.sigla_tarefa from tb_projeto_detalhe_tarefa d inner join tb_projeto_tarefas t on d.id_tarefa=t.id where d.id_sub_item='".$_REQUEST["id_subitem"]."' ");
			if(mysql_num_rows($resutar)>0)
			{
				$rowtar  = mysql_fetch_array($resutar);
				$task_task = "[".$row_task["sigla_tarefa"]."][".$rowtar["sigla_tarefa"]."]";
			}
		}
		if($_REQUEST["countpag"] >= 4)/*cada subitem procurar sigla*/
		{	$conca_tarefa = "";
			$array_tarefa[$row_res["id"]] = "[".$row_task["sigla_tarefa"]."]";
			$array_tarefa = retornar_tarefa($row_res["id_itens_SubComponente"],$array_tarefa);
			if(count($array_tarefa)>0)
			{
				foreach(array_reverse($array_tarefa) as $val)
				{
					$conca_tarefa .= $val;	
				}
			}
			$task_task = $conca_tarefa."[".$rowtar["sigla_tarefa"]."]";
		}
		//pegar usuario executor
		$resu_r2     = mysql_query("select *,upper(name) nome from sec_users where login = '".$row_res["responsavel"]."' ");
		$row_r2      = mysql_fetch_array($resu_r2);
		$res_exe     = "[R. ".$row_r2["nome"]."]";
		if($row_res["executor"] != "")
		{
			$resu_exe = mysql_query("select *,upper(name) nome from sec_users where login = '".$row_res["executor"]."' ");
			$row_exe  = mysql_fetch_array($resu_exe);
			$res_exe = '<img class="img_res_exe" src="images/res_exe.jpg" width="21" height="18" alt="'."[R. ".$row_r2["nome"]."]".'" title="'."[R. ".$row_r2["nome"]."]".'"/>
			[E. '.$row_exe['nome'].']';
		}
	} 
	//SALVAR NOVO SUBITEM   															datainicio,datafim call numdias
$sql="insert into tb_projeto_sub_itens(id_ctr,id_itens,id_tarefa,num_sub_item,descricao,data_inicio,num_dias,data_final,login,data_cadastro,nr_ctr,id_itens_SubComponente,ordem_tarefa,responsavel,liberacao,usu_gerente,usu_coordenador,executor,status_finalizar) 
	values('".$_REQUEST["id_ctr"]."','".$id_item."','".$row_deta["id_tarefa"]."','".$num_sub_item."','',now(),'0',now(),'".$_REQUEST["usuario"]."',now(),'".$_REQUEST["num_ctr"]."','".$IdSubComponente."','".($row_sub["ultimo"]+1)."','".$row_res["responsavel"]."','".$row_res["liberacao"]."','".$row_res["usu_gerente"]."','".$row_res["usu_coordenador"]."','".$row_res["executor"]."','".$row_res["status_finalizar"]."')";
	mysql_query($sql);
	$id = mysql_insert_id();
	//VALIDAR STATUS_FINALIZAR
	if($_REQUEST["countpag"] >= 1)
	{
		$edit_class = "";
		$edit = "";
	}
	else
	{
		$edit_class = "disabled";
		$edit  = "disabled='disabled'";
	}
	if($_REQUEST["countpag"] >= 2)
	{
		$setresponsa_class = "";
		$setresponsa = "";
	}
	else
	{
		$setresponsa_class = "disabled";
		$setresponsa = "disabled='disabled'";	
	}
	//Novo Sub item '.$num_sub_item.'
	$td='<td width="20"><input type="checkbox" name="codigo" class="codigo" value="'.$id.'" gerar_arquivo="0" /></td>
		<td style="font-size:10px; font-weight:bold; text-transform:uppercase !important;" height="45">
		<div class="descricao_label">'.$nomeItem.' |&nbsp;'.$concatenar.'</div>
		<div class="nome_label" task="'.$task.'" id_tarefa="'.$TarefaId.'" style="display:none;" >Novo Sub item '.$num_sub_item.'</div>
		<input type="text" name="'.$id.'nome" class="text_nome" id="'.$id.'nome" value="" size="50" />
		<input type="button" name="save" class="save" value="Salvar" />
		<input type="button" name="edit" class="edit '.$edit_class.'" value="Editar" '.$edit.' style="display:none" />
		<div class="itemResponsa" ><div class="siglaSetor">'.$task_task.'</div> <div class="divResponsavel">'.$res_exe.'</div></div>
		<input type="button" class="set_responsa '.$setresponsa_class.'" name="set_responsa" value="R" '.$setresponsa.' style="display:none" />
		</td>';
	echo $td;
}
elseif($_REQUEST["op"]=="right")
{
	if($_REQUEST["id_item"] > 0)//ITEM
	{
	/*$resu_my = mysql_query("SELECT DAY(data_inicio) dia1,MONTH(data_inicio) mes1,YEAR(data_inicio) ano1,DAY(data_final) dia2,MONTH(data_final) mes2, YEAR(data_final) ano2,data_inicio,data_final FROM tb_projeto_itens WHERE id='".$_REQUEST["id_item"]."'");*/
	//calcular os dias
	$resu_my = mysql_query("SELECT DAY(MIN(ta.data_inicio)) dia1,MONTH(MIN(ta.data_inicio)) mes1,YEAR(MIN(ta.data_inicio)) ano1, DAY(MAX(ta.data_final)) dia2,MONTH(MAX(ta.data_final)) mes2, YEAR(MAX(ta.data_final)) ano2, MIN(ta.data_inicio) data_inicio, MAX(ta.data_final) data_final FROM cro_projeto_item_tarefa ta inner join cro_projeto_etapa_item ei on ta.id_etapa_item = ei.id_etapa_item WHERE ei.id_item = '".$_REQUEST["id_item"]."'");
	}
	elseif($_REQUEST["id_subitem"] > 0)//SUBITEM 
	{
	$resu_my = mysql_query("SELECT DAY(MIN(data_inicio)) dia1,MONTH(MIN(data_inicio)) mes1,YEAR(MIN(data_inicio)) ano1, DAY(MAX(data_final)) dia2,MONTH(MAX(data_final)) mes2, YEAR(MAX(data_final)) ano2, MIN(data_inicio) data_inicio, MAX(data_final) data_final FROM tb_projeto_detalhe_tarefa WHERE id_sub_item='".$_REQUEST["id_subitem"]."'");	
	}
	$row_my = mysql_fetch_array($resu_my);
	$dia1 = $row_my["dia1"];
	$mes  = $row_my["mes1"];
	$ano  = $row_my["ano1"];
	$dia2 = $row_my["dia2"];
	$mes2 = $row_my["mes2"];
	$ano2 = $row_my["ano2"];
	$meses = $mes2;
	if($ano < $ano2)
	{
		$calulo = $ano2-$ano;
		$meses = $mes2 + (12*$calulo);
	} 
	for($k=$mes; $k<=$meses; $k++)
	{
		if($mes > 12)
		{
			$mes = 1;
			$ano ++;
		}
		if($mes>0)
		{
			$total_dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);	
			for ($i=1; $i<=$total_dias; $i++) 
			{	
				$data = $ano."-".str_pad($mes,2,"0",STR_PAD_LEFT)."-".str_pad($i,2,"0",STR_PAD_LEFT);
				$ano3 =  substr($data, 0, 4);
				$mes3 =  substr($data, 5, -3);
				$dia  =  substr($data, 8, 9);
				if($data >= $row_my["data_inicio"] and $data <= $row_my["data_final"])
				{
					$diasemana = date("w", mktime(0,0,0,$mes3,$dia,$ano3));					
					if($diasemana>=1 and $diasemana<=5)  $td .= '<td width="45" height="45" class="td_click" id="'.$data.'">&nbsp;</td>';
					else $td .= '<td width="45" height="45" class="td_click" id="'.$data.'" style="background:#CCCCCC;">&nbsp;</td>';
				}
			}
		}
		$mes++;
	}
	echo $td;
}
?>