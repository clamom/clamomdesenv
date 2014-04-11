/*
 * @CRONOGRAMA GERAL [FASE | ETAPA | ITEM] 
 * @autor  : Eduardo Zambrano <eduardoz@clamom.com.br>
 * @versão : 1.0
 * @data   : 02/04/2014
 * Copyright 2014 http://www.clamom.com.br
 **/
$(document).keydown(function(e){
	if(e.keyCode==36)//inicio
	{
		$("#divRight").animate({scrollLeft: 0}, 'slow');//scroll automatica data atual
	}
	else if(e.keyCode==35)//fim
	{
		$("#divRight").animate({scrollLeft: $('#tabelaRight').width()}, 'slow');//scroll automatica data atual
	}
});
$(document).ready(function(e)
{	
	if($('#tipo').val() != "3")
	{
		$('#save_realizado').removeAttr("disabled");
		$('#save_realizado').removeClass("disabled");
		$('#revisao').removeAttr("disabled");
		$('#revisao').removeClass("disabled");
	}
	usuario = $('#usuario').val();
	$('#editar_alerta').hide();
	$('#AddFaseEtapa').hide();
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
					if(cor != "" && cor != "#FFFFFF")
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
					if(cor != "" && cor != "#FFFFFF")
					{
					$('#'+fila1).children('#'+prox).attr("tarefa",tarefa);//adicionar tarefa
					if(prox == maior)
					{
						$('#'+fila1).children('#'+prox).attr("hora2","18:00");//adicionar hora
						$('#'+fila1).children('#'+prox).removeAttr("detalhe");//remover detalhe
					}
					divHtml = '<div class="tope" style="border-style:solid; border-color: '+cor+' transparent transparent; border-width: 43px 43px 0px 0px; position:relative;"><div class="txt_sigla">'+sigla+'</div></div><div class="boto" style="border-style:solid; border-color: transparent transparent #FFFFFF; border-width: 0px 0px 43px 43px;"></div>';
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
			{//pintar o quadro (realizado)
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
	$.DadosAlerta = function(id_alerta,objeto)
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
					listUser.html(result[4]);//listar usuario para alterar
					$(".loginUsuario").attr("checked",false);
					$(".loginUsuario").each(function(index, element)
					{
						obs = "nao";
						idlogin = $(this).val();
						$.each(logins, function( key, value ) 
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
					rowData = $(this).children().first().next().next().next().next().next().children().attr('id');
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
		mouseX    = e.pageX-280; 
		mouseY    = e.pageY;
		fila1     = $('#fila1').val();
		data1     = $('#data1').val();
		fila2     = $(this).parent().attr('id');
		data2     = $(this).attr('id');
		tipo      = $('#tipo').val();
		situacao1 = $('#'+data1).attr("situacao");
		situacao2 = $(this).attr("situacao");
		fila_row  = fila1.substring(6);//pegar numero da fila
		id_ctr    = $('#id_ctr').val();
		TarefaId  = $('#left_'+fila_row).children().next().children().next().attr("id_tarefa");
		login     = $('#left_'+fila_row).children().next().children('.itemResponsa').attr("login");
		id_detalhe = $('#id_detalhe').val();//id_detalhe pegar
		fila_row   = $('#fila1').val().substring(6);
		id_subitem = $('#left_'+fila_row).children().children().val();
		tipo       = $('#tipo').val();
		alerta_data = data1;
		switch(e.which){
		case 1://click left
			$('#fila2').val(fila2);
			$('#data2').val(data2);
			if($('#finalizar').is(':visible'))
			{
				if(data2!="0" && data2!="")	
				{
					if(fila1 == fila2) 
					{					
						if($(this).children('.tope').children().attr('class') != "ok_icon")//validar quando clicar na imagem para ver as alertas
						{
							if(tipo != 3)
							{
								$('#myMenu .contextMenu .liTarefa').remove();
								dataString = 'codigo='+TarefaId+'&tipo='+tipo+'&id_ctr='+id_ctr+'&login='+login+'&op=menu';
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
					}
					else $('#myMenu').fadeOut();//ocultar menu de tarefas
				}
				else $('#myMenu').fadeOut();//ocultar menu de tarefas
			}
			else if($('#revisao').is(':visible'))
			{
				if(tipo == 3)
				{
					if(data2!="0" && data2!="")	
					{
						if(fila1 == fila2) 
						{					
							if($(this).children('.tope').children().attr('class') != "ok_icon")//validar quando clicar na imagem para ver as alertas
							{
								if(situacao1 != 1 && situacao2 != 1)
								{
									$('#myMenu .contextMenu .liTarefa').remove();
									dataString = 'codigo='+TarefaId+'&tipo='+tipo+'&op=menu';
									$.ajax({
										type: "POST",
										url: "ajax/ajax_menu_tarefas.php",
										data: dataString,
										cache: false,
										success: function(html)
										{
											$('html, body').animate({scrollLeft:(Number($(window).scrollLeft())+500)+'px'}, 'slow');//rolar tela para direita
											$('#myMenu').children('.contextMenu').append(html);
											$('#myMenu').css({'top':mouseY,'left':mouseX}).fadeIn();//mostrar menu de tarefas
										}
									});
								}
							}
						}
					}
				}
			}
			break;
		case 2://alert('click center');
			break;
		case 3://alert('click right');
			/*if($('#finalizar').is(':visible'))
			{*/
				$('#alerta_dia').hide();
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
					if($(this).attr("tarefa")!=$(this).next().attr("tarefa"))//ultima tarefa
					{	
						$('#intX').val(mouseX);
						$('#intY').val(mouseY);
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
					else
					{
						//titulo da alerta
						dataString = 'id_subitem='+id_subitem+'&id_detalhe='+$(this).attr("detalhe")+'&tipo='+tipo+'&op=DadosTitulo';
						$.ajax({
							type: "POST",
							url: "ajax/ajax_alertas.php",
							data: dataString,
							cache: false,
							success: function(html)
							{
								$('.msg_titulo').html(html);
							}
						});
						//listar alertas
						$.ajax({
							type: "POST",
							url: "ajax/ajax_alertas.php",
							data: {op:"ListarAlerta",id_ctr:id_ctr,id_detalhe:id_detalhe,tipo:tipo,alerta_data:data2},
							cache: false,
							success: function(html)
							{
								$('#alerta_tabela .trTitulo').nextAll().remove();
								$('#alerta_tabela tbody').append(html);
							}
						});
						$('#alerta_data').val(data2);
						$('#alerta_dia').show();
					}
				}
			//}
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
	$('td').dblclick(function(){
		idsubitem = $(this).prev().children().val();
		corFonte  = $(this).children('.nome_label').attr('style');
		projeto   = $('#id_ctr').val();
		num_ctr   = $('#num_ctr').val();
		navegar   = $('#navegar').val();
		tipo      = $('#tipo').val();
		if(idsubitem > 0)
		{
			if(!$(this).children('.text_nome').is(':visible'))//validar para não clicar na caixa de texto
			{
				if(tipo == 3)//item para desmembrar
				{
					if($('#revisao').is(':visible'))
					{
						var dataString = 'idetapa='+idsubitem+'&id_ctr='+projeto+'&num_ctr='+num_ctr+'&usuario='+usuario+'&op=tarefa_item';
						$.ajax({
							type: "POST",
							url: "ajax/ajax_subitem_validar.php",//desmembrar
							data: dataString,
							cache: false,
							success: function(html)
							{
								tarefas = html;//tarefas id_etapa_item
								if(tarefas > 0)//existem tarefas
								{
									dataString = 'idetapa='+idsubitem+'&projeto='+projeto+'&num_ctr='+num_ctr+'&op=retornar_iditem';
									$.ajax({
										type: "POST",
										url: "ajax/ajax_menu_direito.php",
										data: dataString,
										cache: false,
										success: function(html)
										{	
											id_item = html;//
											window.location.href = "cronograma/index.php?projeto="+projeto+"&item="+id_item+"&subitem=0&usuario="+usuario;
										}
									});
								}
								else //não tem tarefas
								{
									alertify.alert("Adicionar tarefas e clicar em [Salvar Grafico]");
								}
							}
						});
					}
				}
				else//fase e etapa desmembrar
				{
					var nome_item  = $(this).children('.nome_label').html();
					var dataString = 'idsubitem='+idsubitem+'&op=detalhe_tarefa';
					$.ajax({
					type: "POST",
					url: "ajax/ajax_subitem_validar.php",
					data: dataString,
					cache: false,
					success: function(html)
					{
						valorI = html.split('-');
						if(valorI[0] > 0)//exitem tarefas
						{
							if(valorI[1] == 0)
							{
								if(corFonte == "color:#333333")//não adiciono etapas
								{
									alertify.confirm('Deseja realmente Adicionar <b>ETAPA(S)</b> na Fase <b>'+nome_item.toUpperCase()+'</b> ?', function (e){
									if(e) 
									{
										window.location.href = "index.php?projeto="+projeto+"&id_fase="+idsubitem+"&usuario="+usuario;
									}
									});
								}
								else//já existem etapas
								{
									window.location.href = "index.php?projeto="+projeto+"&id_fase="+idsubitem+"&usuario="+usuario;
								}
							}
							else if(valorI[1] == 1)
							{
								if(corFonte == "color:#333333")//não adiciono itens
								{
									alertify.confirm('Deseja realmente Adicionar <b>ITEN(S)</b> na Etapa <b>'+nome_item.toUpperCase()+'</b> ?', function (e){
									if(e) 
									{	
										window.location.href = "index.php?projeto="+projeto+"&id_fase="+idsubitem+"&usuario="+usuario;
									}
									});
								}
								else//já existem itens
								{
									window.location.href = "index.php?projeto="+projeto+"&id_fase="+idsubitem+"&usuario="+usuario;
								}
							}
						}
						else//não tem tarefas 
						{
							alertify.alert("Adicionar tarefas e clicar em [Salvar Grafico]");
						}
					}
					});
				}
			}
		}
	});
	/************chamar função pintar ***********/
	$('.liTarefa').live("click",function(e){
		var tarefa = $(this).attr('id');
		var cor    = $(this).attr('color');
		var sigla  = $(this).attr('sigla');
		var fila1  = $('#fila1').val();
		var data1  = $('#data1').val();
		var fila2  = $('#fila2').val();
		var data2  = $('#data2').val();
		var tipo   = $('#tipo').val();
		if($('#finalizar').is(':visible'))
		{
			$.pintarCelda(fila1,data1,fila2,data2,cor,tarefa,sigla,dataAtual);
		}
		else if($('#revisao').is(':visible'))
		{
			if(tipo == 3)
			{
				$.pintarCelda(fila1,data1,fila2,data2,cor,tarefa,sigla,dataAtual);
			}
			else
			{
				$.pintarStatus(fila1,data1,fila2,data2,cor,tarefa);
			}
		}
		$('#myMenu').fadeOut();//esconder menu
		$('#SubmyMenu').fadeOut();//esconder submenu
		$('#fila1').val(0);
		$('#data1').val(0);
		$('#fila2').val(0);
		$('#data2').val(0);
		$('#copy').attr("disabled",true);//desabilitar botao duplicar
		$('#copy').addClass('disabled');//adicionar class
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
		tipo       = $('#tipo').val();
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
		
		$('html, body').animate({scrollLeft:(Number($(window).scrollLeft())+500)+'px'}, 'slow');//rolar tela para direita
		if($(this).attr("id") == "alertas")
		{
			$('#salvar_alerta').show();
			$('#editar_alerta').hide();
			//$('#myObs').css({'top':mouseY,'left':mouseX,'display':'block !important','width':'660px'}).fadeIn();//mostrar alterar hora
			$('#myObs').css({'left':'50%','margin-left':'-330px','top':'50%','margin-top':'-150px','width':'840px'}).fadeIn();//mostrar alterar hora
			$("#_"+$(this).attr("id")).fadeIn();
			//$('.tabelaDados .trDados').next().empty();
			$('.tabelaDados .trDados').nextAll().remove();
			//titulo
			dataString = 'id_subitem='+id_subitem+'&id_detalhe='+id_detalhe+'&tipo='+tipo+'&op=DadosTitulo';
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
			dataString = 'id_detalhe='+id_detalhe+'&tipo='+tipo+'&op=ListarDatas';
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
			dataString = 'id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&tipo='+tipo+'&op=ListarAlert';
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
			$('#myObs').css({'top':mouseY,'left':mouseX,'display':'block !important','width':'280px'}).fadeIn();//mostrar alterar hora
			$("#_"+$(this).attr("id")).fadeIn();
			//$("input").focus();//$('#hora_fim').focus();
		}
		else if($(this).attr("id") == "responsavel")
		{
			$('#valor_resposavel').children().remove();
			$('#myObs').css({'top':mouseY,'left':mouseX,'display':'block !important','width':'400px'}).fadeIn();//mostrar alterar hora
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
		tipo 		 = $('#tipo').val();
		ListaLogin   = "";
		if(dataAlerta != "--" && dataAlerta != "")
		{
			if(horaAlerta != "--" && horaAlerta != "")
			{
				if(minutoAlerta != "--" && minutoAlerta != "")
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
							var dataString = 'ListaLogin='+ListaLogin+'&dataAlerta='+dataAlerta+'&horaAlerta='+HoraMinuto+'&id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&obsAlerta='+obsAlerta+'&tipo='+tipo+'&usuario='+usuario+'&op=SalvarAlerta';
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
								$(".loginUsuario").each(function(index, element){
									$(this).attr("checked",false);
								});
								
								$('.tabelaDados .trDados').nextAll().remove();
								$('.tabelaDados tbody').append(html);
								//contar alertas
								dataString = 'id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&dataDT='+dataTD+'&tipo='+tipo+'&op=CountAlert';
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
						alertify.alert("Deve Selecionar um Usu&aacute;rio");
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
		tipo         = $('#tipo').val();
		ListaLogin   = "";
		if(dataAlerta != "--" && dataAlerta != "")
		{
			if(horaAlerta != "--" && horaAlerta != "")
			{
				if(minutoAlerta != "--" && minutoAlerta != "")
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
							var dataString = 'ListaLogin='+ListaLogin+'&dataAlerta='+dataAlerta+'&horaAlerta='+HoraMinuto+'&id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&obsAlerta='+obsAlerta+'&id_alerta='+id_alerta+'&tipo='+tipo+'&op=EditarAlerta';
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
								dataString = 'id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&dataDT='+dataTD+'&tipo='+tipo+'&op=AlertDatas';
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
		var id_ctr     = $('#id_ctr').val();
		var id_projeto = $('#id_item').val();
		var num_ctr    = $('#num_ctr').val();
		var id_pai	   = $('#id_pai').val();
		$('#tabelaLeft tr').each(function(index, element){
            fila = $(this).attr("id");
        });
		fila = parseInt(fila.substring(5))+1;
		if(fila>0) fila = fila;
		else fila=1;
		var dataString = 'id_ctr='+id_ctr+'&id_projeto='+id_projeto+'&num_ctr='+num_ctr+'&usuario='+usuario+'&id_pai='+id_pai+'&op=FaseEtapa';
		$.ajax({
			type: "POST",
			url: "ajax/ajax_insert.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
				if(html == 2)//mensagem para escolha fase e etapa
				{
					$('#AddFaseEtapa').fadeIn(300);
				}
				else if(html == 0 || html == 1)//estado=0,1(Etapa,Fase)
				{
					var dataString = 'id_ctr='+id_ctr+'&id_projeto='+id_projeto+'&num_ctr='+num_ctr+'&usuario='+usuario+'&id_pai='+id_pai+'&estado='+html+'&op=left';
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
					var dataString = 'id_ctr='+id_ctr+'&id_projeto='+id_projeto+'&num_ctr='+num_ctr+'&usuario='+usuario+'&id_pai='+id_pai+'&estado='+html+'&op=right';
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
				}
			}
		});
	});
	/******************************/
	$('#btnFase').click(function(){
		var fila = 0;
		var id_ctr     = $('#id_ctr').val();
		var id_projeto = $('#id_item').val();
		var num_ctr    = $('#num_ctr').val();
		var id_pai	   = $('#id_pai').val();
		$('#tabelaLeft tr').each(function(index, element){
            fila = $(this).attr("id");
        });
		fila = parseInt(fila.substring(5))+1;
		if(fila>0) fila=fila;
		else fila=1;
		var dataString = 'id_ctr='+id_ctr+'&id_projeto='+id_projeto+'&num_ctr='+num_ctr+'&usuario='+usuario+'&id_pai='+id_pai+'&estado=0&op=left';
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
		var dataString = 'id_ctr='+id_ctr+'&id_projeto='+id_projeto+'&num_ctr='+num_ctr+'&usuario='+usuario+'&id_pai='+id_pai+'&estado=0&op=right';
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
		$('#AddFaseEtapa').hide();
	});
	$('#btnEtapa').click(function(){
		var fila = 0;
		var id_ctr     = $('#id_ctr').val();
		var id_projeto = $('#id_item').val();
		var num_ctr    = $('#num_ctr').val();
		var id_pai	   = $('#id_pai').val();
		$('#tabelaLeft tr').each(function(index, element){
            fila = $(this).attr("id");
        });
		fila = parseInt(fila.substring(5))+1;
		if(fila>0) fila=fila;
		else fila=1;
		var dataString = 'id_ctr='+id_ctr+'&id_projeto='+id_projeto+'&num_ctr='+num_ctr+'&usuario='+usuario+'&id_pai='+id_pai+'&estado=1&op=left';
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
		var dataString = 'id_ctr='+id_ctr+'&id_projeto='+id_projeto+'&num_ctr='+num_ctr+'&usuario='+usuario+'&id_pai='+id_pai+'&estado=1&op=right';
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
		$('#AddFaseEtapa').hide();
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
				$('#msn_save').removeAttr("style","display:none;");//mostrar mensagem aguarde...
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
		listUser  = $(this).parent().parent().children().first().next().next();
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
							$.DadosAlerta(id_alerta,objeto);
						}
					});
				}
			});
		}
		else
		{
			$.DadosAlerta(id_alerta,objeto);
		}
	});
	/*************apagar alertas***********************/
	$('.apagar_alerta').live("click",function(e){
		id_alerta  = $(this).attr("id");
		dataTD     = $(this).attr('data_alerta');
		tr_fila    = $(this).parent().parent();
		id_ctr 	   = $('#id_ctr').val();
		id_detalhe = $('#id_detalhe').val();
		fila1      = $('#filaUnica').val();
		tipo       = $('#tipo').val();
		//alert(dataTD+' '+filaUnica);
		if(id_alerta > 0)
		{
			alertify.confirm('Deseja realmente deletar o Registro?', function (e){
			if(e) 
			{
				var dataString = 'id_alerta='+id_alerta+'&id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&tipo='+tipo+'&op=ApagarAlerta';
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
						dataString = 'id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&dataDT='+dataTD+'&tipo='+tipo+'&op=CountAlert';
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
	});
	/************editar subitem***********/
	$('.edit').live("click",function(e){//editar o subitem
		$('#myMenu').fadeOut();//ocultar menu tarefas
		$(this).attr("style","display:none");//ocultar botao editar
		$(this).prev().removeAttr("style","display:none");//mostrar botao salvar
		$(this).prev().prev().attr("style","display:none");//ocultar descricao
		$(this).parent().children('.set_responsa').attr("style","display:none");//ocultar botao R(resposanvel)
		var valor = $(this).parent().prev().children().val();//pegar id subitem
		$('#'+valor+'nome').removeAttr("style","display:none");//mostrar campos text
		$('#'+valor+'nome').focus();//focar caixa de texto
	});
	/************salvar subitem***********/
	$('.save').live("click",function(e){//salvar o subitem
		$('#myMenu').fadeOut();//ocultar menu de tarefas
		var valor  = $(this).parent().prev().children().val();//pegar id subitem
		var nome   = $('#'+valor+'nome').val();//pegar valor nome
		var id_ctr = $('#id_ctr').val();
		var id_pai = $('#id_pai').val();
		if(nome != "")
		{
			thisSave = $(this);
			var dataString = 'valor='+valor+'&nome='+nome+"&usuario="+usuario+'&id_ctr='+id_ctr+'&id_pai='+id_pai;
			$.ajax({
			type: "POST",
			url: "ajax/ajax_edit.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
				if(html == "true")
				{
					thisSave.attr("style","display:none");//ocultar botao salvar
					thisSave.next().removeAttr("style","display:none");//mostrar botao editar
					$('#'+valor+'nome').attr("style","display:none");//ocultar campo text
					thisSave.prev().removeAttr("style","display:none");//mostra descricao
					thisSave.parent().children('.set_responsa').removeAttr("style","display:none");//mostra botao R(resposanvel)
					thisSave.prev().html(nome);
				}
				else//false
				{
					alertify.alert('Descrição já existe <b>'+nome+'</b>, tente novamente');
				}
			}
			});
		}
		else 
		{
			$('#'+valor+'nome').focus();//focar caixa de texto
			alertify.alert('Deve preencher campo');
		}
	});
	/************salvar subitem***********///$("input[type=text]").live("keypress",function(e){//salvar presionando enter
	$(".text_nome").live("keypress",function(e){
		if(e.which == 13)
		{
			$('#myMenu').fadeOut();//ocultar menu de tarefas
			var valor  = $(this).parent().prev().children().val();//pegar id subitem
			var nome   = $('#'+valor+'nome').val();//pegar valor nome
			var id_ctr = $('#id_ctr').val();
			var id_pai = $('#id_pai').val();
			$(this).next().html(nome);
			if(nome != "")
			{	
				thisSave = $(this);
				var dataString = 'valor='+valor+'&nome='+nome+"&usuario="+usuario+'&id_ctr='+id_ctr+'&id_pai='+id_pai;
				$.ajax({
				type: "POST",
				url: "ajax/ajax_edit.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					if(html == "true")
					{
						thisSave.next().next().attr("style","display:none");//ocultar botao salvar
						thisSave.next().next().next().removeAttr("style","display:none");//mostrar botao editar*/
						thisSave.attr("style","display:none");//ocultar campo text
						thisSave.next().removeAttr("style","display:none");//mostra descricao
						thisSave.parent().children('.set_responsa').removeAttr("style","display:none");//mostra botao R(resposanvel)
						thisSave.next().html(nome);
					}
					else
					{
						alertify.alert('Descrição já existe <b>'+nome+'</b>, tente novamente');
					}
				}
				});
			}
			else 
			{
				$('#'+valor+'nome').focus();//focar caixa de texto
				alertify.alert('Deve preencher campo');		
			}
	  	}
	});
	/************responsavel FASE, ETAPA E ITEM********/
	$('.set_responsa').live("click",function(e){
		var mouseX = e.pageX+15;
		var mouseY = e.pageY+15;
		fila1      = $('#fila1').val();
		id_ctr 	   = $('#id_ctr').val();
		id_subitem = $(this).parent().parent().children().children('.codigo').val();//codigo fase e etapa
		id_item    = $(this).parent().parent().children().children('.id_item').val();//codigo item
		$('#lista_resposavel').children().remove();
		$('#myResponsa').css({'top':mouseY,'left':mouseX}).fadeIn();//mostrar responsavel FASE, ETAPA E ITEM
		dataString = 'id_ctr='+id_ctr+'&id_subitem='+id_subitem+'&id_item='+id_item+'&op=ListaResponsa';
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
		$('#myResponsa').fadeOut();//ocultar responsavel FASE, ETAPA E ITEM
		$('#alerta_dia').fadeOut();//ocultar alerta dia
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
		$('#AddFaseEtapa').hide();
	});
	/************marcar desmarcar registros***********/
	$.MarcarDesmarcar = function()//função para marcar e desmarcar os subitens
	{
		if($("#AllCodigo").attr("checked"))
		{
			$('.codigo').each(function(index, element){
            	$(this).attr("checked",true);
				$(this).parent().parent().attr("style","background:#FFFF80;");
				id = $(this).parent().parent().attr("id").substring(5);
				$('#right_'+id).attr("style","background:#FFFF80;");
            });
	   	}
	   	else
	   	{
			$('.codigo').each(function(index, element){
				$(this).attr("checked",false);
				$(this).parent().parent().removeAttr("style","background:#FFFF80;");
				id = $(this).parent().parent().attr("id").substring(5);
				$('#right_'+id).removeAttr("style","background:#FFFF80;");
			});
	   	}		
	}
	/**********adicionar data para alerta*****/
	$("#dataAlerta").change(function(){
		dataAlerta = $(this).val();
		horaAlerta = "";
		minuAlerta = "";
		if(dataAlerta != "--" && dataAlerta != "")
		{
			if($('#hora_alerta').val() != "--")   horaAlerta = $('#hora_alerta').val();
			if($('#minuto_alerta').val() != "--") minuAlerta = $('#minuto_alerta').val();
			if($('#salvar_alerta').is(':visible'))//salvar alerta
			{
				$rowData = $('.tabelaDados tr:last-child').children().html();
				if($rowData == "DATA")
				{
					$('.tabelaDados').append("<tr style='background:#DDDDDD;'>"+
					"<td valign='top' style='color:#FF0000;' align='center'>"+dataAlerta+"</td>"+
					"<td valign='top' style='color:#FF0000;' align='center'>"+horaAlerta+":"+minuAlerta+"</td>"+
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
						$('.tabelaDados tr:last-child td:nth-last-child(5)').html(horaAlerta+":"+minuAlerta);
					}
					else
					{
						$('.tabelaDados').append("<tr style='background:#DDDDDD;'>"+
						"<td valign='top' style='color:#FF0000;' align='center'>"+dataAlerta+"</td>"+
						"<td valign='top' style='color:#FF0000;' align='center'>"+horaAlerta+":"+minuAlerta+"</td>"+
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
					$('.tabelaDados tr:last-child td:nth-last-child(5)').html(horaAlerta+":"+minuAlerta);
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
					$('.tabelaDados tr:last-child td:nth-last-child(5)').html(horaAlerta+":"+minuAlerta);
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
			else//editar alerta
			{
				$('.tabelaDados tr').each(function(index, element)
				{
					rowData = $(this).children().attr('style');
					if(rowData == "color:#FF0000;")
					{
						$(this).children().first().next().next().next().html(dado);
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
			nome = '<li id="'+login+'" style="clear:both;">'+$(this).next().html()+'</li>';
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
							$(this).children().first().next().next().children('#selecUsuario').append(nome);
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
		tipo       = $('#tipo').val();
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
			$('#myObs').css({'left':'50%','margin-left':'-330px','top':'50%','margin-top':'-150px','width':'840px'}).fadeIn();//mostrar alterar hora
			$("#_"+$(this).attr("id")).fadeIn();
			$('.tabelaDados2 .trDados').nextAll().remove();
			//titulo
			dataString = 'id_subitem='+id_subitem+'&id_detalhe='+id_detalhe+'&tipo='+tipo+'&op=DadosTitulo';
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
			dataString = 'id_ctr='+id_ctr+'&id_detalhe='+id_detalhe+'&dataDT='+dataDT+'&tipo='+tipo+'&op=ListarAlert2';
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
	});
	/**************************SALVAR TUDO GRAFICO**************************/
	$('#save_completo').click(function(){
		if(!$('#save_item').is(':visible')) 
		{
			op = "tarefa_geral";
			vazio = 0;
			ArraySubItem = [];
			$('#tabelaLeft tr').each(function(index, element) 
			{	//fila = parseInt($(this).attr("id"));
				fila = parseInt($(this).attr("id").substring(5));
				if(fila>0)
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
			if(vazio > 1)
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
						var IdSubItem = $('#left_'+fila).children().children().val();
						//var IdSubItem = $(this).children().children().val();
						$(this).children().each(function(index, element) 
						{
							if($(this).attr("tarefa")>0)
							{
								if(j==0)
								{
									Atarefa[i] = [];//Array()
									Atarefa[i][0] = IdSubItem;//IdSubItem
									Atarefa[i][1] = $(this).attr("tarefa");//tarefa
									Atarefa[i][2] = ordem;//tarefa
									Atarefa[i][3] = $(this).attr("id");//data1
									ordem++;
									fila_ante = fila;
								}
								else
								{
									if(Atarefa[i][1]!=$(this).attr("tarefa"))
									{
										hora2 = $('#right_'+fila_ante).children('#'+dataAnterior).attr('hora2');
										Atarefa[i][4] = dataAnterior;//data2
										Atarefa[i][5] = hora2//hora fim
										i++;
										Atarefa[i] = [];//Array()
										Atarefa[i][0] = IdSubItem;//IdSubItem
										Atarefa[i][1] = $(this).attr("tarefa");//tarefa
										Atarefa[i][2] = ordem;//tarefa
										Atarefa[i][3] = $(this).attr("id");//data1
										ordem++;
										fila_ante = fila;
									}
									else if(fila > fila_ante)
									{
										hora2 = $('#right_'+fila_ante).children('#'+dataAnterior).attr('hora2');								
										Atarefa[i][4] = dataAnterior;//data2
										Atarefa[i][5] = hora2//hora fim
										i++;
										Atarefa[i] = [];//Array()
										Atarefa[i][0] = IdSubItem;//IdSubItem
										Atarefa[i][1] = $(this).attr("tarefa");//tarefa
										Atarefa[i][2] = ordem;//tarefa
										Atarefa[i][3] = $(this).attr("id");//data1
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
							Atarefa[i][4] = dataAnterior;//data2
							Atarefa[i][5] = hora2;//hora fim	
						}
					}
				});
				if(Atarefa.length > 0)
				{
					$.pleasewait();
					//$('#msn_save').removeAttr("style","display:none;");//mostrar mensagem de aguarde...
					$.ajax({
					type: "POST",
					url: "ajax/ajax_salvar_tarefas.php",
					data: {ArrayTarefa:Atarefa,VetorSubItem:ArraySubItem,usuario:usuario,op:op},
					cache: false,
					success: function(html)
					{
						$("#msn_save").fadeOut();
						$("#copy").removeAttr("disabled",true);//habilitar botao duplicar
						$("#AllCodigo").attr("checked",false);//desmarcar checkbox
						$(".codigo").attr("checked",false);//desmarcar checkbox
						$('.save').click();
						setTimeout(function () {location.reload()},2000);
					}
					});
				}
				else
				{
					alertify.alert("Adicionar tarefas e clicar em [Salvar Grafico]");
				}	
			}
		}
	});
	/****************************************************************/
	$('#save_item').click(function(){
		var codigos = [];
		var fila = 0;
		var id_etapa = $('#id_pai').val();
		$('.codigo').each(function(index, element) {
            if($(this).is(':checked'))
			{
				codigos[fila] = $(this).val();
				fila++;
			}
        });
		if(fila > 0) 
		{
			$.pleasewait();//mostrar mensagem aguarde...
			idcodigo = "";
			vetorCodigo = "";
			//$('#msn_save').removeAttr("style","display:none;");//mostrar mensagem aguarde...
			$.ajax({
			type: "POST",
			url: "ajax/ajax_salvar_etapa_item.php",
			data: {data:codigos,id_etapa:id_etapa,usuario:usuario},
			cache: false,
			success: function(html)
			{
				$("#msn_save").fadeOut();
				setTimeout($.unblockUI, 2000);//ocultar mensagem
			}
			}); 
		}
		else 
		{
			alertify.alert('Deve selecionar um Item');
		}
	})
	/****************************************************************/
	$('#save_realizado').click(function(){
		op = "tarefa_item";
		vazio = 0;
		ArraySubItem = [];
		$('#tabelaLeft tr').each(function(index, element) 
		{	//fila = parseInt($(this).attr("id"));
			fila = $(this).attr("id").substring(5);
			if(fila>0)
			{
				ArraySubItem[index] = $(this).children().children().val();//pegar IdSubItem
				if($(this).children().next().children('.text_nome').val() == "")
				{
					vazio ++;
					focar =  $(this).children().next().children('.text_nome');
				}
			}
		});
		if(vazio > 0)
		{
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
				fila = $(this).attr("id").substring(6);
				ordem = 0;
				if(fila>0)
				{	
					var IdSubItem = $('#left_'+fila).children().children().val();
					//var IdSubItem = $(this).children().children().val();
					$(this).children().each(function(index, element) 
					{
						if($(this).attr("tarefa")>0)
						{
							if(j==0)
							{
								Atarefa[i] = [];//Array()
								Atarefa[i][0] = IdSubItem;//IdSubItem
								Atarefa[i][1] = $(this).attr("tarefa");//tarefa
								Atarefa[i][2] = ordem;//tarefa
								Atarefa[i][3] = $(this).attr("id");//data1
								ordem++;
								fila_ante = fila;
							}
							else
							{
								if(Atarefa[i][1]!=$(this).attr("tarefa"))
								{
									hora2 = $('#right_'+fila_ante).children('#'+dataAnterior).attr('hora2');
									Atarefa[i][4] = dataAnterior;//data2
									Atarefa[i][5] = hora2//hora fim
									Atarefa[i][6] = situacao
									i++;
									Atarefa[i] = [];//Array()
									Atarefa[i][0] = IdSubItem;//IdSubItem
									Atarefa[i][1] = $(this).attr("tarefa");//tarefa
									Atarefa[i][2] = ordem;//tarefa
									Atarefa[i][3] = $(this).attr("id");//data1
									ordem++;
									fila_ante = fila;
								}
								else if(fila > fila_ante)
								{
									hora2 = $('#right_'+fila_ante).children('#'+dataAnterior).attr('hora2');								
									Atarefa[i][4] = dataAnterior;//data2
									Atarefa[i][5] = hora2//hora fim
									Atarefa[i][6] = situacao
									i++;
									Atarefa[i] = [];//Array()
									Atarefa[i][0] = IdSubItem;//IdSubItem
									Atarefa[i][1] = $(this).attr("tarefa");//tarefa
									Atarefa[i][2] = ordem;//tarefa
									Atarefa[i][3] = $(this).attr("id");//data1
									ordem++;
									fila_ante = fila;
								}
							}
							dataAnterior = $(this).attr("id");
							situacao = $(this).attr("situacao");//situacao
							j++;
						}
					});
					if(dataAnterior!="")
					{
						hora2 = $('#right_'+fila_ante).children('#'+dataAnterior).attr('hora2');
						Atarefa[i][4] = dataAnterior;//data2
						Atarefa[i][5] = hora2;//hora fim	
						Atarefa[i][6] = situacao
					}
				}
			});
			if(Atarefa.length > 0)
			{
				/*for(var k = 0; k < Atarefa.length; k++)
				{
					alert(Atarefa[k][0]+' : '+Atarefa[k][1]+' : '+Atarefa[k][2]+' : '+Atarefa[k][3]+' : '+Atarefa[k][4]+' : '+Atarefa[k][5]);
				}*/
				$.pleasewait();//mostrar mensagem aguarde...
				//$('#msn_save').removeAttr("style","display:none;");//mostrar mensagem de aguarde...
				$.ajax({
				type: "POST",
				url: "ajax/ajax_salvar_tarefas.php",
				data: {ArrayTarefa:Atarefa,VetorSubItem:ArraySubItem,usuario:usuario,op:op},
				cache: false,
				success: function(html)
				{
					$("#msn_save").fadeOut();
					$("#copy").removeAttr("disabled",true);//habilitar botao duplicar
					$("#AllCodigo").attr("checked",false);//desmarcar checkbox
					$(".codigo").attr("checked",false);//desmarcar checkbox
					$('.save').click();
					setTimeout(function () {location.reload()},2000);
				}
				});
			}
			else
			{
				alertify.alert("Adicionar tarefas e clicar em [Salvar Grafico]");
			}	
		}
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
	/*********************responsavel da fase, etapa************************/
	$('#save_responsavel').click(function()
	{	
		responsavel = $('#lista_resposavel').val();
		fila_row    = $('#fila1').val();
		fila1       = 'right_'+fila_row.substring(5);
		fila2		= fila1;
		tipo        = $('#tipo').val();
		if(responsavel != 0)
		{
			dataString = 'responsavel='+responsavel+'&usuario='+usuario+'&op=SaveResponsavel';
			$.ajax({
				type: "POST",
				url: "ajax/ajax_menu_direito.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					html = html.split('_');
					//$('#'+fila_row).children().next().children('.itemResponsa').html('[R. '+html[0]+']');//nome responsavel
					$('#'+fila_row).children().next().children('.itemResponsa').children('.divResponsavel').html('[R. '+html[0]+']');
					$('#'+fila_row).children().next().children('.itemResponsa').attr('login',html[1]);//login responsavel
					vetor = ['53','54','55','56'];//M1, M2, M3, M4
					estado = 'true';
					i = 0;
					tarefa1 = html[2];//nova tarefa do responsavel
					cor     = html[3];//cor tarefa
					sigla   = html[4];//sigla tarefa
					//alertify.alert("Salvo com Sucesso!");
					//$.pintarCelda(fila1,data1,fila2,data2,cor,tarefa1,sigla);
					$('.closeMenu').click();
					if(tipo > 0 )
					{
						if($('#finalizar').is(':visible'))//finalizar
						{
							$('#save_completo').click();
						}
						else//realizado
						{
							$('#save_realizado').click();
						}
					}
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
		alertify.confirm('Tem certeza que deseja <b>FINALIZAR</b>', function (e) {
		if(e)
		{
			id_ctr   = $('#id_ctr').val();
			id_etapa = $('#id_pai').val();
			if(id_ctr > 0)
			{
				$.pleasewait();//mostrar mensagem de aguarde...
				dataString = 'id_ctr='+id_ctr+'&usuario='+usuario+'&id_etapa='+id_etapa+'&op=finalizar';
				$.ajax({
				type: "POST",
				url: "ajax/ajax_projeto.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					if(html == "1")//finalizado
					{
						//$('#msn_save').removeAttr("style","display:none;");//mostrar mensagem de aguarde...
						$("#AllCodigo").attr("checked",false);//desmarcar checkbox
						$(".codigo").attr("checked",false);//desmarcar checkbox
						setTimeout(function () {location.reload()},2000);
						//$('#save_completo').click();
					}
					else if(html == "0")//iten(s) sem etapa(s)
					{
						setTimeout($.unblockUI, 0);
						alertify.alert("Tem <b>Iten(s)</b> que faltam salvar na(s) <b>Etapa(s)</b>");
					}
					else if(html == "2")//etapa(s) sen iten(s)
					{
						setTimeout($.unblockUI, 0);
						alertify.alert("Tem <b>Etapas(s)</b> que não tem <b>Itens(s)</b>");
					}
				}
				});
			}
		}
		});
	});
	$('#revisao').click(function(){
		alertify.confirm('Tem certeza que deseja criar <b>REVIS&Atilde;O</b>', function (e) {
		if(e)
		{
			id_ctr = $('#id_ctr').val();
			if(id_ctr > 0)
			{
				$.pleasewait();//mostrar mensagem de aguarde...
				dataString = 'id_ctr='+id_ctr+'&op=revisao';
				$.ajax({
				type: "POST",
				url: "ajax/ajax_projeto.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					location.reload();
				}
				});
			}
		}
		});
	})
	/******ADICIONAR ALERTA******/
	$('#alerta_add').click(function(){
		//adicionar linha na tabela
		cor = '';
		cont = '';
		$('#alerta_tabela tr').each(function(index, element) {
            cor = $(this).attr('style');//pegar ultima cor do TR
			cont = $(this).children().html();
			alert(cont);
        });
		alerta_data = $('#alerta_data').val();
		$.ajax({
			type: "POST",
			url: "ajax/ajax_alertas.php",
			data: {op:"insertar",alerta_data:alerta_data,cor:cor},
			cache: false,
			success: function(html)
			{
				$('#alerta_tabela').append(html);
			}
		});
	});
	/********APAGAR ALERTA*********/
	$('.alerta_apagar').live('click',function(){
		valorid = $(this).attr('id');
		if(valorid > 0)
		{
		
		}
		else
		{
			$(this).parent().parent().remove();
		}
	});
	/*******SALVAR ALERTA*******/
	$('.alerta_salvar').live('click',function(){
		alerta_obs    = $(this).parent().parent().children().children('.alerta_obs').val();
		alerta_para   = $(this).parent().parent().children().next().children().val();
		alerta_copia  = $(this).parent().parent().children().next().next().children().val();
		alerta_hora   = $(this).parent().parent().children().next().next().next().next().children('.alerta_hora').val();
		alerta_minuto = $(this).parent().parent().children().next().next().next().next().children('.alerta_minuto').val();
		alerta_data   = $('#alerta_data').val();
		id_ctr 	      = $('#id_ctr').val();
		id_detalhe    = $('#id_detalhe').val();
		tipo 		  = $('#tipo').val();
		if(alerta_obs != "")
		{
			if(alerta_para != "")
			{
				if(alerta_copia != "")
				{
					if(alerta_hora != "--")
					{
						if(alerta_minuto != "--")
						{
							alerta_data = $('#alerta_data').val();
							$.ajax({
								type: "POST",
								url: "ajax/ajax_alertas.php",
								data: {op:"SalvarDadosAlerta",alerta_minuto:alerta_minuto,alerta_hora:alerta_hora,alerta_copia:alerta_copia,alerta_para:alerta_para,alerta_obs:alerta_obs,id_ctr:id_ctr,id_detalhe:id_detalhe,tipo:tipo,alerta_data:alerta_data,usuario:usuario},
								cache: false,
								success: function(html)
								{
									$('#alerta_tabela .trTitulo').nextAll().remove();
									$('#alerta_tabela tbody').append(html);
								}
							});
						}
						else
						{
							$(this).parent().parent().children().next().next().next().next().children('.alerta_minuto').focus();
							alertify.alert("Deve escolher uma Minuto");
						}			
					}
					else
					{
						$(this).parent().parent().children().next().next().next().next().children('.alerta_hora').focus();
						alertify.alert("Deve escolher uma Hora");
					}
				}
				else
				{
					$(this).parent().parent().children().next().next().children().focus();
					alertify.alert("Deve escolher Copia Para");
				}
			}
			else
			{
				$(this).parent().parent().children().next().children().focus();
				alertify.alert("Deve escolher Para");
			}
		}
		else
		{
			$(this).parent().parent().children().children('.alerta_obs').focus();
			alertify.alert("Deve preencher o campo Observa&ccedil;&atilde;o");
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