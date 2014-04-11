// JavaScript Document

$(document).ready(function()
	{
		//definir local de acesso
		var interno = 'http://192.168.0.190/';
		var externo = 'http://intranet.clamom.com.br/';
		var local = interno;
		//ocultar objetos
		$('#cbo_lst_orcamento').hide();
		$('#cbo_lst_pedido').hide();
		$('#auto_complete_orcamento').hide();
		$('#auto_complete_pedido').hide();
		$('#nm_titulo').hide();
		
	
		$(document).on("click",".TIPO_LISTA", function(){
			
			
			if($(this).val() == "ORCAMENTO")
				{
					$('#cbo_lst_orcamento').show();
					$('#cbo_lst_pedido').hide();
					$('#auto_complete_orcamento').show();
					$('#auto_complete_pedido').hide();
					var tp = 'O';
				}
			else
				{
					$('#cbo_lst_orcamento').hide();
					$('#cbo_lst_pedido').show();
					$('#auto_complete_orcamento').hide();
					$('#auto_complete_pedido').show();
					var tp = 'P';
				}
				var dataString = 'tipo='+tp+'&op=1';
				$.ajax(
					{
						type: "POST",
						url: local+"webdesen/php/central_informacoes/funcao.php",
						data: dataString,
						cache: false,
						success: function(response)
							{	
								if(tp=='O')
								{
									$('#cbo_lst_orcamento option').remove();
									$('#cbo_lst_orcamento').append(response);	
								}
								else
								{
									$('#cbo_lst_pedido option').remove();
									$('#cbo_lst_pedido').append(response);
								}
							
							},
						 error: function(xhr, ajaxOptions, thrownError, exception) 
							 {
								if (xhr.status === 0) {
									alert('Not connect.\n Verify Network.');
								} else if (xhr.status == 404) {
									alert('Requested page not found. [404]');
								} else if (xhr.status == 500) {
									alert('Internal Server Error [500].');
								} else if (exception === 'parsererror') {
									alert('Requested JSON parse failed.');
								} else if (exception === 'timeout') {
									alert('Time out error.');
								} else if (exception === 'abort') {
									alert('Ajax request aborted.');
								} else {
									alert('Uncaught Error.\n' + xhr.responseText);
								}
								
							 }
						})
			
				
			
		})
		
		$(document).on("change",".lista_comercial", function(){
                id = $(this).children(":selected").attr("id_lst");
                tp = $(this).children(":selected").attr("tipo");
			
			var dataString = 'id='+id+'&tipo='+tp+'&op=2';
				$.ajax(
					{
						type: "POST",
						url: local+"webdesen/php/central_informacoes/funcao.php",
						data: dataString,
						datatype: "json",
						cache: false,
						
						success: function(response)
							{	

								
								var res = response.split("***");
								$(".primeiroAcesso").slideUp("slow"); 						
								$(".codigo").slideDown("slow");
								$(".cliente").slideDown("slow");
								$(".codCliente").slideDown( "slow" );
								$(".codigo span").html(res[0]);
								$(".cliente span").html(res[1]);
								//$("#ITEM_0 span").html(res[0]+' - '+res[1]);
								//$("#item_01 .tdItem").html(res[2]);
								//$("#item_01 .tdQtdad").html(res[3]);
								//console.log(response);
							//	for(var i=0; i<3; i++){	
									
							//		$("#item_01 .tdDesc").html(data);$("#main").html(response);
								
							//	}
							$("#main").html(res[2]);	

							 								
							},
						 error: function(xhr, ajaxOptions, thrownError, exception) 
							 {
								if (xhr.status === 0) {
									alert('Not connect.\n Verify Network.');
								} else if (xhr.status == 404) {
									alert('Requested page not found. [404]');
								} else if (xhr.status == 500) {
									alert('Internal Server Error [500].');
								} else if (exception === 'parsererror') {
									alert('Requested JSON parse failed.');
								} else if (exception === 'timeout') {
									alert('Time out error.');
								} else if (exception === 'abort') {
									alert('Ajax request aborted.');
								} else {
									alert('Uncaught Error.\n' + xhr.responseText);
								}
								
							 }
						})
						
						var dataString = 'id='+id+'&tipo='+tp+'&op=5';
						$.ajax(
						{
						type: "POST",
						url: local+"webdesen/php/central_informacoes/funcao.php",
						data: dataString,
						datatype: "json",
						cache: false,
						
						success: function(response)
							{	

								//alert(response);
								
								//$("#main").html(response);
								/*var res = response.split("|");
								$(".primeiroAcesso").slideUp("slow"); 						
								$(".codigo").slideDown("slow");
								$(".cliente").slideDown("slow");
								$(".codCliente").slideDown( "slow" );
								$(".codigo span").html(res[0]);
								$(".cliente span").html(res[1]);
								$("#ITEM_0 span").html(res[0]+' - '+res[1]);
								$("#item_01 .tdItem").html(res[2]);
								$("#item_01 .tdQtdad").html(res[3]);*/	
							//	console.log(data);
							//	for(var i=0; i<3; i++){	
									
							//		$("#item_01 .tdDesc").html(data);
								
							//	}

							 								
							},
						 error: function(xhr, ajaxOptions, thrownError, exception) 
							 {
								if (xhr.status === 0) {
									alert('Not connect.\n Verify Network.');
								} else if (xhr.status == 404) {
									alert('Requested page not found. [404]');
								} else if (xhr.status == 500) {
									alert('Internal Server Error [500].');
								} else if (exception === 'parsererror') {
									alert('Requested JSON parse failed.');
								} else if (exception === 'timeout') {
									alert('Time out error.');
								} else if (exception === 'abort') {
									alert('Ajax request aborted.');
								} else {
									alert('Uncaught Error.\n' + xhr.responseText);
								}
								
							 }
						})
										
				
				
		})

		
		
		$(document).on("click",".barra_ferramentas2", function(){
						//alert($(this).attr("item_id"));
						var item_n = $(this).attr("item_n");
						var fila = 0;
	
					$('#tabela_'+item_n+' tr').each(function(index, element) {
							$('#'+fila).val(fila);
							fila++;
				        });
						
					if(fila>0) fila=fila;
					else fila=1;
					var tipo 		= '<select name="tipo" size="1" class="tipolanc"><option value="P" >P</option><option value="D">D</option></select>';
					var descricao 	= '<textarea name="comentario" id="comentario" class="ccomentario" rows="1" cols="150" ></textarea>';
					var salvar 		= '<img src="../../images/central_informacoes/save.png" alt="Salvar lançamento" height="16" width="16" style="cursor:pointer">';
					var cancelar 	= '<img src="../../images/central_informacoes/cancel.png" class="cancelar_lancamento" alt="Cancelar lançamento" height="16" width="16" style="cursor:pointer">';
					var linha_add = "<tr>";
					linha_add +=	"<td class='linhas_tabela'>"+fila+"</td>";
					linha_add +=	"<td class='linhas_tabela'>";
					linha_add +=	"<input id='252' type='checkbox'>";
					linha_add +=	"</td>";
					linha_add +=	"<td class='linhas_tabela'>"+salvar+"</td>";
					linha_add +=	"<td class='linhas_tabela'>"+cancelar+"</td>";
					linha_add +=	"<td class='linhas_tabela'></td>";
					linha_add +=	"<td class='linhas_tabela'></td> ";
					linha_add +=	"<td class='linhas_tabela'></td>";
					linha_add +=	"<td class='linhas_tabela'>"+tipo+"</td>"; // 08 tipo de lançamento
					linha_add +=	"<td class='linhas_tabela'></td>"; // 07
					linha_add +=	"<td class='linhas_tabela'>"+descricao+"</td>"; // 06 descrição do lançamento
					linha_add +=	"<td class='linhas_tabela'></td>"; // 05
					linha_add +=	"<td class='linhas_tabela'></td>"; // 04
					linha_add +=	"<td class='linhas_tabela'></td>"; // 03
					linha_add +=	"<td class='linhas_tabela'></td>"; // 02
					linha_add +=	"<td class='linhas_tabela'></td>"; // 01
					linha_add +=	"</tr>";
					$('#tabela_'+item_n).append(linha_add);
		
					})
					
					
					
					//--------------------------
					$(document).on("click",".barra_ferramenta", function(){
				var btn_name 	= $(this).attr("id");
				var explode 	= btn_name.split("_");
				var nitem 		= explode[1];
				var tipo		= explode[0];
				var id_item 	= $(this).attr("item_id");
				var ano 		= $(this).attr("ano");
				var cod_orc		= $(this).attr("cod_orc");
				var dataString = 'ano='+ano+'cod_orc='+cod_orc+'&id_item='+id_item+'&tipo='+tipo+'&nitem='+nitem+'&op=4';
			
				$.ajax(
					{
						type: "POST",
						url: local+"webdesen/php/central_informacoes/funcao_marcio.php",
						data: dataString,
						cache: false,
						success: function(response)
							{	
								//alert(response);
								$("#tb_"+nitem).html(response);
								
								// $("#result").text(JSON.stringify(jsonStr));
								//alert(response.id);
								
								
							},
						 error: function(xhr, ajaxOptions, thrownError, exception) 
							 {
								if (xhr.status === 0) {
									alert('Not connect.\n Verify Network.');
								} else if (xhr.status == 404) {
									alert('Requested page not found. [404]');
								} else if (xhr.status == 500) {
									alert('Internal Server Error [500].');
								} else if (exception === 'parsererror') {
									alert('Requested JSON parse failed.');
								} else if (exception === 'timeout') {
									alert('Time out error.');
								} else if (exception === 'abort') {
									alert('Ajax request aborted.');
								} else {
									alert('Uncaught Error.\n' + xhr.responseText);
								}
								
							 }
						})

					})
					
					
		
		
		
	})
	
	
	
	