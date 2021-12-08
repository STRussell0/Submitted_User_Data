<?php 
/**
 * Plugin Name: Shortcode Submitted User Data
 * Description: Show Authors using AJAX
 * Version: 0.1
 * Author: Stephen Russell
 */
/*
    Challenge # 2 in ajax:

    On button click, submit to user_meta_data, that the button was clicked, the time it was clicked and the page it was clicked on!

    Return in the results a list of every time that user has clicked that button.

        
*/

function show_authors() {

    if(!wp_verify_nonce($_REQUEST['nonce'], "show_authors_nonce")) {
        exit( "No naughty business please :)");
    }

    $user_meta = get_user_meta($_REQUEST['user'], "clicks");
    $submitted = ($user_meta == '') ? 0 : count($user_meta);
    date_default_timezone_set("America/Los_Angeles");

    $click_number = $submitted + 1;
    $user_meta = array (
        'click_count' => $click_number,
        'date' => date("h:i:sa"),
        'page_title' => $_REQUEST['post_title']
    );
    add_user_meta($_REQUEST['user'], "clicks", $user_meta);
    $result = $user_meta;
    
    
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { // what exactly happens when this the button is pressed
        $result = json_encode($result);
        echo $result;
     }
     else {
        header("Location: ".$_SERVER["HTTP_REFERER"]);
     }
  
     die();
}

add_action('wp_ajax_show_authors', 'show_authors');

function my_must_login() {
    echo "You must log in to vote";
    die();
 }

 add_action("wp_ajax_nopriv_show_authors", "my_must_login");


function my_script_enqueuer() {
    wp_register_script( "my_display_name_script", WP_PLUGIN_URL.'/show_author/my_display_name_script.js', array('jquery') );
    wp_localize_script( 'my_display_name_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        
 
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'my_display_name_script' );
 
 }
 
 add_action( 'init', 'my_script_enqueuer' );


function display() {
    $nonce = wp_create_nonce("show_authors_nonce");
    ?>
        <button 
            id="linkbutton"
            user="<?php echo get_current_user_id() ?>"
            post-title="<?php echo get_the_title() ?>"
            data-nonce="<?php echo $nonce; ?>">Click here to submit new information
        </button>

        <div>
            <ul id="list">
                
            </ul>

        </div>
    <?php
}

 add_shortcode('shortcode_show_authors', 'display');


