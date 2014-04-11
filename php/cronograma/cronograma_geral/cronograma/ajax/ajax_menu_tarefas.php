<?php
require("cn.php");
?>
<li class="liTarefa" id="0" color="#FFFFFF" sigla=""><div class="quadro" style="background:#FFFFFF"></div><b>EXCLUIR TAREFA</b></li>
<?php
$mar = array(171,172,173,174);//mar01, mar02, mar03, mar04
if($_REQUEST["tipo"]=="0")
{	
	if($_REQUEST["task"]>0)
	{
		//tarefa selecionada
		$resu_tarefa = mysql_query("select * from tb_projeto_tarefas where id='".$_REQUEST["task"]."' ");
		$row_tarefa = mysql_fetch_array($resu_tarefa)
		?>               
		<li class="liTarefa" id="<?php echo $row_tarefa["id"]?>" color="<?php echo $row_tarefa["cor_tarefa"]?>" sigla="<?php echo $row_tarefa["sigla_tarefa"]?>">
			<div class="quadro" style="background:<?php echo $row_tarefa["cor_tarefa"]?>"></div><?php echo "[".$row_tarefa["sigla_tarefa"]."] ".$row_tarefa["desc_tarefa"]?>
		</li>        
<?php
	}
	else
	{
		//listar tarefas filhos
		$resu_ter = mysql_query("select * from tb_projeto_tarefas where situacao='1' and cronograma_novo='1' and e_terceirizado='".$_REQUEST["codigo"]."' order by desc_tarefa");
		while($row_ter = mysql_fetch_array($resu_ter))
		{?>
    		<li class="liTarefa" id="<?php echo $row_ter["id"]?>" color="<?php echo $row_ter["cor_tarefa"]?>" sigla="<?php echo $row_ter["sigla_tarefa"]?>">
        		<div class="quadro" style="background:<?php echo $row_ter["cor_tarefa"]?>"></div><?php echo "[".$row_ter["sigla_tarefa"]."] ".$row_ter["desc_tarefa"]?>
    		</li>
<?php	}
	}
}
elseif($_REQUEST["tipo"]=="1")
{	
	echo '<li class="liTarefa"><div class="separador">SETORES</div></li>';
	if($_REQUEST["task"]>0)
	{
		//tarefa selecionada
		$resu_tarefa = mysql_query("select * from tb_projeto_tarefas where id='".$_REQUEST["task"]."' ");
		$row_tarefa = mysql_fetch_array($resu_tarefa);
		$vetor_tarefas = array($row_tarefa["id"] => $row_tarefa["desc_tarefa"]);           
	}
	//pegar marcenaria do projeto AQUI FIQUE
	$resu_sub = mysql_query("select *,i.responsavel responsa from tb_projeto_sub_itens s inner join tb_projeto_itens i on s.id_itens=i.id where s.id='".$_REQUEST["id_subitem"]."'");
	$row_sub  = mysql_fetch_array($resu_sub);
	$resu_tar = mysql_query("select * from sec_users_groups where login='".$row_sub["responsa"]."' ");
	$row_tar  = mysql_fetch_array($resu_tar);
	switch ($row_tar["group_id"])//grupos pertence a uma marcenaria
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
	if(in_array($idmar,$mar))//validar tarefa filho do subitem
	{
		$tarefa_pro = $idmar;
	}
	//listar tarefas filhos dependentes
	$resu_ter = mysql_query("select * from tb_projeto_tarefas t inner join tb_projeto_tarefas_filho d on t.id=d.id_dependente where d.id_independente='".$_REQUEST["codigo"]."' and user_grupo <> '9999' order by desc_tarefa");
	while($row_ter = mysql_fetch_array($resu_ter))
	{
		if($_REQUEST["task"]!=$row_ter["id"])
		{
			if(in_array($row_ter["id"],$mar))//validar tarefa filho do subitem
			{
				if($row_ter["id"]==$tarefa_pro)//id_tarefas sao iguais
				{
					$vetor_tarefas += array($row_ter["id"] => $row_ter["desc_tarefa"]);
				}
			}
			else
			{
				$vetor_tarefas += array($row_ter["id"] => $row_ter["desc_tarefa"]);
			}
		}
	}
	asort($vetor_tarefas);	
	foreach($vetor_tarefas as $chave => $valor)
	{	$resu1 = mysql_query("select * from tb_projeto_tarefas where id='".$chave."' ");
		$row1  = mysql_fetch_array($resu1);
		echo 
		'<li class="liTarefa" id="'.$chave.'" color="'.$row1["cor_tarefa"].'" sigla="'.$row1["sigla_tarefa"].'">
			<div class="quadro" style="background:'.$row1["cor_tarefa"].'"></div>'.'['.$row1["sigla_tarefa"].'] '.$valor.'
		</li>';
	}
	?>
<?	//listar tarefas filhos dependentes
	$resu_ter = mysql_query("select * from tb_projeto_tarefas t inner join tb_projeto_tarefas_filho d on t.id=d.id_dependente where d.id_independente='".$_REQUEST["codigo"]."' and user_grupo = '9999' order by desc_tarefa");
	if(mysql_num_rows($resu_ter) > 0)
	{
		echo '<li class="liTarefa"><div class="separador">SERVIÇOS EXTERNOS</div></li>';
	}
	while($row_ter = mysql_fetch_array($resu_ter))
	{
		if($_REQUEST["task"]!=$row_ter["id"])
		{?>
		<li class="liTarefa" id="<?php echo $row_ter["id"]?>" color="<?php echo $row_ter["cor_tarefa"]?>" sigla="<?php echo $row_ter["sigla_tarefa"]?>">
			<div class="quadro" style="background:<?php echo $row_ter["cor_tarefa"]?>"></div><?php echo "[".$row_ter["sigla_tarefa"]."] ".$row_ter["desc_tarefa"]?>
		</li>	
<?php	}
	}
	if($_REQUEST["task"]==0 && $_REQUEST["codigo"]==0)
	{
		//listar tarefas filhos
		$resu_ter = mysql_query("select * from tb_projeto_tarefas where situacao='1' and cronograma_novo='1' and e_terceirizado='".$_REQUEST["codigo"]."' order by desc_tarefa");
		while($row_ter = mysql_fetch_array($resu_ter))
		{?>
    		<li class="liTarefa" id="<?php echo $row_ter["id"]?>" color="<?php echo $row_ter["cor_tarefa"]?>" sigla="<?php echo $row_ter["sigla_tarefa"]?>">
        		<div class="quadro" style="background:<?php echo $row_ter["cor_tarefa"]?>"></div><?php echo "[".$row_ter["sigla_tarefa"]."] ".$row_ter["desc_tarefa"]?>
    		</li>
<?php	}
	}
}?>