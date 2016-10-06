$(document).ready(function(){
	$.ajaxSetup({cache: false});

	$("#save").click(function(){
		if(($('#usernamer').val().trim().length > 0)&&($('#passwordr').val().trim().length > 0)){
			if($('#passwordr').val()==$('#passwordr2').val()){
				var username = $('#usernamer').val();
				var password = $('#passwordr').val();
				var datos ='{"username":"'+username+'","password":"'+password+'"}';
				var setHeader = function(xhr) {
		    		xhr.setRequestHeader('Authorization', $('#appkey').val());
				}
				$.ajax({
					type: 'POST',
					url: 'http://apidp.elasticbeanstalk.com/v1/users/register',
					cache: false,
					data: datos,
					dataType: "json",
					beforeSend: setHeader,
					success: function(results, textStatus, jqXHR){
						messages(results.error, results.message);
					},
					error: function(jqXHR, textStatus, errorThrown){
						alert(textStatus);
					}
				});
			}
			else{
				messages(true, "Verify password and confirmation password");
			}
		}
		else{
			messages(true, "Some fields are empty");
		}
	});

	$("#login").click(function(){
		if(($('#username').val().trim().length > 0)&&($('#password').val().trim().length > 0)){
			var username = $('#username').val();
			var password = $('#password').val();
			var datos ='{"username":"'+username+'","password":"'+password+'"}';
			var setHeader = function(xhr) {
	    		xhr.setRequestHeader('Accept', '*/*');
	    		xhr.setRequestHeader('Content-Type', 'application/json');
	    		xhr.setRequestHeader('Accept-Language', 'es-419,es;q=0.8');
			}
			$.ajax({
				type: 'POST',
				url: 'http://apidp.elasticbeanstalk.com/v1/users/login',
				cache: false,
				data: datos,
				dataType: "json",
				beforeSend: setHeader,
				success: function(results, textStatus, jqXHR){
					if(results.error)
						messages(results.error, results.message);
					else{
						Tools.createCookie("user", results.api_key, 7);
						window.location="/pages/dashboard.html";
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(textStatus);
				}
				});
		}	
		else{
			messages(true, "Some fields are empty");
		}	
	});

	$("#add_money").click(function(){
		if(($('#name').val().trim().length > 0)&&($('#price').val().trim().length > 0)){
			var name = $('#name').val();
			var price = $('#price').val();
			var datos ='{"name":"'+name+'","price":"'+price+'"}';
			var setHeader = function(xhr) {
	    		xhr.setRequestHeader('Authorization', Tools.readCookie("user"));
			}
			$.ajax({
				type: 'POST',
				url: 'http://apidp.elasticbeanstalk.com/v1/money',
				cache: false,
				data: datos,
				dataType: "json",
				beforeSend: setHeader,
				success: function(results, textStatus, jqXHR){
						messages(results.error, results.message);
						window.location="/pages/dashboard.html";
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(textStatus);
				}
				});
		}	
		else{
			messages(true, "Some fields are empty");
		}	
	});

	$("#edit_money").click(function(){
		if(($('#namee').val().trim().length > 0)&&($('#pricee').val().trim().length > 0)){
			var name = $('#namee').val();
			var price = $('#pricee').val();
			var datos ='{"name":"'+name+'","price":"'+price+'"}';
			var setHeader = function(xhr) {
	    		xhr.setRequestHeader('Authorization', Tools.readCookie("user"));
			}
			$.ajax({
				type: 'PUT',
				url: 'http://apidp.elasticbeanstalk.com/v1/money/'+$("#id_money").val(),
				cache: false,
				data: datos,
				dataType: "json",
				beforeSend: setHeader,
				success: function(results, textStatus, jqXHR){
						messages(results.error, results.message);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(textStatus);
				}
			});
		}	
		else{
			messages(true, "Some fields are empty");
		}	
	});
	$("#edit_message").click(function(){
		if($('#namme').val().trim().length > 0){
			var message = $('#namme').val();
			var datos ='{"message":"'+message+'"}';
			var setHeader = function(xhr) {
	    		xhr.setRequestHeader('Authorization', Tools.readCookie("user"));
			}
			$.ajax({
				type: 'PUT',
				url: 'http://apidp.elasticbeanstalk.com/v1/messages',
				cache: false,
				data: datos,
				dataType: "json",
				beforeSend: setHeader,
				success: function(results, textStatus, jqXHR){
						messages(results.error, results.message);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(textStatus);
				}
			});
		}	
		else{
			messages(true, "Some fields are empty");
		}
	});
	$("#return").click(function(event) {
		window.location="/pages/dashboard.html";
		});
	$('#login-form-link').click(function(e) {
		$("#imglogo").hide();
		$("#login-form").delay(100).fadeIn(100);
 		$("#register-form").fadeOut(100);
		$('#register-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	$('#register-form-link').click(function(e) {
		$("#imglogo").hide();
		$("#register-form").delay(100).fadeIn(100);
 		$("#login-form").fadeOut(100);
		$('#login-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	$('#add-form-link').click(function(e) {
		$("#add-form").delay(100).fadeIn(100);
 		$("#money-form").fadeOut(100);
		$('#money-form-link').removeClass('active');
		$("#messages-form").fadeOut(100);
		$('#messages-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	$('#money-form-link').click(function(e) {
		$("#money-form").delay(100).fadeIn(100);
 		$("#add-form").fadeOut(100);
		$('#add-form-link').removeClass('active');
		$("#messages-form").fadeOut(100);
		$('#messages-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	$('#messages-form-link').click(function(e) {
		$("#messages-form").delay(100).fadeIn(100);
 		$("#add-form").fadeOut(100);
		$('#add-form-link').removeClass('active');
		$("#money-form").fadeOut(100);
		$('#money-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
		var setHeader = function(xhr) {
    		xhr.setRequestHeader('Authorization', Tools.readCookie("user"));
		}
		$.ajax({
			type: 'GET',
			url: 'http://apidp.elasticbeanstalk.com/v1/messages',
			cache: false,
			dataType: "json",
			beforeSend: setHeader,
			success: function(results, textStatus, jqXHR){
					document.getElementById("namme").value = results.message;
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(textStatus);
			}
		});
	});

	function messages(error, message){
		if (!error)
			$("#messages").hide().html('<span class="label label-success" style="font-size:175%">' +
			message + '</span>').fadeIn(500).delay(1000).fadeOut(1500);
		else
			$("#messages").hide().html('<span class="label label-danger" style="font-size:175%">' +
			message + '</span>').fadeIn(500).delay(1000).fadeOut(1500);
	}
});

var Tools = {
  createCookie: function(name, value,days) {
    if (days) {
      var date = new Date();
      date.setTime(date.getTime()+(days*24*60*60*1000));
      var expires = "; expires="+date.toGMTString();
    }else var expires = "";
      document.cookie = name+"="+value+expires+"; path=/";
  },

  readCookie: function(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
      var c = ca[i];
      while (c.charAt(0)==' ') c = c.substring(1,c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
  },

  eraseCookie: function(name) {
    Tools.createCookie(name,"",-1);
  }
};

    