<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sem título</title>
</head>
<?php

require_once ('../php/lib/conexao.php');

// atribui a instância de conexão na variável
$db = Conexao::getInstance();
// consulta que retorna a lista de usuários do banco ordenado por nome
//$query = $db->query("SELECT * FROM tb_projeto ORDER BY id");
$query = $db->query("SELECT * FROM " . Conexao::getTabela('tb_projeto') . " ORDER BY id");
// escreve cada usuário encontrado na consulta
foreach($query->fetchAll(PDO::FETCH_ASSOC) as $projetos) 
{
    echo $projetos['id'] . '<br>';
}


?>
<body>
</body>
</html>