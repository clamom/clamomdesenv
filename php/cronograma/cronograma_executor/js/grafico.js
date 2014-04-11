$(document).keydown(function(e){
	if(e.keyCode==36)//inicio
	{
		d = new Date();
		month = d.getMonth()+1;
		day = d.getDate();
		dataAtual = d.getFullYear()+ '-'+(month<10 ? '0' : '')+month+'-'+(day<10 ? '0' : '')+day;
		$("#divRight").animate({scrollLeft: 0}, 'slow');//scroll automatica data atual
	}
	else if(e.keyCode==35)//fim
	{
		$("#divRight").animate({scrollLeft: $('#tabelaRight').width()}, 'slow');//scroll automatica data atual
	}
});
$(document).ready(function(e)
{	
	usuario   = $("#usuario").val();
	d = new Date();
	month = d.getMonth()+1;
	day = d.getDate();
	dataAtual = d.getFullYear()+ '-'+(month<10 ? '0' : '')+month+'-'+(day<10 ? '0' : '')+day;
	if($('#'+dataAtual).is(':visible'))
	{
		$("#divRight").animate({scrollLeft: $('#'+dataAtual).offset().left-550}, 'slow');//scroll automatica data atual
	}
	$("#horaAlerta").mask("99:99");//mascara do horario
	$("#hora_fim").mask("99:99");//mascara do horario
	$("#editar_alerta").hide();
	$(".navegacao").animate({scrollLeft:(Number($(window).scrollLeft())+5000)+'px'}, 'slow');//rolar tela para direita
	/*bloquear click direito*/
	$(this).bind("contextmenu", function(e) {
   		e.preventDefault();
    });
	/*************************************/
	$.pleasewait = function()
	{
		$.blockUI({ message: '<font style=" font-weight:bold; font-size:12px;font-family:verdana;">Aguarde Processando Dados...</font>', css: {
			border: 'none',
			padding: '15px',
			backgroundColor: '#000',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			'border-radius': '10px',
			opacity: 0.9,
			color: '#fff'
		},
		overlayCSS: { backgroundColor: '#001C37' } });
	}
	/**************PINTAR*****************/
	$.pintarCelda = function(fila1,data1,fila2,data2,cor,tarefa,sigla,dataA)
	{	var prox; var menor; var maior;
		/**************FUNCAO MESES***************/
		arrayMes = [];
		arrayMes[0]  = "January";
		arrayMes[1]  = "February";
		arrayMes[2]  = "March";		
		arrayMes[3]  = "April";
		arrayMes[4]  = "May";
		arrayMes[5]  = "June";
		arrayMes[6]  = "July";
		arrayMes[7]  = "August";
		arrayMes[8]  = "September";
		arrayMes[9]  = "October";
		arrayMes[10] = "November";
		arrayMes[11] = "December";
		function getMesExtenso(mes)
		{
			return arrayMes[mes];
		}
		/********************************/
		if(fila1 == fila2)
		{
			if(data1 == data2)//datas iguais pintar o mesmo quadro
			{
				if(data1 >= dataA)
				{
					if(cor!="" && cor != "#FFFFFF")
					{//pintar os quadros (dias)
						divHtml = '<div class="tope" style="border-style:solid; border-color: '+cor+' transparent transparent; border-width: 43px 43px 0px 0px; position:relative;"><div class="txt_sigla">'+sigla+'</div></div><div class="boto" style="border-style:solid; border-color: transparent transparent #FFFFFF; border-width: 0px 0px 43px 43px;"></div>'
						$('#'+fila1).children('#'+data1).attr("tarefa",tarefa);//adicionar tarefa
						$('#'+fila1).children('#'+data1).attr("hora2","18:00");//adicionar hora
						$('#'+fila1).children('#'+data1).removeAttr("detalhe");//remover o detalhe
						$('#'+fila1).children('#'+data1).html(divHtml);
					}
					else
					{	//limpar os quadros (dias)
						Cyear  = data1.substring(0,4);//ANO
						Cmonth = parseInt(data1.substring(5,7))-1;//MES
						Cday   = data1.substring(8,10);//DIA
						Cmonth = getMesExtenso(Cmonth);//MES
						Ndate  = new Date(Cmonth+" "+Cday+", "+Cyear+" 01:15:00");
						Semana = Ndate.getDay();//DIA DA SEMANA
						if(Semana == 0)//DOMINGO
						{	//alert("Domingo");
							$('#'+fila1).children('#'+data1).attr("style","background:#CCCCCC;");
						}
						else if(Semana == 6)//SABADO
						{	//alert("Sabado");
							$('#'+fila1).children('#'+data1).attr("style","background:#CCCCCC;");
						}
						$('#'+fila1).children('#'+data1).attr("tarefa",tarefa);
						$('#'+fila1).children('#'+data1).html('&nbsp;');
					}
				}
				else
				{
					//alertify.alert('Marcação a partir da Data Atual');
				}
			}
			else
			{
				if(data1 < data2)
				{
					menor = data1;
					maior = data2;
				}
				else if(data2 < data1)
				{
					menor = data2;
					maior = data1;
				}
				if(menor <  dataA)//menor sempre >= atual 
				{
					menor = dataA;
					//alertify.alert('Marcação a partir da Data Atual');
				}
				prox = $('#'+menor).attr('id');
				while(prox <= maior)
				{
					if(cor!="" && cor != "#FFFFFF")
					{
					$('#'+fila1).children('#'+prox).attr("tarefa",tarefa);//adicionar tarefa
					if(prox == maior)
					{
						$('#'+fila1).children('#'+prox).attr("hora2","18:00");//adicionar hora
						$('#'+fila1).children('#'+prox).removeAttr("detalhe");//remover detalhe
					}
					divHtml = '<div class="tope" style="border-style:solid; border-color: '+cor+' transparent transparent; border-width: 43px 43px 0px 0px; position:relative;"><div class="txt_sigla">'+sigla+'</div></div><div class="boto" style="border-style:solid; border-color: transparent transparent #FFFFFF; border-width: 0px 0px 43px 43px;"></div>'
					$('#'+fila1).children('#'+prox).html(divHtml);
					}
					else
					{
						Cyear  = prox.substring(0,4);//ANOS
						Cmonth = parseInt(prox.substring(5,7))-1;//MES
						Cday   = prox.substring(8,10);//DIA
						Cmonth = getMesExtenso(Cmonth);//MES
						Ndate  = new Date(Cmonth+" "+Cday+", "+Cyear+" 01:15:00");
						Semana = Ndate.getDay();//DIA DA SEMANA
						if(Semana == 0)//DOMINGO
						{	//alert("Domingo");
							$('#'+fila1).children('#'+prox).attr("style","background:#CCCCCC;");
						}
						else if(Semana == 6)//SABADO
						{	//alert("Sabado");
							$('#'+fila1).children('#'+prox).attr("style","background:#CCCCCC;");
						}
						$('#'+fila1).children('#'+prox).attr("tarefa",tarefa);
						$('#'+fila1).children('#'+prox).html('&nbsp;');
					}
					prox = $('#'+prox).next().attr('id');
				}
			}
		}
		else
		{
			alertify.alert("Sele&ccedil;&atilde;o nao permitida!");
		}
	};
	/**************PINTAR*****************/
	$.pintarStatus = function(fila1,data1,fila2,data2,cor,tarefa)
	{
		if(fila1 == fila2)
		{
			if(cor!="")
			{	
				//pintar o quadro (realizado)
				$('#'+fila1).children('#'+data1).attr("idstatus",tarefa);
				$('#'+fila1).children('#'+data1).children('.boto').css('border-color','transparent transparent '+cor);
			}
		}
	}
	/**************Ordenação de nomes ul li*****************/
	$.orderNome = function(mylist)
	{
		var listitems = mylist.children('li').get();
		listitems.sort(function(a, b) 
		{
	   		var compA = $(a).text().toUpperCase();
	   		var compB = $(b).text().toUpperCase();
	   		return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
		})
		$.each(listitems, function(idx, itm){ 
			mylist.append(itm);
		});
	}
	/***********************************************/
	$.nl2br = function(varTest)
	{
		return varTest.replace(/(\r\n|\n\r|\r|\n)/g, "<br>");
	};
	/************************************************/
	$.DadosAlerta = function(id_alerta,objeto,listUser)
	{
		if(id_alerta > 0)
		{
			/**************procurar obs*****************/
			dataString = 'id_alerta='+id_alerta+'&op=DadosAlert';
			$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					result = (eval(html));//transformar vetor
					$('#dataAlerta').val(result[0]);//atualizar data
					Ahora = result[1].split(':');
					$('#hora_alerta').val(Ahora[0]);//atualizar hora
					$('#minuto_alerta').val(Ahora[1]);//atualizar minuto
					$('#obsAlerta').val(result[2]);//atualizar obs
					logins = result[3].split(';');
					listUser.html(result[4]);//listar usuario para alterar <li>
					$('#para_ale').val(result[5]);//atualizar para
					$(".loginUsuario").attr("checked",false);
					$(".loginUsuario").each(function(index, element)
					{
						obs = "nao";
						idlogin = $(this).val();
						$.each(logins, function(key,value) 
						{
							if(value == idlogin)
							{
								obs = "sim";
								return false;
							}
						});
						if(obs == "sim") $(this).attr("checked",true);
					});
				}
			});
			objeto.parent().parent().children().attr("style","color:#FF0000;");//linha vermelha para editar
			objeto.parent().parent().nextAll().children().removeAttr("style","color:#FF0000;");//linhas anteriores tirar vermelha
			objeto.parent().parent().prevAll().children().removeAttr("style","color:#FF0000;");//linhas posteriores tirar vermelha
			$('#salvar_alerta').hide();
			$('#editar_alerta').show();
			$('#editar_alerta').attr("id_alerta",id_alerta);
			$('.tabelaDados tr').each(function(index, element){
				if(index>0)
				{
					rowData = $(this).children().first().next().next().next().next().next().next().children().attr('id');
					if(rowData.length == 0)
					{
						$(this).remove();//apagar registro novo inconcluso
					}
				}
			});
		}
	}
	/************click presiona***********/
	$('td').live("mousedown",function(e){	
		var fila1 = $(this).parent().attr('id');
		var data1 = $(this).attr('id');
		switch (e.which) {
        case 1: //click left
			$('#fila1').val(fila1);
			$('#data1').val(data1);
            break;
        case 2: //alert('Middle mouse button pressed');
            break;
        case 3://alert('Right mouse button pressed');
            $('#fila1').val(fila1);
			$('#data1').val(data1);
			break;
        default:
			$('#fila1').val(fila1);
			$('#data1').val(data1);
			break;
    	}
		//$('#myObs').fadeOut();//esconder alterar hora
	});
	/************click soltar***********/
    $('td').live("mouseup",function(e){
		mouseX       = e.pageX-280; 
		mouseY       = e.pageY;
		$('#intX').val(e.pageX);
		$('#intY').val(e.pageY);
		fila1        = $('#fila1').val();
		data1        = $('#data1').val();
		fila2        = $(this).parent().attr('id');
		data2        = $(this).attr('id');
		tarefa 	     = $(this).attr("tarefa")
		tipo         = $('#tipo').val();
		fila_row     = fila1.substring(6);//pegar numero da fila
		TarefaId     = $('#left_'+fila_row).children().next().children().next().attr("id_tarefa");
		id_item      = $('#id_item').val();
		id_subitem   = $('#id_subitem').val();
		task         = $('#left_'+fila_row).children().next().children().next().attr("task");
		idsub_item   = $('#left_'+fila_row).children().children().val();
		tipo_arquivo = $('#left_'+fila_row).children().children().attr("gerar_arquivo");
		countpag     = $('#countpag').val();
		id_ctr       = $('#id_ctr').val();
		switch(e.which){
		case 1://click left
			$('#fila2').val(fila2);
			$('#data2').val(data2);
			if(tipo_arquivo == "0")//arquivo não foi gerado
			{
				classe = $(this).children('.tope').children().attr('class');
				dataString = 'idsubitem='+idsub_item+'&op=pegar_status';
				$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",//retornar status_finalizar
				data: dataString,
				cache: false,
				success: function(html)
				{
					estado = html;
					//if($('#finalizar').is(':visible'))
					if(estado == "1")
					{
						if(data2!="0" && data2!="")	
						{
							if(fila1 == fila2)
							{					
								if(classe != "ok_icon")//validar quando clicar na imagem para ver as alertas
								{
									$('#myMenu .contextMenu .liTarefa').remove();
									dataString = 'codigo='+TarefaId+'&task='+task+'&tipo='+tipo+'&id_item='+id_item+'&id_subitem='+id_subitem+'&id_ctr='+id_ctr+'&op=menu';
									$.ajax({
										type: "POST",
										url: "ajax/ajax_menu_tarefas.php",
										data: dataString,
										cache: false,
										success: function(html)
										{
											//$('html, body').animate({scrollLeft:(Number($(window).scrollLeft())+500)+'px'}, 'slow');//rolar tela para direita
											$('#myMenu').children('.contextMenu').append(html);
											$('#myMenu').css({'top':mouseY,'left':mouseX}).fadeIn();//mostrar menu de tarefas
										}
									});
								}
							}
							else $('#myMenu').fadeOut();//ocultar menu de tarefas
						}
						else $('#myMenu').fadeOut();//ocultar menu de tarefas
					}
				}
				});			
			}
			else if(tipo_arquivo == "1")//arquivo gerado
			{
				dataString = 'idsubitem='+idsub_item+'&op=pegar_status';
				$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",//retornar status_finalizar
				data: dataString,
				cache: false,
				success: function(html)
				{
					estado = html;
					//if($('#revisao').is(':visible'))
					if(estado == "1")
					{
						if(tarefa > 0)
						{
							if(data1 == data2) 
							{
								$('#myMenu').css({'top':mouseY,'left':mouseX}).fadeIn();//mostrar menu de tarefas
							}
							else $('#myMenu').fadeOut();//ocultar menu de tarefas
						}
					}
				}
				});
			}
			break;
		case 2://alert('click center');
			break;
		case 3://alert('click right');
			if(!$('#save_realizado').is(':visible'))
			{
				alert('ola mundo')
				$('#myObs').hide();//ocultar alterar hora
				$('#fila2').val(fila2);
				$('#data2').val(data2);
				$('.msg_erro').fadeOut();//ocultar mensagem de erro
				if($(this).attr("detalhe")>0) 
				{
					$('#id_detalhe').val($(this).attr("detalhe"));//id_detalhe pegar
					titulo='Alterar Hor&aacute;rio';//alterar tarefa
					$('#alertas').removeAttr("style","display:none;");
					$('#responsavel').removeAttr("style","display:none;");
				}
				else
				{
					$('#id_detalhe').val(0);//id_detalhe 0
					titulo = 'Ajustar Hor&aacute;rio';//ajustar tarefa
					$('#alertas').attr("style","display:none;");
					$('#responsavel').attr("style","display:none;");
				}
				if($(this).attr("tarefa")>0)
				{
					if($(this).attr("tarefa") != $(this).next().attr("tarefa"))//ultima tarefa
					{	
						$('#aux').val($(this).parent().attr("id")+';'+$(this).attr("id"));//pegar fila e celda
						$('#titulo').html(titulo);
						$('#hora_fim').val($(this).attr("hora2"));//pegar hora fim
						aux1 = $(this).attr("hora2").split(':');
						//alert(aux1[0]+':'+aux1[1]);
						$('#hora_horario').val(aux1[0]);
						$('#minuto_horario').val(aux1[1]);
						$('#myMenuRight').css({'top':mouseY,'left':mouseX,'display':'block !important'}).fadeIn();//mostrar alterar hora
						$('html, body').animate({scrollLeft:(Number($(window).scrollLeft())+500)+'px'}, 'slow');//rolar tela para direita
					}
				}
			}
			break;
		default:
			if($('#finalizar').is(':visible'))
			{
				$('#fila2').val(fila2);
				$('#data2').val(data2);
				if(data2!="0" && data2!="")	
				{
					$('#myMenu').css({'top':mouseY,'left':mouseX}).fadeIn();//mostrar menu tarefas
				}
				else $('#myMenu').fadeOut();//ocultar menu de tarefas
			}
			break;	
		}
	});
	/************duplo click url************************/
	$('td').dblclick(function(e){
		e.stopPropagation();
		idsubitem = $(this).prev().children().val();
		projeto   = $('#id_ctr').val();
		num_ctr   = $('#num_ctr').val();
		tipo      = $('#tipo').val();
		countpag  = $('#countpag').val();
		corFonte  = $(this).children('.nome_label').attr('style');
		if(idsubitem>0)
		{
			//if(parseInt(countpag) <= 1)
			//{
				if(!$(this).children('.text_nome').is(':visible'))//caixa de texto visible
				{
					nome_item  = $(this).children('.descricao_label').html()+' '+$(this).children('.nome_label').html();
					dataString = 'idsubitem='+idsubitem+'&op=pegar_status';
					$.ajax({
					type: "POST",
					url: "ajax/ajax_menu_direito.php",//retornar status_finalizar
					data: dataString,
					cache: false,
					success: function(html)
					{
						estado = html;
						dataString = 'idsubitem='+idsubitem+'&op=detalhe_tarefa';
						$.ajax({
						type: "POST",
						url: "ajax/ajax_subitem_validar.php",//validar se tem filhos o item
						data: dataString,
						cache: false,
						success: function(html)
						{
							//if($('#finalizar').is(':visible'))//botão finalizar
							if(estado == "0")
							{
								if(html > 0)
								{
									if(corFonte == "color:#333333")//não adiciono etapas
									{
										alertify.confirm('Deseja realmente desmembrar o item <b>'+nome_item+'</b> ?', function (e){
										if(e) 
										{							
											var dataString = 'id_ctr='+projeto+'&num_ctr='+num_ctr+'&subitem='+idsubitem+"&tipo="+tipo+'&usuario='+usuario+'&op=desmembrar';
											$.ajax({
												type: "POST",
												url: "ajax/ajax_desmembramento.php",
												data: dataString,
												cache: false,
												success: function(html)
												{
													window.location.href = "index.php?projeto="+projeto+"&item=0&subitem="+idsubitem+"&tipo="+tipo+"&usuario="+usuario;
												}
											});
										}
										});
									}
									else
									{
										var dataString = 'id_ctr='+projeto+'&num_ctr='+num_ctr+'&subitem='+idsubitem+'&countpag='+countpag+'&usuario='+usuario+'&op=desmembrar2';
										$.ajax({
											type: "POST",
											url: "ajax/ajax_desmembramento.php",
											data: dataString,
											cache: false,
											success: function(html)
											{
												window.location.href = "index.php?projeto="+projeto+"&item=0&subitem="+idsubitem+"&tipo="+tipo+"&usuario="+usuario;
											}
										});
									}
								}
								else 
								{
									alertify.alert("Adicionar tarefas e clicar em [Salvar Grafico]");
								}
							}
							else//botão revisão
							{
								if(html > 0)
								{
									if(corFonte == "color:#FF0000")//não adiciono etapas
									{
										var dataString = 'id_ctr='+projeto+'&num_ctr='+num_ctr+'&subitem='+idsubitem+'&usuario='+usuario+'&op=desmembrar2';
										$.ajax({
											type: "POST",
											url: "ajax/ajax_desmembramento.php",
											data: dataString,
											cache: false,
											success: function(html)
											{
												window.location.href = "index.php?projeto="+projeto+"&item=0&subitem="+idsubitem+"&tipo="+tipo+"&usuario="+usuario;
											}
										});
									}
								}
							}
						}
						});
						
					}
					});
				}
			//}
		}
	})
	/************chamar função pintar ***********/
	$('.liTarefa').live("click",function(e){
		var tarefa = $(this).attr('id');
		var cor    = $(this).attr('color');
		var sigla  = $(this).attr('sigla');
		var fila1  = $('#fila1').val();
		var data1  = $('#data1').val();
		var fila2  = $('#fila2').val();
		var data2  = $('#data2').val();
		if(tarefa != "")
		{
			fila_row     = fila1.substring(6);//pegar numero da fila
			idsub_item   = $('#left_'+fila_row).children().children().val();
			tipo_arquivo = $('#left_'+fila_row).children().children().attr("gerar_arquivo");
			
			if(tipo_arquivo == "0")//finalizar	if($('#finalizar').is(':visible'))
			{
				$.pintarCelda(fila1,data1,fila2,data2,cor,tarefa,sigla,dataAtual);
			}
			else if(tipo_arquivo == "1") //revisao	if($('#revisao').is(':visible'))
			{
				$.pintarStatus(fila1,data1,fila2,data2,cor,tarefa)
			}
			/*dataString   = 'idsubitem='+idsub_item+'&op=pegar_status';
			$.ajax({
			type: "POST",
			url: "ajax/ajax_menu_direito.php",//retornar status_finalizar
			data: dataString,
			cache: false,
			success: function(html)
			{
				estado = html;
				if(estado == "1")//finalizar	if($('#finalizar').is(':visible'))
				{
					$.pintarCelda(fila1,data1,fila2,data2,cor,tarefa,sigla,dataAtual);
				}
				else //revisao	if($('#revisao').is(':visible'))
				{
					$.pintarStatus(fila1,data1,fila2,data2,cor,tarefa)
				}
			}
			});*/		
			$('#myMenu').fadeOut();//esconder menu
			$('#SubmyMenu').fadeOut();//esconder submenu
			$('#fila1').val(0);
			$('#data1').val(0);
			$('#fila2').val(0);
			$('#data2').val(0);
			$('#copy').attr("disabled",true);//desabilitar botao duplicar
			$('#copy').addClass('disabled');//adicionar class
		}
	});
	/*******************abrir tela click direito**********************/
	$('.listaMenu').live("click",function(e){
		mouseX     = $('#intX').val()+'px';
		mouseY     = $('#intY').val()+'px';
		id_detalhe = $('#id_detalhe').val();//id_detalhe pegar
		id_ctr 	   = $('#id_ctr').val();
		$('#filaUnica').val($('#fila1').val());
		fila_row   = $('#fila1').val().substring(6);
		id_subitem = $('#left_'+fila_row).children().children().val();
		TarefaId   = $('#left_'+fila_row).children().next().children().next().attr("id_tarefa");
		$('#myMenuRight').fadeOut();//ocultar myMenuRight
		$('.listaObs').hide();//ocultar alertas/horario/responsavel
		$('.msg_erro').hide();//ocultar mensagem de erro
		$('.msg_titulo').empty();//ocultar titulo
		$('#dataAlerta').empty();//limpar as datas 
		$(".loginUsuario").each(function(index, element){//desmarcar todos os logins
			$(this).attr("checked",false);
		});
		$('#hora_alerta').val('--');//zerar hora
		$('#minuto_alerta').val('--');//zerar minuto
		$('#obsAlerta').val('');
		//$('html, body').animate({scrollLeft:(Number($(window).scrollLeft())+500)+'px'}, 'slow');//rolar tela para direita
		if($(this).attr("id") == "alertas")
		{
			$('#salvar_alerta').show();
			$('#editar_alerta').hide();
			//$('#myObs').css({'top':mouseY,'left':mouseX,'display':'block !important','width':'660px'}).fadeIn();//mostrar alterar hora
			$('#myObs').css({'left':'50%','margin-left':'-380px','top':'50%','margin-top':'-250px','width':'840px'}).fadeIn();//mostrar alterar hora
			$("#_"+$(this).attr("id")).fadeIn();
			//$('.tabelaDados .trDados').next().empty();
			$('.tabelaDados .trDados').nextAll().remove();
			//titulo
			dataString = 'id_subitem='+id_subitem+'&id_detalhe='+id_detalhe+'&op=DadosTitulo';
			$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$('.msg_titulo').append(html);
				}
			});
			//listar Datas
			dataString = 'id_detalhe='+id_detalhe+'&op=ListarDatas';
			$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$('#dataAlerta').append(html);
				}
			});
			//listar todas as alertas
			dataString = 'id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&op=ListarAlert';
			$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$('.tabelaDados tbody').append(html);
				}
			});
		}
		else if($(this).attr("id") == "horario")
		{
			mouseX     = $('#intX').val()-280+'px';
			$('#myObs')
			$('#myObs').css({'top':mouseY,'left':mouseX,'margin-left':'0','margin-top':'0','display':'block !important','width':'280px'}).fadeIn();//mostrar alterar hora
			$("#_"+$(this).attr("id")).fadeIn();
			//$("input").focus();//$('#hora_fim').focus();
		}
		else if($(this).attr("id") == "responsavel")
		{
			mouseX     = $('#intX').val()-400+'px';
			$('#valor_resposavel').children().remove();
			$('#myObs').css({'top':mouseY,'left':mouseX,'margin-left':'0','margin-top':'0','display':'block !important','width':'400px'}).fadeIn();//mostrar alterar hora
			$("#_"+$(this).attr("id")).fadeIn();
			dataString = 'id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&TarefaId='+TarefaId+'&op=ListarResponsavel';
			$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$('#valor_resposavel').append(html);
				}
			});
		}
	});
	/*********************salvar alerta inclução**************************/
	$('#salvar_alerta').live("click",function(e)
	{
		fila1		 = $('#fila1').val();
		dataAlerta   = $('#dataAlerta').val();
		dataTD		 = dataAlerta.substr(6,4)+'-'+dataAlerta.substr(3,2)+'-'+dataAlerta.substr(0,2);
		horaAlerta   = $('#hora_alerta').val();
		minutoAlerta = $('#minuto_alerta').val();
		HoraMinuto   = $('#hora_alerta').val()+':'+$('#minuto_alerta').val();
		obsAlerta	 = $('#obsAlerta').val();
		id_ctr 	     = $('#id_ctr').val();
		id_detalhe   = $('#id_detalhe').val();
		para_ale     = $('#para_ale').val();
		ListaLogin   = "";
		if(dataAlerta != "--" && dataAlerta != "")
		{
			if(horaAlerta != "--" && horaAlerta != "")
			{
				if(minutoAlerta != "--" && minutoAlerta != "")
				{
					if(para_ale != 0)
					{
						$('.msg_erro').hide();//ocultar mensagem de erro
						$(".loginUsuario").each(function(index, element) {
							if($(this).is(':checked'))
							{
								ListaLogin = ListaLogin+';'+$(this).val();
							}
						});
						if (ListaLogin.length > 0)
						{
							if(obsAlerta != "" && obsAlerta != " ")
							{
								var dataString = 'ListaLogin='+ListaLogin+'&dataAlerta='+dataAlerta+'&horaAlerta='+HoraMinuto+'&id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&obsAlerta='+obsAlerta+'&para_ale='+para_ale+'&op=SalvarAlerta';
								$.ajax({
								type: "POST",
								url: "ajax/ajax_menu_direito.php",
								data: dataString,
								cache: false,
								success: function(html)
								{
									$('#dataAlerta').val('--');
									$('#hora_alerta').val('--');
									$('#minuto_alerta').val('--');
									$('#obsAlerta').val('');
									$('#para_ale').val('0');
									$(".loginUsuario").each(function(index, element){
										$(this).attr("checked",false);
									});
									$('.tabelaDados .trDados').nextAll().remove();
									$('.tabelaDados tbody').append(html);
									//contar alertas
									dataString = 'id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&dataDT='+dataTD+'&op=CountAlert';
									$.ajax({
										type: "POST",
										url: "ajax/ajax_menu_direito.php",
										data: dataString,
										cache: false,
										success: function(html)
										{
											if(html==1)
											{
												$('#'+fila1).children('#'+dataTD).children('.tope').prepend('<img src="images/Ok-icon2.png" width="18" id="alertas_view" class="ok_icon"/>');
											}
										}
									});
								}
								});
							}
							else 
							{
								$('#obsAlerta').focus();
								alertify.alert("Deve preencher o campo Observa&ccedil;&atilde;o");
							}
						}
						else 
						{
							alertify.alert("Deve Selecionar um Usu&aacute;rio Com Copia");
						}
					}
					else 
					{
						$('#para_ale').focus();
						alertify.alert("Deve escolher PARA!");
					}
				}
				else 
				{
					$('#minuto_alerta').focus();
					alertify.alert("Deve escolher os MINUTOS!");
				}
			}	
			else 
			{
				$('#horaAlerta').focus();
				alertify.alert("Deve escolher uma HORA!");
			}
		}
		else 
		{
			$('#dataAlerta').focus();
			alertify.alert("Deve escolher uma DATA!");	
		}
	});
	/*******************editar alerta alterar****************************/
	$('#editar_alerta').live("click",function(e)
	{
		id_alerta    = $(this).attr("id_alerta");
		fila1		 = $('#fila1').val();
		dataAlerta   = $('#dataAlerta').val();
		dataTD		 = dataAlerta.substr(6,4)+'-'+dataAlerta.substr(3,2)+'-'+dataAlerta.substr(0,2);
		horaAlerta   = $('#hora_alerta').val();
		minutoAlerta = $('#minuto_alerta').val();
		HoraMinuto   = $('#hora_alerta').val()+':'+$('#minuto_alerta').val();
		obsAlerta	 = $('#obsAlerta').val();
		id_ctr 	     = $('#id_ctr').val();
		id_detalhe   = $('#id_detalhe').val();
		para_ale     = $('#para_ale').val();
		ListaLogin   = "";
		if(dataAlerta != "--" && dataAlerta != "")
		{
			if(horaAlerta != "--" && horaAlerta != "")
			{
				if(minutoAlerta != "--" && minutoAlerta != "")
				{
					if(para_ale != 0)
					{
						$('.msg_erro').hide();//ocultar mensagem de erro
						$(".loginUsuario").each(function(index, element) {
							if($(this).is(':checked'))
							{
								ListaLogin = ListaLogin+';'+$(this).val();
							}
						});
						if (ListaLogin.length > 0)
						{
							if(obsAlerta != "" && obsAlerta != " ")
							{
								var dataString = 'ListaLogin='+ListaLogin+'&dataAlerta='+dataAlerta+'&horaAlerta='+HoraMinuto+'&id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&obsAlerta='+obsAlerta+'&id_alerta='+id_alerta+'&para_ale='+para_ale+'&op=EditarAlerta';
								$.ajax({
								type: "POST",
								url: "ajax/ajax_menu_direito.php",
								data: dataString,
								cache: false,
								success: function(html)
								{
									$('#dataAlerta').val('--');
									$('#hora_alerta').val('--');
									$('#minuto_alerta').val('--');
									$('#obsAlerta').val('');
									$('#para_ale').val('0');
									$(".loginUsuario").each(function(index, element){
										$(this).attr("checked",false);
									});
									$('.tabelaDados .trDados').nextAll().remove();
									$('.tabelaDados tbody').append(html);
									$('#salvar_alerta').show();
									$('#editar_alerta').hide();
									//contar alertas
									$('#'+fila1).children().each(function(index, element){
										if($(this).attr("detalhe")==id_detalhe)
										{
											$('#'+fila1).children().children('.tope').children('.ok_icon').remove();								
										}
									});
									dataString = 'id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&dataDT='+dataTD+'&op=AlertDatas';
									$.ajax({
										type: "POST",
										url: "ajax/ajax_menu_direito.php",
										data: dataString,
										cache: false,
										success: function(html)
										{
											result = (eval(html));//transformar vetor
											$.each(result, function(key, value) 
											{
												$('#'+fila1).children('#'+value).children('.tope').append('<img src="images/Ok-icon2.png" width="18" id="alertas_view" class="ok_icon"/>');
											});
										}
									});
								}
								});
							}
							else 
							{
								$('#obsAlerta').focus();
								alertify.alert("Deve preencher o campo Observa&ccedil;&atilde;o");
							}
						}
						else 
						{
							alertify.alert("Deve Selecionar um Usu&aacute;rio");
						}
					}
					else
					{
						$('#para_ale').focus();
						alertify.alert("Deve escolher PARA!");
					}
				}
				else 
				{
					$('#minuto_alerta').focus();
					alertify.alert("Deve escolher os MINUTOS!");
				}
			}	
			else 
			{
				$('#horaAlerta').focus();
				alertify.alert("Deve escolher uma HORA!");
			}
		}
		else 
		{
			$('#dataAlerta').focus();
			alertify.alert("Deve escolher uma DATA!");	
		}		
	});
	/************adicionar registro***********/
	$('#add').click(function(e){//adicionar subitens
		var fila = 0;
		$('#tabelaLeft tr').each(function(index, element){
            fila = $(this).attr("id");
        });
		fila = parseInt(fila.substring(5))+1;
		if(fila>0) fila=fila;
		else fila=1;
		var id_ctr     = $('#id_ctr').val();
		var id_item    = $('#id_item').val();
		var id_subitem = $('#id_subitem').val();
		var num_ctr    = $('#num_ctr').val();
		var countpag   = $('#countpag').val();
		var dataString = 'id_ctr='+id_ctr+'&id_item='+id_item+'&id_subitem='+id_subitem+'&num_ctr='+num_ctr+'&countpag='+countpag+'&usuario='+usuario+'&op=left';
		$.ajax({
			type: "POST",
			url: "ajax/ajax_insert.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
				if((fila%2)==0) addclase = 'class="yellow"';
				else addclase = '';
				$('#tabelaLeft').append('<tr id="left_'+fila+'" '+addclase+' ></tr>');
				$('#left_'+fila).html(html);
				$('#left_'+fila).children().next().children("input[type=text]").focus();//ficar caixa de texto
			}
		});
		var dataString = 'id_ctr='+id_ctr+'&id_item='+id_item+'&id_subitem='+id_subitem+'&num_ctr='+num_ctr+'&usuario='+usuario+'&op=right';
		$.ajax({
			type: "POST",
			url: "ajax/ajax_insert.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
				if((fila%2)==0) addclase = 'class="yellow"';
				else addclase = '';
				$('#tabelaRight').append('<tr id="right_'+fila+'" '+addclase+' ></tr>');
				$('#right_'+fila).html(html);
				//$('#right_'+fila).children().next().children("input[type=text]").focus();//ficar caixa de texto
			}
		});
	});
	//************duplicar registro***********/
	$('#copy').click(function(e){
		var codigos = [];
		var fila = 0;
		$('.codigo').each(function(index, element) {
            if($(this).is(':checked'))
			{
				codigos[fila] = $(this).val();
				fila++;
			}
        });
		if(fila > 0)
		{
			$('#msn_save').removeAttr("style","display:none;");//mostrar mensagem de aguarde...
			$.ajax({
			type: "POST",
			url: "ajax/ajax_duplicar_tarefas.php",
			data: {VetorSubItem:codigos},
			cache: false,
			success: function(html)
			{
				$("#msn_save").fadeOut();
				$("#copy").removeAttr("disabled",true);//habilitar botao duplicar
				$("#AllCodigo").attr("checked",false);//desmarcar checkbox
				$(".codigo").attr("checked",false);//desmarcar checkbox
				location.reload();
  			}
 			});
		}
		else 
		{
			alertify.alert('Deve selecionar um Registro');
		}
	});
	/*************deletar registro***********/
	$('#dele').click(function(e){//deletar subitens
		var codigos = [];
		var fila = 0;
		$('.codigo').each(function(index, element) {
            if($(this).is(':checked'))
			{
				codigos[fila] = $(this).val();
				fila++;
			}
        });
		if(fila > 0) 
		{
			alertify.confirm('Deseja realmente deletar o(s) Registro(s)?', function (e){
			if(e) 
			{
				idcodigo = "";
				vetorCodigo = "";
				//$('#msn_save').removeAttr("style","display:none;");//mostrar mensagem aguarde...
				$.ajax({
				type: "POST",
				url: "ajax/ajax_delete.php",
				data: {data:codigos},
				cache: false,
				success: function(html)
				{
					if(html != "")
					{
						vetorCodigo = html.split(',')
						for(var t=0; t<vetorCodigo.length; t++)
						{
							if(vetorCodigo[t]>0)
							{
								idcodigo = idcodigo+' <b>'+$('#'+vetorCodigo[t]+'nome').val()+'</b><br/>';
							}
						}
					}
					if(vetorCodigo.length > 0)
					{
						alertify.confirm('Impossivel remover o(s) iten(s) por conter subitens:<br/>'+idcodigo+'Deseja realmente deletar o(s) Registro(s)?', function (e){
						if(e)
						{
							$.ajax({
							type: "POST",
							url: "ajax/ajax_delete_all.php",
							data: {data1:vetorCodigo},
							cache: false,
							success: function(html)
							{
								$.pleasewait();
								$("#AllCodigo").attr("checked",false);//desmarcar checkbox
								$(".codigo").attr("checked",false);//desmarcar checkbox
								$("#msn_save").fadeOut();
								location.reload();
							}
							});
						}
						else
						{
							$.pleasewait();
							$("#AllCodigo").attr("checked",false);//desmarcar checkbox
							$(".codigo").attr("checked",false);//desmarcar checkbox
							$("#msn_save").fadeOut();
							location.reload();
						}
						});
					}
					else
					{
						$.pleasewait();
						$("#AllCodigo").attr("checked",false);//desmarcar checkbox
						$(".codigo").attr("checked",false);//desmarcar checkbox
						$("#msn_save").fadeOut();
						location.reload();
					}
				}
				});
			}
			}); 
		}
		else 
		{
			alertify.alert('Deve selecionar um Registro');
		}
	});
	/**************alterar alerta**********************/
	$('.alterar_alerta').live("click",function(e){
		fila1     = $('#filaUnica').val();
		$('#fila1').val(fila1);
		objeto    = $(this);
		id_alerta = $(this).attr("id");
		listUser  = $(this).parent().parent().children().first().next().next().next();//posição tabela com copia
		rpt = 1;
		if($('#dataAlerta').val() != "--")
		{
			alertify.confirm('Deseja salvar as altera&ccedil;&otilde;es da alerta?', function (e){
				if(e) 
				{
					if($('#salvar_alerta').is(':visible'))
					{
						$('#salvar_alerta').click();
					}
					else
					{
						$('#editar_alerta').click();
					}
				}
				else
				{
					alertify.confirm('Vai peder os dados da alerta?<br/> Deseja salvar as altera&ccedil;&otilde;es da alerta?', function (e){
						if(e)
						{
							if($('#salvar_alerta').is(':visible'))
							{
								$('#salvar_alerta').click();
							}
							else
							{
								$('#editar_alerta').click();
							}
						}
						else
						{
							$.DadosAlerta(id_alerta,objeto,listUser)
						}
					});
				}
			});
		}
		else
		{
			$.DadosAlerta(id_alerta,objeto,listUser)
		}
	})	
	/*************apagar alertas***********************/
	$('.apagar_alerta').live("click",function(e){
		id_alerta  = $(this).attr("id");
		dataTD     = $(this).attr('data_alerta');
		tr_fila    = $(this).parent().parent();
		id_ctr 	   = $('#id_ctr').val();
		id_detalhe = $('#id_detalhe').val();
		fila1      = $('#filaUnica').val();
		//alert(dataTD+' '+filaUnica);
		if(id_alerta > 0)
		{
			alertify.confirm('Deseja realmente deletar o Registro?', function (e){
			if(e) 
			{
				var dataString = 'id_alerta='+id_alerta+'&id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&op=ApagarAlerta';
				$.ajax({
					type: "POST",
					url: "ajax/ajax_menu_direito.php",
					data: dataString,
					cache: false,
					success: function(html)
					{
						$('.tabelaDados .trDados').nextAll().remove();
						$('.tabelaDados tbody').append(html);
						//contar alertas
						dataString = 'id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&dataDT='+dataTD+'&op=CountAlert';
						$.ajax({
							type: "POST",
							url: "ajax/ajax_menu_direito.php",
							data: dataString,
							cache: false,
							success: function(html)
							{
								if(html==0)
								{
									$('#'+fila1).children('#'+dataTD).children('.tope').children('.ok_icon').remove();
								}
							}
						});
					}
				});
			}
			});
		}
	});
	/************selecionar registros***********/
	$('.codigo').live("click",function(e){//selecionar filas pintar amarelo
		$('#myMenu').fadeOut();//ocultar menu de tarefas
		countpag = $('#countpag').val();
		//finalizar e revisão
		id = $(this).parent().parent().attr("id").substring(5);
		if($(this).is(':checked'))
		{
			$(this).parent().parent().attr("style","background:#FFFF80;");
			$('#right_'+id).attr("style","background:#FFFF80;");
		}
		else
		{
			$(this).parent().parent().removeAttr("style","background:#FFFF80;");
			$('#right_'+id).removeAttr("style","background:#FFFF80;");
		}
		var codigos = [];
		var fila    = 0;
		var1        = "";
		$('.codigo').each(function(index, element) {
            if($(this).is(':checked'))
			{
				codigos[fila] = $(this).val();
				fila++;
			}
        });
		if(fila > 0) 
		{
			$.ajax({
			type: "POST",
			url: "ajax/ajax_menu_direito.php",
			data: {data:codigos,op:'fim_rev'},
			cache: false,
			success: function(html)
			{
				if(html == "false")//sem tarefas
				{
					$('#gerar_arquivo').attr("disabled",true);
					$('#gerar_arquivo').addClass("disabled");
					alertify.alert("Adicionar tarefas e clicar em [Salvar Grafico]");
				}
				else if(html == "liberar")//mostrar botao GERAR ARQUIVO
				{
					$('#gerar_arquivo').removeAttr("disabled");
					$('#gerar_arquivo').removeClass("disabled");
				}
			}
			});
		}
		else
		{
			$('#gerar_arquivo').attr("disabled",true);
			$('#gerar_arquivo').addClass("disabled");
		}
	});
	/************editar subitem***********/
	$('.edit').live("dblclick",function(e){
		e.stopPropagation();
	});
	$('.edit').live("click",function(e){//editar o subitem
		$('#myMenu').fadeOut();//ocultar menu tarefas
		$(this).attr("style","display:none");//ocultar botao editar
		$(this).prev().removeAttr("style","display:none");//mostrar botao salvar
		$(this).prev().prev().prev().attr("style","display:none");//ocultar descricao
		$(this).parent().children('.set_responsa').attr("style","display:none");//ocultar botao R(resposanvel)
		var valor = $(this).parent().prev().children().val();//pegar id subitem
		$('#'+valor+'nome').removeAttr("style","display:none");//mostrar campos text
		$('#'+valor+'nome').focus();//focar caixa de texto
	});
	/************salvar subitem***********/
	$('.save').live("dblclick",function(e){
		e.stopPropagation();
	});
	$('.save').live("click",function(e){//salvar o subitem
		$('#myMenu').fadeOut();//ocultar menu de tarefas
		valor = $(this).parent().prev().children().val();//pegar id subitem
		//id_tr = $(this).parent().parent().attr("id")
		nome  = $('#'+valor+'nome').val();//pegar valor nome
		/*if(nome != "")//if(nome != "" || id_tr == 'left_1')
		{*/
			$(this).attr("style","display:none");//ocultar botao salvar
			$(this).next().removeAttr("style","display:none");//mostrar botao editar
			$('#'+valor+'nome').attr("style","display:none");//ocultar campo text
			$(this).prev().prev().removeAttr("style","display:none");//mostra descricao
			//$(this).parent().children('.set_responsa').removeAttr("style","display:none");//mostra botao R(resposanvel)
			$(this).prev().prev().html(nome);//colocar na div novo nome
			var dataString = 'valor='+valor+'&nome='+nome+'&usuario='+usuario;
			$.ajax({
			type: "POST",
			url: "ajax/ajax_edit.php",
			data: dataString,
			cache: false,
			success: function(html){}
			});
		/*}
		else 
		{
			$('#'+valor+'nome').focus();//focar caixa de texto
			alertify.alert('Deve preencher campo');		
		}*/
	});
	/************salvar subitem***********///$("input[type=text]").live("keypress",function(e){//salvar presionando enter
	$(".text_nome").live("keypress",function(e){
		if(e.which == 13){
			$('#myMenu').fadeOut();//ocultar menu de tarefas
			var valor = $(this).parent().prev().children().val();//pegar id subitem
			var nome = $('#'+valor+'nome').val();//pegar valor nome
			$(this).next().html(nome);
			if(nome != "")
			{	
				$(this).next().attr("style","display:none");//ocultar botao salvar
				$(this).next().next().removeAttr("style","display:none");//mostrar botao editar*/
				$(this).attr("style","display:none");//ocultar campo text
				$(this).prev().removeAttr("style","display:none");//mostra descricao
				//$(this).next().next().next().next().removeAttr("style","display:none");//mostra botao R(resposanvel)
				$(this).prev().html(nome);//colocar na div novo nome
				var dataString = 'valor='+valor+'&nome='+nome+'&usuario='+usuario;
				$.ajax({
				type: "POST",
				url: "ajax/ajax_edit.php",
				data: dataString,
				cache: false,
				success: function(html){}
				});
			}
			else 
			{
				$('#'+valor+'nome').focus();//focar caixa de texto
				alertify.alert('Deve preencher campo');		
			}
	  	}
	});
	/************responsavel sub ITEM********/
	$('.set_responsa').live("click",function(e){
		var mouseX = e.pageX+15;
		var mouseY = e.pageY+15;
		id_ctr 	   = $('#id_ctr').val();
		id_subitem = $(this).parent().parent().children().children('.codigo').val();
		task       = $(this).prev().prev().prev().prev().prev().attr('task');
		id_item    = '';
		$('#lista_resposavel').children().remove();
		$('#myResponsa').css({'top':mouseY,'left':mouseX}).fadeIn();//mostrar responsavel FASE, ETAPA E ITEM
		dataString = 'id_ctr='+id_ctr+'&id_subitem='+id_subitem+'&id_item='+id_item+'&task='+task+'&op=ListaResponsa';
		$.ajax({
			type: "POST",
			url: "ajax/ajax_menu_direito.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
				$('#lista_resposavel').append(html);
			}
		});
	});
	/************fechar menus***********/
	$(".closeMenu").click(function(e){
		$('#myMenu').fadeOut();//ocultar menu de tarefas
		$('#myMenuRight').fadeOut();//ocultar alterar hora
		$('#myResponsa').fadeOut();//ocultar responsavel
		if($('#dataAlerta').is(':visible'))
		{
			if($('#dataAlerta').val() != "--")
			{
				alertify.confirm('Deseja salvar as altera&ccedil;&otilde;es da alerta?', function (e){
					if(e) 
					{
						if($('#salvar_alerta').is(':visible'))
						{
							$('#salvar_alerta').click();
						}
						else
						{
							$('#editar_alerta').click();
						}
					}
					else
					{
						alertify.confirm('Vai peder os dados da alerta?<br/> Deseja salvar as altera&ccedil;&otilde;es da alerta?', function (e){
							if(e)
							{
								if($('#salvar_alerta').is(':visible'))
								{
									$('#salvar_alerta').click();
								}
								else
								{
									$('#editar_alerta').click();
								}
							}
							else
							{
								$('#myObs').fadeOut();//ocultar alterar hora
							}
						});
					}
				});
			}
			else
			{
				$('#myObs').fadeOut();//ocultar alterar hora
			}
		}
		else $('#myObs').fadeOut();//ocultar alterar hora
	});
	/************marcar desmarcar registros***********/
	$.MarcarDesmarcar = function()//função para marcar e desmarcar os subitens
	{
		countpag = $('#countpag').val();
		if($("#AllCodigo").attr("checked"))
		{
			var codigos = [];
			var fila = 0;
			var1 = "";
			$('.codigo').each(function(index, element){
            	$(this).attr("checked",true);
				$(this).parent().parent().attr("style","background:#FFFF80;");
				id = $(this).parent().parent().attr("id").substring(5);
				$('#right_'+id).attr("style","background:#FFFF80;");
				//
				codigos[fila] = $(this).val();
				fila++;
            });
			if(fila > 0)
			{
				$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",
				data: {data:codigos,op:'fim_rev'},
				cache: false,
				success: function(html)
				{
					if(html == "false")//sem tarefas
					{
						$('#gerar_arquivo').attr("disabled",true);
						$('#gerar_arquivo').addClass("disabled");
						alertify.alert("Adicionar tarefas e clicar em [Salvar Grafico]");
					}
					else if(html == "liberar")//mostrar botao GERAR ARQUIVO
					{
						$('#gerar_arquivo').removeAttr("disabled");
						$('#gerar_arquivo').removeClass("disabled");
					}
				}
				});
			}
	   	}
	   	else
	   	{
			$('.codigo').each(function(index, element){
				$(this).attr("checked",false);
				$(this).parent().parent().removeAttr("style","background:#FFFF80;");
				id = $(this).parent().parent().attr("id").substring(5);
				$('#right_'+id).removeAttr("style","background:#FFFF80;");
			});
			$('#gerar_arquivo').attr("disabled",true);
			$('#gerar_arquivo').addClass("disabled");
	   	}		
	}
	/**********adicionar data para alerta*****/
	$("#dataAlerta").change(function(){
		dataAlerta = $(this).val();
		horaAlerta = "";
		minuAlerta = "";
		paraAlerta = "";
		login1     = "";
		if(dataAlerta != "--" && dataAlerta != "")
		{
			if($('#hora_alerta').val() != "--")   horaAlerta = $('#hora_alerta').val();
			if($('#minuto_alerta').val() != "--") minuAlerta = $('#minuto_alerta').val();
			if($('#para_ale').val() != 0)
			{
				login1     = $('#para_ale').val();
				paraAlerta = $("#para_ale option[value='"+login1+"']").text();
			}
			if($('#salvar_alerta').is(':visible'))//salvar alerta
			{
				$rowData = $('.tabelaDados tr:last-child').children().html();
				if($rowData == "DATA")
				{
					$('.tabelaDados').append("<tr style='background:#DDDDDD;'>"+
					"<td valign='top' style='color:#FF0000;' align='center'>"+dataAlerta+"</td>"+
					"<td valign='top' style='color:#FF0000;' align='center'>"+horaAlerta+":"+minuAlerta+"</td>"+
					"<td valign='top' style='color:#FF0000;'>"+paraAlerta+"</td>"+
					"<td valign='top' style='color:#FF0000;'><ul id='selecUsuario'></ul></td>"+
					"<td valign='top' style='color:#FF0000;'></td>"+
					"<td valign='top' style='color:#FF0000;'></td>"+
					"<td valign='top' style='color:#FF0000;' align='center'>"+
					"<input type='image' src='images/edit.png' width='20' title='Editar' class='alterar_alerta'/>"+
					"<input type='image' src='images/delete.png' width='20' title='Apagar' class='apagar_alerta'/>"+
					"</td></tr>");
				}
				else
				{
					$rowData = $('.tabelaDados tr:last-child').children().attr('style');
					if($rowData == "color:#FF0000;")
					{
						$('.tabelaDados tr:last-child td:first-child').html(dataAlerta);
						$('.tabelaDados tr:last-child td:nth-last-child(6)').html(horaAlerta+":"+minuAlerta);
						$('.tabelaDados tr:last-child td:nth-last-child(5)').html(paraAlerta);
					}
					else
					{
						$('.tabelaDados').append("<tr style='background:#DDDDDD;'>"+
						"<td valign='top' style='color:#FF0000;' align='center'>"+dataAlerta+"</td>"+
						"<td valign='top' style='color:#FF0000;' align='center'>"+horaAlerta+":"+minuAlerta+"</td>"+
						"<td valign='top' style='color:#FF0000;'>"+paraAlerta+"</td>"+
						"<td valign='top' style='color:#FF0000;'><ul id='selecUsuario'></ul></td>"+
						"<td valign='top' style='color:#FF0000;'></td>"+
						"<td valign='top' style='color:#FF0000;'></td>"+
						"<td valign='top' style='color:#FF0000;' align='center'>"+
						"<input type='image' src='images/edit.png' width='20' title='Editar' class='alterar_alerta'/>"+
						"<input type='image' src='images/delete.png' width='20' title='Apagar' class='apagar_alerta'/>"+
						"</td></tr>");
					}
				}
			}
			else//editar alerta
			{
				$('.tabelaDados tr').each(function(index, element){
					rowData = $(this).children().attr('style');
					if(rowData == "color:#FF0000;")
					{
						$(this).children().first().html(dataAlerta);
						$(this).children().first().next().html(horaAlerta+":"+minuAlerta);
					}
				});
			}
		}
		else
		{
			$('.tabelaDados tr:last-child td:nth-last-child(5)').html('');
			$("#dataAlerta").focus();
			alertify.alert('Deve escolher uma Data');
		}
	});
	/***************adicionar hora para alerta********************/
	$('#hora_alerta').change(function(){
		dataAlerta = $("#dataAlerta").val();
		horaAlerta = $(this).val();
		minuAlerta = "";
		if(horaAlerta!="")
		{
			if($('#minuto_alerta').val() != "--") minuAlerta = $('#minuto_alerta').val();
			$rowData = $('.tabelaDados tr:last-child').children().attr('style');
			if($('#salvar_alerta').is(':visible'))//salvar alerta
			{
				if($rowData == "color:#FF0000;" && dataAlerta != "--" && dataAlerta != "")
				{
					$('.tabelaDados tr:last-child td:nth-last-child(6)').html(horaAlerta+":"+minuAlerta);
				}
				else
				{
					$('#hora_alerta').val('--');
					$("#dataAlerta").focus();
					alertify.alert('Deve escolher uma Data');
				}
			}
			else//editar alerta
			{
				$('.tabelaDados tr').each(function(index, element)
				{
					rowData = $(this).children().attr('style');
					if(rowData == "color:#FF0000;")
					{
						$(this).children().first().next().html(horaAlerta+":"+minuAlerta);
					}
				});
			}	
		}
		else
		{
			$("#hora_alerta").focus();
			alertify.alert('Deve escolher uma Hora');
		}
	});
	/**************adicionar minuto para alerta******************/
	$('#minuto_alerta').change(function(){
		dataAlerta = $("#dataAlerta").val();
		minuAlerta = $(this).val();
		horaAlerta = "";
		horaComple = "";
		demo = "00:00";
		demo = demo.substr(0,3);
		if(minuAlerta!="")
		{
			if($('#hora_alerta').val() != "--") horaAlerta = $('#hora_alerta').val();
			$rowData = $('.tabelaDados tr:last-child').children().attr('style');
			if($('#salvar_alerta').is(':visible'))//salvar alerta
			{
				if($rowData == "color:#FF0000;" && dataAlerta != "--" && dataAlerta != "")
				{
					$('.tabelaDados tr:last-child td:nth-last-child(6)').html(horaAlerta+":"+minuAlerta);
				}
				else
				{
					$('#minuto_alerta').val('--');
					$("#dataAlerta").focus();
					alertify.alert('Deve escolher uma DATA');
				}
			}
			else//editar alerta
			{
				$('.tabelaDados tr').each(function(index, element)
				{
					rowData = $(this).children().attr('style');
					if(rowData == "color:#FF0000;")
					{
						$(this).children().first().next().html(horaAlerta+":"+minuAlerta);
					}
				});
			}
		}
		else
		{
			$("#minuto_alerta").focus();
			alertify.alert('Deve escolher os MINUTOS');
		}
	});
	$('#para_ale').change(function(){
		dataAlerta = $("#dataAlerta").val();
		paraAlerta = "";
		login1     = "";
		minuAlerta = "";
		horaAlerta = "";
		horaComple = "";
		if($(this).val() != 0)
		{
			login1     = $(this).val();
			paraAlerta = $("#para_ale option[value='"+login1+"']").text();
		}
		if(paraAlerta!="")
		{
			//if($('#hora_alerta').val() != "--") horaAlerta = $('#hora_alerta').val();
			$rowData = $('.tabelaDados tr:last-child').children().attr('style');
			if($('#salvar_alerta').is(':visible'))//salvar alerta
			{
				if($rowData == "color:#FF0000;" && dataAlerta != "--" && dataAlerta != "")
				{
					$('.tabelaDados tr:last-child td:nth-last-child(5)').html(paraAlerta);
				}
				else
				{
					$('#para_ale').val('0');
					$("#dataAlerta").focus();
					alertify.alert('Deve escolher uma DATA');
				}
			}
			else//editar alerta
			{
				$('.tabelaDados tr').each(function(index, element)
				{
					rowData = $(this).children().attr('style');
					if(rowData == "color:#FF0000;")
					{
						$(this).children().first().next().next().html(paraAlerta);
					}
				});
			}
		}
		else
		{
			$("#para_ale").focus();
			alertify.alert('Deve escolher PARA');
		}
	});
	/***************************************************************/
	$('#obsAlerta').keyup(function(e){
		dataAlerta = $("#dataAlerta").val();
		if(dataAlerta != "--" && dataAlerta != "")
		{
			dado = $.nl2br($(this).val().toUpperCase());
			if($('#salvar_alerta').is(':visible'))//salvar alerta
			{
				$('.tabelaDados tr:last-child td:nth-last-child(3)').html(dado);
			}
			else//quando editar alerta
			{
				$('.tabelaDados tr').each(function(index, element)
				{
					rowData = $(this).children().attr('style');
					if(rowData == "color:#FF0000;")
					{
						$(this).children().first().next().next().next().next().html(dado);
					}
				});
			}
		}
		else
		{
				$('#obsAlerta').val('');
				$("#dataAlerta").focus();
				alertify.alert('Deve escolher uma DATA');
		}
	})
	/******************usuarios selecionados*********************/
	$('.loginUsuario').live("click",function(e){
		dataAlerta = $("#dataAlerta").val();
		login	   = $(this).val();
		$rowData   = $('.tabelaDados tr:last-child').children().attr('style');
		objeto     = $(this);
		if(dataAlerta != "--" && dataAlerta != "")
		{
			nome = '<li id="'+login+'" style="clear:both; padding:0;">'+$(this).next().html()+'</li>';
			if($('#salvar_alerta').is(':visible'))//salvar alerta
			{
				if($rowData == "color:#FF0000;")
				{
					if($(this).is(':checked'))
					{
						//$('#selecUsuario').append(nome);
						$('.tabelaDados tr:last-child td:nth-last-child(4)').children('#selecUsuario').append(nome);
						$.orderNome($('#selecUsuario'));
					}
					else
					{
						$('#'+login).remove();
					}
				}
			}
			else//editar alerta
			{
				$('.tabelaDados tr').each(function(index, element)
				{
					rowData = $(this).children().attr('style');
					if(rowData == "color:#FF0000;")
					{
						if(objeto.is(':checked'))
						{
							$(this).children().first().next().next().next().children('#selecUsuario').append(nome);
							$.orderNome($('#selecUsuario'));
						}
						else
						{
							$('#'+login).remove();
						}
					}
				});
			}
		}
		else
		{
			$(this).attr("checked",false);
			$("#dataAlerta").focus();
			alertify.alert('Deve escolher uma DATA');
		}
	});
	/*********************************************************************/
	$('.ok_icon').live("click",function(e){
		mouseX     = e.pageX; 
		mouseY     = e.pageY;
		id_detalhe = $(this).parent().parent().attr("detalhe");//id_detalhe pegar
		dataDT     = $(this).parent().parent().attr("id");
		id_ctr 	   = $('#id_ctr').val();
		fila_row   = $('#fila1').val().substring(6);
		id_subitem = $('#left_'+fila_row).children().children().val();
		$('#myMenuRight').fadeOut();//ocultar myMenuRight
		$('.listaObs').hide();//ocultar alertas/horario/responsavel
		$('.msg_erro').hide();//ocultar mensagem de erro
		$('.msg_titulo').empty();//ocultar titulo
		$('#filaUnica').val($('#fila1').val());
		$('#myObs').hide();//ocultar alterar hora
		if($(this).attr("id") == "alertas_view")
		{
			//$('html, body').animate({scrollLeft:(Number($(window).scrollLeft())+500)+'px'}, 'slow');//rolar tela para direita
			//$('#myObs').css({'top':mouseY,'left':mouseX,'display':'block !important','width':'660px'}).fadeIn();//mostrar alterar hora
			$('#myObs').css({'left':'50%','margin-left':'-380px','top':'50%','margin-top':'-250px','width':'840px'}).fadeIn();//mostrar alterar hora
			$("#_"+$(this).attr("id")).fadeIn();
			$('.tabelaDados2 .trDados').nextAll().remove();
			//titulo
			dataString = 'id_subitem='+id_subitem+'&id_detalhe='+id_detalhe+'&op=DadosTitulo';
			$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$('.msg_titulo').append(html);
				}
			});
			//listar todas as alertas
			dataString = 'id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&dataDT='+dataDT+'&op=ListarAlert2';
			$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$('.tabelaDados2 tbody').append(html);
				}
			});
		}
	})
	/**************************SALVAR TUDO GRAFICO**************************/
	$('#save_completo').click(function(){
		countpag = $('#countpag').val();
		vazio = 0;
		ArraySubItem = [];
		$('#tabelaLeft tr').each(function(index, element) 
		{	//fila = parseInt($(this).attr("id"));
			fila = parseInt($(this).attr("id").substring(5));
			if(fila>0)//if(fila>0)//primera fila não entra id='left_1'
			{
				ArraySubItem[index] = $(this).children().children().val();//pegar IdSubItem
				if(index > 1)//primero registro no valida o campo texto
				{
					if($(this).children().next().children('.text_nome').val() == "")
					{
						vazio ++;
						focar =  $(this).children().next().children('.text_nome');
					}
				}
			}
		});
		if(vazio > 0)// && countpag >= 1000)//nunca validar texto vazio(nivel 4 para salvar text_nome)
		{
			focar.removeAttr("style","display:none;");
			focar.focus();
			alertify.alert('Deve preencher o(s) campo(s)');
		}
		else
		{
			Atarefa = [];//Array tarefa
			i=0; //tarefa
			j=0; //adicionar
			fila_ante = 0;//fila anterior
			dataAnterior = "";
			$('#tabelaRight tr').each(function(index, element) 
			{	
				fila = parseInt($(this).attr("id").substring(6));
				ordem = 0;
				if(fila>0)
				{	
					//var IdSubItem = $(this).children().children().val();
					var IdSubItem = $('#left_'+fila).children().children().val();
					$(this).children().each(function(index, element) 
					{
						if($(this).attr("tarefa")>0)
						{
							if(j==0)
							{
								Atarefa[i] = [];//Array()
								Atarefa[i][0] = IdSubItem;//IdSubItem
								Atarefa[i][1] = $(this).attr("tarefa");//tarefa
								Atarefa[i][2] = ordem;//ordem
								Atarefa[i][3] = $(this).attr("id");//data1
								Atarefa[i][4] = $(this).attr("detalhe")//detalhe tarefa
								ordem++;
								fila_ante = fila;
							}
							else
							{
								if(Atarefa[i][1]!=$(this).attr("tarefa"))
								{
									hora2 = $('#right_'+fila_ante).children('#'+dataAnterior).attr('hora2');
									Atarefa[i][5] = dataAnterior;//data2
									Atarefa[i][6] = hora2//hora fim
									i++;
									Atarefa[i] = [];//Array()
									Atarefa[i][0] = IdSubItem;//IdSubItem
									Atarefa[i][1] = $(this).attr("tarefa");//tarefa
									Atarefa[i][2] = ordem;//ordem
									Atarefa[i][3] = $(this).attr("id");//data1
									Atarefa[i][4] = $(this).attr("detalhe")//detalhe tarefa
									ordem++;
									fila_ante = fila;
								}
								else if(fila > fila_ante)
								{
									hora2 = $('#right_'+fila_ante).children('#'+dataAnterior).attr('hora2');
									Atarefa[i][5] = dataAnterior;//data2
									Atarefa[i][6] = hora2//hora fim
									i++;
									Atarefa[i] = [];//Array()
									Atarefa[i][0] = IdSubItem;//IdSubItem
									Atarefa[i][1] = $(this).attr("tarefa");//tarefa
									Atarefa[i][2] = ordem;//ordem
									Atarefa[i][3] = $(this).attr("id");//data1
									Atarefa[i][4] = $(this).attr("detalhe")//detalhe tarefa
									ordem++;
									fila_ante = fila;
								}
							}
							dataAnterior = $(this).attr("id");
							j++;
						}
					});
					if(dataAnterior!="") 
					{
						hora2 = $('#right_'+fila_ante).children('#'+dataAnterior).attr('hora2');
						Atarefa[i][5] = dataAnterior;//data2
						Atarefa[i][6] = hora2//hora fim
					}
				}
			});
			if(Atarefa.length > 0)
			{
				$.pleasewait();//mostrar mensagem de aguarde...
				//$('#msn_save').removeAttr("style","display:none;");//mostrar mensagem de aguarde...
				$.ajax({
				type: "POST",
				url: "ajax/ajax_salvar_tarefas.php",
				data: {ArrayTarefa:Atarefa,VetorSubItem:ArraySubItem,usuario:usuario},
				cache: false,
				success: function(html)
				{
					$("#msn_save").fadeOut();
					$("#copy").removeAttr("disabled",true);//habilitar botao duplicar
					$("#AllCodigo").attr("checked",false);//desmarcar checkbox
					$(".codigo").attr("checked",false);//desmarcar checkbox
					if(countpag >= 3)
					{
						$('.save').click();
					}
					setTimeout(function () {location.reload()},2000);
					//location.reload();
				}
				});	
			}
			else
			{
				alertify.alert("Adicionar tarefas e clicar em [Salvar Grafico]");
			}
		}
	});
	$('#save_realizado').click(function(){
		Atarefa = [];//Array tarefa
		i=0; //tarefa
		$.pleasewait();//mostrar mensagem de aguarde...
		//$('#msn_save').removeAttr("style","display:none;");//mostrar mensagem de aguarde...
		$('#tabelaRight tr').each(function(index, element) 
		{   //fila = parseInt($(this).attr("id"));
			fila = $(this).attr("id").substring(6);
			if(fila>0)
			{	
				$(this).children().each(function(index, element) 
				{
					if($(this).attr("idstatus")>0)
					{
						Atarefa[i] = [];//Array()
						Atarefa[i][0] = $(this).attr("detalhe");//detalhe(idtarefa)
						Atarefa[i][1] = $(this).attr("idstatus");//id_status
						Atarefa[i][2] = $(this).attr("id");//data1
						i++;
					}
                });
			}
        });
		if(Atarefa.length > 0)
		{
			$.ajax({
			type: "POST",
			url: "ajax/ajax_salvar_execucao.php",
			data: {ArrayTarefa:Atarefa},
			cache: false,
			success: function(html)
			{
				$("#msn_save").fadeOut();
				$("#AllCodigo").attr("checked",false);//desmarcar checkbox
				$(".codigo").attr("checked",false);//desmarcar checkbox
				$('.save').click();
				setTimeout(function () {location.reload()},2000);
				//location.reload();
			}
			});	
		}
		else setTimeout(function () {location.reload()},2000);
	});
	/***************************************************/
	$('#salvar_responsavel').click(function(){
		responsavel = $('#valor_resposavel').val();
		fila_row    = $('#fila1').val().substring(6);
		id_subitem  = $('#left_'+fila_row).children().children().val();
		TarefaId    = $('#left_'+fila_row).children().next().children().next().attr("id_tarefa");
		if(TarefaId > 0)
		{
			if(responsavel != 0)
			{
				dataString = 'responsavel='+responsavel+'&usuario='+usuario+'&op=SalvarResponsavel';
				$.ajax({
					type: "POST",
					url: "ajax/ajax_menu_direito.php",
					data: dataString,
					cache: false,
					success: function(html)
					{
						alertify.alert(html)
						$('.closeMenu').click();
					}
				});
			}
			else
			{
				$('#valor_resposavel').focus();
				alertify.alert("Deve escolher um respons&aacute;vel");
			}
		}
		else $('.closeMenu').click();
	});
	/*********************responsavel subitem************************/
	$('#save_responsavel').click(function(){
		responsavel = $('#lista_resposavel').val();
		fila_row    = $('#fila1').val();
		tipor       = $('.tipor:checked').val();
		if(responsavel != 0)
		{
			dataString = 'responsavel='+responsavel+'&usuario='+usuario+'&tipor='+tipor+'&op=SaveResponsavel';
			$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					if(tipor == "Res")
					{		
						$('#'+fila_row).children().next().children('.itemResponsa').children('.divResponsavel').html('[R. '+html+']');
					}
					//alertify.alert("Salvo com Sucesso!");
					$('.closeMenu').click();
					$('#save_completo').click();
				}
			});
		}
		else
		{
			$('#valor_resposavel').focus();
			alertify.alert("Deve escolher um respons&aacute;vel");
		}
	});
	/***************************************************/
	$('#myResponsa').live({
    mouseenter: function () {
		id = $('#fila1').val().substring(5);
		$('#right_'+id).addClass('hover');
		$('#left_'+id).addClass('hover');
    },
    mouseleave: function () {
		id = $('#fila1').val().substring(5);
		$('#right_'+id).removeClass('hover');
		$('#left_'+id).removeClass('hover');
    }
	});	
	/*************************************************/
	$('#tabelaLeft tr').live({
    mouseenter: function () {
		$(this).addClass('hover');
		id = $(this).attr("id").substring(5);
		$('#right_'+id).addClass('hover');
    },
    mouseleave: function () {
		$(this).removeClass('hover');
		id = $(this).attr("id").substring(5);
		$('#right_'+id).removeClass('hover');
    }
	});	
	$('#tabelaRight tr').live({
    mouseenter: function () {
		$(this).addClass('hover');
		id = $(this).attr("id").substring(6);
		$('#left_'+id).addClass('hover');
    },
    mouseleave: function () {
		$(this).removeClass('hover');
		id = $(this).attr("id").substring(6);
		$('#left_'+id).removeClass('hover');
    }
	});
	/***********************************************/
	/*$('li').hover(function(){
		//if($(this).attr('id') == 60) 
		//{
			$('html, body').animate({scrollLeft:(Number($(window).scrollLeft())+500)+'px'}, 'slow');//rolar tela para direita
			$('#SubmyMenu').show();//mostrar submenu tarefas
		//}
  	},function(){
		//if($(this).attr('id') == 60) 
		$('#SubmyMenu').hide();//esconder submenu tarefas
	});*/
	/**************************salvar ajustar ou alterar horario**************************/
	$('.save_horario').click(function(){
		//var matchhora = new RegExp(/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/gi);//validar hora
		$('.msg_erro').fadeOut();//ocultar mensagem de erro
		//horaFim   = $('#hora_fim').val();
		horaHorario   = $('#hora_horario').val();
		minutoHorario = $('#minuto_horario').val();
		idDetalhe     = $('#id_detalhe').val();
		obsAlerta     = $('#obsAlerta').val();
		if(horaHorario != "--" && horaHorario != "")
		{
			if(minutoHorario != "--" && minutoHorario != "")
			{
				horaFim = horaHorario+':'+minutoHorario;
				aux = $('#aux').val().split(';');
				$('#'+aux[0]).children('#'+aux[1]).attr("hora2",horaFim);
				if(horaFim != "18:00")
				{
					$('#'+aux[0]).children('#'+aux[1]).children('.tope').prepend('<img src="images/clock.png" width="15" class="clock" title="'+horaFim+'" />');
				}
				else if(horaFim == "18:00")
				{
					$('#'+aux[0]).children('#'+aux[1]).children('.clock').remove();
				}
				$('.msg_erro').hide();//ocultar mensagem de erro
				$('#myObs').hide();//ocultar
				//$('#hora_fim').val('');//limpar hora fim
			}
			else
			{
				$('#minuto_horario').focus();//$('#hora_fim').focus();
				$('.msg_erro').fadeIn();
				$('.msg_erro').html("Deve escolher um minuto.");
			}
		}
		else 
		{
			$('#hora_horario').focus();//$('#hora_fim').focus();
			$('.msg_erro').fadeIn();
			$('.msg_erro').html("Deve escolher uma hora.");
		}
		$('#copy').attr("disabled",true);//desabilitar botao duplicar
		$('#copy').addClass('disabled');//adicionar class
	});
	/**************************botao finalizar**************************/
	$('#finalizar').click(function(){
		var codigos = [];
		var fila = 0;
		$('.codigo').each(function(index, element) {
            if($(this).is(':checked'))
			{
				codigos[fila] = $(this).val();
				fila++;
			}
        });
		if(fila > 0)
		{
			alertify.confirm('Tem certeza que deseja <b>FINALIZAR</b>', function (e) {
			if(e)
			{
					$.ajax({
					type: "POST",
					url: "ajax/ajax_projeto.php",
					data: {data:codigos,usuario:usuario,op:"finalizar"},
					cache: false,
					success: function(html)
					{
						$('#save_completo').click();
					}
					});
			}
			});
		}
		else 
		{
			alertify.alert('Deve selecionar um Registro');
		}
	});
	/*******************botao liberação****************/
	$('#liberacao').click(function(){
		var codigos = [];
		var fila = 0;
		$('.codigo').each(function(index, element) {
            if($(this).is(':checked'))
			{
				codigos[fila] = $(this).val();
				fila++;
			}
        });
		if(fila > 0)
		{
			alertify.confirm('Tem certeza que deseja fazer<b>LIBERAÇÃO</b>', function (e) {
			if(e)
			{
				$.ajax({
				type: "POST",
				url: "ajax/ajax_projeto.php",
				data: {data:codigos,usuario:usuario,op:"liberacao"},
				cache: false,
				success: function(html)
				{
					$('#save_completo').click();
				}
				});
			}
			});
		}
		else 
		{
			alertify.alert('Deve selecionar um Registro');
		}
	});
	/*******************botao liberação****************/
	$('#gerar_arquivo').click(function(){
		var codigos = [];
		var fila = 0;
		$('.codigo').each(function(index, element) {
            if($(this).is(':checked'))
			{
				codigos[fila] = $(this).val();
				fila++;
			}
        });
		if(fila > 0)
		{
			alertify.confirm('Tem certeza que deseja <b>GERAR ARQUIVO</b>', function (e) {
			if(e)
			{
				$.ajax({
				type: "POST",
				url: "ajax/ajax_projeto.php",
				data: {data:codigos,usuario:usuario,op:"gerar_arquivo"},
				cache: false,
				success: function(html)
				{
					$('#save_completo').click();
				}
				});
			}
			});
		}
		else 
		{
			alertify.alert('Deve selecionar um Registro');
		}
	});
	$('#revisao').click(function(){
		var codigos = [];
		var fila = 0;
		$('.codigo').each(function(index, element) {
            if($(this).is(':checked'))
			{
				codigos[fila] = $(this).val();
				fila++;
			}
        });
		if(fila > 0)
		{
			alertify.confirm('Tem certeza que deseja criar <b>REVIS&Atilde;O</b>', function (e) {
			if(e)
			{
				if(id_ctr > 0)
				{
					$.ajax({
					type: "POST",
					url: "ajax/ajax_projeto.php",
					data: {data:codigos,usuario:usuario,op:"revisao"},
					cache: false,
					success: function(html)
					{
						if($('#save_realizado').is(':visible'))
						{
							$('#save_realizado').click();
						}
						else
						{
							$('#save_completo').click();
						}
					}
					});
				}
			}
			});
		}
		else 
		{
			alertify.alert('Deve selecionar um Registro');
		}
	})
	/**************************clicar no body e fechar os menus**************************/
	$(document).click(function(e) 
	{//ocultar menu de tarefas
    	if($(e.target).parents().index($('#myMenu')) == -1)
		{
        	if($('#myMenu').is(":visible")) $('#myMenu').hide();
    	}
		if($(e.target).parents().index($('#myMenuRight')) == -1) 
		{
        	if($('#myMenuRight').is(":visible")) $('#myMenuRight').hide();
			/*if($(e.target).parents().index($('#myObs')) == -1) 
			{
        		if($('#myObs').is(":visible")) $('#myObs').hide();
    		}*/
    	}
	});
});