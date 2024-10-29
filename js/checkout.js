//PROMOTIONS

jQuery(document).ready(function($) {$(function() {

	$('.ai-quiz-sub').on('click', function(){
		console.log($(this).attr("data-id"));
		$.ajax({
			url : 'admin-ajax.php',
			type: 'post',
			data: {
			  action: 'ai_quiz_create_sub_link',
			  id: $(this).attr("data-id")
			},
			success: function(data) {
				console.log(data)
				window.location.href = data.url;
			}
		});
	});
	$('.ai_quiz_get_promotion').on('click', function(){
		var promo = $(this).attr("data-type");
		$.ajax({
			url : 'admin-ajax.php',
			type: 'post',
			data: {
			  action: 'ai_quiz_get_promo',
			  promo: promo
			},
			success: function(data) {
				console.log(data)
				if(data.exito){
					ai_quiz_get_info();
				}else {
					document.getElementById("ai-quiz-" + promo + "-error").style.display =
					"block";
					document.getElementById("ai-quiz-" + promo + "-error").innerHTML =
					data.error;
				}
			}
		});
	});
});});