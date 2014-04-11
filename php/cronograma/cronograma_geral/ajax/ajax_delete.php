<?php

/*
 * @CRONOGRAMA GERAL [FASE | ETAPA | ITEM]
 * @autor  : Eduardo Zambrano <eduardoz@clamom.com.br>
 * @file   : delete [apaga os itens ]
 * @versão : 1.0
 * @data   : 26/03/2014
 * Copyright 2014 http://www.clamom.com.br
 **/

require("cn.php");/*arquivo conexão*/
foreach($_REQUEST["data"] as $codigo)
{	//CHECAR SE TEM FILHOS
	$resu = mysql_query("select * from cro_projeto_fase_e_etapa where id_pai='".$codigo."'");
	if(mysql_num_rows($resu)>0) $codigo_pae .= ",".$codigo;	
	else
	{
		//DELETE TAREFAS
		$sql="delete from cro_projeto_fase_e_etapa_tarefa where id_fase_etapa=".$codigo;
		mysql_query($sql);
		//DELETE SUB ITEM
		$sql="delete from cro_projeto_fase_e_etapa where id=".$codigo;
		mysql_query($sql);
		//LISTAR AS TABELAS E ITENS
		$resu1 = mysql_query("select * from cro_projeto_etapa_item where id_etapa='".$codigo."'");
		while($row1 = mysql_fetch_array($resu1))
		{
			//DELETE AS TABELAS E ITENS
			$sql="delete from cro_projeto_item_tarefa where id_etapa_item='".$row1["id_etapa_item"]."'";
			mysql_query($sql);
			//DELETE ALERTAS USUARIOS
			$resu11=mysql_query("select * from orc_alerta where id_sub_item='".$row1["id_etapa_item"]."' ");
			while($row11=mysql_fetch_array($resu11))
			{
				$sql="delete from orc_alerta_usuarios where id_alerta=".$row11["id_alerta"];
				mysql_query($sql);
			}
			//DELETAR AS ALERTAS (ITENS)
			$sql = "delete from orc_alerta where id_sub_item='".$row1["id_etapa_item"]."' ";
			mysql_query($sql);
		}
		//DELETE AS TABELAS E ITENS
		$sql = "delete from cro_projeto_etapa_item where id_etapa='".$codigo."'";
		mysql_query($sql);
		//DELETAR AS ALERTAS (FASE E ETAPA)
		$sql = "delete from orc_alerta where id_sub_item='".$codigo."' ";
		mysql_query($sql);
	}
}
echo $codigo_pae;
?>