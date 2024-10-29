const togglePassword = document.querySelector('#show_hide');
const password = document.querySelector('#ai-api_key');
const eyeIcon = document.querySelector('#eye_icon');
const ai_quiz_email = document.getElementById("ai-email").value;
const ai_quiz_api_key = document.getElementById("ai-api_key").value;

togglePassword.addEventListener('click', function (e) {
	// toggle the type attribute
	const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
	password.setAttribute('type', type);
	// toggle the eye / eye slash icon
	eyeIcon.classList.toggle('fa-eye');
	eyeIcon.classList.toggle('fa-eye-slash');
});


function show_delete_subscription(x){
	x.disabled = true;
	document.getElementById('ai-cancel-subscription').style.display='block';
}



jQuery(document).ready(function ($) {
  $(function () {
	$('#ai-quiz-regenerate-button').on('click', function(){
		ai_quiz_regenerate_api_key($(this).get(0));
	});
	$('#ai-quiz-log-out-button').on('click', function(){
		ai_quiz_log_out($(this).get(0));
	});
	$('#ai-quiz-cancel-sub-button').on('click', function(){
		ai_quiz_cancel_sub($(this).get(0));
	});
	$('#ai-cancel-subscription').on('click', function(){
		ai_cancel_subscription();
	});
	function ai_cancel_subscription() {
		if (
		  confirm(
			"Are you sure you want to cancel your subscription? This action cannot be reversed"
		  ) == true
		) {
			$.ajax({
				url : 'admin-ajax.php',
				type: 'get',
				dataType: 'json',
				data: {
				action: 'ai_quiz_cancel_subscription'
				},
				success: function(data) {
					console.log(data)
					window.location.reload();
				},
				error: function() {
					err.innerHTML="Invalid credentials";
				}
			});
		}
	  }
    function ai_get_settings() {
		$.ajax({
			url : 'admin-ajax.php',
			type: 'get',
			dataType: 'json',
			data: {
			action: 'ai_quiz_get_settings'
			},
			success: function(data) {
				console.log(data)
				//Check if has free plan
				if (!data.user.subscription.has) {
					document.getElementById("ai-free-plan").style.display = "block";
				  }
				  //HAVE SUBSCRIPTION
				  else {
					document.getElementById("ai-subscription-plan").style.display =
					  "block";
					//Subscription name
					document.getElementById("ai-subscription-name").innerHTML =
					  data.user.subscription.name;
					//Subscription n_quizs
					document.getElementById("ai-subscription-n_quizs").innerHTML =
					  data.user.subscription.n_quizs;
					//Subscription price
					document.getElementById("ai-subscription-price").innerHTML =
					  data.user.subscription.price;
					//Subscription next payment
					document.getElementById("ai-subscription-next_payment").innerHTML =
					  data.user.subscription.next_payment;
		
					ai_billing_table(data.user.subscription.invoices);
				  }
			},
			error: function() {
				err.innerHTML="Something went wrong";
			}
		});
    }

    ai_get_settings();
    function ai_billing_table(invoices) {
      $("#ai-billing-table").DataTable().destroy();
      $("#ai-billing-tbody").empty();
      var billing = "";
      for (var i = 0; i < invoices.length; i += 1) {

        billing += `<tr>
							<td><a href="${invoices[i].pdf}" target="_blank">Invoice link</a></td>
							<td>${invoices[i].money} €</td>`;

        billing += `<td>${invoices[i].date}</td>
							</tr>`;
      }
      $("#ai-billing-table").DataTable().destroy();
      $("#ai-billing-table").find("tbody").append(billing);
      $("#ai-billing-table").DataTable().draw();
      //Order by date clicking twice
      $("#ai-billing-date").click();
      $("#ai-billing-date").click();
    }

	function ai_quiz_regenerate_api_key(x) {
		x.display="none";
		var err = document.getElementById("ai-quiz-err");
		var loader = document.getElementById("loader")
		err.innerHTML=""
		loader.style.display="block";
		var xmlhttp = window.XMLHttpRequest
		  ? new XMLHttpRequest()
		  : new ActiveXObject("Microsoft.XMLHTTP");
	  
		xmlhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			const consulta = JSON.parse(this.responseText);
			if(consulta.exito){
				$.ajax({
					url : 'admin-ajax.php',
					type: 'post',
					dataType: 'json',
					data: {
					action: 'ai_quiz_update_api_key', api_key: consulta.api_key
					},
					success: function(data) {
						window.location.reload();
					},
					error: function() {
						err.innerHTML="Something went wrong";
					}
				});
			}else{
				err.innerHTML="Something went wrong";
			}

		  }
		};
	  
		xmlhttp.open("POST", "https://quiz.autowriter.tech/api/regenerate_api_key.php", true);
	  
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	  
		xmlhttp.send("api_key=" + ai_quiz_api_key + "&email=" + ai_quiz_email);
	}
	function ai_quiz_log_out(x) {
		x.display="none";
		var err = document.getElementById("ai-quiz-err");
		var loader = document.getElementById("loader")
		err.innerHTML=""
		loader.style.display="block";
		$.ajax({
			url : 'admin-ajax.php',
			type: 'get',
			dataType: 'json',
			data: {
			action: 'ai_quiz_log_out'
			},
			success: function(data) {
				window.location.reload();
			},
			error: function() {
				err.innerHTML="Something went wrong";
			}
		});
			
	}
  });
});
