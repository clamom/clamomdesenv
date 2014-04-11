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
function lista_link($id_pai,$array,$projeto,$usuario)
{	//dados ETAPA, FASE
	$resu = mysql_query("SELECT *,upper(desc_etapa) desc_etapa FROM cro_projeto_fase_e_etapa where id='".$id_pai."'");
	$row = mysql_fetch_array($resu);
	//dados responsavel
	$resuR = mysql_query("select *,upper(name) nome from sec_users where login='".$row["responsavel"]."' ");
	$rowR = mysql_fetch_array($resuR);
	//dados sigla => responsavel
	$resu_sigla1 = mysql_query("select * from tb_projeto_tarefas where usu_responsavel='".$row["responsavel"]."' ");
	$row_sigla1  = mysql_fetch_array($resu_sigla1);
	$responsame  = "[".return_projeto($row_sigla1["sigla_tarefa"])."][R. ".$rowR["nome"]."]";
	//
	$src = "index.php?projeto=".$projeto."&id_fase=".$row["id_pai"]."&usuario=".$usuario;
	if($row["fase_etapa"]==0)
	{
		$cadena = "{FS}".substr($row["desc_etapa"],0,1).substr($row["desc_etapa"],-1);
	}
	if($row["fase_etapa"]==1)
	{
		$cadena = "{ET}".$row["desc_etapa"];
	}
	$array[$id_pai] = array('nome' => $cadena,'id' => $row["id_pai"],'src' => $src,'responsavel' => $responsame);
	if($row["id_pai"]>0)
	{
		$array = lista_link($row["id_pai"],$array,$projeto,$usuario);
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
		return retornar_iditem($row["id_itens_SubComponente"]);
	}
}
function procurar_nome($usuario)
{
	$check_sql = mysql_query("select name from sec_users where login = '".$usuario."'");
	$rs = mysql_fetch_array($check_sql);
    return  $rs["name"]; 
}
function procurar_item($codigo)
{
	$check_sql = mysql_query("SELECT id FROM tb_projeto_itens WHERE projeto_id = '".$codigo."' and num_item = 0");
	$rs = mysql_fetch_array($check_sql);
	if ($rs["id"]>0)
	{
		return $rs["id"];
	}
	else
	{
		return 0;
	}
}
function find_task($id_task)
{
	$check_sql = mysql_query("SELECT desc_tarefa FROM tb_projeto_tarefas  WHERE id = '".$id_task."'");
	$rs = mysql_fetch_array($check_sql);
	if(isset($rs["desc_tarefa"]))  // Row found
	{
    	$tarefa = $rs["desc_tarefa"];
	}
	else     // No row found
	{
		$tarefa = 0; 
	}
	return $tarefa;
}
function change_marcenaria($id)
{
	$mar = array(171,172,173,174);//mar01, mar02, mar03, mar04   
	//pegar responsavel do subitem pai
	$resu_res = mysql_query("select * from tb_projeto_sub_itens where id='".$id."' ");
	$row_res  = mysql_fetch_array($resu_res);
	if(in_array($row_res["id_tarefa"],$mar))//validar tarefa subitem
	{	//listar os filhos do $_REQUEST["subitem"]
		$resu_tar = mysql_query("select * from tb_projeto_sub_itens where id_itens_SubComponente='".$id."' ");
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
					$sql = "update tb_projeto_sub_itens set id_tarefa='".$row_res["id_tarefa"]."' ".$update_responsa." where id='".$row_tar["id"]."' ";
					mysql_query($sql);
					//alterar o detalhe tarefa do subitem id='".$row_tar["id_tarefa"]."'
					$resu_deta = mysql_query("select * from tb_projeto_detalhe_tarefa where id_sub_item='".$row_tar["id"]."' and id_tarefa='".$row_tar["id_tarefa"]."' ");
					if(mysql_num_rows($resu_deta)>0)
					{
						$row_deta = mysql_fetch_array($resu_deta);
						$sql = "update tb_projeto_detalhe_tarefa set id_tarefa='".$row_res["id_tarefa"]."' where id='".$row_deta["id"]."' ";
						mysql_query($sql);
					}
					change_marcenaria($row_tar["id"]);
				}	
			}
		}
	}
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
		case"1": $diasemana = "Seg"; break;
		case"2": $diasemana = "Ter"; break;
		case"3": $diasemana = "Qua"; break;
		case"4": $diasemana = "Qui"; break;
		case"5": $diasemana = "Sex"; break;
		case"6": $diasemana = "S&aacute;b"; break;
	}
	return $diasemana;
}
?>