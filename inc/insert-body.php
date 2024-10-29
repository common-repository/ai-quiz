<?php
if (!function_exists('ai_quiz_add_integration_code_body')) {

	/**
	 *
	 * 	 * Insert Code into HTML body
	 *
	 * 	 *
	 *
	 * 	 * @since  1.0.0
	 *
	 * 	 * @author Kekotron
	 *
	 * 	 */

	function ai_quiz_add_integration_code_body()
	{


?>

<div id="wp-body-content" class="container">
	<?php

		ai_quiz_add_integration_code_header();
		if(!ai_quiz_check_api_key()){
			ai_quiz_add_integration_code_presentation();
		}else{
	?>
	<div class="wrap" id="AutoQuiz-content">
		<ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">

			<li class="nav-item" role="presentation">

				<button class="nav-link active" style="border: 1px solid #5858ed;" id="pills-home-tab"
					data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab"
					aria-controls="pills-home" aria-selected="true">New Quiz</button>
			</li>
			<li class="nav-item" role="presentation">

				<button class="nav-link" style="border: 1px solid #5858ed;" id="pills-train-tab" data-bs-toggle="pill"
					data-bs-target="#pills-train" type="button" role="tab" aria-controls="pills-train"
					aria-selected="false">My Quizs</button>

			</li>
			<li class="nav-item" role="presentation">

				<button class="nav-link" style="border: 1px solid #5858ed;" data-bs-toggle="pill" type="button"
					role="tab" aria-selected="false"
					onclick="window.location.href= '<?php echo esc_html(get_admin_url()); ?>admin.php?page=autoquiz_upgrade_plan';">Upgrade
					Plan</button>


			</li>

		</ul>

		<div class="container tab-content" id="pills-tabContent">

			<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

				<div class="container px-0 mt-4">
				<div id="ai-quiz-choose" class="text-center">
					<h4 class="my-5">How do you want to create it?</h4>
					<div class="d-flex flex-row align-items-center justify-content-evenly my-5">
						<div class="ai-quiz-choose" id="ai-quiz-choose-post-btn"><img src="<?php echo esc_html(ai_quiz_PLUGIN_URL); ?>/images/post.png"><p>By Post</p></div>
						<div class="ai-quiz-choose" id="ai-quiz-choose-custom-btn"><img src="<?php echo esc_html(ai_quiz_PLUGIN_URL); ?>/images/custom.png"><p>By Custom</p></div>
					</div>
				</div>
				<div id="ai-quiz-choose-post" class="text-center" style="display: none;">
					<div class="d-flex flex-row align-items-center ai-quiz-back-choose my-5" style="cursor:pointer;"><i class="fa fa-angle-left"></i><h5 class="mb-0 ms-3"> Create Quiz By Post</h5></div>
					<!--TABLE OF POSTS -->

					<div style="width:100%; overflow-x:auto;" class="ai-quiz-scrollbar pb-4">
						<table id="ai-quiz-posts-table" class="display responsive nowrap" style="text-align: center;"
							cellspacing="0" width="90%">
							<thead>
								<tr>
									<th scope="col">Choose the post</th>
								</tr>
							</thead>
							<tbody id="ai-quiz-posts-table-tbody">
							</tbody>
						</table>
					</div>
				</div>
				<div id="ai-quiz-choose-custom" class="text-center" style="display: none;">
					<div class="d-flex flex-row align-items-center ai-quiz-back-choose my-5" style="cursor:pointer;"><i class="fa fa-angle-left"></i><h5 class="mb-0 ms-3"> Create Quiz By Custom Input</h5></div>
					<h5>Select your input type</h5>
					<div class="d-flex flex-row align-items-center justify-content-evenly my-5">
						<div class="ai-quiz-choose" id="ai-quiz-choose-pdf-btn"><img src="<?php echo esc_html(ai_quiz_PLUGIN_URL); ?>/images/pdf.png"><p>PDF</p></div>
						<div class="ai-quiz-choose" id="ai-quiz-choose-url-btn"><img src="<?php echo esc_html(ai_quiz_PLUGIN_URL); ?>/images/url.png"><p>URL</p></div>
						<div class="ai-quiz-choose" id="ai-quiz-choose-topic-btn"><img src="<?php echo esc_html(ai_quiz_PLUGIN_URL); ?>/images/topic.png"><p>Topic</p></div>
					</div>
					<!--DROP FILE -->
					<div id="ai-quiz-pdf-show" class=" align-items-center justify-content-center text-center flex-column" style="display: none;">
						<label for="exampleInputEmail1">Insert PDF you want to quiz about.</label>
						<div class="ai-quiz-drop-zone my-5" id="ai-quiz-drop-zone">
							<span class="ai-quiz-drop-zone__prompt">Drop file here or click to upload</span>
							<input type="file" name="myFile" id="myFile" accept="application/pdf" class="ai-quiz-drop-zone__input">
						</div>
					</div>
					<!--URL -->
					<div class="form-group my-4" id="ai-quiz-url-show" style="display: none;">
						<label for="exampleInputEmail1">Insert Url you want to quiz about.</label>
						<input class="form-control my-5" id="ai-quiz-url-input" type="text" placeholder="https://example.com">
					</div>
					<!--TOPIC -->
					<div class="form-group my-4" id="ai-quiz-topic-show" style="display: none;">
						<label for="exampleInputEmail1">Insert topic you want to quiz about.</label>
						<textarea class="form-control my-4" id="ai-quiz-topic-input" maxlength="1000" rows="3" placeholder="Bitcoin"></textarea>
					</div>
					<div id="ai-quiz-choose-idiom-custom">
					</div>
					<button class="btn btn-primary my-5 m-auto" id="ai-quiz-create-custom-btn" style="display: none;">Create new quiz</button>
					<div class="ai-quiz-loader" id="ai-quiz-loader-custom"></div>
					<div id="ai-quiz-err-custom"></div>
				</div>

				</div>

			</div>


			<!--Content Plan-->
			<div class="tab-pane fade" id="pills-train" role="tabpanel" aria-labelledby="pills-train-tab">
				<div class="my-5 ai-quiz-scrollbar pb-3" style="width:100%; overflow-x:auto;">
				<div class="ai-quiz-loader" id="ai-quiz-loader-table"></div>
					<table id="ai-quizs-table" class="display responsive nowrap" style="text-align: center;" cellspacing="0">
						<thead>
							<tr>
								<th scope="col">Quiz</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody id="ai-quizs-table-tbody">
						</tbody>
					</table>
				</div>
				<div id="ai-quiz-shortcode"></div>

			</div>

			<!--END BUY TOKENS-->
			<!--FREE TOKENS-->
			<div class="tab-pane fade" id="pills-free-tokens" role="tabpanel" aria-labelledby="pills-free-tokens-tab">



			</div>
			<!--END FREE TOKENS-->

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