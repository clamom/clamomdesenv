<!doctype html>
<html>
	<head>
	 	<meta charset="utf-8">
		<title>Central de informações</title>
       	<script src="../../js/jquery/jquery-1.9.1.js"></script>
		<script src="../../js/central_informacaoes/funcoes_marcio.js"></script>
        
        <link rel="stylesheet" type="text/css" href="../../css/central_informacoes/index_marcio.css">
        
	</head>
	

<body>
	<section>
    	<header>
        	<div class="topo">
            
                <div class="codCliente">
                    <div class="codigo">
                            Código: <span></span>
                    </div><!-- DIV codigo -->
                    
                    <div class="cliente">
                            Cliente: <span></span>
                    </div><!-- DIV cliente -->
                </div><!-- DIV codCliente -->
                
                <div class="primeiroAcesso">
					<span>Selecione abaixo orçamento ou pedido:</span>
                </div><!-- DIV codCliente -->
                
                <div class="radio">  
                    <input id="orcamento" type="radio" name="radio1" value="ORCAMENTO" CLASS="TIPO_LISTA">  
                    <label for="orcamento">Orçamento</label>  
                    <input id="pedido" type="radio" name="radio1" value="PEDIDO" CLASS="TIPO_LISTA">  
                    <label for="pedido">Pedido</label>  
                </div>          	
                
                <select id="cbo_lst_orcamento" name="cbo_lst_orcamento" class="lista_comercial" />
                </select>
                
                <select name="cbo_lst_pedido" id = "cbo_lst_pedido" class="lista_comercial">
                 </select>
                
                
                
			</div><!-- DIV topo -->
        
        
        
        	<div>
           	                       



           	</div>
		</header>
        
 		          
       
       <div id="main">
       		
	       		<div id="ITEM_0">
                	<span></span>
                    	<input id = "HC_0" name="btn_historico_comercial" type="button" value="HISTÓRICO COMERCIAL"  class="barra_ferramenta" item_id = '1166'  ano='2014' cod_orc = '97'>
	                <article>
                    	<header>
                    	</header>
                   	</article>
    	        </div>
                
        	    <div class="divisao">
                    <div id="item_01">
                        <span></span>
                        <article>
                            <header>
                            </header>
                        </article>
                    </div>
				</div>
    	</div>
	</section>

</body>
</html>