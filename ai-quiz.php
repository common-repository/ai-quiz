<?php

/**

 * Plugin Name: AI-QUIZ | Quiz Maker

 * Description: With this plugin you can generate posts written by AI


 * Version:     1.1

 * Author:      AutoQuiz


 * Author URI:  https://quiz.autowriter.tech

 * Text Domain:  ai-quiz


 *


 *

 * @package    AIPostGenerator


 * @since      1.0.0



 * @copyright  Copyright (c) 2021, Kekotron

 * @license    GPL-2.0+

 */


// Plugin directory
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

define( 'ai_quiz_PLUGIN_DIR' , plugin_dir_path( __FILE__ ) );

define( 'ai_quiz_PLUGIN_URL' , plugin_dir_url( __FILE__ ) );


register_activation_hook( __FILE__, 'ai_quiz_activation_hook' );

function ai_quiz_activation_hook() {
	ai_quiz_set_default_options();
	ai_quiz_create_tables();
    set_transient( 'ai_quiz_transient', true, 5 );
}

register_activation_hook( __FILE__, 'ai_quiz_flush_rewrite_rules' );

function ai_quiz_flush_rewrite_rules() {
    ai_quiz_add_rewrite_rule();
    flush_rewrite_rules();
}

function ai_quiz_add_rewrite_rule() {
    add_rewrite_rule('^ai-quiz/([0-9]+)/?', 'index.php?ai_quiz_id=$matches[1]', 'top');
    add_rewrite_tag('%ai_quiz_id%', '([0-9]+)');
}

add_action('init', 'ai_quiz_add_rewrite_rule');

function ai_quiz_redirect_on_activation() {
    if (get_transient('ai_quiz_transient')) {
        delete_transient('ai_quiz_transient');
        wp_redirect(admin_url('admin.php?page=ai_quiz'));
        exit;
    }
}
add_action('admin_init', 'ai_quiz_redirect_on_activation');


function ai_quiz_notice() {
    // Comprobar si el plugin se acaba de activar
    if (get_option('ai_quiz_just_activated')) {
        ?>
		<div class="updated notice is-dismissible">
			<p>Thank you for using AutoQuiz! <strong>You are awesome</strong>.</p>
		</div>
		<?php
    }
}

add_action('admin_notices', 'ai_quiz_notice');

function ai_quiz_set_default_options() {
    // Sets the default options
    if (get_option('ai_quiz_email') === false) {
        add_option('ai_quiz_email', wp_get_current_user()->user_email);
    }

    if (get_option('ai_quiz_api_key') === false) {
        add_option('ai_quiz_api_key', 0);
    }

    if (get_option('ai_quiz_language') === false) {
        add_option('ai_quiz_language', 'en_US');
    }
	//Colors
	//Question
	if (get_option('ai_quiz_bg_font') === false) {
        add_option('ai_quiz_bg_font', '#000000');
    }
	if (get_option('ai_quiz_bg_color') === false) {
        add_option('ai_quiz_bg_color', '#f2f4ff');
    }
	//Option
	if (get_option('ai_quiz_option_font') === false) {
        add_option('ai_quiz_option_font', '#646464');
    }
	if (get_option('ai_quiz_option_color') === false) {
        add_option('ai_quiz_option_color', '#ffffff');
    }
	//Selected option
	if (get_option('ai_quiz_selected_option_font') === false) {
        add_option('ai_quiz_selected_option_font', '#ffffff');
    }
	if (get_option('ai_quiz_selected_option_color') === false) {
        add_option('ai_quiz_selected_option_color', '#459aed');
    }
	//Failed option
	if (get_option('ai_quiz_failed_option_font') === false) {
        add_option('ai_quiz_failed_option_font', '#c40000');
    }
	if (get_option('ai_quiz_failed_option_color') === false) {
        add_option('ai_quiz_failed_option_color', '#ffb2b2');
    }
	//Success option
	if (get_option('ai_quiz_success_option_font') === false) {
        add_option('ai_quiz_success_option_font', '#32c400');
    }
	if (get_option('ai_quiz_success_option_color') === false) {
        add_option('ai_quiz_success_option_color', '#d7ffbf');
    }
	//Primary 
	if (get_option('ai_quiz_primary_font') === false) {
        add_option('ai_quiz_primary_font', '#ffffff');
    }
	if (get_option('ai_quiz_primary_color') === false) {
        add_option('ai_quiz_primary_color', '#000080');
    }
	if (get_option('ai_quiz_phrase') === false) {
        add_option('ai_quiz_phrase', 'How Much Do You Really Know? Find Out Now!');
    }

}
function ai_quiz_create_tables() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $exams_table_name = $wpdb->prefix . 'ai_quizs';
    $options_table_name = $wpdb->prefix . 'ai_quiz_options';
    $questions_table_name = $wpdb->prefix . 'ai_quiz_questions';

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // Check if the exams table already exists
    if($wpdb->get_var("SHOW TABLES LIKE '$exams_table_name'") != $exams_table_name) {

        $sql = "CREATE TABLE $exams_table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) DEFAULT NULL,
            post_id bigint(20) DEFAULT NULL,
            name varchar(255) DEFAULT NULL,
            type varchar(20) DEFAULT NULL,
            length int(11) DEFAULT NULL,
            level int(11) NOT NULL DEFAULT '0',
            status varchar(20) DEFAULT NULL,
            creation_date timestamp NULL DEFAULT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);
    }

    // Check if the options table already exists
    if($wpdb->get_var("SHOW TABLES LIKE '$options_table_name'") != $options_table_name) {

        $sql = "CREATE TABLE $options_table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            question_id bigint(20) DEFAULT NULL,
            answer longtext,
            answer_bin tinyint(1) DEFAULT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);
    }

    // Check if the questions table already exists
    if($wpdb->get_var("SHOW TABLES LIKE '$questions_table_name'") != $questions_table_name) {

        $sql = "CREATE TABLE $questions_table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id int(11) DEFAULT NULL,
            quiz_id bigint(20) DEFAULT NULL,
            question text,
            type varchar(255) DEFAULT NULL,
            explanation text,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);
    }
}







// Plugin files




require_once(ai_quiz_PLUGIN_DIR . '/functions.php');

require_once(ai_quiz_PLUGIN_DIR . '/inc/insert-body-header.php');
require_once(ai_quiz_PLUGIN_DIR . '/inc/insert-body-presentation.php');


require_once( ai_quiz_PLUGIN_DIR . '/inc/insert-head.php' );	// Insert code (head)



require_once( ai_quiz_PLUGIN_DIR . '/inc/insert-body.php' );	// Insert code (body)
require_once( ai_quiz_PLUGIN_DIR . '/inc/insert-body-video-academy.php' );	// Insert code (body)
require_once( ai_quiz_PLUGIN_DIR . '/inc/insert-body-buy-tokens.php' );	// Insert code (body)
require_once( ai_quiz_PLUGIN_DIR . '/inc/insert-body-style.php' );	// Insert code (body)
require_once( ai_quiz_PLUGIN_DIR . '/inc/insert-body-settings.php' );	// Insert code (body)
