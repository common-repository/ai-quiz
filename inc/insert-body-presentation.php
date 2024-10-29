<?php


if (!function_exists('ai_quiz_add_integration_code_presentation')) {

	function ai_quiz_add_integration_code_presentation()
	{

		$ai_quiz_email = get_option('ai_quiz_email');
?>

<div class="wrap">
	<div class="flex-column align-items-center" id="ai-quiz-send-api-key">

		<h4 class="mt-3 mb-5">Gamify your website for free and boost your SEO</h4>

		<p class="my-4" style="font-size: larger;">
			To start creating quizzes, enter your <b>email</b></p>
		<input class="form-control" id="ai-quiz-email" type="email" value="<?php echo esc_html($ai_quiz_email); ?>"
			placeholder="Enter email">

		<button class="btn btn-lg btn-primary w-auto mt-5 mb-2 d-inline-block" id="ai-quiz-start-button">Get started</button>
		<div id="loader-1" class="ai-quiz-loader"></div>

		<span style="color: blue; text-decoration:underline; cursor: pointer" onclick="insert_api_key(true)"> Already
			have api key?</span>
		<span style="font-size: 13px; margin-top: 20px;"><input type="checkbox" id="ai-gdpr" checked>You agree to
			receive promotional emails.</span>

	</div>
	<div class="flex-column align-items-center" id="ai-quiz-email-send">

		<h4 class="mt-3 mb-5">Please validate your email.</h4>
		<p class="font-size:large;">Insert the 6 digits we send you via e-mail.</p>
		<p>Don't see the e-mail? Please check your <b>SPAM folder</b></p>
		<!--
	<input class="form-control" type="text" id="ai-quiz-api-key" placeholder="Api Key">
	-->
		<form class="otc" name="one-time-code" action="#">
			<fieldset>
				<label for="otc-1">Number 1</label>
				<label for="otc-2">Number 2</label>
				<label for="otc-3">Number 3</label>
				<label for="otc-4">Number 4</label>
				<label for="otc-5">Number 5</label>
				<label for="otc-6">Number 6</label>

				<div>
					<input class="ai-quiz-input-validation" type="number" pattern="[0-9]*" value="" inputtype="numeric" autocomplete="one-time-code"
						id="otc-1" required>
					<input class="ai-quiz-input-validation" type="number" pattern="[0-9]*" min="0" max="9" maxlength="1" value="" inputtype="numeric"
						id="otc-2" required>
					<input class="ai-quiz-input-validation" type="number" pattern="[0-9]*" min="0" max="9" maxlength="1" value="" inputtype="numeric"
						id="otc-3" required>
					<input class="ai-quiz-input-validation" type="number" pattern="[0-9]*" min="0" max="9" maxlength="1" value="" inputtype="numeric"
						id="otc-4" required>
					<input class="ai-quiz-input-validation" type="number" pattern="[0-9]*" min="0" max="9" maxlength="1" value="" inputtype="numeric"
						id="otc-5" required>
					<input class="ai-quiz-input-validation" type="number" pattern="[0-9]*" min="0" max="9" maxlength="1" value="" inputtype="numeric"
						id="otc-6" required>
				</div>
			</fieldset>
		</form>
		<button class="btn btn-lg btn-primary w-auto mt-5 mb-2 d-inline-block" id="ai_quiz_check_email_btn">Validate
			email</button>
		<div id="loader-email" class="ai-quiz-loader"></div>
		<span style="color: blue; text-decoration:underline; cursor: pointer" onclick="insert_api_key(false)"> Resend
			email</span>
	</div>

	<div class="flex-column align-items-center" id="ai-quiz-already-api-key">
		<h5 class="my-3">Insert your email and Api Key</h5>
		<div class="form-group my-4">
			<label for="exampleInputEmail1">Email address</label>
			<input class="form-control" id="ai-quiz-email-check" type="email"
				value="<?php echo esc_html($ai_quiz_email); ?>" placeholder="Enter email">
		</div>
		<div class="form-group my-3">
			<label for="exampleInputEmail1">Api Key</label>
			<input class="form-control" id="ai-quiz-api-key-check" type="text" placeholder="Enter Api Key">
		</div>
		<button class="btn btn-lg btn-primary w-auto mt-5 mb-2 d-inline-block" id="ai_quiz_check_all_btn">Check Api
			Key</button>
		<div id="loader-all" class="ai-quiz-loader"></div>
		<span class="mt-3" style="color: blue; text-decoration:underline; cursor: pointer"
			onclick="insert_api_key(false)"> Don't have api key?</span>

	</div>
	<div id="ai-quiz-error"></div>

</div>


<?php

	}
}