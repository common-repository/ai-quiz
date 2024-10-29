<?php


if (!function_exists('ai_quiz_add_integration_code_body_academy')) {


	function ai_quiz_add_integration_code_body_academy()
	{


?>



<div id="wp-body-content" class="container">
	<?php
		ai_quiz_add_integration_code_header();
		if(!ai_quiz_check_api_key()){
			ai_quiz_add_integration_code_presentation();
		}else{
	?>

	<div class="wrap container" id="AutoQuiz-content">
		
		<div class="my-5 text-center">
			<iframe style="width:80%; height:auto; min-height:300px; max-width:700px;"
				src="https://www.youtube.com/embed/2VM9ENQIcO8">
			</iframe>
		</div>
		
		<div class="my-5 text-center">
			<h4>
				If you have any doubts, don't hesitate to visit our <a target="_blank"
					href="https://quiz.autowriter.tech/video-academy">academy</a>
			</h4>
		</div>

		<div class="accordion" id="accordionExample">
			<div class="accordion-item">
				<h4 class="accordion-header" id="headingOne">
					<button class="accordion-button" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						How does it work?
					</button>
				</h4>
				<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						<strong>AutoQuiz</strong> uses language model GPT-3 to create the quizzes for you.

						All you have to do is choose the post you want to gamify.
You can also create quizzes from a pdf, a url or simply a topic.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h4 class="accordion-header" id="headingTwo">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						Is the content generated unique and natural?
					</button>
				</h4>
				<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
					The content created by the plugin is one-of-a-kind and original, due to its utilization of advanced language processing technology.


					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h4 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						How do I choose the language of the post?
					</button>
				</h4>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						<strong>You can choose the language in the select field before you create any quiz.</strong>
						Sometimes, the IA may detect a different
						language. If this happens, you can contact us and we will refund your quiz credit.
					</div>
				</div>
			</div>
		</div>


	</div>

	<?php
	//End function body
	}
	?>
	<div class="wrap mt-5" style="text-align: right;">



		<p>Developed By <a href="https://quiz.autowriter.tech" target="_blank">AutoQuiz</a></p>



	</div>


</div>

<?php

	}
}