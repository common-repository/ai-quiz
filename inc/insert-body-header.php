<?php


if (!function_exists('ai_quiz_add_integration_code_header')) {

	function ai_quiz_add_integration_code_header()
	{

?>


<div class="wrap">
	<div class="d-flex w-100 justify-content-center">
		<img class="AutoQuiz-banner" src="<?php echo esc_html(ai_quiz_PLUGIN_URL); ?>/images/banner-772x250.png">
	</div>
</div>

<?php
if(ai_quiz_check_api_key()){
	ai_quiz_update_user_quizs();
?>
<div class="wrap" id="ai-body">

	<div class="ai-banner" id="ai-banner-buy" style="display:none;">
		<button onclick="close_banner('buy')" class="ai-close-banner">x</button>

		<h4 class="mt-3" style="font-style: italic;">

		<i class="fa fa-dollar-sign" style="color:#ff9900"></i><i class="fa fa-dollar-sign me-3" style="color:#ff9900"></i>
		Special offer! 30 post for <span style="font-size: xx-large; color: #ff9900" >7<span style="font-size: small;">.99</span>€ </span>

		</h4>
		<p class="my-4" style="font-size: larger;">
		If it is your first purchase, you will receive a <b>gift of 4 extra posts!</b>
		</p>
		<div class="d-flex justify-content-end">
			<a class="btn btn-primary m-3"
				href="<?php echo esc_html(get_admin_url()); ?>admin.php?page=autoquiz_upgrade_plan">Get free posts
			</a>
		</div>

	</div>

	<div class="ai-progress d-flex flex-row w-100 align-items-center">
		<div class="progress my-3 p-0 w-100" style="border-radius: 10px;">

			<div class="progress-bar progress-bar-striped progress-bar-animated" id="ai-quiz-progress-token" role="progressbar"
				aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; border-radius:10px;"></div>

		</div>

		<a class="ps-3 blue-color clickable" href="<?php echo esc_html(get_admin_url()); ?>admin.php?page=autoquiz_upgrade_plan"><span
				class="fa fa-question-circle" style="font-size: 1.2rem;"></span></a>
	</div>

	<div id="ai-quiz-progress-n-tokens" class="mb-5 text-center" style="font-size: 1.3rem;"></div>

	<div id="payment-message" class="alert-success p-2  my-5 mx-2 hidden"></div>

</div>

<?php
	}
	}
}