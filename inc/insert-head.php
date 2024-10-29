<?php
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly






/**

 * Create the administration menus in the left-hand nav and load the JavaScript conditionally only on that page

 */

if (!function_exists('ai_quiz_add_my_admin_menus')) {

	function ai_quiz_add_my_admin_menus()
	{

		$my_page =  add_menu_page('Create Quiz', 'AutoQuiz', 'manage_options', 'ai_quiz', 'ai_quiz_add_integration_code_body', 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(ai_quiz_PLUGIN_DIR . '/images/icon.svg')));



		add_submenu_page(
			'ai_quiz',
			'AutoQuiz',
			'Dashboard',
			'manage_options',
			'ai_quiz',
			'ai_quiz_add_integration_code_body'
		);


		$my_page4 = add_submenu_page(
			'ai_quiz',
			'Style',
			'Style',
			'manage_options',
			'autoquiz_style',
			'ai_quiz_add_integration_code_body_style'
		);
		$my_page3 = add_submenu_page(
			'ai_quiz',
			'Academy',
			'Academy',
			'manage_options',
			'autoquiz_academy',
			'ai_quiz_add_integration_code_body_academy'
		);

		$my_page2 = add_submenu_page(
			'ai_quiz',
			'Upgrade Plan',
			'Upgrade Plan',
			'manage_options',
			'autoquiz_upgrade_plan',
			'ai_quiz_add_integration_code_body_buy_tokens'
		);
		$my_page5 = add_submenu_page(
			'ai_quiz',
			'Settings',
			'Settings',
			'manage_options',
			'autoquiz_settings',
			'ai_quiz_add_integration_code_body_settings'
		);




		// Load the JS conditionally




		add_action('load-' . $my_page, 'ai_quiz_load_admin_ai_quiz_body_js');
		add_action('load-' . $my_page2, 'ai_quiz_load_admin_ai_quiz_tokens_js');
		add_action('load-' . $my_page4, 'ai_quiz_load_admin_ai_quiz_style_js');
		add_action('load-' . $my_page3, 'ai_quiz_load_admin_ai_quiz_academy_js');
		add_action('load-' . $my_page5, 'ai_quiz_load_admin_ai_quiz_settings_js');
	}





	//Review
	add_action('admin_enqueue_scripts', 'ai_quiz_enqueue_css_review', 1);



	add_action('admin_menu', 'ai_quiz_add_my_admin_menus'); // hook so we can add menus to our admin left-hand menu

}


if (!function_exists('ai_quiz_load_admin_ai_quiz_body_js')) {
	// This function is only called when our plugin's page loads!

	function ai_quiz_load_admin_ai_quiz_body_js()
	{

		// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it

		add_action('admin_enqueue_scripts', 'ai_quiz_body_enqueue_js');
	}
}

if (!function_exists('ai_quiz_load_admin_ai_quiz_tokens_js')) {
	// This function is only called when our plugin's page loads!

	function ai_quiz_load_admin_ai_quiz_tokens_js()
	{

		// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it

		add_action('admin_enqueue_scripts', 'ai_quiz_tokens_enqueue_js');
	}
}

if (!function_exists('ai_quiz_load_admin_ai_quiz_style_js')) {
	// This function is only called when our plugin's page loads!

	function ai_quiz_load_admin_ai_quiz_style_js()
	{

		// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it

		add_action('admin_enqueue_scripts', 'ai_quiz_style_enqueue_js');
	}
}
if (!function_exists('ai_quiz_load_admin_ai_quiz_settings_js')) {
	// This function is only called when our plugin's page loads!

	function ai_quiz_load_admin_ai_quiz_settings_js()
	{

		// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it

		add_action('admin_enqueue_scripts', 'ai_quiz_settings_enqueue_js');
	}
}
if (!function_exists('ai_quiz_load_admin_ai_quiz_academy_js')) {
	// This function is only called when our plugin's page loads!

	function ai_quiz_load_admin_ai_quiz_academy_js()
	{

		// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it

		add_action('admin_enqueue_scripts', 'ai_quiz_academy_enqueue_js');
	}
}








if (!function_exists('ai_quiz_enqueue_css_review')) {

	function ai_quiz_enqueue_css_review()
	{

		$my_css_ver = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/my-styles-review.css'));

		$my_js_ver = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/review.js'));

		wp_enqueue_style(

			'ai-quiz-my-styles-ai-review',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/my-styles-review.css",

			null,

			$my_css_ver

		);
		wp_enqueue_script(

			'ai-quiz-my-js-ai-review',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/review.js",

			null,

			$my_js_ver

		);
	}
}



if (!function_exists('ai_quiz_body_enqueue_js')) {

	function ai_quiz_body_enqueue_js()
	{



		// Create version codes

		$my_css_ver = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/my-styles.css'));

		$my_css_bootstrap = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/bootstrap.min.css'));
		
		$my_js_ver  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/main.js'));

		$my_js_header  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/header.js'));

		$my_js_bootstrap  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/bootstrap.bundle.min.js'));

	


		// Enqueue the stylesheet
		wp_enqueue_style(

			'ai-quiz-my-styles',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/my-styles.css",

			null,

			$my_css_ver

		);


		wp_enqueue_style(

			'ai-quiz-my-styles2',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/bootstrap.min.css",

			null,

			$my_css_bootstrap

		);

		wp_enqueue_style(

			'ai-quiz-my-styles3',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/font-awesome.min.css",

			null,

			null

		);







		wp_enqueue_script(

			'ai-quiz-my-functions2',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/bootstrap.bundle.min.js",

			null,

			$my_js_bootstrap,

			true

		);

		wp_enqueue_script(

			'ai-quiz-my-functions-header',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/header.js",

			null,

			$my_js_header,

			true

		);




		wp_enqueue_script(

			'ai-quiz-my-functions4',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/main.js",

			null,

			$my_js_ver,

			true

		);

		//DATATABLE

		$my_css_datatable = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/datatable.min.css'));
		wp_enqueue_style(

			'ai-quiz-my-styles-datatable',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/datatable.min.css",

			null,

			$my_css_datatable

		);
		
		$my_js_datatable  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/datatable.min.js'));

		wp_enqueue_script(

			'ai-quiz-my-functions-datatable',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/datatable.min.js",

			null,

			$my_js_datatable,

			true

		);


	}
}

if (!function_exists('ai_quiz_tokens_enqueue_js')) {

	function ai_quiz_tokens_enqueue_js()
	{



		// Create version codes

		$my_css_ver = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/my-styles.css'));
		$my_css_checkout = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/checkout.css'));

		$my_js_header = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/header.js'));

		$my_js_checkout = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/checkout.js'));
		
		$my_js_checkout2 = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/single-posts-checkout.js'));

		$my_css_bootstrap = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/bootstrap.min.css'));

		$my_js_bootstrap  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/bootstrap.bundle.min.js'));




		// Enqueue the stylesheet
		wp_enqueue_style(

			'ai-quiz-my-styles',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/my-styles.css",

			null,

			$my_css_ver

		);


		wp_enqueue_style(

			'ai-quiz-my-styles2',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/bootstrap.min.css",

			null,

			$my_css_bootstrap

		);

		wp_enqueue_style(

			'ai-quiz-my-styles-checkout',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/checkout.css",

			null,

			$my_css_checkout

		);

		wp_enqueue_style(

			'ai-quiz-my-styles3',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/font-awesome.min.css",

			null,

			null

		);








		wp_enqueue_script(

			'ai-quiz-my-functions2',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/bootstrap.bundle.min.js",

			null,

			$my_js_bootstrap,

			true

		);


		wp_enqueue_script(

			'ai-quiz-my-functions-header',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/header.js",

			null,

			$my_js_header,

			true

		);

		wp_enqueue_script(

			'ai-quiz-my-functions-checkout',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/checkout.js",

			null,

			$my_js_checkout,

			true

		);
		// Enqueue the script

		wp_enqueue_script(

			'ai-quiz-my-strype',

			"https://js.stripe.com/v3/",

			null,

			null,

			true

		);

		wp_enqueue_script(

			'ai-quiz-my-functions-checkout2',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/single-posts-checkout.js",

			null,

			$my_js_checkout2,

			true

		);

	}
}


if (!function_exists('ai_quiz_academy_enqueue_js')) {

	function ai_quiz_academy_enqueue_js()
	{



		// Create version codes

		$my_css_ver = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/my-styles.css'));

		$my_css_checkout = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/checkout.css'));

		$my_js_header = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/header.js'));

		$my_css_bootstrap = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/bootstrap.min.css'));

		$my_js_bootstrap  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/bootstrap.bundle.min.js'));




		// Enqueue the stylesheet
		wp_enqueue_style(

			'ai-quiz-my-styles',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/my-styles.css",

			null,

			$my_css_ver

		);

		wp_enqueue_style(

			'ai-quiz-my-styles1',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/checkout.css",

			null,

			$my_css_checkout

		);

		wp_enqueue_style(

			'ai-quiz-my-styles2',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/bootstrap.min.css",

			null,

			$my_css_bootstrap

		);

		wp_enqueue_style(

			'ai-quiz-my-styles3',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/font-awesome.min.css",

			null,

			null

		);






		wp_enqueue_script(

			'ai-quiz-my-functions2',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/bootstrap.bundle.min.js",

			null,

			$my_js_bootstrap,

			true

		);


		wp_enqueue_script(

			'ai-quiz-my-functions-header',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/header.js",

			null,

			$my_js_header,

			true

		);

	}
}

if (!function_exists('ai_quiz_style_enqueue_js')) {

	function ai_quiz_style_enqueue_js()
	{



		// Create version codes

		$my_css_ver = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/my-styles.css'));


		$my_css_bootstrap = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/bootstrap.min.css'));

		$my_js_ver  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/style.js'));

		$my_js_header  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/header.js'));

		$my_js_bootstrap  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/bootstrap.bundle.min.js'));



		// Enqueue the stylesheet
		wp_enqueue_style(

			'ai-quiz-my-styles',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/my-styles.css",

			null,

			$my_css_ver

		);


		wp_enqueue_style(

			'ai-quiz-my-styles2',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/bootstrap.min.css",

			null,

			$my_css_bootstrap

		);

		wp_enqueue_style(

			'ai-quiz-my-styles3',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/font-awesome.min.css",

			null,

			null

		);





		wp_enqueue_script(

			'ai-quiz-my-functions2',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/bootstrap.bundle.min.js",

			null,

			$my_js_bootstrap,

			true

		);

		wp_enqueue_script(

			'ai-quiz-my-functions-header',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/header.js",

			null,

			$my_js_header,

			true

		);



		wp_enqueue_script(

			'ai-quiz-my-functions4',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/style.js",

			null,

			$my_js_ver,

			true

		);
		$data = array(
			"id" => "4",
			"user_id" => "1",
			"post_id" => "91",
			"name" => "Example",
			"type" => null,
			"length" => null,
			"level" => "0",
			"status" => null,
			"creation_date" => "2023-07-24 17:03:14",
			"questions" => array(
				array(
					"id" => "31",
					"user_id" => "1",
					"quiz_id" => "4",
					"question" => "Example 1",
					"type" => null,
					"explanation" => "Here is the explanation to the question",
					"options" => array(
						array(
							"id" => "148",
							"question_id" => "31",
							"answer" => "Option 1",
							"answer_bin" => "1"
						),
						array(
							"id" => "149",
							"question_id" => "31",
							"answer" => "Option2",
							"answer_bin" => "0"
						),
						array(
							"id" => "150",
							"question_id" => "31",
							"answer" => "Option 3",
							"answer_bin" => "0"
						),
						array(
							"id" => "151",
							"question_id" => "31",
							"answer" => "Option 4",
							"answer_bin" => "0"
						)
					),
				),
				array(
					"id" => "31",
					"user_id" => "1",
					"quiz_id" => "4",
					"question" => "Example 2",
					"type" => null,
					"result" => array("user" => "148",
						"score"=> 0,
						"answer"=> "149"
					),
					"explanation" => "Here is the explanation to the question",
					"options" => array(
						array(
							"id" => "148",
							"question_id" => "31",
							"answer" => "Option 1",
							"answer_bin" => "1"
						),
						array(
							"id" => "149",
							"question_id" => "31",
							"answer" => "Option2",
							"answer_bin" => "0"
						),
						array(
							"id" => "150",
							"question_id" => "31",
							"answer" => "Option 3",
							"answer_bin" => "0"
						),
						array(
							"id" => "151",
							"question_id" => "31",
							"answer" => "Option 4",
							"answer_bin" => "0"
						)
					),
				),
				array(
					"id" => "31",
					"user_id" => "1",
					"quiz_id" => "4",
					"question" => "Example 3",
					"result" => array("user" => "149",
						"score"=> 1,
						"answer"=> "149"
					),
					"type" => null,
					"explanation" => "Here is the explanation to the question",
					"options" => array(
						array(
							"id" => "148",
							"question_id" => "31",
							"answer" => "Option 1",
							"answer_bin" => "1"
						),
						array(
							"id" => "149",
							"question_id" => "31",
							"answer" => "Option2",
							"answer_bin" => "0"
						),
						array(
							"id" => "150",
							"question_id" => "31",
							"answer" => "Option 3",
							"answer_bin" => "0"
						),
						array(
							"id" => "151",
							"question_id" => "31",
							"answer" => "Option 4",
							"answer_bin" => "0"
						)
					),
				),
			),
			"admin" => "1",
			"url" => "/wp-admin/admin-ajax.php",
			"admin_ajax_url" => "/wp-admin/admin-ajax.php"
		);
		wp_localize_script('ai-quiz-my-functions4', 'aiQuiz', $data);
		wp_enqueue_style('ai_quiz_style', trailingslashit(ai_quiz_PLUGIN_URL) . "css/ai_quiz_style.css");

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
		$custom_css = "
			:root {
				--ai_quiz_bg_color: {$ai_quiz_bg_color};
				--ai_quiz_bg_font: {$ai_quiz_bg_font};
				--ai_quiz_option_color: {$ai_quiz_option_color};
				--ai_quiz_option_font: {$ai_quiz_option_font};
				--ai_quiz_selected_option_color: {$ai_quiz_selected_option_color};
				--ai_quiz_selected_option_font: {$ai_quiz_selected_option_font};
				--ai_quiz_failed_option_color: {$ai_quiz_failed_option_color};
				--ai_quiz_failed_option_font: {$ai_quiz_failed_option_font};
				--ai_quiz_success_option_color: {$ai_quiz_success_option_color};
				--ai_quiz_success_option_font: {$ai_quiz_success_option_font};
				--ai_quiz_primary_color: {$ai_quiz_primary_color};
				--ai_quiz_primary_font: {$ai_quiz_primary_font};
			}
		";

		wp_add_inline_style('ai_quiz_style', $custom_css);
		//DATATABLE

		$my_css_datatable = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/datatable.min.css'));
		wp_enqueue_style(

			'ai-quiz-my-styles-datatable',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/datatable.min.css",

			null,

			$my_css_datatable

		);
		
		$my_js_datatable  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/datatable.min.js'));

		wp_enqueue_script(

			'ai-quiz-my-functions-datatable',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/datatable.min.js",

			null,

			$my_js_datatable,

			true

		);
	}
}


if (!function_exists('ai_quiz_settings_enqueue_js')) {

	function ai_quiz_settings_enqueue_js()
	{



		// Create version codes

		$my_css_ver = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/my-styles.css'));


		$my_css_bootstrap = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/bootstrap.min.css'));

		$my_js_ver  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/settings.js'));

		$my_js_header  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/header.js'));

		$my_js_bootstrap  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/bootstrap.bundle.min.js'));



		// Enqueue the stylesheet
		wp_enqueue_style(

			'ai-quiz-my-styles',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/my-styles.css",

			null,

			$my_css_ver

		);


		wp_enqueue_style(

			'ai-quiz-my-styles2',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/bootstrap.min.css",

			null,

			$my_css_bootstrap

		);

		wp_enqueue_style(

			'ai-quiz-my-styles3',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/font-awesome.min.css",

			null,

			null

		);




		wp_enqueue_script(

			'ai-quiz-my-functions2',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/bootstrap.bundle.min.js",

			null,

			$my_js_bootstrap,

			true

		);

		wp_enqueue_script(

			'ai-quiz-my-functions-header',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/header.js",

			null,

			$my_js_header,

			true

		);



		wp_enqueue_script(

			'ai-quiz-my-functions4',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/settings.js",

			null,

			$my_js_ver,

			true

		);

		//DATATABLE

		$my_css_datatable = gmdate('Ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'css/datatable.min.css'));
		wp_enqueue_style(

			'ai-quiz-my-styles-datatable',

			trailingslashit(ai_quiz_PLUGIN_URL) . "css/datatable.min.css",

			null,

			$my_css_datatable

		);
		
		$my_js_datatable  = gmdate('ymd-Gis', filemtime(ai_quiz_PLUGIN_DIR . 'js/datatable.min.js'));

		wp_enqueue_script(

			'ai-quiz-my-functions-datatable',

			trailingslashit(ai_quiz_PLUGIN_URL) . "js/datatable.min.js",

			null,

			$my_js_datatable,

			true

		);
	}
}

//Review
add_action('admin_notices', 'ai_quiz_solicitud_review');
function ai_quiz_solicitud_review() {
	// Los siguientes comentarios se pueden eliminar si ya no son necesarios.
    /*
    delete_option('ai_quiz_review_dismissed');
    delete_transient('ai_quiz_request_review');
    */
    // Solo muestra la notificación a los usuarios que pueden instalar plugins.
    if (!current_user_can('install_plugins')) {
        return;
    }

    // Si el usuario ya ha descartado la notificación, no la muestres de nuevo.
    if (get_option('ai_quiz_review_dismissed')) {
        return;
    }

    // Si el usuario ha seleccionado "Maybe Later", verifica el transitorio.
    if (get_transient('ai_quiz_request_review')) {
        return;
    }

    // Enlace para dejar una valoración.
    $url = 'https://wordpress.org/support/plugin/ai-quiz/reviews/#postform';
    // Enlace para soporte
    $url_support = 'https://quiz.autowriter.tech/contact-us/';

    // Coloca aquí el HTML de tu notificación.
    echo '<div id="ai_quiz-review-notice" class="notice notice-info ai_quiz-notice" data-purpose="review">
        <div class="ai_quiz-notice-thumbnail">
            <img src="' . esc_html(ai_quiz_PLUGIN_URL) . '/images/icon-256x256.gif" alt="">
        </div>
        <div class="ai_quiz-notice-text">
            <div class="ai_quiz-notice-header">
                <h3>LIFE\'S <strong>A BEACH!</strong></h3>
                <a href="#" class="close-btn notice-dismiss-temporarily ai-quiz-notice-dismiss-temporarily">×</a>
            </div>
            <p>That phrase was just a cheeky wave to grab your attention. <span class="dashicons dashicons-smiley smile-icon"></span> </p><p>Hey, we hope you\'re having a blast with our <strong>AI Quiz</strong> plugin. If it\'s not too much to ask, could you spare a minute to write us a review?</p>
            <p class="extra-pad"><strong>Why, you ask?</strong> <br>
			Well, each review powers up our developers like a can of energy drink. It fuels them to bring you zippier updates, cooler features, and fewer bugs. <span class="dashicons dashicons-smiley smile-icon"></span><br>
			Plus, it keeps our <strong>free support</strong> running, kind of like having your own team of AI nerds on call. And all this in return for your feedback. </br> Sweet deal, right?</p>
            <div class="ai_quiz-notice-links">
                <ul class="ai_quiz-notice-ul">
                    <li><a class="button button-primary" href="' . esc_html($url) . '" target="_blank"><span class="dashicons dashicons-external"></span>Sure, I\'d love to!</a></li>
                    <li><a href="#" class="button button-secondary notice-dismiss-permanently ai-quiz-notice-dismiss-permanently"><span class="dashicons dashicons-smiley"></span>I already did!</a></li>
                    <li><a href="#" class="button button-secondary notice-dismiss-temporarily ai-quiz-notice-dismiss-temporarily"><span class="dashicons dashicons-dismiss"></span>Maybe later</a></li>
                                        <li><a href="' . esc_html($url_support) . '" class="button button-secondary notice-have-query" target="_blank"><span class="dashicons dashicons-testimonial"></span>I have a query</a></li>
                </ul>
                <a href="#" class="notice-dismiss-permanently ai-quiz-notice-dismiss-permanently">Never show again</a>
            </div>
        </div>
    </div>';
}
// Si el usuario ha optado por descartar la notificación, guarda esta preferencia.
function ai_quiz_dismiss_review() {
    if (isset($_POST['ai_quiz_review_dismiss']) && $_POST['ai_quiz_review_dismiss'] == 1) {
        update_option('ai_quiz_review_dismissed', '1');
        wp_die();
    } elseif (isset($_POST['ai_quiz_review_later']) && $_POST['ai_quiz_review_later'] == 1) {
        set_transient('ai_quiz_request_review', 'yes', 10 * HOUR_IN_SECONDS);
        wp_die();
    }
    
}
add_action('wp_ajax_ai_quiz_dismiss_review', 'ai_quiz_dismiss_review');




