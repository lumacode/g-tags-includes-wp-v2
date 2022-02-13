<?php 
/* 
* Plugin Name: G-Tags Include 
* Plugin URI:
* Description: Add Google Analytics and Adwords tags to the header simply and quickly.  
* Version: 1.0.0
* Author: Luis Albanese
* Author URI: https://luisalbanese.com.ar
* License: GPL
* Text Domain: gtagsinclude 
*/

if(!defined('ABSPATH')) die();

//Custom item menu 
  
function gtag_create_menu()
{
    add_menu_page(
    'Agregar Google Tags - Analytics y/o Adwords', //Title page
    'G-Tags Include', //Title menu
    'manage_options', //Capability
    'gtags_menu', //slug
    'gtag_template', //function name
    '', //icon
    '6' //position
    );
  }

add_action('admin_menu', 'gtag_create_menu');

//Scripts admin panel 

function gtag_load_plugins()
{

  $plugin_url = plugin_dir_url(__FILE__);

  wp_enqueue_style('styles_gtags', $plugin_url . 'css/style.css');
  wp_enqueue_script('custom_gtag', $plugin_url . 'js/main.js', array('jquery'), '1.0.0', true);

  wp_localize_script( 'custom_gtag', 'ajax_var', array(
    'url'    => admin_url( 'admin-ajax.php' ),
    'action' => 'update-gtag'
  ));
}

add_action('admin_enqueue_scripts', 'gtag_load_plugins');


//Update tags in db

function gtag_insert_tags()
{

  // Check for nonce security
  $nonce = sanitize_text_field( $_POST['nonce'] );

  if ( ! wp_verify_nonce( $nonce, 'gtag-ajax-nonce' ) ) {
      die ( 'Busted!');
  }

  if(isset($_POST['g_tag'])){
    $g_tag = $_POST['g_tag'];
    
    update_option('g_tag_includes', $g_tag);

    return true;

  }    

}

//add_action( 'wp_ajax_nopriv_update-gtag', 'gtag_insert_tags' );
add_action( 'wp_ajax_update-gtag', 'gtag_insert_tags' );

//Template

function gtag_template()
{

  $g_tag = get_option('g_tag_includes') ? str_replace ( '\\' , '' , get_option('g_tag_includes')) : '';
  $plugin_url = plugin_dir_url(__FILE__);

    ?>

    <div class='container-gtag'> 

        <h2 class='text-primary'>Google tags plugin <img src='<?=esc_attr($plugin_url)?>connect.png' alt='icon connect' /> </h2>
        <p>
          <?php echo __('Agregue las etiquetas de seguimiento de Google Analytics y/o conversiones de Google Ads en la etiqueta head del sitio.', 'gtagsinclude'); ?>
        </p>
          <hr>
        <form method='POST' action='?page=gtags_menu' id='formTags'>
        <input type="hidden" name="gtag-ajax-nonce" id="gtag_nonce" value="<?php echo wp_create_nonce( 'gtag-ajax-nonce');?>">
          <label for='analy'><?php echo __('Agregar Google tags:', 'gtagsinclude'); ?></label> <br>
          <textarea id='analy' class='mt-gtag text-area-gtag' name='g_tag'><?php echo esc_html($g_tag); ?></textarea> <br>
            <div class='alert-success-gtag'> 
            <?php echo __('La información ha sido actualizada correctamente', 'gtagsinclude'); ?>
            </div>
          <button class='mt-gtag btn-gtag' type='submit'><?php echo __('Guardar', 'gtagsinclude'); ?></button>
        </form>

          <p class='by-gtag'>
          <?php echo __('Desarrollado por ', 'gtagsinclude'); ?><a href='https://luisalbanese.com.ar' target='_blank'>Luis Albanese</a>
          </p>
    </div>
    
    <?php
}

//Insert gtag in head page

function gtag_global()
{
  echo str_replace ( '\\' , '' , get_option('g_tag_includes'));
}

add_action('wp_head', 'gtag_global');



