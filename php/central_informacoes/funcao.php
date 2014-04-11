<?php

require_once ('../lib/conexao.php');
include('http://192.168.0.190/_lib/appFormata.php');
$op = $_REQUEST['op'];
// P - PROJETO
// O - ORÇAMENTO
$tipo 	= $_REQUEST['tipo'];
// id do orçamento
$id 	= $_REQUEST['id'];

// tabela de historico e pendencias

function listar_dados( $nitem, $id,$tipo)
{
	
		
		require_once ('../lib/conexao.php');
		$db = Conexao::getInstance();
		//$origem = $tipo;
		
		if($tipo == "HC")
		{
			$where = " AND origem = 'C' AND foreign_key = ".$id;	
			
		}
		elseif($tipo == "PC")
		{
			$where = "  AND origem = 'C' AND foreign_key = ".$id." AND tipo IN ('P', 'R','E','PR')";
		}
		elseif($tipo == "HP")
		{
			$where = "  AND origem = 'P' AND foreign_key = ".$id;			
		}
		elseif($tipo == "PP")
		{
			$where = "  AND origem = 'P' AND foreign_key = ".$id."  AND tipo IN ('P', 'R','E','PR')";
		}
		
		//$query = $db->query("SELECT * FROM ".Conexao::getTabela('orc_pendencias'));
		
		$sql = "SELECT  id,id_pai, item, tipo,descricao, de, para, copia, data_limite, data_hora_inclusao, situacao, origem 
		FROM (
				SELECT 
					  P.id,
					  P.id_pai,
					  P.tipo,
					  P.descricao, 
					  D.name AS de, 
					  PR.NAME AS para, 
					  P.copia, 
					  P.data_limite, 
					  P.data_hora_inclusao,
					  P.id sort1, 
					  P.id_pai sort2 ,
					  P.item,
					  P.origem,
					  P.situacao
					   
     			FROM 
					".Conexao::getTabela('orc_pendencias')." P 
      			LEFT JOIN
					".Conexao::getTabela('sec_users')." PR ON P.para = PR.login
      			LEFT JOIN 
					".Conexao::getTabela('sec_users')." D ON P.para = D.login
				WHERE 
					P.id_pai = 0 ".$where."
			UNION
  				SELECT 
					P.id,
					P.id_pai,
					P.tipo,
					P.descricao, 
					D.name AS de, 
					PR.NAME AS para, 
					P.copia, 
					P.data_limite, 
					P.data_hora_inclusao, 
					P.id_pai, 
					1 sort2,
					P.item,
				  	P.origem,
					P.situacao
					 
			FROM 
				".Conexao::getTabela('orc_pendencias')." P 
   			LEFT JOIN
				".Conexao::getTabela('sec_users')." PR ON P.para = PR.login
   			LEFT JOIN 
				".Conexao::getTabela('sec_users')." D ON P.para = D.login
			WHERE 
				id_pai <> 0 ".$where."
		) AS v 
		ORDER BY sort1, sort2";
		
		//return $sql;
		
		$query = $db->query($sql);
		
		
		$tabela = "";
		$tabela .= '<input id="add" item_id = "'.$id.'" item_n = "'.$nitem.'" class="barra_ferramentas2" type="button" value="ADICIONAR" name="add"><br /><br />
				<table id="tabela_'.$nitem.'" border=1 width=1800 class="tabela_pendencias">
					<tr>
						<th colspan="2" width=50 class="titulo_tabela">
							<input type="checkbox"></th> 
						<th width=40  	class="titulo_tabela"></th> 
						<th width=40  	class="titulo_tabela"></th> 
						<th width=40  	class="titulo_tabela"></th> 
						<th width=40  	class="titulo_tabela"></th> 
						<th width=40  	class="titulo_tabela"></th> 
						<td width=40  	class="titulo_tabela"></th>
						<th width=40  	class="titulo_tabela"></th> 
						<th width=800 	class="titulo_tabela" align="left">HISTÓRICO COMERCIAL</th> 
						<th width=160  	class="titulo_tabela" align="left">De</th> 
						<th width=160  	class="titulo_tabela" align="left">Para</th>
						<th width=100  	class="titulo_tabela" align="left">Cópia para</th> 
						<th width=100  	class="titulo_tabela" align="left">Data e hora limite</th>
						<th width=100  	class="titulo_tabela" align="left">Data e Hora</th>
					</tr>';
								
        $x=1;
		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $pendencia) {
			
			$pendencia[data_limite] = date("d/m/o");
			$pendencia[data_hora_inclusao] = date("d/m/o");
			
			/*
			SITUACAO 0 - TIPO P - SITUACAO PENDENTE --> AZUL

  			SITUACAO 1- TIPO P - SITUACAO CONCLUIDO --> PRETO

  			SITUACAO 0 - TIPO E - SITUACAO CONCLUIDO --> PRETO ERRATA TACHADO

  			TIPO D -> SITUACAO = 0 - COR PRETO

 			TIPO R SITUACAO 0 COR VERMELHA

  			TIPO R SITUACAO 1 COR PRETA

  			ERRATA SEM ICONES
			*/
			
			$id = '';
			$errata = false;
			$pendente = false;
			
			if($pendencia[situacao] == 0 && $pendencia[tipo] == 'P') {
				// Cor azul
				$id = 'situacao_pendente';
				$pendente = true;
			}
			else if($pendencia[situacao] == 1 && $pendencia[tipo] == 'P') {
				//Cor preta
				$id = 'situacao_concluido';
			}
			else if($pendencia[situacao] == 0 && $pendencia[tipo] == 'E') {
				//Cor preta - Errata - Tachado - Sem imagens
				$id = 'situacao_errata';
				$errata = true;
			}
			else if($pendencia[situacao] == 0 && $pendencia[tipo] == 'R') {
				//COR VERMELHA
				$id = 'situacao_resposta0';
			}
			
			// 'V' - só no P e quando não estiver concluído
			
			$tabela .= '
						<tr>
							<td class="linhas_tabela">'.$x.'</td>
							<td class="linhas_tabela" id="'.$id.'"><input id = "'.$pendencia['id'].'"type="checkbox"></td> 
							<td class="linhas_tabela" id="'.$id.'"></td>
							<td class="linhas_tabela" id="'.$id.'"></td>';




			if($errata == true) {
				
				$tabela .= '<td class="linhas_tabela"></td> 
							<td class="linhas_tabela"></td> 
							<td class="linhas_tabela"></td>';
			}
							
			else if($pendente == true) {
				
				$tabela .= '<td class="linhas_tabela"><a href=""><img src="../../images/central_informacoes/comment_remove.png" alt="comment_remove" align="center"></a></td> 
							<td class="linhas_tabela"><a href=""><img src="../../images/central_informacoes/mail_send.png" alt="mail_send" align="center"></a></td> 
							<td class="linhas_tabela"><a href=""><img src="../../images/central_informacoes/sys__NM__check.png" alt="sys__NM__check" align="center"></a></td>';
			}
			else {
				
				$tabela .= '<td class="linhas_tabela"><a href=""><img src="../../images/central_informacoes/comment_remove.png" alt="comment_remove" align="center"></a></td> 
							<td class="linhas_tabela"><a href=""><img src="../../images/central_informacoes/mail_send.png" alt="mail_send" align="center"></a></td> 
							<td class="linhas_tabela"></td>';
			}
						
			
			$tabela.= '
						<td class="linhas_tabela" id="'.$id.'">'.$pendencia[tipo].'</td> 
						<td class="linhas_tabela"></td>
						<td class="linhas_tabela"><div id="'.$id.'">'.utf8_encode($pendencia[descricao]).'</div></td> 
						<td class="linhas_tabela"><div id="'.$id.'">'.utf8_encode($pendencia[de]).'</div></td> 
						<td class="linhas_tabela"><div id="'.$id.'">'.utf8_encode($pendencia[para]).'</div></td> 
						<td class="linhas_tabela"><div id="'.$id.'">'.utf8_encode($pendencia[copia]).'</div></td>  
						<td class="linhas_tabela"><div id="'.$id.'">'.$pendencia[data_limite].'</div></td> 
						<td class="linhas_tabela"><div id="'.$id.'">'.$pendencia[data_hora_inclusao].'</div></td>
					</tr>';
					
					$x++;
		}
		
		$tabela .= '</table>';
		return $tabela;	
}




// listar todos os itens do projeto
function listar_itens($tipo, $id) {
		
		
		
		$db = Conexao::getInstance();
		
		try{
			if($tipo=='P')
			{

			}
			else
			{
				$sql = "
				SELECT 
					CONCAT(C.orc_ano, ' - ', C.orc_cod_orcamento, '.', C.orc_cod_revisao, ' - ', C.orc_obra) as desc_orcamentoss,					
					C.orc_ano AS OA,
					C.orc_cod_orcamento AS OCO,
					C.orc_cod_revisao AS OCR,
					C.orc_obra AS OO,
					P.id_pecas_orcamento, 
					P.orc_cabecalho_orcamento_id_orcamento, 
					P.pec_desc_peca,  
					P.pec_cod_peca,
					pec_quantidade,
					C.orc_obra
				FROM 
					orc_pecas_orcamento AS P 
				INNER JOIN
					orc_cabecalho_orcamento AS C
				ON
					P.orc_cabecalho_orcamento_id_orcamento = C.id_orcamento
				AND 
					P.orc_cabecalho_orcamento_id_orcamento = ".$id." 
				ORDER BY 
					P.pec_cod_peca";
					
				
				
			}
			
			$query = $db->query($sql);
			
			$tabela = "";
			$i=0;
			foreach($query->fetchAll(PDO::FETCH_ASSOC) as $lista_itens) {

					while($i<1){
						$cod = $lista_itens['OA'].'-'.$lista_itens['OCO'].'.'.$lista_itens['OCR'];
						$cliente = utf8_encode($lista_itens['OO']);
						
						$tabela .= '
						<div id="itemPai">	
							<div id="item_'.$lista_itens['pec_cod_peca'].'">
								<table>
									<tbody>
										<tr>
											<td class="tdItem" width="100%" style="text-align:center">'.$lista_itens['OA'].'-'.$lista_itens['OCO'].'.'.$lista_itens['OCR'].' | '.$lista_itens['OO'].'</td>                 
										</tr>
									</tbody>
								</table>
							</div>
						</div><!-- itemPai -->
						<br />
						<input id = "HC_'.$lista_itens["pec_cod_peca"].'" name="btn_historico_comercial" type="button" value="HISTÓRICO COMERCIAL"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens['OA'].'" cod_orc = "'.$lista_itens['OCO'].'"><br /><br />
						<br /><br /><div id="tb_'.$lista_itens["pec_cod_peca"].'"></div>
						';
						$i++;
					}
					
					$tabela .= ' 
					<br />
					<br />
							
				<div id="itemPai">	
					<div id="item_'.$lista_itens['pec_cod_peca'].'">
                  		<table>
                        	<tbody>
                        		<tr>
                        	    	<td class="tdItem" width="5%" style="text-align:center">'.
										$formata = new appFormata();
										$formata->formataNumero();
									.'</td>                 
                               		<td rowspan="2" class="tdDesc divisaoInterna" width="95%">'.utf8_encode($lista_itens['pec_desc_peca']).'</td>
                        		</tr>
	                        	<tr>
									
    	                    		<td class="tdQtdad" width="5%" style="text-align:center">'.number_format(round($lista_itens['pec_quantidade'], 1,PHP_ROUND_HALF_UP), 0, ',', '').'</td>
        	                	</tr>
                        	</tbody>
                    	</table>
                	</div>
				</div><!-- itemPai -->
				<br />
				<input id = "HC_'.$lista_itens["pec_cod_peca"].'" name="btn_historico_comercial" type="button" value="HISTÓRICO COMERCIAL"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="2014" cod_orc = "97"><br /><br />
				<br /><br /><div id="tb_'.$lista_itens["pec_cod_peca"].'"></div>
				
				';
				
				
					
					
			}
			
			return array($cod, $cliente, $tabela);
			
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
 			list($cod, $cliente, $tabela) = listar_itens($tipo, $id);	
		}
			echo $cod."***".$cliente."***".$tabela;
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
       	   echo listar_dados($nitem, $id,$tipo);
        break;
	
}

?>