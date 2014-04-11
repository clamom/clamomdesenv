<?php
require("cn.php");
if($_REQUEST["tipo"]==1) $excluir = "EXCLUIR FASE";
elseif($_REQUEST["tipo"]==2) $excluir = "EXCLUIR ETAPA";
elseif($_REQUEST["tipo"]==3) $excluir = "EXCLUIR TAREFA";
?>
<li class="liTarefa" id="0" color="#FFFFFF" sigla=""><div class="quadro" style="background:#FFFFFF"></div><b><?php echo $excluir;?></b></li>
<?php

if($_REQUEST["tipo"]==1 or $_REQUEST["tipo"]==2)
{
	if($_REQUEST["tipo"]==1)
	{
		$resu1 = mysql_query("select * from cro_projeto_fase_e_etapa where id_projeto='".$_REQUEST["id_ctr"]."' order by 1");
		$row1 = mysql_fetch_array($resu1);
		if($row1["fase_etapa"] == 0)
		{	
			$id = "'85'";//fase
		}
		else//etapa 1
		{
			$resu = mysql_query("select * from tb_projeto_tarefas where user_grupo =(select group_id from sec_users_groups where login='".$_REQUEST["login"]."')");
			$row = mysql_fetch_array($resu);
			//$id = "'".$row["id"]."','22','57'";
			$id = "'1','175','170'";
		}
	}
	elseif($_REQUEST["tipo"]==2)
	{
		$resu = mysql_query("select * from tb_projeto_tarefas where user_grupo =(select group_id from sec_users_groups where login='".$_REQUEST["login"]."')");
		$row = mysql_fetch_array($resu);
		//$id = "'".$row["id"]."','22','57'";
		$id = "'1','175','170'";
	}
	$resu_tarefa = mysql_query("select * from tb_projeto_tarefas where situacao='1' and id in (".$id.") order by desc_tarefa");
		while($row_tarefa = mysql_fetch_array($resu_tarefa))
		{?>               
			<li class="liTarefa" id="<?php echo $row_tarefa["id"]?>" color="<?php echo $row_tarefa["cor_tarefa"]?>" sigla="<?php echo $row_tarefa["sigla_tarefa"]?>">
				<div class="quadro" style="background:<?php echo $row_tarefa["cor_tarefa"]?>"></div><?php echo "[".$row_tarefa["sigla_tarefa"]."] ".$row_tarefa["desc_tarefa"]?>
			</li>            
	<?php      	
		}
}
elseif($_REQUEST["tipo"]==3)
{
	if($_REQUEST["codigo"]==0)
	{	//listar tarefas pae
		$resu_tarefa = mysql_query("select * from tb_projeto_tarefas where situacao='1' and cronograma_novo='1' and e_terceirizado='".$_REQUEST["codigo"]."' order by desc_tarefa");
		while($row_tarefa = mysql_fetch_array($resu_tarefa))
		{?>               
			<li class="liTarefa" id="<?php echo $row_tarefa["id"]?>" color="<?php echo $row_tarefa["cor_tarefa"]?>" sigla="<?php echo $row_tarefa["sigla_tarefa"]?>">
				<div class="quadro" style="background:<?php echo $row_tarefa["cor_tarefa"]?>"></div><?php echo "[".$row_tarefa["sigla_tarefa"]."] ".$row_tarefa["desc_tarefa"]?>			</li>            
<?php   }
	}
	elseif($_REQUEST["codigo"]>0)
	{	
		//listar tarefas filhos
		$resu_ter = mysql_query("select * from tb_projeto_tarefas where situacao='1' and cronograma_novo='1' and e_terceirizado='".$_REQUEST["codigo"]."' order by desc_tarefa");
		while($row_ter = mysql_fetch_array($resu_ter))
		{?>
			<li class="liTarefa" id="<?php echo $row_ter["id"]?>" color="<?php echo $row_ter["cor_tarefa"]?>" sigla="<?php echo $row_ter["sigla_tarefa"]?>">
				<div class="quadro" style="background:<?php echo $row_ter["cor_tarefa"]?>"></div><?php echo "[".$row_ter["sigla_tarefa"]."] ".$row_ter["desc_tarefa"]?>
			</li>
<?php	}//listar tarefas filhos dependentes
		$resu_ter = mysql_query("select * from tb_projeto_tarefas t inner join tb_projeto_tarefas_filho d on t.id=d.id_dependente where d.id_independente='".$_REQUEST["codigo"]."' ");
		while($row_ter = mysql_fetch_array($resu_ter))
		{?>
			<li class="liTarefa" id="<?php echo $row_ter["id"]?>" color="<?php echo $row_ter["cor_tarefa"]?>" sigla="<?php echo $row_ter["sigla_tarefa"]?>">
				<div class="quadro" style="background:<?php echo $row_ter["cor_tarefa"]?>"></div><?php echo "[".$row_ter["sigla_tarefa"]."] ".$row_ter["desc_tarefa"]?>
			</li>
<?php 	}
	}
}
?>
<!--<div id="SubmyMenu">            
    <ul class="contextMenu">            
		<img src="images/seta_right.png" class="seta" width="10" />	
	</ul>
</div>-->