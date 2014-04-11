<?php
require("cn.php");
if($_REQUEST["op"]=="left")
{
	/*//PEGAR O  PROXIMO SUBITEM
	$resu_sub = mysql_query("select max(num_etapa) as ultimo from cro_projeto_fase_e_etapa where id_projeto = ".$_REQUEST["id_projeto"]);
	$row_sub = mysql_fetch_array($resu_sub);
	$num_sub_item = str_pad($row_sub["ultimo"]+1, 3, "0", STR_PAD_LEFT);*/
	//PEGAR O  PROXIMO FASE ETAPA
	$resu_sub = mysql_query("select max(num_etapa) as ultimo from cro_projeto_fase_e_etapa where id_projeto=".$_REQUEST["id_projeto"]." and fase_etapa='".$_REQUEST["estado"]."' ");
	$row_sub = mysql_fetch_array($resu_sub);
	$num_sub_item = str_pad($row_sub["ultimo"]+1, 2, "0", STR_PAD_LEFT);
	if($_REQUEST["id_pai"] > 0)
	{
		//PEGAR RESPONSAVEL DA FASE, ETAPA
		$resu_res = mysql_query("select * from cro_projeto_fase_e_etapa where id = '".$_REQUEST["id_pai"]."' ");
		$row_res = mysql_fetch_array($resu_res);
		$responsa = $row_res["responsavel"];
	}
	else
	{
		//PEGAR RESPONSAVEL DO PROJETO
		$resu_res = mysql_query("select * from tb_projeto where id = '".$_REQUEST["id_projeto"]."' ");
		$row_res = mysql_fetch_array($resu_res);
		$responsa = $row_res["proj_responsavel"];
	}
	if($_REQUEST["estado"] == 0)
	{
		$descricao = "FASE ".$num_sub_item;
	}
	else
	{
		$descricao = "";
	}
	//SALVAR NOVO SUBITEM
	$sql="INSERT INTO cro_projeto_fase_e_etapa(id_pai,id_projeto,num_etapa,desc_etapa,data_inicio,num_dias,data_final,login,data_cadastro,nctr,fase_etapa,responsavel)  
	values('".$_REQUEST["id_pai"]."','".$_REQUEST["id_projeto"]."','".$num_sub_item."','".$descricao."',now(),'1',now(),'".$_REQUEST["usuario"]."',now(),'".$_REQUEST[	"num_ctr"]."','".$_REQUEST["estado"]."','".$responsa."')";
	mysql_query($sql);
	$id = mysql_insert_id();
	
	$resu_r2     = mysql_query("select *,upper(name) nome from sec_users where login = '".$responsa."' ");
	$row_r2      = mysql_fetch_array($resu_r2);	
	$responsavel = $row_r2["nome"];
	
	$td='<td width="20"><input type="checkbox" name="codigo" class="codigo" value="'.$id.'" /></td>
	<td style="border-style:solid; font-size:11px; font-weight:bold;" height="45">
	<input type="text" name="'.$id.'nome" class="text_nome" id="'.$id.'nome" value="'.$descricao.'" size="40" />
	<div class="nome_label" style="display:none">'.$descricao.'</div>
	<input type="button" name="save" class="save" value="Salvar" />
	<input type="button" name="edit" class="edit" value="Editar" style="display:none" />
	<div class="itemResponsa" login="'.$responsa.'" >[R. '.$responsavel.']</div>
	<input class="set_responsa" type="button" value="R" name="set_responsa" style="display:none">
	</td>';
	echo $td;
}
elseif($_REQUEST["op"]=="right")
{
//calcular os dias
	if($_REQUEST["id_pai"]=='')
	{
		$check_sql = "SELECT DAY(proj_data_inicial) dia1,MONTH(proj_data_inicial) mes1,YEAR(proj_data_inicial) ano1,DAY(proj_data_final) dia2,
		MONTH(proj_data_final) mes2, YEAR(proj_data_final) ano2,proj_data_inicial as data_inicio,proj_data_final  as data_final
		FROM tb_projeto WHERE id='".$_REQUEST["id_projeto"]."'";
	}
	else
	{
		/*$check_sql = "SELECT DAY(proj_data_inicial) dia1,MONTH(proj_data_inicial) mes1,YEAR(proj_data_inicial) ano1,DAY(proj_data_final) dia2,
		MONTH(proj_data_final) mes2, YEAR(proj_data_final) ano2,proj_data_inicial as data_inicio,proj_data_final  as data_final
		FROM tb_projeto WHERE id='".$_REQUEST["id_projeto"]."'";*/
		$check_sql = "SELECT DAY(data_inicio) dia1,MONTH(data_inicio) mes1,YEAR(data_inicio) ano1,DAY(data_final) dia2,MONTH(data_final) mes2, 
		YEAR(data_final) ano2,data_inicio,data_final 
		FROM cro_projeto_fase_e_etapa WHERE id ='".$_REQUEST["id_pai"]."'";
	}
	$resu_my = mysql_query($check_sql);
	$teste = '';
	$row_my = mysql_fetch_array($resu_my);
	$dia1 = $row_my["dia1"];
	$mes  = $row_my["mes1"];
	$ano  = $row_my["ano1"];
	$dia2 = $row_my["dia2"];
	//$mes2 = $row_my["mes1"];
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
		$total_dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
		$icount=0;	
		for ($i=1; $i<=$total_dias; $i++) 
		{
			$data = $ano."-".str_pad($mes,2,"0",STR_PAD_LEFT)."-".str_pad($i,2,"0",STR_PAD_LEFT);
			$ano3 =  substr($data, 0, 4);
			$mes3 =  substr($data, 5, -3);
			$dia  =  substr($data, 8, 9);
			$teste .= $row_my["data_inicio"]. ' - '. $row_my["data_final"].' - '.$data."<br>";
			if($data >= $row_my["data_inicio"] and $data <= $row_my["data_final"])
			{
				$diasemana = date("w", mktime(0,0,0,$mes3,$dia,$ano3));					
				if($diasemana>=1 and $diasemana<=5)  $td .= '<td class="td_click" id="'.$data.'">&nbsp;</td>';
				else $td .= '<td width="45" height="45" class="td_click" id="'.$data.'" style="background:#CCCCCC;">&nbsp;</td>';	
			}
		}
		$mes++;
	}
	echo $td;
}
if($_REQUEST["op"]=="FaseEtapa")
{
	if($_REQUEST["id_pai"]>0)
	{
		echo $valor = 1;//SÓ ETAPAS
	}
	else
	{
		$resu = mysql_query("SELECT * FROM cro_projeto_fase_e_etapa where id_projeto='".$_REQUEST["id_projeto"]."' and  id_pai='' ORDER BY num_etapa");
		if(mysql_num_rows($resu)>0)
		{
			$row = mysql_fetch_array($resu);
			echo $row["fase_etapa"];//FASES OU ETAPAS
		}
		else echo $valor = 2;
	}
}