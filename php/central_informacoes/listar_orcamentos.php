<?php

require_once ('../lib/conexao.php');
// atribui a instância de conexão na variável
$db = Conexao::getInstance();
try{
	// consulta que retorna a lista de usuários do banco ordenado por nome
	$combo = " <option  id='id_orcamento_0'></option>";
	$query = $db->query("SELECT id_orcamento, CONCAT(orc_ano, ' - ', orc_cod_orcamento, '.', orc_cod_revisao, ' - ', orc_obra) as desc_orcamento
			FROM " . Conexao::getTabela('orc_cabecalho_orcamento') . " ORDER BY  orc_cod_orcamento desc, orc_cod_revisao desc");

	// escreve cada usuário encontrado na consulta
	foreach($query->fetchAll(PDO::FETCH_ASSOC) as $cab_orcamento) {
    	$combo .= " <option tipo = 'O'  id_lst ='".$cab_orcamento['id_orcamento']."' id='id_orcamento_".$cab_orcamento['id_orcamento']."'>".$cab_orcamento['desc_orcamento'] . "</option>";
	}

 } catch (PDOException $ex) {
                exit ("Erro ao conectar com o banco de dados: " . $ex->getMessage());
 }
			
echo $combo;


?>