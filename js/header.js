document.getElementById("wpfooter").style.display="none";
var ai_quiz_get_info;
var ai_quiz_user;
function close_banner(x){
	document.getElementById("ai-banner-" + x).style.display="none";
}

function insert_api_key(x = true){
	if(x){
		document.getElementById("ai-quiz-send-api-key").style.display="none";
		document.getElementById("ai-quiz-already-api-key").style.display="flex";
	}else{
		document.getElementById("ai-quiz-already-api-key").style.display="none";
		document.getElementById("ai-quiz-email-send").style.display="none";
		document.getElementById("ai-quiz-send-api-key").style.display="flex";
	}
}


jQuery(document).ready(function($) {

	$(function() {
		$('#ai-quiz-start-button').on('click', function(){
			start($(this).get(0));
		});
		$('#ai_quiz_check_email_btn').on('click', function(){
			check_email($(this).get(0));
		});
		$('#ai_quiz_check_all_btn').on('click', function(){
			check_all($(this).get(0));
		});


		function validateEmail(email) {
			var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			return re.test(String(email).toLowerCase());
		}
		function start(x) {
			var btn = x;
			var loader = document.getElementById("loader-1");
			var err = document.getElementById("ai-quiz-error");
			var email = document.getElementById("ai-quiz-email").value;
			if(!validateEmail(email)){
				document.getElementById("ai-quiz-email").classList.add("ai-quiz-required");
				return;
			}else{
				document.getElementById("ai-quiz-email").classList.remove("ai-quiz-required");
			}
			btn.style.display="none";
			loader.style.display="block";
		  var xmlhttp = window.XMLHttpRequest
			? new XMLHttpRequest()
			: new ActiveXObject("Microsoft.XMLHTTP");
		
		  xmlhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
			  const consulta = JSON.parse(this.responseText);
				btn.style.display="block";
				loader.style.display="none";
				err.innerHTML=""
			  if(consulta.exito){
				$.ajax({
					url : 'admin-ajax.php',
					type: 'post',
					dataType: 'json',
					data: {
					  action: 'ai_quiz_update_email', email: email
					},
					success: function(data) {

					}
				});
				document.getElementById("ai-quiz-send-api-key").style.display="none";
				document.getElementById("ai-quiz-email-send").style.display="flex";
			  }else{
				err.innerHTML="Something went wrong";
			  }
			}
		  };
		
		  xmlhttp.open("POST", "https://quiz.autowriter.tech/api/new_email.php", true);
		
		  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		  var gdpr = document.getElementById("ai-gdpr").checked
		  console.log(gdpr)
		  xmlhttp.send("email=" + email + "&promo=" + gdpr);
		}

		function check_email(x) {
			var btn = x;
			var loader = document.getElementById('loader-email');
			var err_html = document.getElementById("ai-quiz-error");
			//var api_key = document.getElementById("ai-quiz-api-key").value;
			var code = '';

			$('.ai-quiz-input-validation').each(function() {
				code += $(this).val();
			});

			code = parseInt(code, 10);


			btn.style.display="none";
			loader.style.display="block";
			get_email_ajax().then(email => {
				console.log(email);
				var xmlhttp = window.XMLHttpRequest
					? new XMLHttpRequest()
					: new ActiveXObject("Microsoft.XMLHTTP");
				
				xmlhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
					const consulta = JSON.parse(this.responseText);
						btn.style.display="block";
						loader.style.display="none";
						err_html.innerHTML=""
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
								err_html.innerHTML="Something went wrong";
							}
						});
					}else{
						err_html.innerHTML="Invalid Credentials";
						
					}
					}
				};
				
				xmlhttp.open("POST", "https://quiz.autowriter.tech/api/check_code.php", true);
				
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send("email=" + email + "&code=" + code);
			}).catch(err => {
				console.log(err);
				err_html.innerHTML="Something went wrong";
				btn.style.display="none";
				loader.style.display="block";
				
			});

			
		}

		function check_all(x) {
			var btn = x;
			var loader = document.getElementById('loader-all');

			var err = document.getElementById("ai-quiz-error");
			var email = document.getElementById("ai-quiz-email-check").value;
			var api_key = document.getElementById("ai-quiz-api-key-check").value;
			if(!validateEmail(email)){
				document.getElementById("ai-quiz-email-check").classList.add("ai-quiz-required");
				return;
			}else{
				document.getElementById("ai-quiz-email-check").classList.remove("ai-quiz-required");
			}
			if(api_key == ""){
				document.getElementById("ai-quiz-api-key-check").classList.add("ai-quiz-required");
				return;
			}else{
				document.getElementById("ai-quiz-api-key-check").classList.remove("ai-quiz-required");
			}

			btn.style.display="none";
			loader.style.display="block";
		  var xmlhttp = window.XMLHttpRequest
			? new XMLHttpRequest()
			: new ActiveXObject("Microsoft.XMLHTTP");
		
		  xmlhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
			  const consulta = JSON.parse(this.responseText);
				btn.style.display="block";
				loader.style.display="none";
				err.innerHTML=""
			  if(consulta.exito){
				$.ajax({
					url : 'admin-ajax.php',
					type: 'post',
					dataType: 'json',
					data: {
					  action: 'ai_quiz_update_email_api_key', email: email, api_key: api_key
					},
					success: function(data) {
						window.location.reload();
					},
					error: function() {
						err.innerHTML="Something went wrong";
					}
				});
			  }else{
				err.innerHTML="Invalid credentials";
			  }
			}
		  };
		
		  xmlhttp.open("POST", "https://quiz.autowriter.tech/api/check_api_key.php", true);
		
		  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		  xmlhttp.send("email=" + email + "&api_key=" + api_key);
		}
		function get_email_ajax(){
			return new Promise((resolve, reject) => {
				$.ajax({
					url : 'admin-ajax.php',
					type: 'get',
					dataType: 'json',
					data: {
					  action: 'ai_quiz_get_email'
					},
					success: function(data) {
						console.log(data);
						resolve(data.email);
					},
					error: function(err) {
						reject(err);
					}
				});
			});
		}
		function ai_quiz_get_credentials_ajax(){
			return new Promise((resolve, reject) => {
				$.ajax({
					url : 'admin-ajax.php',
					type: 'get',
					dataType: 'json',
					data: {
					  action: 'ai_quiz_get_credentials'
					},
					success: function(data) {
						resolve(data.email, data.api_key);
					},
					error: function(err) {
						reject(err);
					}
				});
			});
		}
		
		function numberWithCommas(x) {
		  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		}
		
		ai_quiz_get_info = function() {
			$.ajax({
				url : 'admin-ajax.php',
				type: 'get',
				dataType: 'json',
				data: {
				  action: 'ai_quiz_get_info'
				},
				success: function(data) {
					if(data.exito){
						let progress_token = document.getElementById("ai-quiz-progress-token");

						if (data.user.n_quizs > data.user.n_quizs_sub) {
							progress_token.style.width = "100%";
						} else {
							progress_token.style.width =
							(data.user.n_quizs / data.user.n_quizs_sub) * 100 + "%";
						}
						if (data.user.first_purchase == "1") {
							if (
							  document.body.contains(
								document.getElementById("ai-quiz-first-purchase")
							  )
							) {
							  document.getElementById("ai-quiz-first-purchase").style.display =
								"block";
							}
						  }
					
						  if (data.user.fifth_purchase == "1") {
							if (
							  document.body.contains(
								document.getElementById("ai-quiz-fifth-purchase")
							  )
							) {
							  document.getElementById("ai-quiz-fifth-purchase").style.display =
								"block";
							}
						  }

						document.getElementById("ai-quiz-progress-n-tokens").innerHTML =
							numberWithCommas(data.user.n_quizs) +
							"/" + data.user.n_quizs_sub + " quizs (Refill in " + data.user.next_payment + " days)";
						ai_quiz_user = data.user;
						//Update idiom
						if(document.getElementById("ai-quiz-select-language")){
							document.getElementById("ai-quiz-select-language").value = ai_quiz_user.idiom
						}
					}
				},
				error: function(err) {
					reject(err);
					return null;
				}
			});
		}
		
		ai_quiz_get_info();
		

	});
	let in1 = document.getElementById('otc-1'),
		ins = document.querySelectorAll('.ai-quiz-input-validation')
		if(ins.length > 0){
			let splitNumber = function(e) {
				let data = e.data || e.target.value; // Chrome doesn't get the e.data, it's always empty, fallback to value then.
				if ( ! data ) return; // Shouldn't happen, just in case.
				if ( data.length === 1 ) return; // Here is a normal behavior, not a paste action.
				
				popuNext(e.target, data);
			}
			let popuNext = function(el, data) {
				el.value = data[0]; // Apply first item to first input
				data = data.substring(1); // remove the first char.
				if ( el.nextElementSibling && data.length ) {
					// Do the same with the next element and next data
					popuNext(el.nextElementSibling, data);
				}
			};

			ins.forEach(function(input) {
				input.addEventListener('keyup', function(e){
					// Break if Shift, Tab, CMD, Option, Control.
					if (e.keyCode === 16 || e.keyCode == 9 || e.keyCode == 224 || e.keyCode == 18 || e.keyCode == 17) {
						return;
					}
					
					// On Backspace or left arrow, go to the previous field.
					if ( (e.keyCode === 8 || e.keyCode === 37) && this.previousElementSibling && this.previousElementSibling.tagName === "INPUT" ) {
						this.previousElementSibling.select();
					} else if (e.keyCode !== 8 && this.nextElementSibling) {
						this.nextElementSibling.select();
					}
					
					// If the target is populated to quickly, value length can be > 1
					if ( e.target.value.length > 1 ) {
						splitNumber(e);
					}
				});
		
				input.addEventListener('focus', function(e) {
					// If the focus element is the first one, do nothing
					if ( this === in1 ) return;
					
					// If value of input 1 is empty, focus it.
					if ( in1.value == '' ) {
						in1.focus();
					}
					
					// If value of a previous input is empty, focus it.
					// To remove if you don't wanna force user respecting the fields order.
					if ( this.previousElementSibling.value == '' ) {
						this.previousElementSibling.focus();
					}
				});
			});

			in1.addEventListener('input', splitNumber);
		}
});