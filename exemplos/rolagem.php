<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META NAME="robots" CONTENT="noindex,nofollow">
	<title>Tabela com barras de rolagens e cabeçalhos fixos</title>
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
</head>
<script type="text/javascript" language="javascript">
$(document).ready(function () {
    // Ao mover a barra de rolagem da tabela, mover seus cabecalhos e o 'versus'
    $("#tabela").scroll(function () {
        $('#tabela #cabecalhoHorizontal, #versus').css('top', $(this).scrollTop());
        $('#tabela #cabecalhoVertical, #versus').css('left', $(this).scrollLeft());
    });
});
</script>
<style type="text/css">
	#tabela
	{
		width: 800px;      /* Largura da minha tabela na tela */
		height: 600px;     /* Altura da minha tabela na tela */
		overflow: auto;    /* Barras de rolagem automáticas nos eixos X e Y */
		margin: 0 auto;    /* O 'auto' é para ficar no centro da tela */
		position:relative; /* Necessário para os cabecalhos fixos */
		top:0;             /* Necessário para os cabecalhos fixos */
		left:0;            /* Necessário para os cabecalhos fixos */
	}
	#tabela table
	{
		border-collapse:collapse; /* Sem espaços entre as células */
	}
	#tabela table td
	{
		font-size:12px;
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		border:1px solid #d8d8d8;	
		width:70px;      /* Células precisam ter altura e largura fixas */
		min-width:70px;  /* Se você não colocar isso, as células menores que 70px vão ser diminuídas */
		max-width:70px; 
		height:30px;
		min-height:30px;
		max-height:30px;
	}
	#tabela #cabecalhoHorizontal td, 
	#tabela #cabecalhoVertical td 
	{
		background-color:buttonface;
	}
	#tabela #cabecalhoHorizontal
	{
		margin-left:72px;  /* 70px de largura do cabecalho vertical + 2 pixels das bordas do cabecalho */
		position:absolute; /* Posição variável em relação ao topo da #tabela */
		top:0;             /* Posição inicial em relação ao topo da #tabela */
		z-index:5;         /* Para ficar por cima da tabela de dados */
	}
	#tabela #cabecalhoHorizontal td 
	{
		text-align:center;
		vertical-align:middle;
	}
	#tabela #cabecalhoVertical
	{
		margin-top:33px;   /* 30px de altura do cabecalho horizontal + 2 pixels das bordas do cabecalho + 1px */
		position:absolute; /* Posição variável em relação a esquerda da #tabela */
		left:0;            /* Posição inicial em relação a esquerda da #tabela */
		z-index:5;         /* Para ficar por cima da tabela de dados */
	}
	#tabela #cabecalhoVertical td
	{
		white-space:nowrap; /* Não quebrar linhas */
		text-align:left;
		/* Aqui temos um problema: preciso de uma margem, mas a largura da margem é somada à largura
		 * da célula e por isso a largura extrapola o tamanho máximo definido (70px). 
		 * Por isso, aqui eu diminuo a largura para, somada à margem, ficar do tamanho certo.
		*/
		width:65px;
		min-width:65px;
		max-width:65px;
		padding-left:5px;
	}
	#tabela #dados
	{
		margin-top:33px;  /* 30px de altura do cabecalho horizontal + 2 pixels das bordas do cabecalho + 1 px*/
		margin-left:72px; /* 70px de largura do cabecalho vertical + 2 pixels das bordas do cabecalho */
		z-index:2;		  /* Menor que dos cabecalhos, para que fique por detrás deles */
	}
	#tabela #dados td
	{
		background:white;
		text-align:center;
	}
	/* Célula com o 'X', que virtualmente pertence ao cabecalho vertical e horizontal */
	#tabela #versus
	{
 		display:inline-block;
		position:absolute;
		top:0;
		left:0;
		z-index:10;
		height:32px;
		line-height:32px;
		width:71px;
		min-width:71px;
		text-align:center;
		vertical-align:middle;
		border:1px solid #d8d8d8;
		background-color:#F4FAE8;
		color:#A1D16D;
	}
</style>
<body>
<div id="tabela">
    <span id="versus">X</span>
    <!-- Primeiro, cria o cabecalho horizontal da tabela -->
    <table id="cabecalhoHorizontal">
        <thead>
            <tr>
               <td>01/03/2013</td><td>02/03/2013</td><td>03/03/2013</td><td>04/03/2013</td><td>05/03/2013</td>
               <td>06/03/2013</td><td>07/03/2013</td><td>08/03/2013</td><td>09/03/2013</td><td>10/03/2013</td>
               <td>11/03/2013</td><td>12/03/2013</td><td>13/03/2013</td><td>14/03/2013</td><td>15/03/2013</td>
               <td>16/03/2013</td><td>17/03/2013</td><td>18/03/2013</td><td>19/03/2013</td><td>20/03/2013</td>
               <td>21/03/2013</td><td>22/03/2013</td><td>23/03/2013</td><td>24/03/2013</td><td>25/03/2013</td>
               <td>26/03/2013</td><td>27/03/2013</td><td>28/03/2013</td><td>29/03/2013</td><td>30/03/2013</td>               
            </tr>
        </thead>
    </table>
	<!-- Depois, cria o cabecalho vertical da tabela -->
    <table id="cabecalhoVertical">
        <thead>
           <tr><td>Ana</td></tr>
           <tr><td>Aparecida</td></tr> 
           <tr><td>Breno</td></tr>    
           <tr><td>Carlos</td></tr>
           <tr><td>Celso</td></tr>
           <tr><td>Danila</td></tr>
           <tr><td>Everton</td></tr>
           <tr><td>Fabiana</td></tr>
           <tr><td>Fernanda</td></tr>
           <tr><td>Filipe</td></tr>
           <tr><td>Gilberto</td></tr>
           <tr><td>Hilda</td></tr>
           <tr><td>Irineu</td></tr>
           <tr><td>Jânio</td></tr>
           <tr><td>Juliana</td></tr>
           <tr><td>Magno</td></tr>
           <tr><td>Marcelo</td></tr>
           <tr><td>Mariana</td></tr>
           <tr><td>Miguel</td></tr>
           <tr><td>Olga</td></tr>
           <tr><td>Patrícia</td></tr>
           <tr><td>Pedro G.</td></tr>
           <tr><td>Pedro M.</td></tr>
           <tr><td>Sabrina</td></tr>
           <tr><td>Waléria</td></tr>
        </thead>
    </table>    
    <!-- Dados da tabela -->
    <table id="dados">        
        <tbody>  
        <!-- Ana -->
        <tr>
        	<td> L </td><td> F </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>
        </tr>
        <!-- Aparecida -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Breno -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Carlos -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Celso -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Danila -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Everton -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Fabiana -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Fernanda -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Filipe -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Gilberto -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Hilda -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Irineu -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>

        <!-- Jânio -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Juliana -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Magno -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Marcelo -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Mariana -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Miguel -->     
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Olga -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Patrícia -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Pedro G. -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Pedro M. -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>    
        </tr>
        <!-- Sabrina -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        <!-- Waléria -->
        <tr>
        	<td> L </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> - </td><td> - </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> P </td><td> - </td><td> - </td><td> P </td>
            <td> P </td><td> P </td><td> P </td><td> P </td><td> - </td>
            <td> - </td><td> P </td><td> P </td><td> P </td><td> P </td>
            <td> P </td><td> - </td><td> - </td><td> P </td><td> P </td>        
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>