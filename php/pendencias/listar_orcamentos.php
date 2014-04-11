<?php

require_once ('../lib/conexao.php');
// atribui a instância de conexão na variável
$db = Conexao::getInstance();
try{
	// consulta que retorna a lista de usuários do banco ordenado por nome
	$query = $db->query("SELECT * FROM " . Conexao::getTabela('cabecalho_orcamento') . " ORDER BY id_orcamento");

	// escreve cada usuário encontrado na consulta
	foreach($query->fetchAll(PDO::FETCH_ASSOC) as $cab_orcamento) {
    	echo $cab_orcamento['id_orcamento'] . '<br>';
	}

 } catch (PDOException $ex) {
                exit ("Erro ao conectar com o banco de dados: " . $ex->getMessage());
 }
			



?>