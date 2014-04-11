// JavaScript Document

$(document).ready(function() {

            $("#submit").click(function(){
			var id = $(this).children(":selected").attr("id_lst");
			//var tp = $(this).children(":selected").attr("tipo");
			//var dataString = 'id='+id+'&tipo='+tp+'&op=2';
			
            $.ajax({
            url: "cabecalho.php",
            type: "POST",
            data: {
                id: id,
                firstName: $("#firstName").val(),
                lastName: $("#lastName").val(),
                email: $("#email").val()
            },
            dataType: "JSON",
            success: function (jsonStr) {
              //  $("#result").text(JSON.stringify(jsonStr));
				alert(jsonStr.id);
				alert(jsonStr.descricao);
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
			
        });

    }); 

    });
	