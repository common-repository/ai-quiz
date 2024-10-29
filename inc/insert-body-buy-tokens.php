<?php


if (!function_exists('ai_quiz_add_integration_code_body_buy_tokens')) {


	function ai_quiz_add_integration_code_body_buy_tokens()
	{
		$get_data = ai_quiz_call_api('GET', "https://quiz.autowriter.tech/api/get_prices.php", false);
		$response = json_decode($get_data, true);
		$prices = $response['response'];


?>



<div id="wp-body-content" class="container">
	<?php
		ai_quiz_add_integration_code_header();
		if(!ai_quiz_check_api_key()){
			ai_quiz_add_integration_code_presentation();
		}else{
	?>

	<div class="wrap container" id="AutoQuiz-content">
		<ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">


			<li class="nav-item" role="presentation">


				<button class="nav-link active" style="border: 1px solid #5858ed;" id="pills-tokens-tab"
					data-bs-toggle="pill" data-bs-target="#pills-tokens" type="button" role="tab"
					aria-controls="pills-tokens" aria-selected="false">Upgrade Plan</button>


			</li>

			<li class="nav-item" role="presentation">

				<button class="nav-link" style="border: 1px solid #5858ed;" id="pills-single-quizs-tab"
					data-bs-toggle="pill" data-bs-target="#pills-single-quizs" type="button" role="tab"
					aria-controls="pills-single-quizs" aria-selected="false">Get Single quizs</button>


			</li>

			<li class="nav-item" role="presentation">

				<button class="nav-link" style="border: 1px solid #5858ed;" id="pills-free-tokens-tab"
					data-bs-toggle="pill" data-bs-target="#pills-free-tokens" type="button" role="tab"
					aria-controls="pills-free-tokens" aria-selected="false">Free quizs</button>


			</li>


		</ul>

		<div class="tab-content" id="pills-tabContent">



			<!--BUY TOKENS-->


			<div class="tab-pane fade show active" id="pills-tokens" role="tabpanel" aria-labelledby="pills-tokens-tab">
				<p class="mt-5 mb-4" style="font-size: large; text-align:center;">You can unsubscribe whenever you want and you will not lose the quizs you haven't used!</p>
				<div
					class="d-flex flex-row flex-xl-row overflow-auto ai-quiz-scrollbar align-items-center justify-content-between pb-4">

					<!-- FREE PLAN -->
					<div class="card AutoQuiz-price-card mx-2"
						style="min-width: 300px; width: 300px; min-height: 454px;">

						<div class="d-flex flex-column align-items-center justify-content-evenly mt-4 mb-5">

							<h3 class="mb-5">Free Plan</h3>

							<div class="d-flex flex-column my-4 w-100 align-items-center justify-content-between">
								<div class="mb-5">
									<h3>5 quizs<span style="font-size:0.8rem;">/mo</span></h3>
								</div>
								<h3 class="my-1" style="font-size: 32px; color: #5858ed;">0<span
										style="font-size:0.8rem;">.00</span>€<span style="font-size:0.8rem;">/mo</span></h3>
							</div>
						</div>
						<button disabled class="btn btn-lg w-auto btn-success mt-2 my-5 d-inline-block"
							><i class="fa fa-check me-2"></i>Subscribed</button>

					</div>
					<?php 
						$i = 0;
						foreach($prices as $price){
						if($i%2==0){
							$bg = '#fbfcfc!important';
						}else{
							$bg = 'initial';

						}
					?>
					<div class="card AutoQuiz-price-card mx-2"
					    style="min-width: 300px; width: 300px; min-height: 454px; background: <?php echo esc_attr($bg); ?>">

					    <div class="d-flex flex-column align-items-center justify-content-evenly mt-4 mb-5">

					        <h3 class="mb-5"><?php echo esc_html($price['name']); ?> Plan
					        <!--DISCOUNT-->
					        <em class="text-danger" style="font-size: 1.3rem;">
					        <?php 
					        if($price['discount']){
					            echo esc_html($price['discount']) . "%"; 
					        }
					        ?>
					        </em></h3>

					        <div class="d-flex flex-column my-4 w-100 align-items-center justify-content-between">

					            <div class="mb-5">
					                <h3><?php echo esc_html($price['quizs']); ?> quizs<span style="font-size:0.8rem;">/mo</span></h3>
					            </div>
					            <h3 class="my-1" style="font-size: 32px; color: #5858ed;"><?php echo esc_html(floor($price['price'])); ?><span
					                style="font-size:0.8rem;"><?php echo esc_html(strstr(strval($price['price'] - floor($price['price'])), '.')); ?></span>€<span style="font-size:0.8rem;">/mo</span></h3>
					        </div>
					    </div>
						<button data-id="<?php echo esc_attr($price['price_id'])?>" class="btn btn-lg w-auto btn-primary mt-2 my-5 d-inline-block ai-quiz-sub"
					        >Upgrade plan</button>
							

					</div>

					<?php 
						$i++;
						}
					?>

					<div class="card AutoQuiz-price-card mx-2"
						style="min-width: 300px; width: 300px; min-height: 454px;">


						<div class="d-flex flex-column align-items-center justify-content-between mt-4 mb-5 ">


							<h3 class="mb-5">Enterprise Plan</h3>


							<p class="my-4" style="font-size: 19px;">Need more quizs or want to get a special plan? Get
								in touch!</p>




							<a class="btn btn-lg w-auto btn-primary my-5 d-inline-block"
								href="https://quiz.autowriter.tech/contact-us" target="_blank">Get in touch</a>


						</div>


					</div>


				</div>
				<div id="pop-cont"></div>


			</div>

			<!--END BUY TOKENS-->

			<!--SINGLE quizs-->
			<div class="tab-pane fade" id="pills-single-quizs" role="tabpanel" aria-labelledby="pills-single-quizs-tab">
					<div class="d-flex flex-column align-items-center justify-content-evenly mt-4 mb-5 price-quizs-single">


						
						<div class="d-flex flex-column">


							<p class="my-1 res-text" style="font-size:xx-large;" id="n_quizs_text">10 quizs</p>


						</div>

						<input class="my-5" type="range" id="n_quizs" name="n_quizs" min="10" value="10"
							step="5" max="100">

						<div class="res-text mb-5" style="font-size:2rem"><span id="price_text" data-price="3">3€</span> <span id="ai-percent" style="color:red;font-size: 15px;"></span></div>




					<button class="btn btn-lg w-auto btn-primary mt-2 my-5 d-inline-block" id="ai-quiz-show_pay"
						>Get quizs</button>

					</div>


				<div id="ai-payment-pop-cont"></div>
			</div>


			</div>
			<!--END SINGLE quizs-->

			<!--FREE TOKENS-->
			<div class="tab-pane fade" id="pills-free-tokens" role="tabpanel" aria-labelledby="pills-free-tokens-tab">


				<div class="d-flex flex-column align-items-center mt-5">

					<div id="form-errors"></div>
					<div class="card my-3 align-items-center"
						style="min-width: 100%; min-height: 250px;background: whitesmoke;box-shadow: 0 0 5px #00000069;">

						<div class="d-flex flex-column align-items-center justify-content-evenly my-3">

							<h3 class="mb-5">Make your first purchase</h3>

							<p style="font-size : 1rem;">Make your first purchase and get this 4 quizs reward!</p>

						</div>

						<button class="btn btn-lg w-auto btn-primary mt-2 my-5 d-inline-block ai_quiz_get_promotion"
							data-type="first-purchase">Get 4 quizs</button>
						<p style="font-size:1rem; display: none;" id="ai-quiz-first-purchase-error" class="text-danger">
						</p>
						<p style="font-size:1rem; display: none;" id="ai-quiz-first-purchase" class="my-3 text-success">
							Obtained! 🦾 </p>

					</div>


					

					<div class="card my-3 align-items-center"
						style="min-width: 100%; min-height: 250px;background: whitesmoke;box-shadow: 0 0 5px #00000069;">

						<div class="d-flex flex-column align-items-center justify-content-evenly my-3">

							<h3 class="mb-5">Pro customer! Complete 5 purchases</h3>

							<p style="font-size : 1rem;">Make 5 purchases to enjoy this AutoQuiz gift.</p>

						</div>

						<button class="btn btn-lg w-auto btn-primary mt-2 my-5 d-inline-block ai_quiz_get_promotion"
							data-type="fifth-purchase">Get 20 quizs</button>
						<p style="font-size:1rem; display: none;" id="ai-quiz-fifth-purchase-error" class="text-danger">
						</p>
						<p style="font-size:1rem; display: none;" id="ai-quiz-fifth-purchase" class="my-3 text-success">
							Obtained! 🦾 </p>


					</div>


				</div>
				<div id="pop-cont"></div>


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