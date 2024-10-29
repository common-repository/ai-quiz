<?php


if (!function_exists('ai_quiz_add_integration_code_body_style')) {


	function ai_quiz_add_integration_code_body_style()
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
		<p class="my-4" style="font-size: large;">Define your Quiz Style!</p>
		<div class="accordion accordion-flush" id="accordionFlushExample">
			<div class="accordion-item">
				<h5 class="accordion-header" id="flush-headingOne">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
					Question
				</button>
				</h5>
				<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
					<div class="accordion-body">
						<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_bg_color">Background Color:</label>
							<input id="ai_quiz_bg_color" type="color" value="<?php echo esc_html(get_option('ai_quiz_bg_color')); ?>" style="border: none;">
						</div>
						<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_bg_font">Font Color:</label>
							<input id="ai_quiz_bg_font" type="color" value="<?php echo esc_html(get_option('ai_quiz_bg_font')); ?>" style="border: none;">
						</div>
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h5 class="accordion-header" id="flush-headingTwo">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
					Default Option
				</button>
				</h5>
				<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
					<div class="accordion-body">
						<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_option_color">Background Color:</label>
							<input id="ai_quiz_option_color" type="color" value="<?php echo esc_html(get_option('ai_quiz_option_color')); ?>" style="border: none;">
						</div>
						<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_option_font">Font Color:</label>
							<input id="ai_quiz_option_font" type="color" value="<?php echo esc_html(get_option('ai_quiz_option_font')); ?>" style="border: none;">
						</div>
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h5 class="accordion-header" id="flush-headingThree">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
					Selected Option
				</button>
				</h5>
				<div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
					<div class="accordion-body">
					<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_option_color">Background Color:</label>
							<input id="ai_quiz_selected_option_color" type="color" value="<?php echo esc_html(get_option('ai_quiz_selected_option_color')); ?>" style="border: none;">
						</div>
						<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_selected_option_font">Font Color:</label>
							<input id="ai_quiz_selected_option_font" type="color" value="<?php echo esc_html(get_option('ai_quiz_selected_option_font')); ?>" style="border: none;">
						</div>
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h5 class="accordion-header" id="flush-heading4">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
					Failed Option
				</button>
				</h5>
				<div id="flush-collapse4" class="accordion-collapse collapse" aria-labelledby="flush-heading4" data-bs-parent="#accordionFlushExample">
					<div class="accordion-body">
						<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_failed_option_color">Background Color:</label>
							<input id="ai_quiz_failed_option_color" type="color" value="<?php echo esc_html(get_option('ai_quiz_failed_option_color')); ?>" style="border: none;">
						</div>
						<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_failed_option_font">Font Color:</label>
							<input id="ai_quiz_failed_option_font" type="color" value="<?php echo esc_html(get_option('ai_quiz_failed_option_font')); ?>" style="border: none;">
						</div>
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h5 class="accordion-header" id="flush-heading5">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse5" aria-expanded="false" aria-controls="flush-collapse5">
					Success Option
				</button>
				</h5>
				<div id="flush-collapse5" class="accordion-collapse collapse" aria-labelledby="flush-heading5" data-bs-parent="#accordionFlushExample">
					<div class="accordion-body">
						<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_success_option_color">Background Color:</label>
							<input id="ai_quiz_success_option_color" type="color" value="<?php echo esc_html(get_option('ai_quiz_success_option_color')); ?>" style="border: none;">
						</div>
						<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_success_option_font">Font Color:</label>
							<input id="ai_quiz_success_option_font" type="color" value="<?php echo esc_html(get_option('ai_quiz_success_option_font')); ?>" style="border: none;">
						</div>
					</div>
				</div>
				
			</div>
			<div class="my-3">
				<label class="my-3">Customize the phrase</label>
				<input type="text" id="ai-quiz-phrase" class="form-control" value="<?php echo esc_html(get_option('ai_quiz_phrase')); ?>">
			</div>
			<div class="accordion-item">
				<h5 class="accordion-header" id="flush-heading6">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse6" aria-expanded="false" aria-controls="flush-collapse6">
					Explanation and buttons
				</button>
				</h5>
				<div id="flush-collapse6" class="accordion-collapse collapse" aria-labelledby="flush-heading6" data-bs-parent="#accordionFlushExample">
					<div class="accordion-body">
					<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_primary_color">Background Color:</label>
							<input id="ai_quiz_primary_color" type="color" value="<?php echo esc_html(get_option('ai_quiz_primary_color')); ?>" style="border: none;">
						</div>
						<div class="d-flex flex-row align-items-center my-3">
							<label class="me-3" for="ai_quiz_primary_font">Font Color:</label>
							<input id="ai_quiz_primary_font" type="color" value="<?php echo esc_html(get_option('ai_quiz_primary_font')); ?>" style="border: none;">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="d-flex flex-row align-items-center justify-content-center my-4">
			<button class="btn btn-primary me-3" id="ai-quiz-update-style">Update style</button>
			<button class="btn btn-success me-3" id="ai-quiz-reset-style">Reset style</button>
		</div>

		<div>
			<h5 class="my-2 text-center"><?php echo esc_html(get_option('ai_quiz_phrase')); ?></h5>

			<div class="d-flex justify-content-center align-items-center mb-4">
				<div id="ai-quiz-pagination" class="ai-quiz-shortcode-scrollbar p-3 pt-0">
					<!-- Aquí se agregarán los botones de navegación -->
				</div>
			</div>

			<div id="ai-quiz-question">
				<div class="ai-quiz-loader d-flex my-5"></div>
			</div>
			<div class="d-flex flex-row align-items-center justify-content-evenly" id="ai-quiz-next-ant-buttons">
				<button id="ai-quiz-prev-btn" style="display: none;"><</button>
				<button id="ai-quiz-next-btn" style="display: none;">></button>
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