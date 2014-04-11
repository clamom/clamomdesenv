<!--
 * @CRONOGRAMA GERAL [FASE | ETAPA | ITEM]
 * @autor  : Eduardo Zambrano <eduardoz@clamom.com.br>
 * @versão : 1.0
 * @data   : 26/03/2014
 * Copyright 2014 http://www.clamom.com.br
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
<script type="text/javascript" src="../../../js/cronograma_geral/grafico.js"></script><?php /* todas as acoes do cronograma */?>
<link rel="stylesheet" type="text/css" href="../../../css/alert/alertify.core.css"/><?php /*personalizar alert jquery*/?>
<link rel="stylesheet" type="text/css" href="../../../css/alert/alertify.default.css"/><?php /*personalizar alert jquery*/?>
<link rel="stylesheet" type="text/css" href="../../../css/autocomplete/chosen.css"/>
<link rel="stylesheet" type="text/css" href="../../../css/cronograma_geral/grafico.css"/>
<title>Cronograma Geral</title>
</head>

<body>
<?php
require("ajax/cn.php");/* arquivo conexão  */
if($_REQUEST["projeto"] > 0)
{
	$id_ctr = $_REQUEST["projeto"];
	if($_REQUEST['id_fase'] == 0)//fase / etapa
	{
		$id_fase = '';
		$tipo = 1; 
	}
else //etapa
{
	$id_fase = $_REQUEST['id_fase'];
	$tipo = 2; 	
}
if($tipo==1)//fase / etapa
{
	$resu1 = mysql_query("SELECT *,concat(proj_ctr,' | ',proj_desc_ctr) titulo,proj_ctr,id,proj_desc_ctr desc_etapa,proj_finalizar_macro status,proj_responsavel responsa  FROM tb_projeto where id = ".$id_ctr);		
	$resu = mysql_query("SELECT * FROM cro_projeto_fase_e_etapa where id_projeto='".$id_ctr."' and  id_pai='' ORDER BY num_etapa");
	if(mysql_num_rows($resu)>0)
	{
		$row = mysql_fetch_array($resu);
		if($row["fase_etapa"]==0) $FaseEtapa="(Fases)";
		elseif($row["fase_etapa"]==1) $FaseEtapa="(Etapas)";
	}
	else $FaseEtapa = "";
}
else //etapa
{		
	$resu1 = mysql_query("SELECT *,concat(p.proj_ctr,' | ',p.proj_desc_ctr,'<br/>',c.num_etapa,' - ', c.desc_etapa) titulo,p.proj_ctr,p.id, c.num_etapa, c.desc_etapa as desc_etapa, p.proj_finalizar_macro status, c.id id_etapa, c.id_pai, c.responsavel responsa FROM tb_projeto p INNER JOIN cro_projeto_fase_e_etapa c ON p.id = id_projeto WHERE p.id = ".$id_ctr." AND c.id = ".$id_fase."");
	$resu_1 = mysql_query("select * from cro_projeto_fase_e_etapa where id=".$id_fase);
	$row_1  = mysql_fetch_array($resu_1);
	if($row_1["fase_etapa"]==0) $FaseEtapa="(Etapas)";
	elseif($row_1["fase_etapa"]==1)
	{
		$FaseEtapa="(Itens)";
		$tipo = 3; //itens
	}
}
if(mysql_num_rows($resu1)>0)     
{
	$row1    = mysql_fetch_array($resu1);
    $titulo  = $row1["titulo"];
    //$id_item = $ctr =  $id_ctr;
	$num_ctr = $row1["proj_ctr"];
	if($tipo == 1)//fase / etapa
	{
		$titulo_link = $row1["desc_etapa"];
		$status		 = $row1["status"];
	}
	else//etapa
	{
		$codigo_pai = $row1["id_pai"];
		$id_etapa   = $row1["id_etapa"];
		$status     = $row1["status"];
	}
	//pegar usuario do responsa[vel]
	$resu_r = mysql_query("select * from sec_users where login = '".$row1["responsa"]."' ");
	$row_r = mysql_fetch_array($resu_r);
	$resu_sigla = mysql_query("select * from tb_projeto_tarefas where usu_responsavel='".$row1["responsa"]."' ");
	$row_sigla  = mysql_fetch_array($resu_sigla);
	$valor_sigla = return_projeto($row_sigla["sigla_tarefa"]);
	$responsa   = "[".$valor_sigla."][R. ".$row_r["name"]."]";
}
else $titulo = "ERRO NO CRONOGRAMA FASE | ETAPA | ITEM <br/>Entrar em contato [Eduardo Zambrano][eduardoz@clamom.com.br][TI]";

if($id_fase>0)
{	//dados ETAPA, FASE
	$resu1 = mysql_query("SELECT *,upper(desc_etapa) desc_etapa FROM cro_projeto_fase_e_etapa where id='".$id_fase."'");
	$row1 = mysql_fetch_array($resu1);
	//dados responsavel
	$resuR = mysql_query("select *,upper(name) nome from sec_users where login='".$row1["responsavel"]."' ");
	$rowR = mysql_fetch_array($resuR);
	//dados sigla => responsavel
	$resu_sigla1 = mysql_query("select * from tb_projeto_tarefas where usu_responsavel='".$row1["responsavel"]."' ");
	$row_sigla1  = mysql_fetch_array($resu_sigla1);
	$responsame  = "[".return_projeto($row_sigla1["sigla_tarefa"])."][R. ".$rowR["nome"]."]";
	//criar navegação [você está em:]
	$src = "index.php?projeto=".$id_ctr."&id_fase=".$row1["id_pai"]."&usuario=".$_REQUEST["usuario"];
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
		$array_link = lista_link($row1["id_pai"],$array_link,$id_ctr,$_REQUEST["usuario"]);
	}
	$m=0;
	if(count($array_link)>0)//while na navegação
	{
		foreach(array_reverse($array_link) as $vetor=>$v)
		{
			if($m==0) $navegacao .= "<div class='link'><a href='".$v["src"]."'>".$v["nome"]."<br/>".$v["responsavel"]."</a></div>";
			else      $navegacao .= "<img src='../../../images/cronograma_all/arrow.png' width='20' class='imgArrow' /><div class='link'><a href='".$v["src"]."'>".$v["nome"]."<br/>".$v["responsavel"]."</a></div>";
			$m++;
		}
	}
}
else 
{
	$navegacao = "<b>".$titulo."</b>";
}
//$link_menu = '<a href="http://192.168.0.190:8080/scriptcase7/app/ERP_CLAMOM/cro_lista_projetos/cro_lista_projetos.php">Todos as OBRAS</a><br>';
if($status==0)
{	//QUERY VALIDAR ITEM
	if($id_fase>0)
	{
		$resu = mysql_query("select * from cro_projeto_fase_e_etapa where id=".$id_fase);
		$row = mysql_fetch_array($resu);
	}
	//ITEM SÓ PODE SALVAR
	if(!$row_1["fase_etapa"]=="1")
	{ /*VARIAVEIS GLOBAIS*/?>
    <input type="button" id="dele" value="Apagar" class="botao" />
	<input type="button" id="add" value="Adicionar" class="botao" />
    <input type="button" id="save_completo" value="Salvar Grafico" class="botao" />
<?php }else{?> 
	<input type="button" id="save_item" value="Salvar Itens" class="botao" />
<?php }?>    
	<input type="button" id="finalizar" value="Finalizar" class="botaoFim" style="background:#FF0000; font-weight:bold; border:#FFA3A4 solid 1px;" />
<?php
}
else
{?>
	<input type="button" id="revisao" value="Revis&atilde;o" class="botaoFim disabled" disabled="disabled" />
<?php		    
}?>
	<input type="hidden" id="id_ctr" name="id_ctr" value="<?php echo $id_ctr?>" />
    <input type="hidden" id="id_item" name="id_item" value="<?php echo $id_ctr?>">
    <input type="hidden" id="num_ctr" name="num_ctr" value="<?php echo $num_ctr?>">
    <input type="hidden" id="id_pai" name="id_pai" value="<?php echo $id_fase?>">
    <input type="hidden" id="usuario" name="usuario" value="<?php echo $_REQUEST["usuario"]?>"><!-- scriptcase [usr_login] -->
    <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo?>" size="10" />
    <input type="hidden" id="data1" name="data1"  value="0" size="10" />
    <input type="hidden" id="fila1" name="fila1"  value="0" size="10" />
    <input type="hidden" id="filaUnica" name="filaUnica"  value="0" size="10" />
    <input type="hidden" id="data2" name="data2"  value="0" size="10" />
    <input type="hidden" id="fila2" name="fila2"  value="0" size="10" />
    <input type="hidden" id="id_detalhe" name="id_detalhe" value="0" size="10" />
    <input type="hidden" id="intX" name="intX" value="0" size="10" />
    <input type="hidden" id="intY" name="intY" value="0" size="10" />
    <input type="hidden" id="aux" name="aux" value="0" size="10" />
    <br />
    <input type="hidden" id="name" size="40" />
    <div class="navegacao"><pre><div class="aqui">Voc&eacute; est&aacute; em :&nbsp;</div><div class="esta"><?php echo $navegacao?></div></pre></div>
<?php
if($tipo == 1)//fase / etapa
{
	$resu2 = mysql_query("SELECT DAY(proj_data_inicial) dia1,MONTH(proj_data_inicial) mes1,YEAR(proj_data_inicial) ano1,DAY(proj_data_final) dia2,MONTH(proj_data_final) mes2, YEAR(proj_data_final) ano2,proj_data_inicial data_inicio,proj_data_final data_final FROM tb_projeto WHERE id ='".$id_ctr."'");
}
else//etapa
{
	$resu2 = mysql_query("SELECT DAY(data_inicio) dia1,MONTH(data_inicio) mes1,YEAR(data_inicio) ano1,DAY(data_final) dia2,MONTH(data_final) mes2,YEAR(data_final) ano2,data_inicio,data_final FROM cro_projeto_fase_e_etapa WHERE id ='".$id_fase."'");
}
if (mysql_num_rows($resu2)>0)
{
	$row2 = mysql_fetch_array($resu2);
	$dia1         = $row2["dia1"];
	$mes          = $row2["mes1"];
	$mes1         = $row2["mes1"];
	$ano          = $row2["ano1"];
	$ano1         = $row2["ano1"];
	$dia2         = $row2["dia2"];
	$mes2         = $row2["mes2"];
	$ano2         = $row2["ano2"];
	$data_inicial = $row2["data_inicio"];
	$data_final   = $row2["data_final"];
}
else $titulo = "ERRO NO CRONOGRAMA FASE | ETAPA | ITEM <br/>Entrar em contato [Eduardo Zambrano][eduardoz@clamom.com.br][TI]";
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
	/* titulo do cronograma DESCRIÇÃO */
	$linha_titulo = "
	<tr class='0'>
	<td width='20' style='background: url(../../../images/cronograma_all/fundotr.jpg) repeat !important;'><input type='checkbox' name='AllCodigo' id='AllCodigo' onclick='$.MarcarDesmarcar();'/></td>
	<td width='510' height='70' style='color:#006633; font-weight:bold; font-size:12px; text-transform:uppercase !important; background: url(../../../images/cronograma_all/fundotr.jpg) repeat !important;'>
	<div id='tituloPrincipal'>".$titulo."<br/>".$FaseEtapa."</div>
	<div id='topResponsa'>".$responsa."&nbsp;</div>
	</td></tr>";
	for($k=$mes; $k<=$meses; $k++) //while($mes <= $meses) //
	{
		if($mes > 12)
		{
			$mes = 1;//$meses = $meses -12;
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
		$total_dias1 = ceil(CalculaDias(dataform($data_inicial),dataform($data_final)));
		$total_dias2=0;//$total_dias1+1;
        if($ano1.$mes1==$ano2.$mes2) $total_dias2   = cal_numero($dia1,$dia2);
		elseif($ano1.$mes1==$ano.$mes) $total_dias2 = cal_days_in_month(CAL_GREGORIAN, $mes, $ano) - ($dia1-1);//primeiro mes
        elseif($ano2.$mes2==$ano.$mes) $total_dias2 = $dia2;//ultimo mes
        else $total_dias2 = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);//todos os meses restantes
        $tamanho_cell = 0;
		$tamanho_cell = ($total_dias2*35);
		/* titulo do cronograma MES/AMO */
		$coluna_mes .= "<td height='28' colspan=".($total_dias2)." width='".$tamanho_cell."px'  align='center' style='font-size:11px; font-weight:bold; background:url(../../../images/cronograma_all/fundotr.jpg) repeat !important; color:#006633;'>".$mes_ext."/".substr($ano,2,2)."</td>";
        //calcular dias da semana
        $total_dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
		$tamanho_tabela = $tamanho_tabela + $tamanho_cell;
		for ($i=1; $i<=$total_dias; $i++) 
		{
			$data = $ano."-".str_pad($mes,2,"0",STR_PAD_LEFT)."-".str_pad($i,2,"0",STR_PAD_LEFT);
			$ano  = substr($data, 0, 4);
			$mes  = substr($data, 5, -3);
			$dia  = substr($data, 8, 9);
			$data_valor = $ano."-".$mes."-".$dia;
			if($data_valor>=$data_inicial and $data_valor<=$data_final)
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
				$data_1 = $ano.'-'.$mes.'-'.str_pad($i,2,"0",STR_PAD_LEFT);
			/* titulo do cronograma DIA DO MES */
				$coluna_dia .= "<td width='30' height='20' align='center' style='border-style:solid; background:".$background."; color:".$fonte.";' id='".$data_1."'>".str_pad($i,2,"0",STR_PAD_LEFT)."</td>";
			}
				$tot_col = $tot_col +1;	
		}
		for ($i=1; $i<=$total_dias; $i++) 
		{
			$data=$ano."-".str_pad($mes,2,"0",STR_PAD_LEFT)."-".str_pad($i,2,"0",STR_PAD_LEFT);
			$ano =  substr($data, 0, 4);
			$mes =  substr($data, 5, -3);
			$dia =  substr($data, 8, 9);
			$data_valor = $ano."-".$mes."-".$dia;
			if($data_valor>=$data_inicial and $data_valor<=$data_final)
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
			/* titulo do cronograma DIA DA SEMANA */
				$coluna_dia_txt .= "<td width='30' height='20' align='center' style='border-style:solid; background:".$background."; color:".$fonte.";'>&nbsp;".$diasemana."&nbsp;</td>";
			}
		}
		$mes++;
	}
	$tot_col = $tot_col+1;
	$cont=0;
	if($tipo == 1)//FASE OU ETAPA
	{
		$resu3 = mysql_query("SELECT * FROM cro_projeto_fase_e_etapa where id_projeto='".$id_ctr."' and  id_pai='' ORDER BY num_etapa");	
	}
	elseif($tipo == 2)//ETAPA
	{
		$resu3 = mysql_query("SELECT * FROM cro_projeto_fase_e_etapa where id_projeto='".$id_ctr."' and id_pai=".$id_fase." ORDER BY num_etapa");
	}
	elseif($tipo == 3)//ITEM
	{
		if($status==0)//REVISÃO
		{
		$resu3 = mysql_query("SELECT *,concat(num_item,' - ',desc_item) desc_etapa FROM tb_projeto_itens where projeto_id='".$id_ctr."' and num_item<>'000' order by num_item");
		}
		else//FINALIZAR CRONOGRAMA MACRO
		{
		$resu3 = mysql_query("select *,ei.id_etapa_item id,concat(t.num_item,' - ',t.desc_item) desc_etapa, t.responsavel responsavel from cro_projeto_etapa_item ei inner join tb_projeto_itens t on ei.id_item=t.id  where ei.id_etapa='".$id_fase."' and t.projeto_id='".$id_ctr."' and t.num_item<>'000' order by t.num_item");
		}
	}
	while($row8 = mysql_fetch_array($resu3))
	{	
		//pegar usuario da FASE, ETAPA
		$resu_r2     = mysql_query("select *,upper(name) nome from sec_users where login = '".$row8["responsavel"]."' ");
		$row_r2      = mysql_fetch_array($resu_r2);	
		$responsavel = $row_r2["nome"];
		$task        = $row8["id_tarefa"];
		//pegar sigla da tarefa
		$resu_gru    = mysql_query("select * from sec_users_groups where login='".$row8["responsavel"]."' ");
		$row_gru     = mysql_fetch_array($resu_gru);
		$idcodigo    = return_mar($row_gru["group_id"]);
		$resu_task   = mysql_query("select * from tb_projeto_tarefas where id='".$idcodigo."' ");
		$row_task    = mysql_fetch_array($resu_task);
		//$valor_sigla1 = return_projeto($row_task["sigla_tarefa"]);
		$valor_sigla1 = ($row_task["sigla_tarefa"]);
		$task_task    = "[".$valor_sigla1."]";
		$viewItem     = "true";
		//select ITEM existe na ETAPA
		if($tipo == 3)
		{	
			if($status==0)
			{
				//select tabela intermedia etapa item
				$checked = '';
				$resuEI = mysql_query("select * from cro_projeto_etapa_item where id_etapa='".$id_fase."' and id_item='".$row8["id"]."' ");
				if(mysql_num_rows($resuEI) > 0) 
				{
					$checked = "checked='checked'";
					$rowEI = mysql_fetch_array($resuEI);
				}
				else
				{
					$resuND = mysql_query("select * from cro_projeto_etapa_item where id_item='".$row8["id"]."'");
					if(mysql_num_rows($resuND) > 0)
					{
						$viewItem = "false";
					}				
				}
			}
		}
		if($viewItem == "true") /**/
		{
			$cont++;
			if(($cont%2) == 0) $adclass="class='yellow'";
			else $adclass="";
			if($row8["id_tarefa"]=="") $TarefaId = 0;
			else $TarefaId = $row8["id_tarefa"];
			$fonteItem = "#333333";//sem desmembramento
			if($tipo == 1)
			{	//FASE
				$resuFonte = mysql_query("select * from cro_projeto_fase_e_etapa where id_pai='".$row8["id"]."'");
				if(mysql_num_rows($resuFonte)>0) 
				{
					$fonteItem = "#FF0000";//com desmembramento
				}
				else//ETAPA
				{
					$resuFonte = mysql_query("select * from cro_projeto_etapa_item where id_etapa='".$row8["id"]."'");
					if(mysql_num_rows($resuFonte)>0) $fonteItem = "#FF0000";
				}
			}
			elseif($tipo == 2)//ETAPA
			{
				$resuFonte = mysql_query("select * from cro_projeto_etapa_item where id_etapa='".$row8["id"]."'");
				if(mysql_num_rows($resuFonte)>0) $fonteItem = "#FF0000";
			}
			elseif($tipo == 3)//ITEM
			{
				$resuFonte = mysql_query("select * from tb_projeto_sub_itens where id_itens='".$row8["id_item"]."' and id_ctr='".$id_ctr."' and id_tarefa is not null");
				if(mysql_num_rows($resuFonte)>0) $fonteItem = "#FF0000";
			}
			$corpo_gantt .= "<tr id='left_".$cont."' ".$adclass.">
			<td width='20'>
			<input type='checkbox' name='codigo' class='codigo' value='".$row8["id"]."' ".$checked." />
			<input type='hidden' name='id_item' class='id_item' value='".$row8["id_item"]."'>
			</td>
			<td style='font-size:10px; font-weight:bold; text-transform:uppercase !important;' height='45'>
				<input type='text' name='".$row8["id"]."nome' class='text_nome' id='".$row8["id"]."nome' value='".$row8["desc_etapa"]."' size='40' style='display:none;'/>
				<div class='nome_label' id_tarefa='".$TarefaId."' style='color:".$fonteItem."' >".$row8["desc_etapa"]."</div>";
			if($tipo != 3)//FASE E ETAPA
			{	
				$corpo_gantt .= "<input type='button' name='save' class='save' value='Salvar' style='display:none' />
				<input type='button' name='edit' class='edit' value='Editar' />
				<div class='itemResponsa'><div class='siglaSetor'>".$task_task."</div><div class='divResponsavel'>[R. ".$responsavel."]</div></div>
				<input type='button' class='set_responsa' name='set_responsa' value='R' />";
				//<div class='itemResponsa' login='".$row8["responsavel"]."' >[R. ".$responsavel."]</div>
			}
			elseif($tipo == 3)//ITEM
			{
				if($status == 1)
				{
					$corpo_gantt .= "<div class='itemResponsa'><div class='siglaSetor'>".$task_task."</div><div class='divResponsavel'>[R. ".$responsavel."]</div></div>
					<input type='button' class='set_responsa' name='set_responsa' value='R' style='margin-left:20px' />";
				}
			}
			$corpo_gantt .= "</td></tr>";
			$corpo_gantt1 .= "<tr id='right_".$cont."' ".$adclass.">";
			$count_vetor = 0;
			//DETALHE TAREFA
			unset($vetor);
			if($tipo == 1)//FASE OU ETAPA
			{
				$resu_tarefa = mysql_query("select *,d.id id_detalhes from cro_projeto_fase_e_etapa_tarefa d inner join tb_projeto_tarefas t on d.id_tarefa=t.id where d.id_fase_etapa='".$row8["id"]."' order by d.data_inicio");
			}
			elseif($tipo == 2)//ETAPA
			{
				$resu_tarefa = mysql_query("select *,d.id id_detalhes from cro_projeto_fase_e_etapa_tarefa d inner join tb_projeto_tarefas t on d.id_tarefa=t.id where d.id_fase_etapa='".$row8["id"]."' order by d.data_inicio");
				
			}
			elseif($tipo == 3)//ITEM
			{
				$resu_tarefa = mysql_query("select *,d.id_item_tarefa id_detalhes,d.situacao situacaot from cro_projeto_item_tarefa d inner join tb_projeto_tarefas t on d.id_tarefa=t.id where d.id_etapa_item = '".$row8["id"]."' order by d.data_inicio");	
			}
			while($row_tarefa = mysql_fetch_array($resu_tarefa))
			{
				$vetor[$count_vetor][1] = $row_tarefa["data_inicio"];  //DATA INICIO
				$vetor[$count_vetor][2] = $row_tarefa["data_final"];   //DATA FINAL
				$vetor[$count_vetor][3] = $row_tarefa["cor_tarefa"];   //COR TAREFA
				$vetor[$count_vetor][4] = $row_tarefa["id_tarefa"];    //ID TAREFA
				$vetor[$count_vetor][5] = $row_tarefa["sigla_tarefa"]; //SIGLA TAREFA
				$vetor[$count_vetor][6] = $row_tarefa["id_detalhes"];  //ID DETALHE TAREFA
				$vetor[$count_vetor][7] = $row_tarefa["hora_final"];   //HORA FINAL
				$vetor[$count_vetor][8] = $row_tarefa["desc_tarefa"];  //DESCRIÇÃO DA TERAFA
				$vetor[$count_vetor][9] = $row_tarefa["situacaot"];    //TIPO DE SITUAÇÃO
				$count_vetor++;
			}
			$mes   = $mes1;
			$ano   = $ano1;
			$mes2  = $mes2;
			$ano2  = $ano2;
			$meses = $mes2;
			if($ano < $ano2)
			{
				$meses = $mes2 + 12;
			} 
			$icount=0;
			while($mes <= $meses)//while todos os dias do cronograma
			{
				if($mes > 12){
					$mes   = $mes -12;
					$meses = $meses -12;
					$ano   = $ano2;
				}
				$total_dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
				for ($i = 1; $i <= $total_dias; $i++) 
				{
						$data = $ano."-".str_pad($mes,2,"0",STR_PAD_LEFT)."-".str_pad($i,2,"0",STR_PAD_LEFT);
						$ano3 =  substr($data, 0, 4);
						$mes3 =  substr($data, 5, -3);
						$dia  =  substr($data, 8, 9);
						$id_data = $ano3."-".$mes3.'-'.$dia; 
					if($id_data >= $data_inicial and $id_data <= $data_final )
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
							$situacao     = $vetor[$icount][9];
							if($data >= $date_ini and $data <= $date_fim) //validar dias dentro do periodo do cronograma /*tarefas*/
							{
								$hora2        = "";
								$imagem_clock = "";
								$image_alerta = "";
								if($data == $date_fim)//data fim colocar horario 
								{
									$hora2 = 'hora2="'.$hora_final.'"';
									if($hora_final != "18:00") $imagem_clock = '<img src="../../../images/cronograma_all/clock.png" width="15" class="clock" title="'.$hora_final.'" />';
								}
								//lista de alertas inativas old
								$resu_alert = mysql_query("select * from orc_alerta where id_detalhe='".$id_detalhes."' and alert_data='".$data."' and alert_status='1'");
								if(mysql_num_rows($resu_alert) > 0)
								{
									$image_alerta = '<img src="../../../images/cronograma_all/Ok-icon2.png" width="18" id="alertas_view" class="ok_icon"/>';
								}
								//lista de alertas ativas old
								$resu_alert = mysql_query("select * from orc_alerta where id_detalhe='".$id_detalhes."' and alert_data='".$data."' and alert_status='0'");
								if(mysql_num_rows($resu_alert) > 0)
								{
									$image_alerta = '<img src="../../../images/cronograma_all/Ok-icon.png" width="18" id="alertas_view" class="ok_icon"/>';
								}
								//dias do cronograma /*tarefas e execução*/
								$corpo_gantt1 .= '<td width="45" height="45" class="td_click" id="'.$data.'" tarefa="'.$id_tarefa.'" detalhe="'.$id_detalhes.'"'.$hora2.' situacao="'.$situacao.'" >
									<div class="tope" style="border-style:solid; border-color: '.$color.' transparent transparent; border-width: 43px 43px 0px 0px; position:relative;">
									'.$imagem_clock.$image_alerta.'
									<div title="'.$desc_tarefa.'" class="txt_sigla">'.$sigla_tarefa.'</div>
									</div>
									<div class="boto" style="border-style:solid; border-color: transparent transparent #FFFFFF; border-width: 0px 0px 43px 43px; position:relative;" title="'.$titleTXT.'" '.$id_status.'></div>
									</td>';
								if($data == $date_fim) $icount++;
							}	
							else//sabado e domingo
							{	
								if($diasemana >= 1 and $diasemana <= 5)  $corpo_gantt1 .= '<td width="45" height="45" class="td_click" id="'.$id_data.'">&nbsp;</td>';
							 	else $corpo_gantt1 .= '<td width="45" height="45" class="td_click" id="'.$id_data.'" style="background:#CCCCCC;">&nbsp;</td>';
							}
						}
						else//sabado e domingo
						{	
							if($diasemana >= 1 and $diasemana <= 5)  $corpo_gantt1 .= '<td width="45" height="45" class="td_click" id="'.$id_data.'">&nbsp;</td>';
							else $corpo_gantt1 .= '<td width="45" height="45" class="td_click" id="'.$id_data.'" style="background:#CCCCCC;">&nbsp;</td>';
						}
					}
				}
				$mes++;//proximo mês
			}
			$corpo_gantt1 .= "</tr>";
		}
	}
	if(($total_dias1+1)<=2) $total_dias1 = $total_dias1*90;      //cronograma só com 02 dias
	elseif(($total_dias1+1)<=3) $total_dias1 = $total_dias1*80;  //cronograma só com 03 dias
	elseif(($total_dias1+1)<=5) $total_dias1 = $total_dias1*70;  //cronograma só com 05 dias
	elseif(($total_dias1+1)<=11) $total_dias1 = $total_dias1*60; //cronograma só com 11 dias
	elseif(($total_dias1+1)<=21) $total_dias1 = $total_dias1*50; //cronograma só com 21 dias	
	elseif(($total_dias1+1)<=31) $total_dias1 = $total_dias1*45; //cronograma só com 31 dias
	elseif(($total_dias1+1)<=60) $total_dias1 = $total_dias1*40; //cronograma só com 60 dias
	elseif(($total_dias1+1)<=90) $total_dias1 = $total_dias1*30; //cronograma só com 90 dias
	else $total_dias1 = $total_dias1*30;                         //cronograma maior a 90 dias
	$pagina .= "
	<div id='divLeft'>
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
echo $pagina;
}
else echo "<div class='erro_geral'>ERRO NO CRONOGRAMA FASE | ETAPA | ITEM <br>Projeto ou Item n&atilde;o encontrados<br/>Entrar em contato [Eduardo Zambrano][eduardoz@clamom.com.br][TI]</div>";
if($status == 0)//cronograma não finalizado
	{?><!-- menu de tarefas -->
<div id="myMenu"> 
	<div class="closeMenu"><img src="../../../images/cronograma_all/x.png" width="25" height="29" /></div>
	<ul class="contextMenu">        
	</ul>
</div>
<div id="AddFaseEtapa"><!-- menu de fase e etapa -->
	<div class="closeMenu"><img src="../../../images/cronograma_all/x.png" width="25" height="29" /></div>
    <div id="corpo_aviso">
    	<div class="txt_aviso">Deseja Adicionar?</div>
        <input type="button" value="Fase" id="btnFase" class="botao1" />
    	<input type="button" value="Etapa" id="btnEtapa" class="botao1" />
    </div>
</div>
<?php }
		else//cronograma finalizado
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
?>
<!-- menu old sem funcionamento -->
<div id="myMenuRight">
	<div class="closeMenu"><img src="../../../images/cronograma_all/x.png" /></div>
    <ul class="listaMenuRight">
        <li class="listaMenu" id="alertas" style="display:none;"><img class="imgQuadro" src="../../../images/cronograma_all/alert-icon.png" width="15" /><b>Alertas</b></li>
        <li class="listaMenu" id="horario"><img class="imgQuadro" src="../../../images/cronograma_all/calendar-icon.png" width="15" /><b>Hor&aacute;rio</b></li>
    </ul>       
</div>
<!-- HORARIO, ALERTAS, RESPONSAVEL -->
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
                <td width="200" align="center">USU&Aacute;RIOS SELECIONADOS</td>
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
            	<li>
                	DATA:<br/>
                    <select name="dataAlerta" id="dataAlerta">
                    </select>
                    <br/>
                    HORAS : MINUTOS<br/>
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
                    <br/>
                    <div class="divBotao">
                    <input type="button" value="Salvar Alerta" id="salvar_alerta" class='botao' />
                    <input type="button" value="Salvar Alerta" id="editar_alerta" class='botao' />
                    </div>
                </li>
                <li>USU&Aacute;RIOS:<br/>
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
                <td width="200" align="center">USU&Aacute;RIOS SELECIONADOS</td>
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
    <img class="imgQuadro" src="../../../images/cronograma_all/user-icon.png" width="15" /><div class="titulo1">Designar Respons&aacute;vel</div>
    <ul>
        <li>RESPONS&Aacute;VEL:<br/>
        <select name="lista_resposavel" id="lista_resposavel">
        </select>
        </li>
        <li>
        <input type="button" value="Salvar" id="save_responsavel" class='botao' style="margin-top:17px; margin-left:5px;" />
        </li>
    </ul>
</div>
<!-- TELA ALERTA DIA ATUAL -->
<div id="alerta_dia">
	<div class="closeMenu"><img src="../../../images/cronograma_all/x.png" /></div>
    <img class="imgQuadro" src="../../../images/cronograma_all/alert-icon.png" width="15" /><div class="titulo1">Alertas</div>
    <div class="msg_titulo"></div>
    <input type="hidden" name="alerta_data" id="alerta_data" value="" />
    <input type="button" name="alerta_add" id="alerta_add" value="Adicionar" class="botao11" />
    <input type="button" name="alerta_complete" id="alerta_complete" value="Salvar Tudo" class="botao11" />
    <div class="conten_tabela">
    <table id="alerta_tabela" cellpadding="1" cellspacing="1">
    <tr class="trTitulo">
    	<td width="20">#</td>
    	<td width="200">OBSERVA&Ccedil;&Atilde;O</td>
        <td width="150">PARA</td>
        <td width="150">COPIA PARA</td>
        <td width="80">DATA</td>
        <td width="100">HORA</td>
        <td width="100">CRIADO POR</td>
        <td width="50">OPÇÕES</td>
    </tr>
    </table>
    </div>
</div>
</body>
</html>