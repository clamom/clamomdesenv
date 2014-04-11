<!--
 * @CRONOGRAMA RESPONSAVEL [GERENTE | COORDENADOR]
 * @autor  : Eduardo Zambrano <eduardoz@clamom.com.br>
 * @versão : 1.0
 * @data   : 26/03/2014
 * @Copyright 2014 http://www.clamom.com.br
 *-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../../../js/jquery/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../../js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="../../../js/jquery/jquery.maskedinput-1.2.2.js"></script>
<script type="text/javascript" src="../../../js/alert/alertify.min.js"></script><?php /*personalizar alert jquery*/?>
<script type="text/javascript" src="../../../js/jquery/jquery.blockUI.js"></script><?php /*bloquear tela Aguarde Processando Dados...*/?>
<script type="text/javascript" src="../../../js/autocomplete/chosen.jquery.js"></script>
<script type="text/javascript" src="../../../js/cronograma_responsavel/grafico.js"></script><?php /* todas as acoes do cronograma */?>
<link rel="stylesheet" type="text/css" href="../../../css/alert/alertify.core.css"/><?php /*personalizar alert jquery*/?>
<link rel="stylesheet" type="text/css" href="../../../css/alert/alertify.default.css"/><?php /*personalizar alert jquery*/?>
<link rel="stylesheet" type="text/css" href="../../../css/autocomplete/chosen.css"/>
<link rel="stylesheet" type="text/css" href="../../../css/cronograma_responsavel/grafico.css"/>

<title>Meu Cronograma [Respons&aacute;vel]</title>
</head>
<body>
<div id="msn_save" style="display:none;">
<img src="../../../images/cronograma_all/aguarde.gif" width="30" height="30" class="img_gif" /><div class="txt_gif">Aguarde Processando Dados...</div>
</div>
<?php
require("ajax/cn.php");
$id_ctr     = $_REQUEST["projeto"];//"13200";
$id_item    = $_REQUEST["item"];//'1991';
$id_subitem = $_REQUEST["subitem"];//;
if($id_ctr > 0)
{	//tipo
	if($_REQUEST["tipo"]=="0") $tipo = 1;
	else $tipo = 0;
	//ITEM
	if($id_subitem > 0)//SUBITEM
	{
		$resu7 = mysql_query("select upper(s.descricao) desc_nome,s.id_itens,s.id_itens_SubComponente,s.id idsubitem,upper(u.name) nome,s.id_tarefa,s.nr_ctr proj_ctr,concat(p.proj_ctr,' | ',p.proj_desc_ctr) titulo, s.status_finalizar estado_finalizar,s.responsavel FROM tb_projeto_sub_itens s inner join sec_users u on s.responsavel=u.login inner join tb_projeto p on p.proj_ctr=s.nr_ctr where s.id='".$id_subitem."' ");
		if (mysql_num_rows($resu7)>0)
		{
    		$row7       = mysql_fetch_array($resu7);
			$resu_sigla = mysql_query("select * from tb_projeto_tarefas where id='".$row7["id_tarefa"]."' ");
			$row_sigla  = mysql_fetch_array($resu_sigla);
			$responsa   = "[".$row_sigla["sigla_tarefa"]."][R. ".$row7["nome"]."]";
			$titulo     = $row7["titulo"];
			$estado_finalizar = $row7["estado_finalizar"];
			//pegar responsavel
			$resuRI = mysql_query("select upper(u.name) nome,upper(i.desc_item) desc_nome,i.responsavel responsavel,i.num_item,i.quantidade from sec_users u inner join tb_projeto_itens i on u.login=i.responsavel where i.id='".$row7["id_itens"]."' ");
			$rowRI  = mysql_fetch_array($resuRI);
			//dados sigla => responsavel
			$resu_sigla3 = mysql_query("select * from tb_projeto_tarefas where usu_responsavel='".$rowRI["responsavel"]."' ");
			$row_sigla3  = mysql_fetch_array($resu_sigla3);
			$responsame  = "[".return_projeto($row_sigla3["sigla_tarefa"])."][R. ".$rowRI["nome"]."]";
			//
			$nomeItem    = "Nº ".$rowRI["num_item"]." ITEM | ".$rowRI["desc_nome"];
			$qtdItem     = "Quantidade ".$rowRI["quantidade"];
			if($row7["desc_nome"] != "")
			{
				$nomeSubItem = "<img src='../../../images/cronograma_all/msn.png' width='15' title='".$row7["desc_nome"]."' class='img_title' />";
			}
			else
			{
				$nomeSubItem = "";
			}
		}
		else $titulo = "<div class='erro_geral'>ERRO NO CRONOGRAMA RESPONSAVEL [GERENTE | COORDENADOR] <br/>Entrar em contato [Eduardo Zambrano][eduardoz@clamom.com.br][TI]</div>";
$resu_my = mysql_query("SELECT DAY(MIN(data_inicio)) dia1,MONTH(MIN(data_inicio)) mes1,YEAR(MIN(data_inicio)) ano1, DAY(MAX(data_final)) dia2,MONTH(MAX(data_final)) mes2, YEAR(MAX(data_final)) ano2, MIN(data_inicio) data_inicio, MAX(data_final) data_final, id_tarefa FROM tb_projeto_detalhe_tarefa WHERE id_sub_item='".$id_subitem."'");
		//navegação fase e etapa
		$resu_fase = mysql_query("select * from cro_projeto_etapa_item where id_item = '".$row7["id_itens"]."'");
		$row_fase = mysql_fetch_array($resu_fase);
		if($row_fase["id_etapa"]>0)
		{
			//dados ETAPA, FASE
			$resu1 = mysql_query("SELECT *,upper(desc_etapa) desc_etapa FROM cro_projeto_fase_e_etapa where id='".$row_fase["id_etapa"]."'");
			$row1 = mysql_fetch_array($resu1);
			//dados responsavel
			$resuR = mysql_query("select *,upper(name) nome from sec_users where login='".$row1["responsavel"]."' ");
			$rowR = mysql_fetch_array($resuR);
			$src = "index.php?projeto=".$id_ctr."&id_fase=".$row1["id_pai"]."&usuario=".$_REQUEST["usuario"];
			//dados sigla => responsavel
			$resu_sigla1 = mysql_query("select * from tb_projeto_tarefas where usu_responsavel='".$row1["responsavel"]."' ");
			$row_sigla1  = mysql_fetch_array($resu_sigla1);
			$responsame  = "[".return_projeto($row_sigla1["sigla_tarefa"])."][R. ".$rowR["nome"]."]";
			$resu1 = mysql_query("SELECT *,upper(desc_etapa) desc_etapa FROM cro_projeto_fase_e_etapa where id='".$row_fase["id_etapa"]."'");
			$row1 = mysql_fetch_array($resu1);
			//PEGAR RESPONSAVEL DO ITEM
			$resu_item = mysql_query("select * from tb_projeto_itens where id='".$row7["id_itens"]."' ");
			$row_item  = mysql_fetch_array($resu_item);
			//dados responsavel
			$resu_res = mysql_query("select *,upper(name) nome from sec_users where login='".$row_item["responsavel"]."' ");
			$row_res  = mysql_fetch_array($resu_res);
			//dados grupo 
			$resu_gru = mysql_query("select * from sec_users_groups where login='".$row_item["responsavel"]."' ");
			$row_gru = mysql_fetch_array($resu_gru);
			$idcodigo = return_mar($row_gru["group_id"]);
			$resu_task = mysql_query("select * from tb_projeto_tarefas where id='".$idcodigo."' ");
			$row_task = mysql_fetch_array($resu_task);
			$responsame1  = "[".($row_task["sigla_tarefa"])."][R. ".$row_res["nome"]."]";
			
			if($row1["fase_etapa"]==0)//fase
			{
				$cadena = "{FS}".substr($row1["desc_etapa"],0,1).substr($row1["desc_etapa"],-1);
			}
			if($row1["fase_etapa"]==1)//etapa
			{
				$cadena = "{ET}".$row1["desc_etapa"];
			}
			$array_link[$id_fase] = array('nome' => $cadena,'id' => $id_fase,'src' => $src,'responsavel' => $responsame);
			if($row1["id_pai"]>0)
			{
				$array_link = lista_link_fase($row1["id_pai"],$array_link,$id_ctr,$_REQUEST["usuario"]);
			}
			$m=0;
			if(count($array_link)>0)
			{
				foreach(array_reverse($array_link) as $vetor=>$v)
				{
	//if($m==0) $navegacao_fase .= "<div class='link'><a href='../".$v["src"]."'>".$v["nome"]."<br/>".$v["responsavel"]."</a></div>";
	if($m==0) $navegacao_fase .= "<div class='link'>".$v["nome"]."<br/>".$v["responsavel"]."</div>";
	//else      $navegacao_fase .= "<img src='../../../images/cronograma_all/arrow.png' width='15' class='imgArrow' /><div class='link'><a href='../".$v["src"]."'>".$v["nome"]."<br/>".$v["responsavel"]."</a></div>";
	else      $navegacao_fase .= "<img src='../../../images/cronograma_all/arrow.png' width='15' class='imgArrow' /><div class='link'>".$v["nome"]."<br/>".$v["responsavel"]."</div>";
					$m++;
				}
			//$navegacao_fase .= "<img src='../../../images/cronograma_all/arrow.png' width='15' class='imgArrow' /><div class='link'><a href='../index.php?projeto=".$id_ctr."&id_fase=".$row_fase["id_etapa"]."&usuario=".$_REQUEST["usuario"]."'>".$rowRI["desc_nome"]."<br>".$responsame1."</a></div>";
			$navegacao_fase .= "<img src='../../../images/cronograma_all/arrow.png' width='15' class='imgArrow' /><div class='link'>".$rowRI["desc_nome"]."<br>".$responsame1."</div>";
			}
		}
		unset($array_link);
		//navegação principal
		if($row7["id_itens"] > 0 && $row7["id_itens_SubComponente"] == 0)
		{
			$src="index.php?projeto=".$id_ctr."&item=".$row7["id_itens"]."&subitem=0&tipo=1&usuario=".$_REQUEST["usuario"]."&tipo2=".$_REQUEST["tipo2"];
			$array_link[$row7["id"]] = array('nome' => $nomeItem." | ".$nomeSubItem,'id' => $row7["id"],'src' => $src,'responsavel' => $responsa);
		}
		elseif($row7["id_itens_SubComponente"]>0)
		{
			$tipo1 = $tipo;//tipo 
			$src = "index.php?projeto=".$id_ctr."&item=0&subitem=".$row7["id_itens_SubComponente"]."&tipo=".$tipo1."&tipo2=".$_REQUEST["tipo2"]."&gere_coor=".$_REQUEST["gere_coor"]."&usuario=".$_REQUEST["usuario"];
		  //$src = "index.php?projeto=".$id_ctr."&item=0&subitem=".$row7["id_itens_SubComponente"]."&tipo=".$tipo1."&usuario=".$_REQUEST["usuario"]."&tipo2=".$_REQUEST["tipo2"];
			$array_link[$row7["id"]] = array('nome' => $nomeItem." | ".$nomeSubItem,'id' => $row7["id"],'src' => $src,'responsavel' => $responsa);
			$array_link = lista_link($row7["id_itens_SubComponente"],$array_link,$id_ctr,$tipo1,$_REQUEST["usuario"],$_REQUEST["tipo2"],$_REQUEST["gere_coor"]);
		}
	}
	if($_REQUEST["tipo2"] == "ger") 
		$j = 2;
	elseif($_REQUEST["tipo2"] == "coo") 
		$j = 3;
	$i = 1;
	if(count($array_link)>0)
	{
		foreach(array_reverse($array_link) as $vetor=>$v)
		{
			//if($i > $j)//if($i > 2)
			//{
				//if($v["src"] == "#") $navegacao .= "<b>".$v["nome"]."</b>";
				//else $navegacao .= "<img src='../../../images/cronograma_all/arrow.png' width='15' class='imgArrow' /><div class='link'><a href='".$v["src"]."'>".$v["nome"]."<br/>".$v["responsavel"]."</a></div>";
			if($i <= ($j+1))
				$navegacao .= "<img src='../../../images/cronograma_all/arrow.png' width='15' class='imgArrow' /><div class='link'>".$v["nome"]."<br/>".$v["responsavel"]."</div>";
			else	
				$navegacao .= "<img src='../../../images/cronograma_all/arrow.png' width='15' class='imgArrow' /><div class='link'><a href='".$v["src"]."'>".$v["nome"]."<br/>".$v["responsavel"]."</a></div>";
			//}
			$i++;
		}
	}
	if($estado_finalizar == "0")
	{	?>
    <!--<input type="button" id="copy" value="Duplicar" class="botao" />-->
<?php if(count($array_link) > 2)
	  {?>    
    <input type="button" id="dele" value="Apagar" class="botao" />  
	<input type="button" id="add" value="Adicionar" class="botao" />
    <input type="button" id="save_completo" value="Salvar Grafico" class="botao" />
<?php }
	else
	{?>
    <input type="button" id="dele" value="Apagar"    class="botao disabled" disabled="disabled" />
    <input type="button" id="add"  value="Adicionar" class="botao disabled" disabled="disabled" />
    <input type="button" id="save_completo" value="Salvar Grafico" class="botao disabled" disabled="disabled" />
<?php 
	}?>        
    <input type="button" id="liberacao" value="Liberar Cronograma" class="botaoFim disabled" disabled="disabled" />
    <input type="button" id="finalizar" value="Finalizar Executor" class="botaoFim disabled" disabled="disabled" />
    <input type="button" id="revisao" value="Revis&atilde;o Executor" class="botaoFim disabled" disabled="disabled" />
<?php	
	}
	else//realizado
	{?>
    <input type="button" id="save_realizado" value="Salvar Grafico" class="botao" />
    <input type="button" id="finalizar" value="Finalizar Executor" class="botaoFim" style="background:#FF0000; display:none;" />
    <input type="button" id="revisao" value="Revis&atilde;o Executor" class="botaoFim" style="display:none;" />
<?php }?>  
    <input type="hidden" id="id_ctr" name="id_ctr" value="<?php echo trim($id_ctr)?>" size="10" />
    <input type="hidden" id="id_item" name="id_item" value="<?php echo trim($id_item)?>" size="10">
    <input type="hidden" id="id_subitem" name="id_subitem" value="<?php echo trim($id_subitem)?>" size="10">
    <input type="hidden" id="num_ctr" name="num_ctr" value="<?php echo trim($row7["proj_ctr"])?>" size="10">
    <input type="hidden" id="usuario" name="usuario" value="<?php echo $_REQUEST["usuario"]?>"><!-- scriptcase [usr_login] -->
    <input type="hidden" id="gere_coor" name="gere_coor" value="<?php echo $_REQUEST["gere_coor"]?>"><!-- gerente / coordenador -->
    <input type="hidden" id="data1" name="data1"  value="0" size="10" />
    <input type="hidden" id="fila1" name="fila1"  value="0" size="10" />
    <input type="hidden" id="filaUnica" name="filaUnica"  value="0" size="10" />
    <input type="hidden" id="data2" name="data2"  value="0" size="10" />
    <input type="hidden" id="fila2" name="fila2"  value="0" size="10" />
    <input type="hidden" id="id_detalhe" name="id_detalhe" value="0" size="10" />
    <input type="hidden" id="intX" name="intX" value="0" size="10" />
    <input type="hidden" id="intY" name="intY" value="0" size="10" />
    <input type="hidden" id="aux" name="aux" value="0" size="10" />
    <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo?>" size="10" />
    <input type="hidden" id="tipo2" name="tipo2" value="<?php echo $_REQUEST["tipo2"]?>" size="10" />
    <input type="hidden" id="countpag" name="countpag" value="<?php echo count($array_link)?>" size="10" />
	<div class="navegacao"><pre><div class="aqui">Voc&eacute; est&aacute; em :&nbsp;</div><div class="esta"><?php echo $navegacao_fase.$navegacao?></div></pre></div>
<?php /****************/
	$row_my = mysql_fetch_array($resu_my);
	if(count($array_link)>=4)//campo titulo(descricao) tabela
	{	
		//pegar sigla
		$resu_sigla1 = mysql_query("select * from tb_projeto_tarefas where id='".$row_my["id_tarefa"]."' ");
		if(mysql_num_rows($resu_sigla1) > 0)
		{
			$row_sigla1  = mysql_fetch_array($resu_sigla1);	
			$responsa    = "[".$row_sigla["sigla_tarefa"]."][".$row_sigla1["sigla_tarefa"]."][R. ".$row7["nome"]."]";
		}
	}
	if(count($array_link) >= 5)//campo titulo(descricao) tabela
	{	$conca_tarefa1 = "";
		$array_tarefa1[$row7["id"]] = "[".$row_sigla["sigla_tarefa"]."]";
		$array_tarefa1 = retornar_tarefa($row7["id_itens_SubComponente"],$array_tarefa1);
		if(count($array_tarefa1)>0)
		{
			foreach(array_reverse($array_tarefa1) as $val)
			{
				$conca_tarefa1 .= $val;	
			}
		}
		$responsa = $conca_tarefa1."[R. ".$row7["nome"]."]";
	}
	$dia1 = $row_my["dia1"];
	$mes  = $row_my["mes1"];
	$ano  = $row_my["ano1"];
	$dia2 = $row_my["dia2"];
	$mes2 = $row_my["mes2"];
	$ano2 = $row_my["ano2"];
	$tot_col = 0;
	$coluna_mes = "";
	$coluna_dia_txt = "";
	$coluna_dia = "";
	$corpo_gantt = "";
	$meses = $mes2;
	if($ano < $ano2)
	{
		$calulo = $ano2-$ano;
		$meses = $mes2 + (12*$calulo);
	}
if($row_my["data_inicio"]!="" and $row_my["data_final"]!="")
{	
for($k=$mes; $k<=$meses; $k++)//while($mes <= $meses)
{
	if($mes > 12)
	{
		$mes = 1;
		$ano ++;
	}
	switch ($mes) 
	{
		case 1: $mes_ext = "JAN"; break;
		case 2: $mes_ext = "FEV"; break;
		case 3: $mes_ext = "MAR"; break;
		case 4: $mes_ext = "ABR"; break;
		case 5: $mes_ext = "MAIO"; break;
		case 6: $mes_ext = "JUN"; break;
		case 7: $mes_ext = "JUL"; break;
		case 8: $mes_ext = "AGO"; break;
		case 9: $mes_ext = "SET"; break;
		case 10: $mes_ext = "OUT"; break;
		case 11: $mes_ext = "NOV"; break;
		case 12: $mes_ext = "DEZ"; break;
	}
	$total_dias1 = ceil(CalculaDias(dataform($row_my["data_inicio"]),dataform($row_my["data_final"])));
	$total_dias2=0;//$total_dias1+1;
	if($row_my["ano1"].$row_my["mes1"]==$row_my["ano2"].$row_my["mes2"]) $total_dias2 = cal_numero($row_my["dia1"],$row_my["dia2"]);//unico mes
	elseif($row_my["ano1"].$row_my["mes1"]==$ano.$mes) $total_dias2 = cal_days_in_month(CAL_GREGORIAN, $mes, $ano) - ($row_my["dia1"]-1);//primeiro mes
	elseif($row_my["ano2"].$row_my["mes2"]==$ano.$mes) $total_dias2 = $row_my["dia2"];//ultimo mes
	else $total_dias2 = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);//todos os meses restantes
	$coluna_mes .= "<td height='20' colspan=".($total_dias2)." align='center' style='font-size:11px; font-weight:bold; background:url(../../../images/cronograma_all/fundotr.jpg) repeat !important; color:#006633;'>".$mes_ext."/".substr($ano,2,2)."</td>";
	//calcular dias da semana
	$total_dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
	for ($i=1; $i<=$total_dias; $i++) 
	{
		$data=$ano."-".str_pad($mes,2,"0",STR_PAD_LEFT)."-".str_pad($i,2,"0",STR_PAD_LEFT);
		$ano =  substr($data, 0, 4);
		$mes =  substr($data, 5, -3);
		$dia =  substr($data, 8, 9);
		if($data >= $row_my["data_inicio"] and $data <= $row_my["data_final"])
		{	
			$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano));
			switch($diasemana) 
			{
				case"0": $diasemana = "Dom"; $background="#CCCCCC"; break;
				case"1": $diasemana = "Seg"; $background="#FFFFFF"; break;
				case"2": $diasemana = "Ter"; $background="#FFFFFF"; break;
				case"3": $diasemana = "Qua"; $background="#FFFFFF"; break;
				case"4": $diasemana = "Qui"; $background="#FFFFFF"; break;
				case"5": $diasemana = "Sex"; $background="#FFFFFF"; break;
				case"6": $diasemana = "S&aacute;b"; $background="#CCCCCC"; break;
			}
			if(intval(date('Y'))==$ano && intval(date('m'))==$mes && $i==intval(date('d'))) 
			{
				$background = "#FF0000";
				$fonte = "#FFFFFF";
			}
			else $fonte = "#004E9B";
			$coluna_dia .= "<td width='30' height='20' align='center' style='border-style:solid; background:".$background."; color:".$fonte.";'>".str_pad($i,2,"0",STR_PAD_LEFT)."</td>";
		}
		$tot_col = $tot_col +1;
	}
	for ($i=1; $i<=$total_dias; $i++) 
	{
		$data=$ano."-".str_pad($mes,2,"0",STR_PAD_LEFT)."-".str_pad($i,2,"0",STR_PAD_LEFT);
		$ano =  substr($data, 0, 4);
		$mes =  substr($data, 5, -3);
		$dia =  substr($data, 8, 9);
		if($data >= $row_my["data_inicio"] and $data <= $row_my["data_final"])
		{
			$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano));
			switch($diasemana) 
			{
				case"0": $diasemana = "Dom"; $background="#CCCCCC"; break;
				case"1": $diasemana = "Seg"; $background="#FFFFFF"; break;
				case"2": $diasemana = "Ter"; $background="#FFFFFF"; break;
				case"3": $diasemana = "Qua"; $background="#FFFFFF"; break;
				case"4": $diasemana = "Qui"; $background="#FFFFFF"; break;
				case"5": $diasemana = "Sex"; $background="#FFFFFF"; break;
				case"6": $diasemana = "S&aacute;b"; $background="#CCCCCC"; break;
			}
			if(intval(date('Y'))==$ano && intval(date('m'))==$mes && $i==intval(date('d'))) 
			{
				$background = "#FF0000";
				$fonte = "#FFFFFF";
			}
			else $fonte = "#004E9B";
		$coluna_dia_txt .= "<td width='30' height='20' align='center' style='border-style:solid; background:".$background."; color:".$fonte.";'>&nbsp;".$diasemana."&nbsp;</td>";
		}
	}
	$mes++;
}
}
/*******/
$tot_col = $tot_col+1;
$linha_titulo = "
	<tr class='0'>
	<td width='20' style='background:url(../../../images/cronograma_all/fundotr.jpg) repeat !important;'>
	<input type='checkbox' name='AllCodigo' id='AllCodigo' onclick='$.MarcarDesmarcar();'/></td>
	<td width='510' height='62' style='color:#006633; font-weight:bold; font-size:12px; background:url(../../../images/cronograma_all/fundotr.jpg) repeat !important; text-transform:uppercase !important;'>
	<div id='tituloPrincipal'>".$titulo."<br/>".$nomeItem.' | '.$nomeSubItem."<br/>".$qtdItem."</div>
	<div id='topResponsa'>".$responsa."&nbsp;</div>
	</td></tr>";
$cont=0;
	//SUBITEM
	/*if($id_item > 0)//ITEM
	{
		$resu8 = mysql_query("SELECT *,upper(descricao) descricao FROM tb_projeto_sub_itens where id_itens= '".$id_item."' and id_itens_SubComponente='0' ORDER BY num_sub_item");
	}
	else*/
	if($id_subitem > 0)//SUBITEM
	{
		if($_REQUEST["tipo2"] == "ger")//GERENTE
		{
			$resu8 = mysql_query("SELECT *,upper(descricao) descricao FROM tb_projeto_sub_itens where id_itens_SubComponente='".$id_subitem."' and usu_gerente='".$_REQUEST["gere_coor"]."' ORDER BY num_sub_item");
		}
		elseif($_REQUEST["tipo2"] == "coo")//COORDENADOR
		{
			$resu8 = mysql_query("SELECT *,upper(descricao) descricao FROM tb_projeto_sub_itens where id_itens_SubComponente='".$id_subitem."' and usu_coordenador='".$_REQUEST["gere_coor"]."' ORDER BY num_sub_item");
		}
	}
	while($row8 = mysql_fetch_array($resu8))
	{	
		if($id_subitem > 0)//SUBITEM
		{
			if($row8["id_itens_SubComponente"] > 0)
			{
				$listanome = retorna_desc($row8["id_itens_SubComponente"],$listanome);
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
		//pegar usuario
		$resu_r2     = mysql_query("select *,upper(name) nome from sec_users where login = '".$row8["responsavel"]."' ");
		$row_r2      = mysql_fetch_array($resu_r2);
		$res_exe     = "[R. ".$row_r2["nome"]."]";
		//pegar executor
		$executor = "";
		if($row8["executor"] != "")
		{
			$resu_exe = mysql_query("select *,upper(name) nome from sec_users where login = '".$row8["executor"]."' ");
			$row_exe  = mysql_fetch_array($resu_exe);
			$executor = $row_exe["nome"];
			$res_exe = '<img class="img_res_exe" src="../../../images/cronograma_all/res_exe.jpg" width="21" height="18" alt="'."[R. ".$row_r2["nome"]."]".'" title="'."[R. ".$row_r2["nome"]."]".'"/>
			[E. '.$row_exe['nome'].']';
		}
			
		$cont++;
		if(($cont%2) == 0) $adclass="class='yellow'";
		else $adclass="";
		if($id_item > 0)//primeiro nivel
		{ 
			$TarefaId = 0;
			$task = $row8["id_tarefa"];
			//pegar sigla da tarefa
			$resu_task = mysql_query("select * from tb_projeto_tarefas where id='".$task."' ");
			$row_task = mysql_fetch_array($resu_task);
			$task_task = "[".$row_task["sigla_tarefa"]."]";
		}
		else//todos os niveis
		{
			if($row8["id_tarefa"]=="") $TarefaId = 0;
			else $TarefaId = $row8["id_tarefa"];
			$task = $row8["id_tarefa"];
			//pegar sigla da tarefa
			$resu_task = mysql_query("select * from tb_projeto_tarefas where id='".$task."' ");
			$row_task = mysql_fetch_array($resu_task);
			$task_task = "[".$row_task["sigla_tarefa"]."]";
			if($concatenar != "") $concatenar = "<img src='../../../images/cronograma_all/msn.png' width='15' title='".$concatenar."' class='img_title' />";
			if(count($array_link) >= 3)/*contatena sigla subitem*/
			{
				$resutar = mysql_query("select *,t.sigla_tarefa from tb_projeto_detalhe_tarefa d inner join tb_projeto_tarefas t on d.id_tarefa=t.id where d.id_sub_item='".$row8["id"]."' ");
				if(mysql_num_rows($resutar)>0)
				{
					$rowtar  = mysql_fetch_array($resutar);
					$task_task = "[".$row_task["sigla_tarefa"]."][".$rowtar["sigla_tarefa"]."]";
				}
			}
			if(count($array_link) >= 4)/*cada subitem procurar sigla*/
			{	$conca_tarefa = "";
				$array_tarefa[$row7["id"]] = "[".$row_task["sigla_tarefa"]."]";
				$array_tarefa = retornar_tarefa($row7["id_itens_SubComponente"],$array_tarefa);
				if(count($array_tarefa)>0)
				{
					foreach(array_reverse($array_tarefa) as $val)
					{
						$conca_tarefa .= $val;	
					}
				}
				$task_task = $conca_tarefa."[".$rowtar["sigla_tarefa"]."]";
			}
		}
		//VALIDAR FONTE
		$fonteItem = "#333333";
		$resuFonte = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$row8["id"]."'");
		if(mysql_num_rows($resuFonte)>0) $fonteItem = "#FF0000";
		if(count($array_link) >= 1)//VALIDAR STATUS_FINALIZAR
		{
			$edit_class = "";
			$edit = "";
		}
		else
		{
			$edit_class = "disabled";
			$edit  = "disabled='disabled'";
		}
		if(count($array_link) >= 2)//setar responsavel
		{
			$setresponsa_class = "";
			$setresponsa = "";
		}
		else
		{
			$setresponsa_class = "disabled";
			$setresponsa = "disabled='disabled'";	
		}
		if(count($array_link) <= 2 or $fonteItem == "#FF0000")//botao Editar Set Responsavel
		{
			$edit_class = "disabled";
			$edit  = "disabled='disabled'";
			$setresponsa_class = "disabled";
			$setresponsa = "disabled='disabled'";	
		}
		if($row8["status_finalizar"]=='1')//finalizar ativo
		{
			$edit_class = "disabled1";
			$edit  = "disabled='disabled'";
			$setresponsa_class = "disabled1";
			$setresponsa = "disabled='disabled'";	
		}
		$checkbox = "";
		if($row7["responsavel"] == $row8["responsavel"])
		{
			if($executor == "")
				$checkbox = "disabled='disabled'";
		}
		if($_REQUEST["tipo2"] == "ger")//cronograma gerente
		{
			if($row8["executor"] != "")
			{
				$value_res = "E";
			}
			elseif($row8["executor"] == "")
			{
				$value_res = "R";
			}
		}
		elseif($_REQUEST["tipo2"] == "coo")//cronograma coordenador
		{
			$value_res = "E";
		}
		$corpo_gantt .= "<tr id='left_".$cont."' ".$adclass.">
		<td width='20'><input type='checkbox' name='codigo' class='codigo' value='".$row8["id"]."' liberacao='".$row8["liberacao"]."' ".$checkbox." /></td>
		<td style='font-size:10px; font-weight:bold; text-transform:uppercase !important;' height='45'>
			<div class='descricao_label' style='color:".$fonteItem."'>".$nomeItem." |&nbsp;".$concatenar."</div>
			<div class='nome_label' task='".$task."' id_tarefa='".$TarefaId."' style='color:".$fonteItem."' >".$row8["descricao"]."</div>
			<input type='text' name='".$row8["id"]."nome' class='text_nome' id='".$row8["id"]."nome' value='".$row8["descricao"]."' size='50' style='display:none' />
			<input type='button' name='save' class='save' value='Salvar' style='display:none' />
			<input type='button' name='edit' class='edit ".$edit_class."' value='Editar' ".$edit." />
			<div class='itemResponsa'><div class='siglaSetor'>".$task_task."</div><div class='divResponsavel'>".$res_exe."</div></div>
			<input type='button' name='set_responsa' class='set_responsa ".$setresponsa_class."' value='".$value_res."' ".$setresponsa." />
		</td></tr>";
		$corpo_gantt1 .= "<tr id='right_".$cont."' ".$adclass.">";
		$count_vetor = 0;
		//DETALHE TAREFA
		unset($vetor);
		$resu_tarefa = mysql_query("select *,d.id id_detalhes from tb_projeto_detalhe_tarefa d inner join tb_projeto_tarefas t on d.id_tarefa=t.id where d.id_sub_item='".$row8["id"]."' order by d.data_inicio");
		while($row_tarefa = mysql_fetch_array($resu_tarefa))
		{
			$vetor[$count_vetor][1] = $row_tarefa["data_inicio"];
			$vetor[$count_vetor][2] = $row_tarefa["data_final"];
			$vetor[$count_vetor][3] = $row_tarefa["cor_tarefa"];
			$vetor[$count_vetor][4] = $row_tarefa["id_tarefa"];
			$vetor[$count_vetor][5] = $row_tarefa["sigla_tarefa"];
			$vetor[$count_vetor][6] = $row_tarefa["id_detalhes"];
			$vetor[$count_vetor][7] = $row_tarefa["hora_final"];
			$vetor[$count_vetor][8] = $row_tarefa["desc_tarefa"];
			$count_vetor++;
		}
		$mes  = $row_my["mes1"];
		$ano  = $row_my["ano1"];
		$mes2 = $row_my["mes2"];
		$ano2 = $row_my["ano2"];
		$meses = $mes2;
		$icount=0;
		if($ano < $ano2)
		{
			$calulo = $ano2-$ano;
			$meses = $mes2 + (12*$calulo);
		} 
		if($meses>0)
		{
			for($k=$mes; $k<=$meses; $k++)
			{
				if($mes > 12)
				{
					$mes = 1;
					$ano ++;
				}
				$total_dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano)."<br>";	
				for ($i=1; $i<=$total_dias; $i++) 
				{
					$data = $ano."-".str_pad($mes,2,"0",STR_PAD_LEFT)."-".str_pad($i,2,"0",STR_PAD_LEFT);
					$ano3 =  substr($data, 0, 4);
					$mes3 =  substr($data, 5, -3);
					$dia  =  substr($data, 8, 9);
					if($data >= $row_my["data_inicio"] and $data <= $row_my["data_final"])
					{
						$diasemana = date("w", mktime(0,0,0,$mes3,$dia,$ano3));	
						if($icount<$count_vetor)
						{	
							$date_ini     = $vetor[$icount][1];
							$date_fim     = $vetor[$icount][2];
							$color        = $vetor[$icount][3];
							$id_tarefa 	  = $vetor[$icount][4];
							$sigla_tarefa = $vetor[$icount][5];
							$id_detalhes  = $vetor[$icount][6];
							$hora_final   = $vetor[$icount][7];
							$desc_tarefa  = $vetor[$icount][8];
							if($data>=$date_ini and $data<=$date_fim)
							{
								$hora2="";$imagem_clock="";$image_alerta="";
$resu_sta = mysql_query("select * from tb_projeto_execucao e inner join tb_projeto_status s on e.id_status=s.id where e.id_tarefa='".$id_detalhes."' and e.data_status like '".$data."%' order by 1 desc");
								if(mysql_num_rows($resu_sta)>0)
								{
									$row_sta   = mysql_fetch_array($resu_sta);
									$codcor    = $row_sta["codcor_status"];
									$titleTXT  = strtoupper($row_sta["desc_status"])." - ".substr($row_sta["data_status"],11,5);
									$id_status = 'id_status="'.$row_sta["id_status"].'"';
									if($row_sta["id_status"]=="2" && substr($row_sta["data_status"],11,5) != "00:00")
									{
										$imagem_clock_exe='<img src="../../../images/cronograma_all/clock_exe.png" title="'.substr($row_sta["data_status"],11,5).'" class="clock_exe" width="15" />';									
									}
									else $imagem_clock_exe="";
								}
								else
								{
									$imagem_clock_exe = "";
									$codcor    = "#FFFFFF";
									$coddes    = "";
									$titleTXT  = "";
									$id_status = "";
								}
								if($data==$date_fim)
								{
									$hora2 = 'hora2="'.$hora_final.'"';
									if($hora_final != "18:00") $imagem_clock = '<img src="../../../images/cronograma_all/clock.png" width="15" class="clock" title="'.$hora_final.'" />';
								}
								$resu_alert = mysql_query("select * from orc_alerta where id_detalhe='".$id_detalhes."' and alert_data='".$data."' and alert_status='1'");
								if(mysql_num_rows($resu_alert) > 0)
								{
									$image_alerta = '<img src="../../../images/cronograma_all/Ok-icon2.png" width="18" id="alertas_view" class="ok_icon"/>';
								}
								$resu_alert = mysql_query("select * from orc_alerta where id_detalhe='".$id_detalhes."' and alert_data='".$data."' and alert_status='0'");
								if(mysql_num_rows($resu_alert) > 0)
								{
									$image_alerta = '<img src="../../../images/cronograma_all/Ok-icon.png" width="18" id="alertas_view" class="ok_icon"/>';
								}
								$corpo_gantt1 .= '<td width="45" height="45" class="td_click" id="'.$data.'" tarefa="'.$id_tarefa.'" detalhe="'.$id_detalhes.'" '.$hora2.'>
									<div class="tope" style="border-style:solid; border-color: '.$color.' transparent transparent; border-width: 43px 43px 0px 0px; position:relative;">
									'.$imagem_clock.$image_alerta.'
									<div title="'.$desc_tarefa.'" class="txt_sigla">'.$sigla_tarefa.'</div>
									</div>
									<div class="boto" style="border-style:solid; border-color: transparent transparent '.$codcor.'; border-width: 0px 0px 43px 43px; position:relative;" title="'.$titleTXT.'" '.$id_status.' >
									'.$imagem_clock_exe.'
									</div>
									</td>';
								if($data==$date_fim) $icount++;
							}	
							else
							{	
							if($diasemana>=1 and $diasemana<=5)  $corpo_gantt1 .= '<td width="45" height="45" class="td_click" id="'.$data.'">&nbsp;</td>';
							else $corpo_gantt1 .= '<td width="45" height="45" class="td_click" id="'.$data.'" style="background:#CCCCCC;">&nbsp;</td>';
							}
						}
						else
						{	
							if($diasemana>=1 and $diasemana<=5)  $corpo_gantt1 .= '<td width="45" height="45" class="td_click" id="'.$data.'">&nbsp;</td>';
							else $corpo_gantt1 .= '<td width="45" height="45" class="td_click" id="'.$data.'" style="background:#CCCCCC;">&nbsp;</td>';
						}
					}
				}
				$mes++;
			}
		}    
		$corpo_gantt1 .= "</tr>";
	}
if(($total_dias1+1)<=2) $total_dias1 = $total_dias1*90;
elseif(($total_dias1+1)<=3) $total_dias1 = $total_dias1*80;
elseif(($total_dias1+1)<=5) $total_dias1 = $total_dias1*70;
elseif(($total_dias1+1)<=11) $total_dias1 = $total_dias1*60;
elseif(($total_dias1+1)<=21) $total_dias1 = $total_dias1*50;	
elseif(($total_dias1+1)<=31) $total_dias1 = $total_dias1*45;
elseif(($total_dias1+1)<=60) $total_dias1 = $total_dias1*40;
elseif(($total_dias1+1)<=90) $total_dias1 = $total_dias1*30;
else $total_dias1 = $total_dias1*30;
echo "<div id='divLeft'>
<table id='tabelaLeft' cellpadding='0' cellspacing='0' border='0'>
".$linha_titulo."
".$corpo_gantt."
</table>
</div>
<div id='divRight'>
<table id='tabelaRight' cellpadding='0' cellspacing='0' border='0' width='".$total_dias1."'>
<tr class='0'>".$coluna_mes."</tr>
<tr class='0'>".$coluna_dia_txt."</tr>
<tr class='0'>".$coluna_dia."</tr>
".$corpo_gantt1."
</table>
</div>";
	if($estado_finalizar==0)//cronograma
	{?>
<div id="myMenu"> 
	<div class="closeMenu"><img src="../../../images/cronograma_all/x.png" width="25" height="29" /></div>
	<ul class="contextMenu">        
	</ul>
</div>
<div id="myMenuRight">
	<div class="closeMenu"><img src="../../../images/cronograma_all/x.png" /></div>
    <ul class="listaMenuRight">
        <li class="listaMenu" id="alertas" style="display:none;"><img class="imgQuadro" src="../../../images/cronograma_all/alert-icon.png" width="15" /><b>Alertas</b></li>
        <li class="listaMenu" id="horario"><img class="imgQuadro" src="../../../images/cronograma_all/calendar-icon.png" width="15" /><b>Hor&aacute;rio</b></li>
        <!--<li class="listaMenu" id="responsavel" style="display:none;"><img class="imgQuadro" src="../../../images/cronograma_all/user-icon.png" width="15" /><b>Respons&aacute;vel</b></li>
        <li class="listaMenu" id="outros"><img class="imgQuadro" src="../../../images/cronograma_all/other-icon.png" width="15" /><b>Outros</b></li>-->
    </ul>       
</div>

<?php }
		else//realizado
		{?>
<div id="myMenu"> 
	<div class="closeMenu"><img src="../../../images/cronograma_all/x.png" /></div>
	<ul class="contextMenu">
<?php 	$resu_sta = mysql_query("select * from tb_projeto_status where id <> 7 order by 1;");
		while($row_sta = mysql_fetch_array($resu_sta))
		{?>
        <li class="liTarefa" id="<?php echo $row_sta["id"]?>" color="<?php echo $row_sta["codcor_status"]?>">
        	<div class="quadro" style="background:<?php echo $row_sta["codcor_status"]?>"></div><?php echo $row_sta["desc_status"]?>
        </li>
<?php  	}?>                
    </ul>
</div>		
<?php	}
	}
else echo "<div class='erro_geral'>ERRO NO CRONOGRAMA RESPONSAVEL [GERENTE | COORDENADOR] <br>Projeto ou Item n&atilde;o encontrados<br/>Entrar em contato [Eduardo Zambrano][eduardoz@clamom.com.br][TI]</div>";
?>
<div id="myObs">
	<div class="closeMenu"><img src="../../../images/cronograma_all/x.png" /></div>
    <ul class="corpomyObs">
    	<li class="listaObs" id="_alertas_view">
        	<img class="imgQuadro" src="../../../images/cronograma_all/alert-icon.png" width="15" /><div class="titulo1">Alertas</div>
            <div class="msg_titulo"></div>
            <div class="msg_erro"></div>
            <table class="tabelaDados2">
            <tr class="trDados">
            	<td width="80" align="center">DATA</td>
                <td width="50" align="center">HORA</td>
                <td width="190" align="center">PARA</td>
                <td width="190" align="center">COM COPIA</td>
                <td width="160" align="center">OBSERVA&Ccedil;&Atilde;O</td>
                <td align="center">CRIADO POR</td>
            </tr>
            </table>
        </li>
    	<li class="listaObs" id="_alertas">
    		<img class="imgQuadro" src="../../../images/cronograma_all/alert-icon.png" width="15" /><div class="titulo1">Alertas</div>
            <div class="msg_titulo"></div>
            <div class="msg_erro"></div>
            <ul>
            	<li style="width:150px;">
                	DATA:<br/>
                    <select name="dataAlerta" id="dataAlerta">
                    </select>
                    <!--<input type="text" name="dataAlerta" id="dataAlerta" size="12" />--><br/>
                    HORAS&nbsp;&nbsp;&nbsp;: MINUTOS<br/>
                    <select name="hora_alerta" id="hora_alerta">
                        <option value="--">--</option>
                        <option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                	</select> : 
                    <select name="minuto_alerta" id="minuto_alerta">
                    	<option value="--">--</option>
                        <option value="00">00</option>
                        <option value="05">05</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="30">30</option>
                        <option value="35">35</option>
                        <option value="40">40</option>
                        <option value="45">45</option>
                        <option value="50">50</option>
                        <option value="55">55</option>
                	</select>
                    <!--<input type="text" name="horaAlerta" id="horaAlerta" size="10" />--><br/>
                    <br/>
                    <div class="divBotao">
                    <input type="button" value="Salvar Alerta" id="salvar_alerta" class='botao' />
                    <input type="button" value="Salvar Alerta" id="editar_alerta" class='botao' />
                    </div>
                </li>
                <li style="width:330px;">
                	PARA:<br/>
                    <select name="para_ale" id="para_ale">
                    	<option value="0">SELECIONE</option>
<?php $listaUsuario = mysql_query("SELECT login,upper(name) as nome,email FROM sec_users where active='Y' and login<>'admin' and login<>'gilberto' and login<>'leonardo' and login<>'gilberto' and login<>'eduardoz' and login<>'marcio' and login<>'[usr_login]' and login<>'sistema' and login<>'teste' and login<>'automatico' order by name;");
		while($itemUsuario = mysql_fetch_array($listaUsuario)){?>                    
                    	<li class="itemUsuario">
                        <option value="<?php echo trim($itemUsuario["login"])?>"><?php echo substr($itemUsuario["nome"],0)?></option>
<?php }?>                        
                    </select><br/>
                	COM COPIA:<br/>
                	<ul id="listaUsuario">
<?php $listaUsuario = mysql_query("SELECT login,upper(name) as nome,email FROM sec_users where active='Y' and login<>'admin' and login<>'gilberto' and login<>'leonardo' and login<>'gilberto' and login<>'eduardoz' and login<>'marcio' and login<>'[usr_login]' and login<>'sistema' and login<>'teste' and login<>'automatico' order by name;");
		while($itemUsuario = mysql_fetch_array($listaUsuario)){?>                    
                    	<li class="itemUsuario">
                        <input style="float:left;" type="checkbox" name="loginUsuario[]" class="loginUsuario" value="<?php echo trim($itemUsuario["login"])?>" />
						<div style="float:left; margin-top:3px;"><?php echo substr($itemUsuario["nome"],0)?></div>
                        </li>
<?php					}?>                        
                    </ul>
                </li>
                <li>OBSERVA&Ccedil;&Atilde;O:<br/>
                	<textarea name="obsAlerta" id="obsAlerta"></textarea>
                </li>
            </ul>
            <table class="tabelaDados">
            <tr class="trDados">
            	<td width="75" align="center">DATA</td>
                <td width="45" align="center">HORA</td>
                <td width="190" align="center">PARA</td>
                <td width="190" align="center">COM COPIA</td>
                <td width="160" align="center">OBSERVA&Ccedil;&Atilde;O</td>
                <td align="center">CRIADO POR</td>
                <td width="60" align="center">OP&Ccedil;&Otilde;ES</td>
            </tr>
            </table>
    	</li>
    	<li class="listaObs" id="_horario">
    		<img class="imgQuadro" src="../../../images/cronograma_all/calendar-icon.png" width="15" /><div id="titulo"></div>
    		<div class="msg_erro"></div>
			<ul id="_finalDIV">
    			<li></li>
        		<li style="clear:both; margin-left:10px;">
               	<!-- <input type="text" id="hora_fim" name="hora_fim" size="10" />-->
               	HORAS : MINUTOS<br/>
               	<select name="hora_horario" id="hora_horario">
                        <option value="--">--</option>
                        <option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                	</select> : 
                    <select name="minuto_horario" id="minuto_horario">
                    	<option value="--">--</option>
                        <option value="00">00</option>
                        <option value="05">05</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="30">30</option>
                        <option value="35">35</option>
                        <option value="40">40</option>
                        <option value="45">45</option>
                        <option value="50">50</option>
                        <option value="55">55</option>
                	</select>
                </li>
    		</ul>
    		<div class="bottom"><input type="button" value="Salvar" class='save_horario' /></div>
    	</li>
        <li class="listaObs" id="_responsavel">
    		<img class="imgQuadro" src="../../../images/cronograma_all/user-icon.png" width="15" /><div class="titulo1">Designar Respons&aacute;vel</div>
            <ul>
            	<li>RESPONS&Aacute;VEL:<br/>
                <select name="valor_resposavel" id="valor_resposavel">
				</select>
                </li>
                <li>
                <input type="button" value="Salvar" id="salvar_responsavel" class='botao' style="margin-top:17px; margin-left:5px;" />
                </li>
            </ul>
        </li>
    </ul>
</div>
<!-- RESPONSAVEL DAS FASES, ETAPAS E ITENS -->
<div id="myResponsa">
	<div class="closeMenu"><img src="../../../images/cronograma_all/x.png" /></div>
    <img class="imgQuadro" src="../../../images/cronograma_all/user-icon.png" width="15" /><div class="titulo1">Designar Coordenador | Executor</div>
    <ul>
    	<li><input type="radio" name="tipor" class="tipor" value="Res" checked="checked" /><b>COORDENADOR</b></li>
        <li><input type="radio" name="tipor" class="tipor" value="Exe" /><b>EXECUTOR</b></li>
    </ul>
    <ul><li>&nbsp;</li></ul>
    <ul>
        <li><div id="Res">COORDENADOR</div> <div id="Exe" style="display:none;">EXECUTOR:</div>
        <select name="lista_resposavel" id="lista_resposavel">
        </select>
        </li>
        <li>
        <input type="button" value="Salvar" id="save_responsavel" class='botao' style="margin-top:17px; margin-left:5px;" />
        </li>
    </ul>
    <input type="hidden" name="id_subitem2" id="id_subitem2" value="0" />
    <input type="hidden" name="task2" id="task2" value="0" />
</div>
</body>
</html>