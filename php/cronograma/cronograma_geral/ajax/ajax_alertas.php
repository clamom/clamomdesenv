<?php
/*
 * @CRONOGRAMA GERAL [FASE | ETAPA | ITEM]
 * @autor  : Eduardo Zambrano <eduardoz@clamom.com.br>
 * @file   : alertas
 * @versão : 1.0
 * @data   : 26/03/2014
 * Copyright 2014 http://www.clamom.com.br
 **/
require("cn.php");// arquivo conexão
$cor="#DDDDDD";
if($_REQUEST["op"]=="DadosTitulo")
{	
	if($_REQUEST["tipo"]=="3")//item
	{
		//dados do sub item
		$resu = mysql_query("select *,upper(i.desc_item) descricao_item from cro_projeto_etapa_item e inner join tb_projeto_itens i on e.id_item=i.id where e.id_etapa_item='".$_REQUEST["id_subitem"]."'");
		$row = mysql_fetch_array($resu);
		//dados da tarefa
		$resu1 = mysql_query("select *,upper(t.desc_tarefa) as desc_tarefa from cro_projeto_item_tarefa d inner join tb_projeto_tarefas t on d.id_tarefa=t.id where d.id_item_tarefa='".$_REQUEST["id_detalhe"]."'");
		$row1 = mysql_fetch_array($resu1);
		echo $row["descricao_item"]." | ".$row1["desc_tarefa"];
	}
	else//fase e etapa
	{
		//dados do sub item
		$resu = mysql_query("select upper(desc_etapa) as descricao_subitem from cro_projeto_fase_e_etapa where id='".$_REQUEST["id_subitem"]."'");
		$row = mysql_fetch_array($resu);
		//dados da tarefa
		$resu1 = mysql_query("select *,upper(t.desc_tarefa) as desc_tarefa from cro_projeto_fase_e_etapa_tarefa d inner join tb_projeto_tarefas t on d.id_tarefa=t.id where d.id='".$_REQUEST["id_detalhe"]."'");
		$row1 = mysql_fetch_array($resu1);
		echo $row["descricao_subitem"]." | ".$row1["desc_tarefa"];
	}
}
if($_REQUEST["op"]=="SalvarDadosAlerta")
{	//listar os usuarios copia
	for($i=0; $i < count($_REQUEST["alerta_copia"]); $i++)
	{
		if($i==0) $copia  = $_REQUEST["alerta_copia"][$i];
		else      $copia .= ",".$_REQUEST["alerta_copia"][$i];
	}
	$resu = mysql_query("select * from cro_projeto_fase_e_etapa_tarefa where id=".$_REQUEST["id_detalhe"]);
	$row  = mysql_fetch_array($resu);
	$resu_ctr = mysql_query("select proj_ctr from tb_projeto where id='".$_REQUEST["id_ctr"]."' ");
	$row_ctr  = mysql_fetch_array($resu_ctr);
	$ano_orcamento = "20".substr($row_ctr["proj_ctr"],0,2);
	$cod_orcamento = intval(substr($row_ctr["proj_ctr"],2));
	$data_limite   = $_REQUEST["alerta_data"]." ".$_REQUEST["alerta_hora"].":".$_REQUEST["alerta_minuto"].":00";
	if($_REQUEST["tipo"]=="3")//ITEM
	{
		$resu = mysql_query("select * from cro_projeto_item_tarefa where id_item_tarefa=".$_REQUEST["id_detalhe"]);
		$row = mysql_fetch_array($resu);
		//INSERT TABELA ALERTA
		$sql = "insert into orc_pendencias(ano_orcamento,cod_orcamento,item,tipo,descricao,de,para,copia,situacao,data_limite,login_inclusao,data_hora_inclusao,errata,respondido,origem,alerta_sigla,alerta_id_ctr,alerta_id_sub_item,alerta_id_detalhe,enviado,id_pai,sequencia)
		values('".$ano_orcamento."','".$cod_orcamento."','0','p','".strtoupper($_REQUEST["alerta_obs"])."','".$_REQUEST["usuario"]."','".$_REQUEST["alerta_para"]."','".$copia."','0','".$data_limite."','".$_REQUEST["usuario"]."',now(),'N','N','A','CIT','".$_REQUEST["id_ctr"]."','".$row["id_fase_etapa"]."','".$_REQUEST["id_detalhe"]."','0','0','0')";
		mysql_query($sql);
	}
	else//FASE E ETAPA
	{	
		//INSERT TABELA ALERTA
$sql = "insert into orc_pendencias(ano_orcamento,cod_orcamento,item,tipo,descricao,de,para,copia,situacao,data_limite,login_inclusao,data_hora_inclusao,errata,respondido,origem,alerta_sigla,alerta_id_ctr,alerta_id_sub_item,alerta_id_detalhe,enviado,id_pai,sequencia)
		values('".$ano_orcamento."','".$cod_orcamento."','0','p','".strtoupper($_REQUEST["alerta_obs"])."','".$_REQUEST["usuario"]."','".$_REQUEST["alerta_para"]."','".$copia."','0','".$data_limite."','".$_REQUEST["usuario"]."',now(),'N','N','A','CFA','".$_REQUEST["id_ctr"]."','".$row["id_fase_etapa"]."','".$_REQUEST["id_detalhe"]."','0','0','0')";
		mysql_query($sql);
	}
	$resu1 = mysql_query("select *,upper(u.name) nome from orc_pendencias a left join sec_users u on a.login_inclusao = u.login where a.alerta_sigla='CFA' and a.alerta_id_ctr='".$_REQUEST["id_ctr"]."' and a.alerta_id_sub_item='".$row["id_fase_etapa"]."' and a.alerta_id_detalhe='".$_REQUEST["id_detalhe"]."' and data_limite like '".$_REQUEST["alerta_data"]."%' order by a.data_limite");
	while($row1 = mysql_fetch_array($resu1))
	{	
		$resu_para = mysql_query("select *,upper(name) nome from sec_users where login='".$row1["para"]."' ");
		$row_para  = mysql_fetch_array($resu_para);
		$usuarios = '';
		$listacopia = explode(',',$row1["copia"]);
		foreach($listacopia as $copia)
		{
			$resu2 = mysql_query("select upper(name) as nome from sec_users where login='".$copia."' order by name");
			$row2  = mysql_fetch_array($resu2);
			$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
		}
		if($cor=="#FFFFFF") $cor="#DDDDDD";
		else $cor = "#FFFFFF";
		$alerta_data = dataform(substr($row1["data_limite"],0,10));
		$alerta_hora = substr($row1["data_limite"],10);
		$hora = dataform(substr($row1["data_hora_inclusao"],0,10))." ".substr($row1["data_hora_inclusao"],10);
$tr .= "
	<tr style='background:".$cor.";'>
		<td valign='top'></td>
		<td valign='top'>".nl2br($row1["descricao"])."</td>
		<td valign='top'>".$row_para["nome"]."</td>
		<td valign='top'><ul>".$usuarios."</ul></td>
		<td valign='top' align='center'>".$alerta_data."</td>
		<td valign='top' align='center'>".$alerta_hora."</td>
		<td valign='top'>".$row1["nome"]."<br/>".$hora."</td>
		<td valign='top' align='center'>";
	if($row1["enviado"]==0)
	{		
$tr .= 	'
		<img id="'.$row1["id"].'" src="images/save_icon.png" width="20" class="alerta_salvar" style="display:none;" title="Salvar Alerta">
		<img id="'.$row1["id"].'" src="images/edit_icon.png" width="20" class="alerta_editar" title="Editar Alertar">
		<img id="'.$row1["id"].'" src="images/delete_icon.png" width="20" class="alerta_apagar" title="Apagar Alerta">';
	}
$tr .=	"</td>
	</tr>";
	}
	echo $tr;
}
if($_REQUEST["op"]=="ListarAlerta")
{
	$resu = mysql_query("select * from cro_projeto_fase_e_etapa_tarefa where id=".$_REQUEST["id_detalhe"]);
	$row  = mysql_fetch_array($resu);
	if($_REQUEST["tipo"]=="3")//ITEM
	{
		$resu1 = mysql_query("select *,upper(u.name) nome from orc_pendencias a left join sec_users u on a.login_inclusao = u.login where a.alerta_sigla='CIT' and a.alerta_id_ctr='".$_REQUEST["id_ctr"]."' and a.alerta_id_sub_item='".$row["id_fase_etapa"]."' and a.alerta_id_detalhe='".$_REQUEST["id_detalhe"]."' and data_limite like '".$_REQUEST["alerta_data"]."%' order by a.data_limite");
	}
	else//FASE E ETAPA
	{	
		$resu1 = mysql_query("select *,upper(u.name) nome from orc_pendencias a left join sec_users u on a.login_inclusao = u.login where a.alerta_sigla='CFA' and a.alerta_id_ctr='".$_REQUEST["id_ctr"]."' and a.alerta_id_sub_item='".$row["id_fase_etapa"]."' and a.alerta_id_detalhe='".$_REQUEST["id_detalhe"]."' and data_limite like '".$_REQUEST["alerta_data"]."%' order by a.data_limite");
	}
	while($row1 = mysql_fetch_array($resu1))
	{	
		$resu_para = mysql_query("select *,upper(name) nome from sec_users where login='".$row1["para"]."' ");
		$row_para  = mysql_fetch_array($resu_para);
		$usuarios = '';
		$listacopia = explode(',',$row1["copia"]);
		foreach($listacopia as $copia)
		{
			$resu2 = mysql_query("select upper(name) as nome from sec_users where login='".$copia."' order by name");
			$row2  = mysql_fetch_array($resu2);
			$usuarios .= '<li style="clear:both;">'.$row2["nome"].'</li>';
		}
		if($cor=="#FFFFFF") $cor="#DDDDDD";
		else $cor = "#FFFFFF";
		$alerta_data = dataform(substr($row1["data_limite"],0,10));
		$alerta_hora = substr($row1["data_limite"],10);
		$hora = dataform(substr($row1["data_hora_inclusao"],0,10))." ".substr($row1["data_hora_inclusao"],10);
$tr .= "
	<tr style='background:".$cor.";'>
		<td valign='top'></td>
		<td valign='top'>".nl2br($row1["descricao"])."</td>
		<td valign='top'>".$row_para["nome"]."</td>
		<td valign='top'><ul>".$usuarios."</ul></td>
		<td valign='top' align='center'>".$alerta_data."</td>
		<td valign='top' align='center'>".$alerta_hora."</td>
		<td valign='top'>".$row1["nome"]."<br/>".$hora."</td>
		<td valign='top' align='center'>";
	if($row1["enviado"]==0)
	{		
$tr .= 	'
		<img id="'.$row1["id"].'" src="images/save_icon.png" width="20" class="alerta_salvar" style="display:none;" title="Salvar Alerta">
		<img id="'.$row1["id"].'" src="images/edit_icon.png" width="20" class="alerta_editar" title="Editar Alertar">
		<img id="'.$row1["id"].'" src="images/delete_icon.png" width="20" class="alerta_apagar" title="Apagar Alerta">';
	}
$tr .=	"</td>
	</tr>";
	}
	echo $tr;
}
if($_REQUEST["op"]=="insertar")
{
	if($_REQUEST["cor"] == "")
	{
		$cor = "#DDDDDD";
	}
	else
	{
		$cor = substr($_REQUEST["cor"],11,7);
	}

if($cor=="#FFFFFF") $cor="#DDDDDD";
else $cor = "#FFFFFF";
$tr = 
'<tr style="background:'.$cor.';">
	<td valign="top"></td>
	<td valign="top">
		<textarea name="alerta_obs" class="alerta_obs"></textarea>
	</td>
	<td valign="top">
		<select data-placeholder="SELECIONE" class="chosen-select" style="width:200px;">
            <option value=""></option>';
$resu_usu = mysql_query("select u.login, upper(u.name) as nome, u.email FROM sec_users u inner join sec_users_groups p on u.login = p.login inner join sec_groups g on g.group_id = p.group_id where u.active='Y' order by u.name");
		while($row_usu = mysql_fetch_array($resu_usu))
		{
$tr .=		'<option value="'.$row_usu["login"].'">'.$row_usu["nome"].'</option>';	
		}			
$tr .= '</select>
	</td>
	<td valign="top">
		<select data-placeholder="SELECIONE" class="chosen-select" multiple style="width:200px;">
            <option value=""></option>';
$resu_usu = mysql_query("select u.login, upper(u.name) as nome, u.email FROM sec_users u inner join sec_users_groups p on u.login = p.login inner join sec_groups g on g.group_id = p.group_id where u.active='Y' order by u.name");
		while($row_usu = mysql_fetch_array($resu_usu))
		{
$tr .=		'<option value="'.$row_usu["login"].'">'.$row_usu["nome"].'</option>';	
		}			
$tr .= '</select>
	</td>
	<td valign="top" align="center">'.dataform($_REQUEST["alerta_data"]).'</td>
	<td valign="top">
	<select name="alerta_hora" class="alerta_hora">
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
	<select name="alerta_minuto" class="alerta_minuto">
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
	</td>
	<td valign="top">Eduardo</td>
	<td valign="top">
	<img id="0" src="images/save_icon.png" width="20" class="alerta_salvar" title="Salvar Alerta">
	<img id="0" src="images/edit_icon.png" width="20" class="alerta_editar" style="display:none;" title="Editar Alertar">
	<img id="0" src="images/delete_icon.png" width="20" class="alerta_apagar" title="Apagar Alerta">
	</td>
</tr>';
echo $tr;
}
?>
<script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>