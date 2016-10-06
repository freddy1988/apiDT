<html>
<head>
<script src="../bootstrap/js/functions.js"></script>
<script>
		var setHeader = function(xhr) {
    		xhr.setRequestHeader('Authorization', Tools.readCookie("user"));
		}
		$.ajax({
			type: 'GET',
			url: 'http://apidp.elasticbeanstalk.com/v1/money/'+<?php echo $_POST["id"];?>,
			cache: false,
			dataType: "json",
			beforeSend: setHeader,
			success: function(results, textStatus, jqXHR){
        		var i;
        		var html="<thead>";
                html += "<tr>";
                  html += "<th class='hidden-xs hidden-sm'>Price</th>";
				  html += "<th class='hidden-xs hidden-sm'>Update Date</th>";
                html += "</tr>";
              html += "</thead>";
              	html += "<tbody>";
        			for ( i in results.prices ) {
        				html += "<tr>";
						html += "<td class='hidden-xs hidden-sm'>"+results.prices[i].price+"</td>";
						html += "<td class='hidden-xs hidden-sm'>"+results.prices[i].update_date+"</td>";					
						html += "</tr>";
        			}
        			html += "</tbody>";
					document.getElementById("selectprof").innerHTML = html;
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(textStatus);
			}
			});
</script>
</head>
<form id="form2" role="form" style="margin:0 auto;max-width:600px;padding:15px;">
<legend>Money (<?php echo $_POST["name"];?>) Historial</legend>
<div class="table-responsive">
	<table class="table table-striped" id="selectprof">
         </table>    
</div>
<button type="button" class="btn btn-primary" id="return" value="return">Return</button>
</form>

</html>
