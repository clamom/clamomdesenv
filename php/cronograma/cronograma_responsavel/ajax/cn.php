<?php
$conect = mysql_connect("192.168.0.190", "root", "clamom2012");
if (!$conect) die ("<h1>Falha na coneco com o Banco de Dados!</h1>");
$db = mysql_select_db("db_rh");
//$db = mysql_select_db("db_rh_desen");
//$conect = mysql_connect("localhost", "root", "clamom2012");
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');

function total_dias($data_inicial,$data_final)
{
	$time_inicial = strtotime($data_inicial);
	$time_final = strtotime($data_final);
	// Calcula a diferença de segundos entre as duas datas:
	$diferenca = $time_final - $time_inicial; // 19522800 segundos
	// Calcula a diferença de dias
	return $dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
}
function dataToTimestamp($data)
{
   	$ano = substr($data, 6,4);
   	$mes = substr($data, 3,2);
   	$dia = substr($data, 0,2);
	return mktime(0, 0, 0, $mes, $dia, $ano);  
} 
function CalculaDias($xDataInicial, $xDataFinal)
{
	$time1 = dataToTimestamp($xDataInicial);  
   	$time2 = dataToTimestamp($xDataFinal);  
   	$tMaior = $time1>$time2 ? $time1 : $time2;  
   	$tMenor = $time1<$time2 ? $time1 : $time2;  
   	$diff = $tMaior-$tMenor;  
   	$numDias = $diff/86400; //86400 é o número de segundos que 1 dia possui  
   	return $numDias;
}	
function dataform($data)
{
	return substr($data,8,2)."-".substr($data,5,2)."-".substr($data,0,4);
}
function database($data)
{
	return substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
}	
function cal_numero($dia1,$dia2)
{	$i=0;
	while($dia1<=$dia2)
	{
		$dia1++;
		$i++;
	}
	return $i;
}
function lista_link($id_subitem,$array,$id_ctr,$tipo,$usuario,$tipo2,$gere_coor)
{
	if($tipo == "0") $tipo = 1;
	else $tipo = 0;
	$resu = mysql_query("select s.descricao desc_nome,p.proj_ctr,s.id_itens,s.id_itens_SubComponente,s.id idsubitem,s.responsavel,s.id_tarefa FROM tb_projeto p inner join tb_projeto_sub_itens s on p.id=s.id_ctr where s.id='".$id_subitem."' and p.proj_situacao <> 'Finalizado'");
	$row  = mysql_fetch_array($resu);
	//pegar sigla
	$resu_sigla = mysql_query("select * from tb_projeto_tarefas where id='".$row["id_tarefa"]."' ");
	$row_sigla  = mysql_fetch_array($resu_sigla);
	//pegar responsavel
	$resuRI   = mysql_query("select *,upper(name) nome from sec_users where login='".$row["responsavel"]."' ");
	$rowRI    = mysql_fetch_array($resuRI);
	$responsa = "[".$row_sigla["sigla_tarefa"]."][R. ".$rowRI["nome"]."]";
	//pegar dados do item
	$resu99 = mysql_query("select *,upper(desc_item) desc_item FROM tb_projeto_itens where id='".$row["id_itens"]."'");
	$row99  = mysql_fetch_array($resu99);
	$nomeItem = "Nº ".$row99["num_item"]." ITEM | ".$row99["desc_item"];
	if($row["id_itens"]>0 && $row["id_itens_SubComponente"]==0)
	{
		$src   = "index.php?projeto=".$id_ctr."&item=".$row["id_itens"]."&subitem=0&tipo=1&usuario=".$usuario."&tipo2=".$tipo2;
		$array[$row["idsubitem"]] = array('nome' => $nomeItem." | ".$row["desc_nome"],'id' => $row["idsubitem"],'src' => $src,'responsavel' => $responsa);
	}
	elseif($row["id_itens_SubComponente"]>0) 
	{	
		$resu9 = mysql_query("select * FROM tb_projeto_sub_itens  where id='".$row["id_itens_SubComponente"]."'");
		$row9  = mysql_fetch_array($resu9);
		$src   = "index.php?projeto=".$id_ctr."&item=0&subitem=".$row["id_itens_SubComponente"]."&tipo=".$tipo."&tipo2=".$tipo2."&gere_coor=".$gere_coor."&usuario=".$usuario;
		//$src   = "index.php?projeto=".$id_ctr."&item=0&subitem=".$row["id_itens_SubComponente"]."&tipo=".$tipo."&usuario=".$usuario."&tipo2=".$tipo2;
		$array[$row["idsubitem"]] = array('nome' => $nomeItem." | ".$row["desc_nome"],'id' => $row["idsubitem"],'src' => $src,'responsavel' => $responsa);
		$array = lista_link($row["id_itens_SubComponente"],$array,$id_ctr,$tipo,$usuario,$tipo2,$gere_coor);
	}	
return $array;
}
function lista_link_fase($id_pai,$array,$projeto,$usuario)
{	
	//dados ETAPA, FASE
	$resu = mysql_query("SELECT *,upper(desc_etapa) desc_etapa FROM cro_projeto_fase_e_etapa where id='".$id_pai."'");
	$row = mysql_fetch_array($resu);
	//dados responsavel
	$resuR = mysql_query("select *,upper(name) nome from sec_users where login='".$row["responsavel"]."' ");
	$rowR = mysql_fetch_array($resuR);
	$src = "index.php?projeto=".$projeto."&id_fase=".$row["id_pai"]."&usuario=".$usuario;
	//dados sigla => responsavel
	$resu_sigla1 = mysql_query("select * from tb_projeto_tarefas where usu_responsavel='".$row["responsavel"]."' ");
	$row_sigla1  = mysql_fetch_array($resu_sigla1);
	$responsame  = "[".return_projeto($row_sigla1["sigla_tarefa"])."][R. ".$rowR["nome"]."]";
	//
	if($row["fase_etapa"]==0)//fase
	{
		$cadena = "{FS}".substr($row["desc_etapa"],0,1).substr($row["desc_etapa"],-1);
	}
	if($row["fase_etapa"]==1)//etapa
	{
		$cadena = "{ET}".$row["desc_etapa"];
	}
	$array[$id_pai] = array('nome' => $cadena,'id' => $row["id_pai"],'src' => $src,'responsavel' => $responsame);
	if($row["id_pai"]>0)
	{
		$array = lista_link_fase($row["id_pai"],$array,$projeto,$usuario);
	}
	return $array;
}
function retornar_iditem($IdSubItem)
{
	$resu = mysql_query("select * from tb_projeto_sub_itens where id=".$IdSubItem);
	$row = mysql_fetch_array($resu);
	if($row["id_itens"] > 0)
	{
		return $row["id_itens"];
	}
	else
	{
		retornar_iditem($row["id_itens_SubComponente"]);
	}
}
function retorna_desc($id,$listanome)
{
	$resu = mysql_query("select *,upper(descricao) descricao from tb_projeto_sub_itens where id='".$id."'");
	$row  = mysql_fetch_array($resu);
	$listanome[$id] = $row["descricao"];
	if($row["id_itens_SubComponente"] > 0)
	{
		$listanome = retorna_desc($row["id_itens_SubComponente"],$listanome);
	}
	return $listanome;
}
function retornar_tarefa($id,$array)
{	//subitem
	$resu1 = mysql_query("select * from tb_projeto_sub_itens where id='".$id."' ");
	$row1  = mysql_fetch_array($resu1);
	//detalhe tarefa
	$resu3 = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item='".$id."' ");
	$row3  = mysql_fetch_array($resu3);
	//tarefa
	$resu2 = mysql_query("select * from tb_projeto_tarefas where id='".$row3["id_tarefa"]."' ");
	$row2  = mysql_fetch_array($resu2);
	$array[$row1["id"]] = "[".$row2["sigla_tarefa"]."]";
	if($row2["e_terceirizado"] > 0)
	{
		$array = retornar_tarefa($row1["id_itens_SubComponente"],$array);
	}
	return $array;
}
function return_projeto($sigla)
{
	switch ($sigla) 
	{
    case "M1":
        $valor_sigla = "P1";
        break;
    case "M2":
        $valor_sigla = "P2";
        break;
    case "M3":
        $valor_sigla = "P3";
        break;
	case "M4":
        $valor_sigla = "P4";
        break;	
	default:
		$valor_sigla = $row_sigla["sigla_tarefa"];
        break;	
	}
	return $valor_sigla;
}
function return_mar($codigo)
{
	switch ($codigo)//grupos pertence a uma marcenaria
	{
		case 26://mar01
			$idmar = 171;
			break;
		case 27://mar02
			$idmar = 172;
			break;
		case 28://mar03
			$idmar = 173;
			break;
		case 29://mar04
			$idmar = 174;
			break;	
	}
	return $idmar;
}
function diasemana($data) 
{
	$ano =  substr("$data", 0, 4);
	$mes =  substr("$data", 5, -3);
	$dia =  substr("$data", 8, 9);
	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
	switch($diasemana) 
	{
		case"0": $diasemana = "Dom"; break;
		case"1": $diasemana = "Seg&nbsp;"; break;
		case"2": $diasemana = "Ter&nbsp;&nbsp;"; break;
		case"3": $diasemana = "Qua&nbsp;"; break;
		case"4": $diasemana = "Qui&nbsp;&nbsp;"; break;
		case"5": $diasemana = "Sex&nbsp;"; break;
		case"6": $diasemana = "S&aacute;b&nbsp;"; break;
	}
	return $diasemana;
}
function finalizar_sub_itens($idsubitem,$usuario)
{
	//listar pae do sub_item
	$resu = mysql_query("select * from tb_projeto_sub_itens where id='".$idsubitem."' ");
	$row  = mysql_fetch_array($resu);
	//listar os filhos do sub_item
	$estado = "true";
	$resu1 = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$row["id_itens_SubComponente"]."' ");
	while($row1  = mysql_fetch_array($resu1))
	{
		if($row1["status_finalizar"]=="0")
		{
			$estado = "false";
			break;
		}
	}
	if($estado == "true")
	{
		//atualizar sub_item
		$sql = "update tb_projeto_sub_itens set status_finalizar='1',login_alteracao='".$usuario."',data_alteracao=now() where id='".$row["id_itens_SubComponente"]."' ";
		mysql_query($sql);
		finalizar_sub_itens($row["id_itens_SubComponente"],$usuario);
	}
}
function finalizar_sub_iten_filhos($subitem,$usuario)
{
	$resu = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$subitem."' ");
	while($row = mysql_fetch_array($resu))
	{
		$sql = "update tb_projeto_sub_itens set status_finalizar='1',login_alteracao='".$usuario."',data_alteracao=now() where id='".$row["id"]."' ";
		mysql_query($sql);
		finalizar_sub_iten_filhos($row["id"],$usuario);
	}
}
function revisao_sub_itens($subitem,$usuario)
{
	$resu = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$subitem."' ");
	while($row = mysql_fetch_array($resu))
	{
		$sql = "update tb_projeto_sub_itens set status_finalizar='0',login_alteracao='".$usuario."',data_alteracao=now() where id='".$row["id"]."' ";
		mysql_query($sql);
		revisao_sub_itens($row["id"],$usuario);
	}
}
?>