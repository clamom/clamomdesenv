<?php

require_once ('../lib/conexao.php');
// atribui a instância de conexão na variável
$db = Conexao::getInstance();
try{
	// consulta que retorna a lista de usuários do banco ordenado por nome
	$combo = " <option  id='id_pedido_0'></option>";
	$query = $db->query("SELECT  id_pedido, CONCAT(ped_ano,' - ', ped_cod_pedido,' - ', ped_ped_revisao,' - ' , ped_obra) as desc_pedido, MAX(ped_ped_revisao) 
			FROM " . Conexao::getTabela('ped_cabecalho_pedido') . " GROUP BY ped_cod_pedido ORDER BY ped_cod_pedido desc");

	// escreve cada usuário encontrado na consulta
	foreach($query->fetchAll(PDO::FETCH_ASSOC) as $cab_pedido) {
    	$combo .=  " <option tipo = 'P'  id_lst ='".$cab_pedido['id_pedido']."' id='id_pedido_".$cab_pedido['id_pedido']."'>".$cab_pedido['desc_pedido'] . "</option>";
		
	}

 } catch (PDOException $ex) {
                exit ("Erro ao conectar com o banco de dados: " . $ex->getMessage());
 }
			
echo $combo;




?>