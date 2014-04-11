<style>
/*
.barra_ferramenta {
	font-family: arial, sans-serif;
	font-size: 11px;
	color: #555;
	font-weight:bold;
	border-color: #9CBDD8;
	border-style: solid;
	border-width: 1px;
	border-radius: 3px;
	padding: 3px 14px;
	cursor: pointer;
	background-image: url("../../images/central_informacoes/scriptcase__NM__space_blue.jpg"); 	
}
*/
</style>

<?php

require_once ('../lib/conexao.php');
$op = $_REQUEST['op'];
// P - PROJETO
// O - ORÇAMENTO
$tipo 	= $_REQUEST['tipo'];
// id do orçamento
$id 	= $_REQUEST['id'];

$ano 		= $_REQUEST['ano'];
$cod_orc	= $_REQUEST['cod_orc'];
$nitem		= $_REQUEST['nitem'];
// tabela de historico e pendencias

function listar_dados( $nitem, $id,$tipo,$ano, $cod_orc)
{
	
	
		
		require_once ('../lib/conexao.php');
		$db = Conexao::getInstance();
		//$origem = $tipo;
		
		if($tipo == "HC")
		{
			$where = " AND item = ".$nitem." AND origem = 'C' AND ano_orcamento = ".$ano." AND cod_orcamento = ".$cod_orc;		

		}
		elseif($tipo == "PC")
		{
			$where = " AND item = ".$nitem."   AND origem = 'C' AND ano_orcamento = ".$ano." AND cod_orcamento = ".$cod_orc." AND tipo IN ('P', 'R','E','PR')";
		}
		elseif($tipo == "HP")
		{
			$where = " AND item = ".$nitem."   AND origem = 'P' AND ano_orcamento = ".$ano." AND cod_orcamento = ".$cod_orc ;			
		}
		elseif($tipo == "PP")
		{
			$where = " AND item = ".$nitem."   AND origem = 'P' AND ano_orcamento = ".$ano." AND cod_orcamento = ".$cod_orc."  AND tipo IN ('P', 'R','E','PR')";
		}
		
		//$query = $db->query("SELECT * FROM ".Conexao::getTabela('orc_pendencias'));
		// return $where;
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
		$tabela .= '<input id="add" item_id = "'.$id.'" item_n = "'.$nitem.'" class="barra_ferramenta" type="button" value="ADICIONAR" name="add">
					<input type="button" value="SALVAR" class="barra_ferramenta" name="salvar"><br /><br />
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

  			SITUACAO 0 - TIPO E - SITUACAO CONCLUIDO --> PRETO ERRATA TACHADO / ERRATA SEM ICONES

  			TIPO D -> SITUACAO = 0 - COR PRETO

 			TIPO R SITUACAO 0 COR VERMELHA

  			TIPO R SITUACAO 1 COR PRETA

  
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
function listar_itens($vtipo, $vid) {
		
		
		
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
					pec_quantidade,
					P.pec_ano, 
					P.pec_cod_orcamento 
				FROM 
					orc_pecas_orcamento P 
				WHERE 
					P.orc_cabecalho_orcamento_id_orcamento = ".$vid." 
				ORDER BY 
					P.pec_cod_peca";
			}
			
			
			$query = $db->query($sql);
			$tabela = "";
			foreach($query->fetchAll(PDO::FETCH_ASSOC) as $lista_itens) {

					
					$tabela .= ' 
					<br />
					<br />
					
					
					<div id="item_'.$lista_itens['pec_cod_peca'].'">
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
                </div>
				<br />
				
				<!-- Linha Alterada Atualizar no index--> 
				
				<br />
				<div id="botoes_tabela_0">
				
				<!-- BOTÃO HISTÓRICO COMERCIAL -->
				<input id = "HC_'.$lista_itens["pec_cod_peca"].'" name="btn_historico_comercial" type="button" value="HISTÓRICO COMERCIAL"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">
				
				<!-- BOTÃO PENDÊNCIAS COMERCIAL -->
				<input id = "PC_'.$lista_itens["pec_cod_peca"].'" name="btn_historico_pendencia" type="button" value="PENDÊNCIAS COMERCIAL"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">
				
				<!-- BOTÃO HISTÓRICO PROJETOS -->
				<input id = "HP_'.$lista_itens["pec_cod_peca"].'" name="btn_historico_projetos" type="button" value="HISTÓRICO PROJETOS"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">
				
				<!-- BOTÃO PENDÊNCIAS PROJETOS -->
				<input id = "PP_'.$lista_itens["pec_cod_peca"].'" name="btn_historico_projetos" type="button" value="PENDÊNCIAS PROJETOS"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">
				
				<!-- BOTÃO CRONOGRAMA -->
				<input id = "CP_'.$lista_itens["pec_cod_peca"].'" name="btn_cronograma" type="button" value="CRONOGRAMA"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">
				
				<!-- BOTÃO ALERTAS CRONOGRAMA -->
				<input id = "AC_'.$lista_itens["pec_cod_peca"].'" name="btn_alerta" type="button" value="ALERTAS CRONOGRAMA"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">
				
				<!-- BOTÃO OBSERVAÇÃO PRODUTOS -->
				<input id = "OP_'.$lista_itens["pec_cod_peca"].'" name="btn_alerta" type="button" value="OBSERVAÇÃO PRODUTOS"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">
				
				<!--  BOTÃO ALERTA PRODUTOS -->
				<input id = "AP_'.$lista_itens["pec_cod_peca"].'" name="btn_alerta" type="button" value="ALERTAS PRODUTOS"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">
				
				<!-- BOTÃO LISTA DE MATERIAIS SINTÉTICO -->				
				<input id = "LM_'.$lista_itens["pec_cod_peca"].'" name="btn_alerta" type="button" value="LISTA DE MATERIAIS SINTÉTICO"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">
				
				<!-- BOTÃO LISTA DE MATERIAIS C/VALORES -->				
				<input id = "LV_'.$lista_itens["pec_cod_peca"].'" name="btn_alerta" type="button" value="LISTA DE MATERIAIS C/VALORES"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">		
				
				<!-- BOTÃO LISTA DE MATERIAIS NÃO CONFORME -->
				<input id = "NC_'.$lista_itens["pec_cod_peca"].'" name="btn_alerta" type="button" value="LISTA DE MATERIAIS NÃO CONFORME"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">				
				
				<!-- BOTÃO ARQUIVOS COMERCIAL -->
				<input id = "FC_'.$lista_itens["pec_cod_peca"].'" name="btn_alerta" type="button" value="ARQUIVOS COMERCIAL"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'">	
				
				<!-- BOTÃO ARQUIVOS PROJETOS -->
				<input id = "FP_'.$lista_itens["pec_cod_peca"].'" name="btn_alerta" type="button" value="ARQUIVOS PROJETOS"  class="barra_ferramenta" item_id = "'.$lista_itens['id_pecas_orcamento'].'"  ano="'.$lista_itens["pec_ano"].'" cod_orc = "'.$lista_itens["pec_cod_orcamento"].'"><br /><br /><br /><br />										
				
				<br /><br /><div id="tb_'.$lista_itens["pec_cod_peca"].'"></div>';
		
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
							CONCAT(OCO.orc_ano, ' - ', OCO.orc_cod_orcamento, '.', OCO.orc_cod_revisao) AS codigo, 
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
			
			//$res = $query->fetchAll(PDO::FETCH_ASSOC);

			
		//	$rest = json_encode($res);
			
			//return print $rest;

			//return print 
			/*$hml = '<div id="item_01">
                    
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

                </div>';*/

			
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
			echo localizar_cab($tipo, $id);	
			
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
       	   echo listar_dados($nitem, $id,$tipo,$ano, $cod_orc);
		  
        break;
		
	case 5:
		//echo "teste";
	
		if($tipo=='P')
		{
		}
		else
		{
			echo listar_itens($tipo, $id);	
		}
		//echo $itens;
		//echo $tipo." - ".$id;
		
		break;
	case 6:
		
		
		break;
}

?>