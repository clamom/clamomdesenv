<!--/*
*===============================================================
*  Sistema      : SIG 
*  Versão       : 1.0.0              Criado em : 
*  Desenvolvido : 
*  Módulo       : listar_pendencias_dos_itens_geral
*  Objetivo     : Tela de consulta das minhas pendencias
*   teste de servidor svn
*  *****************  Controle das alterações  *****************  afda adf asd asdf asdf
*  Data        	Desenvolvedor	Descrição                                         
* 24/03/2014	Gilberto		Incluido opção de filtro onScriptiInit e botão de retorno Atalhos Pesquisa                                                               
* ===============================================================
*/-->
<?php
/* 
// sc_redir(listar_pendencias_dos_itens_geral,login = [usr_login];email = [usr_email];nome = [usr_name];idctr=[idctr];iditem=[item]);
// alert(nome);
// $.post("../listar_pendencias_dos_itens_geral/listar_pendencias_dos_itens_geral.php", {nome:"'.[usr_name].'",idctr:"'.[idctr].'",iditem:"'.[item].'", login:"'.[usr_login].'"});
*/

$inicio = '<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Painel Atalhos</title>
</head>
<body>
<script src="http://192.168.0.190/erpclamom/_lib/personalizado/cronograma_macro/js/jquery-1.9.1.js"></script>
<script src="http://192.168.0.190/erpclamom/_lib/personalizado/cronograma_macro/js/jquery.min.js" type="text/javascript"></script>
<script src="http://192.168.0.190/erpclamom/_lib/personalizado/cronograma_macro/js/grafico.js" type="text/javascript"></script>
<script src="http://192.168.0.190/erpclamom/_lib/personalizado/cronograma_macro/js/jquery.maskedinput-1.2.2.js" type="text/javascript"></script>
<script src="http://192.168.0.190/erpclamom/_lib/personalizado/cronograma_macro/js/jquery.maskedinput-1.2.2.js" type="text/javascript"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://192.168.0.190/erpclamom/_lib/personalizado/cronograma_macro/js/chromatable/jquery.chromatable.js"></script>
<link rel="stylesheet" href="http://192.168.0.190/erpclamom/_lib/personalizado/cronograma_macro/css/alert/alertify.core.css" />
<link rel="stylesheet" href="http://192.168.0.190/erpclamom/_lib/personalizado/cronograma_macro/css/alert/alertify.default.css" id="toggleCSS" />
<script src="http://192.168.0.190/erpclamom/_lib/personalizado/cronograma_macro/js/alert/alertify.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://192.168.0.190/erpclamom/_lib/personalizado/cronograma_macro/css/grafico.css"/>
<script>
$(document).ready(function(e) {
	$(".btn_pend").click(function(){
		var nome = $("#nome").val();
		var login = $("#login").val();
		var ctr = $("#ctr").val();
		var item = $("#item").val();
	//window.open("http://192.168.0.190/erpclamom/listar_pendencias_dos_itens_geral/listar_pendencias_dos_itens_geral.php?nome="+nome+"&login="+login+"&idctr="+ctr+"&iditem="+item);
window.open("http://172.19.10.2:8080/scriptcase7/app/ERP_CLAMOM/listar_pendencias_dos_itens_geral/listar_pendencias_dos_itens_geral_teste.php?idctr="+ctr+"&iditem="+item);
	})
	$(".btn_sig").click(function(){
		var nome = $("#nome").val();
		var login = $("#login").val();
		var ctr = $("#ctr").val();
		var item = $("#item").val();
		//window.open("http://192.168.0.190/erpclamom/blank_acesso_atalhos/blank_acesso_atalhos.php?nome="+nome+"&login="+login+"&idctr="+ctr+"&iditem="+item);
		window.open("http://172.19.10.2:8080/scriptcase7/app/ERP_CLAMOM/menu/menu.php");
		})
});
</script>
<style type="text/css">
.btn_pend 
	{
	background-image: url("http://192.168.0.190/webdesen/images/acesso/grp__NM__CalendarioTarefas.jpg");
	background-position:center;
    background-attachment:extends;
	background-border:none;
	position:relative;
    width: 150px;
	height: 110px;
	}
.btn_sig 
	{
	background-image: url("http://192.168.0.190/webdesen/images/acesso/grp__NM__AcessoSig.png");
	background-position:center;
    background-attachment:extends;
	background-border:none;
	position:relative;
    width: 150px;
	height: 110px;
	}
</style>
</head>
<body>
<table>
<input type="hidden" id="nome" 	name="nome" value="'.$usr_name.'" />
<input type="hidden" id="login" name="login" value="'.$usr_login.'" />
<input type="hidden" id="ctr" 	name="ctr" 	value="'.$idctr.'" />
<input type="hidden" id="item" 	name="item" value="'.$iditem.'" />
</table>
';

$btn_pend = "";
$pendid=1;

// [pend]  = "<img src='../_lib/img/edit.png' class='btn_pend".[pendid]."'  height='16' width='16' ></img>";

$botao= '<br><br><br><br><br>
<div width="1200px" height="400px" align="center">
	<table >
		<tr>
			<td>
				<tr>
					<td align="center"  >
						<input type="submit" value="" class="btn_pend">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					<td></td>
					<td align="center" >
						<input type="submit" value="" class="btn_sig">
					</td>
				</tr>
			</td>
			<td>
				<tr>
            		<td align="center">
						<strong>Minhas Pendências</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					<td></td>
					<td align="center">
						<strong>Acesso ao SIG</strong>
					</td>
				</tr>
			</td>
		</tr>
	</table>
</div>';

echo $inicio.$botao;

/*
echo [pend].'<script>
		$(document).ready(function(e) 
			{
				$(".btn_pend'.[pendid].'").attr("style","cursor:pointer;");
				
				$(".btn_pend'.[pendid].'").click(function() 
						{
							var id = $(this).attr("id");
							
							$.post("../finalizar_pendencia/finalizar_pendencia.php", {id: id, login:"'.[usr_login].'",usr_name:"'.[usr_name].'"}).done(function( data )
							{
							});
					});
			});
		</script>';

// }
// $(".btn_concluir'.[pendid].'").hide();
// $(".linha'.[pendid].'").attr("style","color:#000000 !important;");
//	window.location=window.location;
//	location.reload(forceGet);
//	alert("Não é possível concluir uma pendência sem uma resposta!!!");
 */
?>


