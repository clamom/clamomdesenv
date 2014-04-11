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
						url: local+"webdesen/php/central_informacoes/gusfuncao.php",
						data: dataString,
						contentType:'application/json; charset=utf-8;',
						datatype: "json",						
						cache: false,
						
						success: function(data)
							{	
								//var teste = jQuery.parseJSON(data);
								//alert(data);

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

								console.log(data);
								
								for(var i=0; i<3; i++){	
									
									$("#item_01 .tdDesc").html(data);
								
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
						
												
					//selecionar os itens dentro da grid.
				/*	var dataString = 'id='+id+'&tipo='+tp+'&op=3';
				$.ajax(
					{
						type: "POST",
						url: local+"webdesen/php/central_informacoes/funcao.php",
						data: dataString,
						cache: false,
						success: function(response)
							{	
								var res = response.split("|"); 						
								$(".codigo span").html(res[0]);
								$(".cliente span").html(res[1]);
								
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
						*/
		})

		
		
	})
	
	
	
	