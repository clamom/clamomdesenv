


<?php
header('Content-type: application/json');
require_once ('../lib/conexao.php');
$op = $_POST['op'];
// P - PROJETO
// O - ORÇAMENTO
$tipo 	= $_POST['tipo'];
// id do orçamento
$id 	= $_POST['id'];

	
    /* $amount      = $_POST["amount"];
    $firstName   = $_POST["firstName"];
    $lastName    = $_POST["lastName"];
    $email       = $_POST["email"]; */
    //if(isset($amount)){
		$codigo = "654321";
		$descricao = "PRADA PRADA PRADA PRADA";
        $data = array(
           // "amount"     => $amount,
            //"firstName"  => $firstName,
            "descricao"   => $descricao,
            "id"      => $codigo
        );
        echo json_encode($data);
    //}
?>