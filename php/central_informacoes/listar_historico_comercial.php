<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../../css/central_informacoes/index.css">
<title></title>
</head>

<body>
	<?php
		
    	require_once ('../lib/conexao.php');
	
		$db = Conexao::getInstance();
		
		$query = $db->query("SELECT * FROM ".Conexao::getTabela('orc_pendencias'));

		echo '<table id="listar_historico_comercial" border=1 width=90% cellspacing="0" cellpadding="4">
				<tr bgcolor="#B4C0F4">
					<td colspan="2" width="5%">';
				
		echo '<input type="checkbox"></td> 
				<td width="5%"></td> <td width="2%"></td> <td width="2%"></td> 
				<td width="2%"></td> <td width="2%"></td> <td width="5%"></td>
				<td width="5%"></td> <td width= "5%"></td>
				<td width="20%"><b>HISTÓRICO COMERCIAL</b></td> 
				<td width="5%"><b>De</b></td> <td width="5%"><b>Para</b></td>';
        
		echo '<td width="2%"><b>Cópia para</b></td> 
				<td width="2%"><b>Data e hora limite</b></td>
				<td width="2%"><b>Data e Hora</b></td></tr>';
								
        
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $projetos) {
			
			$projetos[data_limite] = date("d/m/o - H:i");
			$projetos[data_hora_inclusao] = date("d/m/o - H:i");
			
			echo '<tr>' . '<td>' . $projetos['id'] .'</td>' . 
					'<td><input type="checkbox"></td>' . 
					'<td></td> <td></td> <td><a href=""><img src="../../images/central_informacoes/comment_remove.png" alt="comment_remove" align="center"></a></td> 
					<td><a href=""><img src="../../images/central_informacoes/mail_send.png" alt="mail_send" align="center"></a></td> 
					<td><a href=""><img src="../../images/central_informacoes/sys__NM__check.png" alt="sys__NM__check" align="center"></a></td> <td>P</td> <td></td>' .
					'<td></td>' .
					'<td> ' .$projetos[descricao] . '</td>' . 
					'<td>' . $projetos[de] . '</td>' . 
					'<td> ' . $projetos[para] . '</td>' . 
					'<td>' . $projetos[copia] . '</td>' .  
					'<td>' . $projetos[data_limite] . '</td>' . 
					'<td> ' . $projetos[data_hora_inclusao] . '</td></tr>';
		}
		
		echo '</table>';
    ?>
</body>
</html>