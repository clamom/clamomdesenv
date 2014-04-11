// JavaScript Document

$(document).ready(function() {

            $("#submit").click(function(){

            $.ajax({
            url: "text.php",
            type: "POST",
            data: {
                amount: $("#amount").val(),
                firstName: $("#firstName").val(),
                lastName: $("#lastName").val(),
                email: $("#email").val()
            },
            dataType: "JSON",
            success: function (jsonStr) {
                $("#result").text(JSON.stringify(jsonStr));
				alert(jsonStr.amount);
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
	