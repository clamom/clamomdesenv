<?php
require("cn.php");
//Atarefa[i][0] = $(this).attr("detalhe");//detalhe(idtarefa)
//Atarefa[i][1] = $(this).attr("idstatus");//id_status
//Atarefa[i][2] = $(this).attr("id");//data1
for ($i=0; $i<count($_REQUEST["ArrayTarefa"]); $i++) 
{
	$idtarefa = $_REQUEST["ArrayTarefa"][$i][0];
	$idstatus = $_REQUEST["ArrayTarefa"][$i][1];
	$data1	  = $_REQUEST["ArrayTarefa"][$i][2];
	$resu_sta = mysql_query("select * from tb_projeto_execucao where id_tarefa='".$idtarefa."' and data_status like '".$data1."%' and id_status='".$idstatus."'");
	if(mysql_num_rows($resu_sta)==0)
	{
		$data_status=$data1." ".date("H:i:s");
		//NOVA EXECUCAO DA TAREFA
		$sql="insert into tb_projeto_execucao(id_tarefa,data_status,id_status,login,data_inclusao) 
		values('".$idtarefa."','".$data_status."','".$idstatus."','".$_REQUEST["usuario"]."',now())";
		mysql_query($sql);
	}
	if($idstatus==2)
	{
		$resu = mysql_query("select * from tb_projeto_detalhe_tarefa where id='".$idtarefa."'");
		$row = mysql_fetch_array($resu);
		//total_dias($_REQUEST["ArrayTarefa"][$i][2],$row["data_final"]);
		$dia1 = substr($data1,8,2);
		$mes  = substr($data1,5,2);
		$ano  = substr($data1,0,4);
		$dia2 = substr($row["data_final"],8,2);
		$mes2 = substr($row["data_final"],5,2);
		$ano2 = substr($row["data_final"],0,4);
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
					if($data > $data1 and $data <= $row["data_final"])
					{
						$sql="insert into tb_projeto_execucao(id_tarefa,data_status,id_status,login,data_inclusao) 
						values('".$idtarefa."','".$data."','".$idstatus."','".$_REQUEST["usuario"]."',now())";
						mysql_query($sql);
					}
				}
			}
			$mes++;
		}
	}
}
?>