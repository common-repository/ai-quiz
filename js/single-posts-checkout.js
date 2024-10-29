
const stripe = Stripe(
  "pk_live_51NY8lkLm7tF3W0OjkbcXAQ0Bo7H3cW2GJcpysM6XwvWeuWesGJXC59mNtlM4oywdrLY1vQ47UN7KTzha1H72BcQ400kCJ2J59C"
);
let elements;





// Variables iniciales
let pricePerQuiz = 0.3; // En euros
let maxDiscount = 0.20; // 20% máximo

// Función para calcular el descuento y el precio total
function updateValues() {
    let nquizs = document.getElementById("n_quizs").value; // Obtiene el número de quizs
    let discount = Math.min((nquizs - 10) * 0.002, maxDiscount); // Calcula el descuento
    let totalPrice = (nquizs * pricePerQuiz) * (1 - discount); // Calcula el precio total

    // Actualiza los valores en la página
    document.getElementById("n_quizs_text").innerText = `${nquizs} quizs`;
    document.getElementById("price_text").innerText = `${totalPrice.toFixed(2)}€`;
    document.getElementById("price_text").setAttribute('data-price', totalPrice.toFixed(2));
    document.getElementById("ai-percent").innerText = `-${(discount * 100).toFixed(2)}%`;
}

// Ejecuta la función al cambiar el valor del rango
document.getElementById("n_quizs").addEventListener('input', updateValues);


// Ejecuta la función al cambiar el valor del rango
document.getElementById("n_quizs").addEventListener('input', updateValues);

jQuery(document).ready(function($) {

	$(function() {
		$('#ai-quiz-show_pay').on('click', function(){
			show_pay();
		});

		function show_pay(pro = false) {
			if (document.getElementById("ai-quiz-custom-pop")) {
				document.getElementById("ai-quiz-custom-pop").remove();
			  }
			  price = document.querySelector("#price_text").getAttribute("data-price");
			  n_quizs = document.getElementById("n_quizs").value;
			  const div = document.createElement("div");
			  div.setAttribute("class", "ai-quiz-popup-container");
			
			  div.setAttribute("id", "ai-quiz-pop");
			
			  div.innerHTML = `
			  <div class="ai-quiz-popup flex-column align-items-center" id="ai-quiz-pop-cont">
			  <form id="payment-form" class="bg-white">
		  
			  <h3 class="text-center mb-5">${n_quizs} quizs</h3>
		  
				<div id="payment-element"></div>
		  
				<button id="submit">
		  
				  <div class="spinner hidden" id="spinner"></div>
		  
				  <span id="button-text">Pay <strong>` +
			  price +
			  `€</strong></span>
		  
				</button>
		  
			  </form>
			  </div>`;
			  //Creamos el elemento donde va a pegarse el popup
			  const div_pop = document.createElement("div");
			  div_pop.setAttribute("id", "ai-quiz-custom-pop");
			  document.body.appendChild(div_pop);
			
			//Añadimos el popup
			document.getElementById("ai-quiz-custom-pop").appendChild(div);
			
			document.getElementById('ai-quiz-pop').onclick = function(e) {
			
				container=document.getElementById('ai-quiz-pop-cont')
			
				if (container !== e.target && !container.contains(e.target)) {
			
				document.getElementById("ai-quiz-pop").remove();
			
				}
			
			}
			ai_quiz_initialize(price, n_quizs);
		  
			document
			  .querySelector("#payment-form")
			  .addEventListener("submit", handleSubmit);
		  }
function get_if_sub() {
	$.ajax({
		url : 'admin-ajax.php',
		type: 'get',
		dataType: 'json',
		data: {
		  action: 'ai_quiz_get_info'
		},
		success: function(data) {
			console.log(data)
			if(!data.exito){
					document.getElementById("pills-single-quizs").innerHTML =
					  "Only available to subscribers";
				}
			else{
				if (data.user.subscription_id == null) {
					document.getElementById("pills-single-quizs").innerHTML =
					  "Only available to subscribers";
				}
			}
		},
		error: function(err) {
			reject(err);
		}
	});
  }
  
  get_if_sub();


  checkStatus();

// Fetches a payment intent and captures the client secret

async function ai_quiz_initialize(price, n_quizs) {
  setLoading(true);
  $.ajax({
	url : 'admin-ajax.php',
	type: 'post',
	dataType: 'json',
	data: {
	  action: 'ai_quiz_single_checkout', price: price, n_quizs: n_quizs
	},
	success: function(data) {
		console.log(data)
		if(data.exito){
	  
			const clientSecret = data.clientSecret;
	  
			elements = stripe.elements({ clientSecret });
	  
			const paymentElement = elements.create("payment");
	  
			paymentElement.mount("#payment-element");
	  
			setLoading(false);
			}
	},
	error: function(err) {
		reject(err);
	}
});

}

async function handleSubmit(e) {
  e.preventDefault();

  setLoading(true);

  const { error } = await stripe.confirmPayment({
    elements,

    confirmParams: {
      // Make sure to change this to your payment completion page

      return_url: window.location.href,
    },
  });

  // This point will only be reached if there is an immediate error when

  // confirming the payment. Otherwise, your customer will be redirected to

  // your `return_url`. For some payment methods like iDEAL, your customer will

  // be redirected to an intermediate site first to authorize the payment, then

  // redirected to the `return_url`.

  if (error.type === "card_error" || error.type === "validation_error") {
    showMessage(error.message);
  } else {
    showMessage("An unexpected error occurred.");
  }

  setLoading(false);
}

// Fetches the payment intent status after payment submission

async function checkStatus() {
  const clientSecret = new URLSearchParams(window.location.search).get(
    "payment_intent_client_secret"
  );

  if (!clientSecret) {
    return;
  }

  const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

  switch (paymentIntent.status) {
    case "succeeded":
      console.log(paymentIntent);

      showMessage("Payment succeeded!");

      console.log(paymentIntent.client_secret);

      break;

    case "processing":
      showMessage("Your payment is processing.");

      break;

    case "requires_payment_method":
      showMessage("Your payment was not successful, please try again.");

      break;

    default:
      showMessage("Something went wrong.");

      break;
  }
}

// ------- UI helpers -------

function showMessage(messageText) {
  const messageContainer = document.querySelector("#payment-message");

  messageContainer.classList.remove("hidden");

  messageContainer.textContent = messageText;

  setTimeout(function () {
    messageContainer.classList.add("hidden");

    messageText.textContent = "";
  }, 4000);
}

// Show a spinner on payment submission

function setLoading(isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner

    document.querySelector("#submit").disabled = true;

    document.querySelector("#spinner").classList.remove("hidden");

    document.querySelector("#button-text").classList.add("hidden");
  } else {
    document.querySelector("#submit").disabled = false;

    document.querySelector("#spinner").classList.add("hidden");

    document.querySelector("#button-text").classList.remove("hidden");
  }
}

/*

-

-

-

-

-

-

ENDCHECKOUT

-

-

-

-

-

-

-

*/
});});