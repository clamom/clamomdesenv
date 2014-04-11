<?php

//header("Content-type: application/json; charset=utf-8");

require_once ('../lib/conexao.php');
$op = $_REQUEST['op'];
// P - PROJETO
// O - ORÇAMENTO
$tipo 	= $_REQUEST['tipo'];
// id do orçamento
$id 	= $_REQUEST['id'];

// listar todos os itens do projeto
function listar_itens($vtipo, $vid) {
		
		echo "teste";
		
		$db = Conexao::getInstance();
		try{
			if($tipo=='P')
			{

			}
			else
			{
				$sql = "
				SELECT 
					P.id_pecas_orcamento, 
					P.orc_cabecalho_orcamento_id_orcamento, 
					P.pec_desc_peca,  
					P.pec_cod_peca,
					pec_quantidade 
				FROM 
					orc_pecas_orcamento P 
				WHERE 
					P.orc_cabecalho_orcamento_id_orcamento = 175 
				ORDER BY 
					P.pec_cod_peca";
			}
			
			
			$tabela = "";
			foreach($query->fetchAll(PDO::FETCH_ASSOC) as $lista_itens) {

					
					$tabela .= ' <div id="item_'.$lista_itens['pec_cod_peca'].'">
                  	<table>
                        <tbody>
                        	<tr>
                        	    <td class="tdItem" width="5%" style="text-align:center">'.$lista_itens['pec_cod_peca'].'</td>                 
                                <td rowspan="2" class="tdDesc" width="95%">'.utf8_encode($lista_itens['pec_desc_peca']).'</td>
                        	</tr>
	                        <tr>
    	                    	<td class="tdQtdad" width="5%" style="text-align:center">'.$lista_itens['pec_quantidade'].'</td>
        	                </tr>
                        </tbody>
                    </table>
                </div>';
				
				
					
					
			}
			
			return $tabela;
		 } catch (PDOException $ex) {
						exit ("Erro ao conectar com o banco de dados: " . $ex->getMessage());
		 }
	

	
}




// localizar cabeçalho do pedido ou do orçamento
function localizar_cab($tipo, $id)
{
		$db = Conexao::getInstance();
		try{
			if($tipo=='P')
			{

				$query = $db->query("SELECT   
										CONCAT(PCP.ped_ano, ' - ', PCP.ped_cod_pedido, '.', PCP.ped_cod_revisao)  AS codigo, 
										PCP.ped_obra AS descricao, 
										PPP.ped_pec_desc_peca AS item, 
										MAX(PCP.ped_ped_revisao) 
									FROM 
										" . Conexao::getTabela('ped_cabecalho_pedido') . "  AS PCP
									INNER JOIN
										" . Conexao::getTabela('ped_pecas_pedido') . " 		AS PPP
									ON
										PCP.id_pedido = PPP.id_id_ped_pedido
									AND
										PCP.id_pedido = ".$id);
			}
			else
			{
				$sql = "SELECT  
							 
							OCO.orc_obra AS descricao, 
							OPO.pec_desc_peca AS descItem, 
							OPO.pec_quantidade as qtdade,
							OPO.pec_cod_peca AS item
						FROM 
							" . Conexao::getTabela('orc_cabecalho_orcamento') . " AS OCO
						INNER JOIN 
							" . Conexao::getTabela('orc_pecas_orcamento') . " AS OPO
						ON
							OCO.id_orcamento = OPO.orc_cabecalho_orcamento_id_orcamento
						AND 
							OCO.id_orcamento = ".$id;
				$query = $db->query($sql);
				//$query->execute();
								
				//$query2 = $db->query("SELECT pec_desc_peca 
					//	FROM " . Conexao::getTabela('orc_pecas_orcamento') . " WHERE orc_cabecalho_orcamento_id_orcamento = '$id'");
			}
			
			//return var_dump($query->fetchAll(PDO::FETCH_ASSOC));
			
			$res = $query->fetchAll(PDO::FETCH_ASSOC);
			
			$rest = json_encode($res);
			
			return print $rest;
			
			//return var_dump($rest);
			
			
			
			


			//return print 
			$hml = '<div id="item_01">
                    
                  	<table>

                        <tbody>
                        	<tr>
                        	    <td class="tdItem" width="5%" style="text-align:center">Item<span></span></td>
                        	
                                <td rowspan="2" class="tdDesc" width="95%">DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao DescricaoDescricao </td>
                        	</tr>
                        
	                        <tr>
    	                    	<td class="tdQtdad" width="5%" style="text-align:center">Qtdade</td>
        	                </tr>
                        
                        </tbody>
                    
                    </table>

                </div>';

			
			/*foreach($query->fetchAll(PDO::FETCH_ASSOC) as $cabecalho) {
				return  array($cabecalho['codigo'],
								utf8_encode($cabecalho['descricao']),
								$cabecalho['descItem'],
								$cabecalho['qtdade'],
								$cabecalho['item'],
								);
			}*/
		
		 } catch (PDOException $ex) {
						exit ("Erro ao conectar com o banco de dados: " . $ex->getMessage());
		 }
	
}


// localizar cabeçalho do pedido ou do orçamento
function montar_itens($tipo, $id)
{
		$db = Conexao::getInstance();
		try{
			if($tipo=='P')
			{

				$query = $db->query("SELECT   CONCAT(ped_ano,' - ', ped_cod_pedido,'.', ped_ped_revisao) as codigo, ped_obra as descricao, MAX(ped_ped_revisao) 
						FROM " . Conexao::getTabela('ped_cabecalho_pedido') . " Where id_pedido = ".$id);
			}
			else
			{
				$query = $db->query("SELECT  CONCAT(orc_ano, ' - ', orc_cod_orcamento, '.', orc_cod_revisao) as codigo, orc_obra as descricao
						FROM " . Conexao::getTabela('orc_cabecalho_orcamento') . " where id_orcamento = ".$id);
						
				$query2 = $db->query("SELECT pec_desc_peca 
						FROM " . Conexao::getTabela('orc_pecas_orcamento') . " WHERE orc_cabecalho_orcamento_id_orcamento = '$id'");
			}
			
			
			foreach($query->fetchAll(PDO::FETCH_ASSOC) as $cabecalho) {
				return  array($cabecalho['codigo'],utf8_encode($cabecalho['descricao']));
			}
			
			foreach($query2->fetchAll(PDO::FETCH_ASSOC) as $item){
				return array($item['pec_desc_peca']);
			}
		
		 } catch (PDOException $ex) {
						exit ("Erro ao conectar com o banco de dados: " . $ex->getMessage());
		 }
	
}



switch ($op) {
    case 1:
		//PREENCHER COMBOBOX DO PROJETO OU DO ORCAMENTO 
		if($tipo=='P')
		{
			require_once('listar_pedidos.php');	
		}
		else
		{
			require_once('listar_orcamentos.php');	
		}
		
        break;
		
	case 2:
		
		if($tipo=='P')
		{
			list($codigo, $descricao, $item) = localizar_cab($tipo, $id);	
		}
		else
		{
			//list($codigo, $descricao, $descItem, $qtdade, $item) = localizar_cab($tipo, $id);	
			localizar_cab($tipo, $id);	
			
			//list($ped_desc_peca) = localizar_cab($tipo, $id);	
		}
		// a quantidade retorna com três zeros depois do ponto, 
		// ...então usa-se explode para transformar em array com o 
		// ...ponto como divisão e pegando a primeira parte da array que é a quantidade total
		//$qtdade = explode(".", $qtdade);
		//echo $codigo."|".$descricao."|".$descItem."|".$qtdade[0]."|".$item;
		
        break;
		
		
    case 3:
       if($tipo=='P')
		{
			list($codigo, $descricao) = montar_itens($tipo, $id);	
		}
		else
		{
			list($codigo, $descricao) = montar_itens($tipo, $id);	
		}
		echo $codigo."|".$descricao;
	   
        break;
	case 4:
       
        break;
	case 5:
	
		if($tipo=='P')
		{
	//		list($codigo, $descricao) = montar_itens($tipo, $id);	
		}
		else
		{
			//echo $tipo." - ".$id;
			//listar_itens($tipo, $id);	
			echo "teste";
		}
		//echo $itens;
		//echo $tipo." - ".$id;
		
		break;
	case 6:
		
		
		break;
}

?>