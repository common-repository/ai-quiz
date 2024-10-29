<?php

if (!function_exists('ai_quiz_return_json')) {

	function ai_quiz_return_json($response = array())
	{

		header('Content-Type: application/json');

		exit(json_encode($response));
	}
}

if (!function_exists('ai_quiz_stripAccents')) {

	function ai_quiz_stripAccents($str)
	{

		return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
	}
}

if (!function_exists('ai_quiz_call_api')){
	function ai_quiz_call_api($method, $url, $data) {
		$args = array(
			'method'    => $method,
			'timeout'   => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array()
		);
	
		if ($method == 'POST' || $method == 'PUT') {
			$args['body'] = $data;
			if (isset($data['file'])) {
				// Ajusta aquí para manejar la subida de archivos si es necesario.
			}
		} else {
			if (!empty($data)) {
				$url = add_query_arg($data, $url);
			}
		}
	
		$response = wp_remote_request($url, $args);
	
		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			die("Algo salió mal: $error_message");
		} else {
			return wp_remote_retrieve_body($response);
		}
	}
}
if (!function_exists('ai_quiz_call_api2')){
	function ai_quiz_call_api2($method, $url, $data) {
		$args = array(
			'method'    => $method,
			'timeout'   => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array()
		);
	
		if ($method == 'POST' || $method == 'PUT') {
			// Ajusta para la solicitud multipart/form-data
			if (isset($data['file']) && is_array($data['file'])) {
				$boundary = wp_generate_password(24);
				$args['headers']['Content-Type'] = 'multipart/form-data; boundary=' . $boundary;
				$payload = '';
	
				// Agrega los campos normales
				foreach ($data as $key => $value) {
					if ($key !== 'file') {
						$payload .= '--' . $boundary;
						$payload .= "\r\n";
						$payload .= 'Content-Disposition: form-data; name="' . $key . '"' . "\r\n\r\n";
						$payload .= $value;
						$payload .= "\r\n";
					}
				}
	
				// Agrega el archivo
				$file_path = $data['file']['tmp_name'];
				$file_name = basename($data['file']['name']);
				$file_type = $data['file']['type'];
				$payload .= '--' . $boundary;
				$payload .= "\r\n";
				$payload .= 'Content-Disposition: form-data; name="file"; filename="' . $file_name . '"' . "\r\n";
				$payload .= 'Content-Type: ' . $file_type . "\r\n\r\n";
				$payload .= file_get_contents($file_path);
				$payload .= "\r\n";
				$payload .= '--' . $boundary . '--';
	
				$args['body'] = $payload;
			} else {
				$args['body'] = http_build_query($data);
			}
		} else {
			if (!empty($data)) {
				$url = add_query_arg($data, $url);
			}
		}
	
		$response = wp_remote_request($url, $args);
	
		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			die("Algo salió mal: $error_message");
		} else {
			return wp_remote_retrieve_body($response);
		}
	}
}
if (!function_exists('ai_quiz_get_info')){

	function ai_quiz_get_info() {
		if(get_option('ai_quiz_api_key') === false){
			return false;
		}else{
			$data = array(
				'email' => get_option('ai_quiz_email'),
				'api_key' => get_option('ai_quiz_api_key')
			);
			$get_data = ai_quiz_call_api('POST', "https://quiz.autowriter.tech/api/get-info.php", $data);
			$response = json_decode($get_data, true);
			if($response['exito']){
				ai_quiz_return_json(array('exito' => true, 'user' => $response['user']));
			}else{
				ai_quiz_return_json(array('exito' => false));
			}

		}
	}
	add_action( 'wp_ajax_ai_quiz_get_info', 'ai_quiz_get_info' );
}

if (!function_exists('ai_quiz_get_settings')){

	function ai_quiz_get_settings() {
		if(get_option('ai_quiz_api_key') === false){
			return false;
		}else{
			$data = array(
				'email' => get_option('ai_quiz_email'),
				'api_key' => get_option('ai_quiz_api_key')
			);
			$get_data = ai_quiz_call_api('POST', "https://quiz.autowriter.tech/api/get-settings.php", $data);
			$response = json_decode($get_data, true);
			if($response['exito']){
				ai_quiz_return_json(array('exito' => true, 'user' => $response['user']));
			}else{
				ai_quiz_return_json(array('exito' => false));
			}

		}
	}
	add_action( 'wp_ajax_ai_quiz_get_settings', 'ai_quiz_get_settings' );
}
if (!function_exists('ai_quiz_cancel_subscription')){

	function ai_quiz_cancel_subscription() {
		if(get_option('ai_quiz_api_key') === false){
			return false;
		}else{
			$data = array(
				'email' => get_option('ai_quiz_email'),
				'api_key' => get_option('ai_quiz_api_key')
			);
			$get_data = ai_quiz_call_api('POST', "https://quiz.autowriter.tech/api/cancel_subscription.php", $data);
			$response = json_decode($get_data, true);
			if($response['exito']){
				ai_quiz_return_json(array('exito' => true));
			}else{
				ai_quiz_return_json(array('exito' => false));
			}

		}
	}
	add_action( 'wp_ajax_ai_quiz_cancel_subscription', 'ai_quiz_cancel_subscription' );
}

if (!function_exists('ai_quiz_get_promo')){

	function ai_quiz_get_promo() {
		if(get_option('ai_quiz_api_key') === false){
			return false;
		}else{
			$data = array(
				'email' => get_option('ai_quiz_email'),
				'api_key' => get_option('ai_quiz_api_key'),
				'promo' => sanitize_text_field($_POST['promo'])
			);
			$get_data = ai_quiz_call_api('POST', "https://quiz.autowriter.tech/api/promotion.php", $data);
			$response = json_decode($get_data, true);
			if($response['exito']){
				ai_quiz_return_json(array('exito' => true));
			}else{
				ai_quiz_return_json(array('exito' => false, 'error' => $response['error']));
			}

		}
	}
	add_action( 'wp_ajax_ai_quiz_get_promo', 'ai_quiz_get_promo' );
}

if (!function_exists('ai_quiz_update_question')){

	function ai_quiz_update_question() {
		$question = $_POST['question'];
		global $wpdb;
		$options_table_name = $wpdb->prefix . 'ai_quiz_options';
		$questions_table_name = $wpdb->prefix . 'ai_quiz_questions';
		$query = $wpdb->prepare("UPDATE $questions_table_name SET question = %s, explanation = %s WHERE id = %s", sanitize_text_field($question['question']), sanitize_text_field($question['explanation']), intval($question['id']));
		$wpdb->query($query);
		foreach ($question['options'] as $opt) {
			$query = $wpdb->prepare("UPDATE $options_table_name SET answer = %s WHERE id = %s", sanitize_text_field($opt['answer']), intval($opt['id']));
			$wpdb->query($query);
		}
		ai_quiz_return_json(array('exito' => true, 'question' => ai_quiz_get_question($question['id'])));

	}
	add_action( 'wp_ajax_ai_quiz_update_question', 'ai_quiz_update_question' );
	add_action( 'wp_ajax_nopriv_ai_quiz_update_question', 'ai_quiz_update_question' );
}

if (!function_exists('ai_quiz_delete_question')){

	function ai_quiz_delete_question() {
		$id = intval($_POST['id']);
		global $wpdb;
		$options_table_name = $wpdb->prefix . 'ai_quiz_options';
		$questions_table_name = $wpdb->prefix . 'ai_quiz_questions';
		$query = $wpdb->prepare("DELETE FROM $options_table_name WHERE question_id = %s", $id);
		$wpdb->query($query);
		$query = $wpdb->prepare("DELETE FROM $questions_table_name WHERE id = %s", $id);
		$wpdb->query($query);
		ai_quiz_return_json(array('exito' => true));

	}
	add_action( 'wp_ajax_ai_quiz_delete_question', 'ai_quiz_delete_question' );
	add_action( 'wp_ajax_nopriv_ai_quiz_delete_question', 'ai_quiz_delete_question' );
}
if (!function_exists('ai_quiz_delete_quiz')){

	function ai_quiz_delete_quiz() {
		$id = intval($_POST['id']);
		global $wpdb;
		$options_table_name = $wpdb->prefix . 'ai_quiz_options';
		$questions_table_name = $wpdb->prefix . 'ai_quiz_questions';
		$exams_table_name = $wpdb->prefix . 'ai_quizs';
		$query = $wpdb->prepare("SELECT * FROM $questions_table_name WHERE quiz_id=%s", $id);
        $questions = $wpdb->get_results($query, ARRAY_A);
        if($questions!= null){
			foreach ($questions as $question) {
				$query = $wpdb->prepare("DELETE FROM $options_table_name WHERE question_id = %s", $question['id']);
				$wpdb->query($query);
				$query = $wpdb->prepare("DELETE FROM $questions_table_name WHERE id = %s", $question['id']);
				$wpdb->query($query);
			}
		}
		$query = $wpdb->prepare("DELETE FROM $exams_table_name WHERE id = %s", $id);
		$wpdb->query($query);

		ai_quiz_return_json(array('exito' => true));

	}
	add_action( 'wp_ajax_ai_quiz_delete_quiz', 'ai_quiz_delete_quiz' );
	add_action( 'wp_ajax_nopriv_ai_quiz_delete_quiz', 'ai_quiz_delete_quiz' );
}

if (!function_exists('ai_quiz_create_sub_link')){

	function ai_quiz_create_sub_link() {
		if(get_option('ai_quiz_api_key') === false){
			return false;
		}else{
			$data = array(
				'email' => get_option('ai_quiz_email'),
				'api_key' => get_option('ai_quiz_api_key'),
				'id' => $_POST['id'],
				'url' => get_home_url() . '/wp-admin/admin.php?page=autoquiz_upgrade_plan'
			);
			$get_data = ai_quiz_call_api('POST', "https://quiz.autowriter.tech/api/checkout_stripe.php", $data);
			$response = json_decode($get_data, true);
			if($response['exito']){
				ai_quiz_return_json(array('exito' => true, 'url' => $response['url']));
			}else{
				ai_quiz_return_json(array('exito' => false));
			}

		}
	}
	add_action( 'wp_ajax_ai_quiz_create_sub_link', 'ai_quiz_create_sub_link' );
}

if (!function_exists('ai_quiz_log_out')){

	function ai_quiz_log_out() {
		if(get_option('ai_quiz_api_key') === false){
			return false;
		}else{
			update_option('ai_quiz_api_key', null);
			ai_quiz_return_json(array('exito' => true));

		}
	}
	add_action( 'wp_ajax_ai_quiz_log_out', 'ai_quiz_log_out' );
}

if (!function_exists('ai_quiz_single_checkout')){

	function ai_quiz_single_checkout() {
		if(get_option('ai_quiz_api_key') === false){
			return false;
		}else{
			$data = array(
				'email' => get_option('ai_quiz_email'),
				'api_key' => get_option('ai_quiz_api_key'),
				'price' => floatval($_POST['price']),
				'n_quizs' => intval($_POST['n_quizs'])
			);
			$get_data = ai_quiz_call_api('POST', "https://quiz.autowriter.tech/api/single-quizs-checkout.php", $data);
			$response = json_decode($get_data, true);
			if($response['exito']){
				ai_quiz_return_json(array('exito' => true, 'clientSecret' => $response['clientSecret']));
			}else{
				ai_quiz_return_json(array('exito' => false));
			}

		}
	}
	add_action( 'wp_ajax_ai_quiz_single_checkout', 'ai_quiz_single_checkout' );
}

if (!function_exists('ai_quiz_check_api_key')){
	function ai_quiz_check_api_key(){
		if(get_option('ai_quiz_api_key') === false){
			return false;
		}else{
			$data = array(
				'email' => get_option('ai_quiz_email'),
				'api_key' => get_option('ai_quiz_api_key')
			);
			$get_data = ai_quiz_call_api('POST', "https://quiz.autowriter.tech/api/check_api_key.php", $data);
			$response = json_decode($get_data, true);
			
			$success = $response['exito'];
			return $success;
		}
	}
}


if (!function_exists('ai_quiz_update_user_quizs')){
	function ai_quiz_update_user_quizs(){
		if(get_option('ai_quiz_api_key') === false){
			return false;
		}else{
			if(isset($_SERVER['HTTP_REFERER'])) {
				$domain = str_replace('www.', '', parse_url(filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL) ? $_SERVER['HTTP_REFERER'] : '', PHP_URL_HOST));
			} else {
				$domain = str_replace('www.', '', parse_url(get_home_url(), PHP_URL_HOST));
			}
			$data = array(
				'email' => get_option('ai_quiz_email'),
				'api_key' => get_option('ai_quiz_api_key'),
				'domain' => $domain
			);
			$get_data = ai_quiz_call_api('POST', "https://quiz.autowriter.tech/api/update_user_quizs.php", $data);
			$response = json_decode($get_data, true);
			$success = $response['exito'];
			global $wpdb;
			$exams_table_name = $wpdb->prefix . 'ai_quizs';
			$options_table_name = $wpdb->prefix . 'ai_quiz_options';
			$questions_table_name = $wpdb->prefix . 'ai_quiz_questions';
			foreach($response['quizs'] as $quiz){
				$query = $wpdb->prepare("INSERT INTO $exams_table_name (user_id, post_id, name, creation_date) VALUES(%s, %s, %s, %s)", sanitize_text_field($quiz['user_id']), sanitize_text_field($quiz['post_id']), sanitize_text_field($quiz['name']), sanitize_text_field($quiz['creation_date']));
				$result = $wpdb->query($query);
				if ($result === false) {
					ai_quiz_return_json( array('exito' => False, 'error' => $wpdb->last_error) );
				}
				$quiz_id = $wpdb->insert_id;
				if(intval($quiz['post_id'])){
					ai_quiz_add_shortcode_to_post(intval($quiz['post_id']), $quiz_id, "start");
				}
				foreach($quiz['questions'] as $q){
					$query = $wpdb->prepare("INSERT INTO $questions_table_name (user_id, quiz_id, question, explanation) VALUES(%s, %s, %s, %s)", sanitize_text_field($q['user_id']), sanitize_text_field($quiz_id), sanitize_text_field($q['question']), sanitize_text_field($q['explanation']));
					$result = $wpdb->query($query);
					if ($result === false) {
						ai_quiz_return_json( array('exito' => False, 'error' => $wpdb->last_error) );
					}
					$question_id = $wpdb->insert_id;
					foreach($q['options'] as $o){
						$query = $wpdb->prepare("INSERT INTO $options_table_name (question_id, answer, answer_bin) VALUES(%s, %s, %s)", $question_id, sanitize_text_field($o['answer']), sanitize_text_field($o['answer_bin']));
						$result = $wpdb->query($query);
						if ($result === false) {
							ai_quiz_return_json( array('exito' => False, 'error' => $wpdb->last_error) );
						}
					}
				}
			}
			return $success;
		}
	}
}
if (!function_exists('ai_quiz_add_shortcode_to_post')) {

	function ai_quiz_add_shortcode_to_post($post_id, $quiz_id, $position = "start"){
		// Obtén el contenido del post
		$post_content = get_post_field('post_content', $post_id);

		$text_to_add = '[ai_quiz id="' . $quiz_id . '"]';

		if($position == "end"){
			$new_content = $post_content . $text_to_add;
		}else{
			$new_content = $text_to_add . $post_content;
		}
		// Actualiza el contenido del post
		$updated_post = array(
			'ID'           => $post_id,
			'post_content' => $new_content,
		);
		// Actualiza el post en la base de datos
		wp_update_post( $updated_post );
	}
}


if (!function_exists('ai_quiz_update_email')) {

	function ai_quiz_update_email()
	{
		if (!isset($_POST['email'])) {

			ai_quiz_return_json(array('exito' => false, 'error' => 'Mensaje vacío'));
		}
		$email = sanitize_email($_POST['email']);
		update_option('ai_quiz_email', sanitize_text_field($email));

		ai_quiz_return_json(array('exito' => true));
	}


	add_action('wp_ajax_ai_quiz_update_email', 'ai_quiz_update_email');
}

if (!function_exists('ai_quiz_get_email')) {

	function ai_quiz_get_email()
	{
		$email = get_option('ai_quiz_email');

		ai_quiz_return_json(array('exito' => true, 'email' => $email));
	}


	add_action('wp_ajax_ai_quiz_get_email', 'ai_quiz_get_email');
}
if (!function_exists('ai_quiz_get_credentials')) {

	function ai_quiz_get_credentials()
	{
		$email = get_option('ai_quiz_email');
		$api_key = get_option('ai_quiz_api_key');

		ai_quiz_return_json(array('exito' => true, 'email' => $email, 'api_key' => $api_key));
	}


	add_action('wp_ajax_ai_quiz_get_credentials', 'ai_quiz_get_credentials');
}

if (!function_exists('ai_quiz_update_api_key')) {

	function ai_quiz_update_api_key()
	{
		if (!isset($_POST['api_key'])) {

			ai_quiz_return_json(array('exito' => false, 'error' => 'Mensaje vacío'));
		}
		$api_key = sanitize_text_field($_POST['api_key']);
		update_option('ai_quiz_api_key', $api_key);
		
		ai_quiz_return_json(array('exito' => true));
	}


	add_action('wp_ajax_ai_quiz_update_api_key', 'ai_quiz_update_api_key');
}
if (!function_exists('ai_quiz_update_email_api_key')) {

	function ai_quiz_update_email_api_key()
	{
		if (!isset($_POST['api_key']) || !isset($_POST['email'])) {

			ai_quiz_return_json(array('exito' => false, 'error' => 'Mensaje vacío'));
		}
		$api_key = sanitize_text_field($_POST['api_key']);
		update_option('ai_quiz_api_key', $api_key);

		$email = sanitize_email($_POST['email']);
		update_option('ai_quiz_email', $email);
		
		ai_quiz_return_json(array('exito' => true));
	}


	add_action('wp_ajax_ai_quiz_update_email_api_key', 'ai_quiz_update_email_api_key');
}

if (!function_exists('ai_quiz_get_posts')){

	function ai_quiz_get_posts() {
		$all_posts = array();
		$data = array(
			'email' => get_option('ai_quiz_email'),
			'api_key' => get_option('ai_quiz_api_key')
		);
		$get_data = ai_quiz_call_api('POST', "https://quiz.autowriter.tech/api/get_posts.php", $data);
		$response = json_decode($get_data, true);
		if(count($response['posts'])){
			$all_posts = $response['posts'];
		}
		global $wpdb;
		
		$exams_table_name = $wpdb->prefix . 'ai_quizs';
		
		if (empty($all_posts)) {
			$query = $wpdb->prepare(
				"SELECT p.ID, p.post_title 
				FROM {$wpdb->posts} AS p
				LEFT JOIN $exams_table_name AS e ON p.ID = e.post_id
				WHERE p.post_type = 'post' AND e.post_id IS NULL
				ORDER BY p.post_title ASC"
			);
		} else {
			// Convirtiendo los IDs en un string para la consulta SQL
			$post_ids = implode(',', array_map('intval', $all_posts)); 
	
			$query = $wpdb->prepare(
				"SELECT p.ID, p.post_title 
				FROM {$wpdb->posts} AS p
				LEFT JOIN $exams_table_name AS e ON p.ID = e.post_id
				WHERE p.post_type = 'post' AND e.post_id IS NULL AND p.ID NOT IN ($post_ids)
				ORDER BY p.post_title ASC"
			);
		}
	
		$posts = $wpdb->get_results( $query, ARRAY_A );
		ai_quiz_return_json(array('exito' => true, 'posts' => $posts));
	}
	
	
	add_action( 'wp_ajax_ai_quiz_get_posts', 'ai_quiz_get_posts' );
}

if (!function_exists('ai_quiz_get_quizs')){

	function ai_quiz_get_quizs() {
		if(get_option('ai_quiz_api_key') === false){
			return false;
		}else{
			$all_quizs = array();

			if(isset($_SERVER['HTTP_REFERER'])) {
				$domain = str_replace('www.', '', parse_url(filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL) ? $_SERVER['HTTP_REFERER'] : '', PHP_URL_HOST));
			} else {
				$domain = str_replace('www.', '', parse_url(get_home_url(), PHP_URL_HOST));
			}
			$data = array(
				'email' => get_option('ai_quiz_email'),
				'api_key' => get_option('ai_quiz_api_key'),
				'domain' => $domain
			);
			$get_data = ai_quiz_call_api('POST', "https://quiz.autowriter.tech/api/get_quizs.py", $data);
			$response = json_decode($get_data, true);
			global $wpdb;
			$exams_table_name = $wpdb->prefix . 'ai_quizs';
			$questions_table_name = $wpdb->prefix . 'ai_quiz_questions';
			if(count($response['quizs'])){
				foreach ($response['quizs'] as $quiz) {
					if ($quiz['status'] === 'created') {
						ai_quiz_update_user_quizs();
						ai_quiz_get_quizs();  // Vuelve a llamar a la función
						return;  // Esto detendrá la ejecución de la actual llamada a ai_quiz_get_quizs()
					}
				}
				$all_quizs = array_merge($all_quizs, $response['quizs']);
			}
			
			$query = $wpdb->prepare("SELECT * FROM $exams_table_name ORDER BY id DESC");
			$quizs = $wpdb->get_results($query, ARRAY_A);
			if($quizs!= null){
				foreach ($quizs as $quiz) {
					$questions = array();
					$query = $wpdb->prepare("SELECT * FROM $questions_table_name WHERE quiz_id = %s", $quiz['id']);
					$quests = $wpdb->get_results($query, ARRAY_A);
					if($quests!= null){
						foreach ($quests as $quest) {
						$questions[] = ai_quiz_get_question($quest['id']);
						}
						$quiz['questions'] = $questions;
						$quiz['status'] = 'created';
						if($quiz['post_id']!= "0" && $quiz['post_id']!= null){
							$quiz['post_url'] = get_permalink($quiz['post_id']);
						}
						$all_quizs[] = $quiz;
					}
				}
			}
		}
		ai_quiz_return_json(array('exito' => true, 'quizs' => $all_quizs));
	}
	add_action( 'wp_ajax_ai_quiz_get_quizs', 'ai_quiz_get_quizs' );
}

if (!function_exists('ai_quiz_get_question')){

	function ai_quiz_get_question($id) {
		global $wpdb;
		$options_table_name = $wpdb->prefix . 'ai_quiz_options';
		$questions_table_name = $wpdb->prefix . 'ai_quiz_questions';
		$query = $wpdb->prepare("SELECT * FROM $questions_table_name WHERE id = %s LIMIT 1", $id);
		$question = $wpdb->get_row($query, ARRAY_A);
		if($question!= null){
			//Getting options
			$query = $wpdb->prepare("SELECT * FROM $options_table_name WHERE question_id=%s", $question['id']);
			$options = $wpdb->get_results($query, ARRAY_A);
			if($options!= null){
				$question['options'] = array();
				foreach ($options as $option) {
				$question['options'][] = $option;
				}
			}
			return $question;
		}
	}
}
if (!function_exists('ai_quiz_get_quiz')){

	function ai_quiz_get_quiz($id) {
		global $wpdb;
		$exams_table_name = $wpdb->prefix . 'ai_quizs';
		$questions_table_name = $wpdb->prefix . 'ai_quiz_questions';
		$query = $wpdb->prepare("SELECT * FROM $exams_table_name WHERE id = %s LIMIT 1", $id);
		$quiz = $wpdb->get_row($query, ARRAY_A);
		if($quiz!= null){
			$questions = array();
			$query = $wpdb->prepare("SELECT * FROM $questions_table_name WHERE quiz_id = %s", $id);
			$quests = $wpdb->get_results($query, ARRAY_A);
			if($quests!= null){
				foreach ($quests as $quest) {
					$questions[] = ai_quiz_get_question($quest['id']);
				}
				$quiz['questions'] = $questions;
			}
		}
		return $quiz;
	}
}
if (!function_exists('ai_quiz_get_if_exist_quiz')){

	function ai_quiz_get_if_exist_quiz($id) {
		global $wpdb;
		$exams_table_name = $wpdb->prefix . 'ai_quizs';
		$query = $wpdb->prepare("SELECT * FROM $exams_table_name WHERE id = %s LIMIT 1", $id);
		$quiz = $wpdb->get_row($query, ARRAY_A);
		if($quiz!= null){
			return true;
		}
		return false;
	}
}

if (!function_exists('ai_quiz_update_style')){

	function ai_quiz_update_style() {
		$colors = json_decode(stripslashes(sanitize_text_field($_POST['colors'])), true); // Convertir de JSON a array
		$phrase = sanitize_text_field($_POST['phrase']);
		// Actualizar cada opción
		update_option('ai_quiz_phrase', $phrase);
		foreach ($colors as $option => $value) {
			update_option($option, $value);
		}

		ai_quiz_return_json(array('exito' => true, 'colors' => $colors));
	}
	add_action( 'wp_ajax_ai_quiz_update_style', 'ai_quiz_update_style' );
}
if (!function_exists('ai_quiz_reset_style')){

	function ai_quiz_reset_style() {

		update_option('ai_quiz_bg_font', '#000000');
		update_option('ai_quiz_bg_color', '#f2f4ff');
		update_option('ai_quiz_option_font', '#646464');
		update_option('ai_quiz_option_color', '#ffffff');
		update_option('ai_quiz_selected_option_font', '#ffffff');
		update_option('ai_quiz_selected_option_color', '#459aed');
		update_option('ai_quiz_failed_option_font', '#c40000');
		update_option('ai_quiz_failed_option_color', '#ffb2b2');
		update_option('ai_quiz_success_option_font', '#32c400');
		update_option('ai_quiz_success_option_color', '#d7ffbf');
		update_option('ai_quiz_primary_font', '#ffffff');
		update_option('ai_quiz_primary_color', '#000080');
		
		ai_quiz_return_json(array('exito' => true));
	}
	add_action( 'wp_ajax_ai_quiz_reset_style', 'ai_quiz_reset_style' );
}


if (!function_exists('ai_quiz_create_by_post')){

	function ai_quiz_create_by_post() {
		$post_id = intval($_POST['id']);
		// Obtener el contenido del post
		$post_object = get_post( $post_id );
		$post_content = $post_object->post_content;
		$post_title = $post_object->post_title;

		// Limpiar el contenido del post
		$post_text = wp_strip_all_tags( $post_content );
		
		$data = array(
			'email' => get_option('ai_quiz_email'),
			'api_key' => get_option('ai_quiz_api_key'),
			'id' => $post_id,
			'text' => $post_text,
			'title' => $post_title,
			'type' => 'post',
			'idiom' => sanitize_text_field($_POST['idiom']),
			'domain' => str_replace('www.', '', parse_url(filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL) ? $_SERVER['HTTP_REFERER'] : '', PHP_URL_HOST))
		);

		$get_data = ai_quiz_call_api('POST', "https://quiz.autowriter.tech/api/create_quiz.py", $data);
		$response = json_decode($get_data, true);
		ai_quiz_return_json(array('exito' => true, 'response' => $response));
	}
	add_action( 'wp_ajax_ai_quiz_create_by_post', 'ai_quiz_create_by_post' );
}

if (!function_exists('ai_quiz_create_by_custom')){

	function ai_quiz_create_by_custom() {
		$url = isset($_POST['url']) ? sanitize_url($_POST['url']) : '';
		$topic = isset($_POST['topic']) ? sanitize_text_field($_POST['topic']) : '';
		$type = isset($_POST['custom_type']) ? sanitize_text_field($_POST['custom_type']) : '';
		$idiom = isset($_POST['idiom']) ? sanitize_text_field($_POST['idiom']) : '';
		

		if ($type === "pdf") {
			//Manejando file
			$file = $_FILES['file'];

			if ($file['error'] !== UPLOAD_ERR_OK) {
				ai_quiz_return_json(array('exito' => false, 'error' => 'PDF Error, try again.'));
			}
			$name = sanitize_file_name($file['name']);
		} else if ($type === "url") {
			$name = $url;
		} else if ($type === "topic") {
			$name = $topic;
		}
	
		$domain = str_replace('www.', '', parse_url(filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL) ? $_SERVER['HTTP_REFERER'] : '', PHP_URL_HOST));
		$domain = sanitize_text_field($domain);

		$data = array(
			'email' => get_option('ai_quiz_email'),
			'api_key' => get_option('ai_quiz_api_key'),
			'url' => $url,
			'text' => $topic,
			'title' => $name,
			'type' => $type,
			'idiom' => $idiom,
			'domain' => $domain
		);
		if ($type === 'pdf') {
			//$data['file'] = curl_file_create($file['tmp_name'], $file['type'], $file['name']);
			$data['file'] = $file;
		}
		//ai_quiz_return_json(array('exito' => false, 'error' => $data));

		$get_data = ai_quiz_call_api2('POST', "https://quiz.autowriter.tech/api/create_quiz.py", $data);
		$response = json_decode($get_data, true);
		if($response!=null){
			if(!$response['exito']){
				ai_quiz_return_json(array('exito' => false, 'error' => $response['error']));
			}
		}else{
			ai_quiz_return_json(array('exito' => false, 'response' => $response, 'error' => 'Could not extract text from your input, please try again.'));
		}
		ai_quiz_return_json(array('exito' => true, 'response' => $response));
	}
	add_action( 'wp_ajax_ai_quiz_create_by_custom', 'ai_quiz_create_by_custom' );
}

if (!function_exists('ai_quiz_add_rewrite_rules')){
	function ai_quiz_add_rewrite_rules() {
		add_rewrite_rule('^ai-quiz/([0-9]+)/?', 'index.php?ai_quiz_id=$matches[1]', 'top');
	}
	add_action('init', 'ai_quiz_add_rewrite_rules');
}
if (!function_exists('ai_quiz_add_query_vars')){
	function ai_quiz_add_query_vars($query_vars) {
		$query_vars[] = 'ai_quiz_id';
		return $query_vars;
	}
	add_filter('query_vars', 'ai_quiz_add_query_vars');
}
if (!function_exists('ai_quiz_template_redirect')){
	function ai_quiz_template_redirect() {
		$quiz_id = get_query_var('ai_quiz_id');
		if ($quiz_id) {
			include(ai_quiz_PLUGIN_DIR . '/inc/quiz-template.php');	// Insert code (body)
			exit;
		}
	}
	add_action('template_redirect', 'ai_quiz_template_redirect');
}


if (!function_exists('ai_quiz_create_shortcode')){
    function ai_quiz_create_shortcode($atts) {
        // Extrae los atributos de la etiqueta del shortcode
        extract(shortcode_atts(array(
            'id' => 'default'
        ), $atts));
        
		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . esc_url_raw($_SERVER['HTTP_HOST']) . esc_url_raw($_SERVER['REQUEST_URI']);

        // Aquí puedes obtener la información del quiz a partir del ID
        $quiz = ai_quiz_get_if_exist_quiz($id);
        // Suponiendo que quieres mostrar el nombre del quiz
        $output = "";
        if($quiz) {
            // Generamos el iframe que apunta a la url del cuestionario.
            $output .= '<iframe src="'.home_url().'/ai-quiz/'.$id.'?parent_url='.urlencode($current_url).'" width="100%" height="800" style="border:0; padding-right:10px;"></iframe>';
        } else {
            $output .= "<p>Quiz no encontrado.</p>";
        }
    
        return $output;
    }
    add_shortcode('ai_quiz', 'ai_quiz_create_shortcode');
}
if (!function_exists('ai_quiz_create_shortcode_ajax')){
	function ai_quiz_create_shortcode_ajax() {
		$quiz_id = intval($_POST['id']);
		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . esc_url_raw($_SERVER['REQUEST_URI']);
	
		$quiz = ai_quiz_get_if_exist_quiz($quiz_id);
		$output = "";
		if ($quiz) {
			// Asegúrate de escapar todos los datos al imprimirlos
			$iframe_src = esc_url(home_url() . '/ai-quiz/' . $quiz_id . '?parent_url=' . urlencode($current_url));
			$output .= '<iframe src="' . $iframe_src . '" width="100%" height="800" style="border:0; padding-right:10px;"></iframe>';
		} else {
			$output .= esc_html("<p>Quiz no encontrado.</p>");
		}
	
		echo $output;
		die();
	}
	
	// Esta acción manejará la llamada AJAX
	add_action('wp_ajax_ai_quiz_create_shortcode_ajax', 'ai_quiz_create_shortcode_ajax');
	add_action('wp_ajax_nopriv_ai_quiz_create_shortcode_ajax', 'ai_quiz_create_shortcode_ajax');


}
if (!function_exists('ai_quiz_my_custom_colors')){
	function ai_quiz_my_custom_colors() {
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
		<style type="text/css">
			:root {
				--ai_quiz_bg_color: <?php echo esc_attr($ai_quiz_bg_color); ?>;
				--ai_quiz_bg_font: <?php echo esc_attr($ai_quiz_bg_font); ?>;
				--ai_quiz_option_color: <?php echo esc_attr($ai_quiz_option_color); ?>;
				--ai_quiz_option_font: <?php echo esc_attr($ai_quiz_option_font); ?>;
				--ai_quiz_selected_option_color: <?php echo esc_attr($ai_quiz_selected_option_color); ?>;
				--ai_quiz_selected_option_font: <?php echo esc_attr($ai_quiz_selected_option_font); ?>;
				--ai_quiz_failed_option_color: <?php echo esc_attr($ai_quiz_failed_option_color); ?>;
				--ai_quiz_failed_option_font: <?php echo esc_attr($ai_quiz_failed_option_font); ?>;
				--ai_quiz_success_option_color: <?php echo esc_attr($ai_quiz_success_option_color); ?>;
				--ai_quiz_success_option_font: <?php echo esc_attr($ai_quiz_success_option_font); ?>;
				--ai_quiz_primary_color: <?php echo esc_attr($ai_quiz_primary_color); ?>;
				--ai_quiz_primary_font: <?php echo esc_attr($ai_quiz_primary_font); ?>;
			}
		</style>
		<?php
	}
	add_action('wp_head', 'ai_quiz_my_custom_colors');
}
?>