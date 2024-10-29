<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    // Obtiene el ID del quiz de la URL
    $quiz_id = get_query_var('ai_quiz_id');
    
    // Obtiene la información del quiz a partir del ID
    $quiz = ai_quiz_get_quiz($quiz_id);

	if (!isset($_GET['parent_url']) || !filter_var(sanitize_url($_GET['parent_url']), FILTER_VALIDATE_URL)) {
		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . esc_url_raw($_SERVER['HTTP_HOST']) . esc_url_raw($_SERVER['REQUEST_URI']);

		$current_url = esc_url($current_url); // Escape the URL when outputting it
	} else {
		$current_url = sanitize_url($_GET['parent_url']); // Sanitize the input
	}
	
	$quiz['admin'] = current_user_can('administrator');
	$quiz['url'] = $current_url;
	$quiz['admin_ajax_url'] = admin_url( 'admin-ajax.php' );


    // Cargar estilos y scripts
    wp_enqueue_style('ai_quiz_style', trailingslashit(ai_quiz_PLUGIN_URL) . "css/ai_quiz_style.css");
    wp_enqueue_script('ai_quiz_script', trailingslashit(ai_quiz_PLUGIN_URL) . "js/ai_quiz_script.js", array('jquery'));
    
    // Pasar el objeto del cuestionario al archivo JS
    wp_localize_script('ai_quiz_script', 'aiQuiz', $quiz);
	$my_css_ver = date('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/my-styles.css'));


	$my_css_bootstrap = date('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/bootstrap.min.css'));
	// Enqueue the stylesheet
	wp_enqueue_style('my-styles',trailingslashit(ai_quiz_PLUGIN_URL) . "css/my-styles.css", null, $my_css_ver);
	wp_enqueue_style('my-styles2', trailingslashit(ai_quiz_PLUGIN_URL) . "css/bootstrap.min.css", null, $my_css_bootstrap);
	wp_enqueue_style('my-styles3',trailingslashit(ai_quiz_PLUGIN_URL) . "css/font-awesome.min.css",null,null);
	
	//Colors
	$ai_quiz_bg_color = get_option('ai_quiz_bg_color');
	$ai_quiz_bg_font = get_option('ai_quiz_bg_font');

	$ai_quiz_option_color = get_option('ai_quiz_option_color');
	$ai_quiz_option_font = get_option('ai_quiz_option_font');

	$ai_quiz_selected_option_color = get_option('ai_quiz_selected_option_color');
	$ai_quiz_selected_option_font = get_option('ai_quiz_selected_option_font');

	$ai_quiz_failed_option_color = get_option('ai_quiz_failed_option_color');
	$ai_quiz_failed_option_font = get_option('ai_quiz_failed_option_font');

	$ai_quiz_success_option_color = get_option('ai_quiz_success_option_color');
	$ai_quiz_success_option_font = get_option('ai_quiz_success_option_font');

	$ai_quiz_primary_color = get_option('ai_quiz_primary_color');
	$ai_quiz_primary_font = get_option('ai_quiz_primary_font');
?>

<html>
<head>
	<style type="text/css">
			:root {
				--ai_quiz_bg_color: <?php echo esc_html($ai_quiz_bg_color); ?>;
				--ai_quiz_bg_font: <?php echo esc_html($ai_quiz_bg_font); ?>;
				--ai_quiz_option_color: <?php echo esc_html($ai_quiz_option_color); ?>;
				--ai_quiz_option_font: <?php echo esc_html($ai_quiz_option_font); ?>;
				--ai_quiz_selected_option_color: <?php echo esc_html($ai_quiz_selected_option_color); ?>;
				--ai_quiz_selected_option_font: <?php echo esc_html($ai_quiz_selected_option_font); ?>;
				--ai_quiz_failed_option_color: <?php echo esc_html($ai_quiz_failed_option_color); ?>;
				--ai_quiz_failed_option_font: <?php echo esc_html($ai_quiz_failed_option_font); ?>;
				--ai_quiz_success_option_color: <?php echo esc_html($ai_quiz_success_option_color); ?>;
				--ai_quiz_success_option_font: <?php echo esc_html($ai_quiz_success_option_font); ?>;
				--ai_quiz_primary_color: <?php echo esc_html($ai_quiz_primary_color); ?>;
				--ai_quiz_primary_font: <?php echo esc_html($ai_quiz_primary_font); ?>;
			}
	</style>
    <?php
        // Aquí imprimimos los estilos de WordPress
        wp_print_styles();
        wp_print_scripts();
    ?>
</head>
<body class="px-2">
<!-- Ahora puedes usar tu HTML aquí -->
    <!-- NOTA EMOJI -->
	<div>
		<h5 class="my-2 text-center"><?php echo esc_html(get_option('ai_quiz_phrase')); ?></h5>
		<div class="align-items-start justify-content-center" id="face-score" >
			<div id="ai-quiz-share"><i class="fa-solid fa-share-from-square"></i></div>
			<form class="fr" action="">
				<div class="fr__face" role="img" aria-label="Straight face">
					<div class="fr__face-right-eye" data-right></div>
					<div class="fr__face-left-eye" data-left></div>
					<div class="fr__face-mouth-lower" data-mouth-lower></div>
					<div class="fr__face-mouth-upper" data-mouth-upper></div>
				</div>
				<input class="fr__input " id="face-rating" type="hidden" value="2.5" min="0" max="5">
			</form>
			<h5 class="mb-0" id="score-text">
				<span id="success-score"></span> /
				<span id="total-score"></span>
			</h5>
		</div>

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
		<div>
			<p class="text-end mt-2">Powered by <span><a target="_blank" href="https://wordpress.org/plugins/ai-quiz/">AI-Quiz</a></span></p>
		</div>
	</div>


</body>
</html>
