<?php

/**
 * Plugin Name: Gogodigital Cookie Consent
 * Plugin URI: http://www.gogodigital.it
 * Description: Adding Cookie Consent script to a Wordpress site
 * Author: Gogodigital S.r.l.s.
 * Version: 2.0.0
 * Author URI: http://www.gogodigital.it
 */
 
if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}
 
class CookieConsentSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Cookie Consent Settings Admin', 
            'WP Cookie Consent', 
            'manage_options', 
            'cookie-consent-settings', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'cookieconsent_options' );
        ?>
        <div class="wrap">
			<div style="border-right: 1px solid #ddd; float: left; padding-right: 2%;  width: 50%">
				<h1>WP Gogo Cookie Consent Settings</h1>
				<form method="post" action="options.php">
				<?php
					// This prints out all hidden setting fields
					settings_fields( 'cookieconsent_group' );
					do_settings_sections( 'cookie-consent-settings' );
					submit_button();
				?>
				</form>
			</div>
			<div style="float: left; margin-left: 2%; width: 40%">
				<h3 style="margin-top: 2em;">Donate $5, $10 or $20</h3>
				<p>If you like this plugin, consider supporting us donating the time to develop it.</p>
				<div>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="TNGRD94CRZLVU">
						<input type="image" src="https://www.paypalobjects.com/en_US/IT/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
				<p>Some other ways to support this plugin</p>
				<ul>
					<li><a href="http://wordpress.org/support/view/plugin-reviews/wp-gogo-cookie-consent?rate=5#postform" target="_blank">Leave a ★★★★★ review on WordPress.org</a></li>
					<li><a href="https://twitter.com/intent/tweet/?text=I+am+using+Wordpress+%22WP+GoGo+Cookie+Consent%22+plugin+to+show+simple+cookie+alert+message+on+my+WordPress+site.&amp" target="_blank">Tweet about this plugin</a></li>
					<li><a href="http://wordpress.org/plugins/wp-gogo-cookie-consent/#compatibility" target="_blank">Vote "works" on the WordPress.org plugin page</a></li>
				</ul>
				<h3 class="ss-title">Looking for support?</h3>
				<p>Please use the <a href="#">plugin support forums</a> on WordPress.org.</p>
				<div style="clear: both"></div>
			</div>
			<div style="clear: both"></div>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'cookieconsent_group', // Option group
            'cookieconsent_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            '', // Title
            array( $this, 'print_section_info' ), // Callback
            'cookie-consent-settings' // Page
        );  
		
		add_settings_field(
            'theme',  // ID
            'Theme',  // Title 
            array( $this, 'theme_callback' ),  // Callback
            'cookie-consent-settings',  // Page
            'setting_section_id' // Section  
        );      
		
		add_settings_field(
            'message',  // ID
            'Message',  // Title 
            array( $this, 'message_callback' ),  // Callback
            'cookie-consent-settings',  // Page
            'setting_section_id' // Section  
        );     

        add_settings_field(
            'dismiss_message', // ID
            'Dismiss Message', // Title 
            array( $this, 'dismiss_message_callback' ), // Callback
            'cookie-consent-settings', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'learn_more_message',  // ID
            'Learn More Message',  // Title 
            array( $this, 'learn_more_message_callback' ),  // Callback
            'cookie-consent-settings',  // Page
            'setting_section_id' // Section  
        );      
		
		add_settings_field(
            'privacy_link',  // ID
            'Privacy Link',  // Title 
            array( $this, 'privacy_link_callback' ),  // Callback
            'cookie-consent-settings',  // Page
            'setting_section_id' // Section  
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
		
		if( isset( $input['theme'] ) )
            $new_input['theme'] = sanitize_text_field( $input['theme'] );
		
		if( isset( $input['message'] ) )
            $new_input['message'] = sanitize_text_field( $input['message'] );
		
        if( isset( $input['dismiss_message'] ) )
            $new_input['dismiss_message'] = sanitize_text_field( $input['dismiss_message'] );

        if( isset( $input['learn_more_message'] ) )
            $new_input['learn_more_message'] = sanitize_text_field( $input['learn_more_message'] );
		
		if( isset( $input['privacy_link'] ) )
            $new_input['privacy_link'] = sanitize_text_field( $input['privacy_link'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        //print 'Enter your settings below:';
    }
	
	/** 
     * Get the settings option array and print one of its values
     */
    public function theme_callback()
    {
		$select  = '<select id="theme" name="cookieconsent_options[theme]" aria-describedby="timezone-description">';
		
		if($this->options['theme'] == "dark-bottom") {
			$select .= '<option value="dark-bottom" selected="selected">Dark Bottom</option>';
		} else {
			$select .= '<option value="dark-bottom">Dark Bottom</option>';
		}
		
		if($this->options['theme'] == "dark-floating") {
			$select .= '<option value="dark-floating" selected="selected">Dark Floating</option>';
		} else {
			$select .= '<option value="dark-floating">Dark Floating</option>';
		}
		
		if($this->options['theme'] == "dark-top") {
			$select .= '<option value="dark-top" selected="selected">Dark Top</option>';
		} else {
			$select .= '<option value="dark-top">Dark Top</option>';
		}
		
		if($this->options['theme'] == "light-bottom") {
			$select .= '<option value="light-bottom" selected="selected">Light Bottom</option>';
		} else {
			$select .= '<option value="light-bottom">Light Bottom</option>';
		}
		
		if($this->options['theme'] == "light-floating") {
			$select .= '<option value="light-floating" selected="selected">Light Floating</option>';
		} else {
			$select .= '<option value="light-floating">Light Floating</option>';
		}
		
		if($this->options['theme'] == "light-top") {
			$select .= '<option value="light-top" selected="selected">Light Top</option>';
		} else {
			$select .= '<option value="light-top">Light Top</option>';
		}
		
		$select .= '</select>';
		
        printf(
            $select,
            isset( $this->options['theme'] ) ? esc_attr( $this->options['theme']) : ''
        );
    }
	
	/** 
     * Get the settings option array and print one of its values
     */
    public function message_callback()
    {
        printf(
            '<input type="text" class="widefat" id="message" name="cookieconsent_options[message]" value="%s" />',
            isset( $this->options['message'] ) ? esc_attr( $this->options['message']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function dismiss_message_callback()
    {
        printf(
            '<input type="text" class="widefat" id="dismiss_message" name="cookieconsent_options[dismiss_message]" value="%s" />',
            isset( $this->options['dismiss_message'] ) ? esc_attr( $this->options['dismiss_message']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function learn_more_message_callback()
    {
        printf(
            '<input type="text" class="widefat" id="learn_more_message" name="cookieconsent_options[learn_more_message]" value="%s" />',
            isset( $this->options['learn_more_message'] ) ? esc_attr( $this->options['learn_more_message']) : ''
        );
    }
	
	/** 
     * Get the settings option array and print one of its values
     */
    public function privacy_link_callback()
    {
        printf(
            '<input type="text" class="widefat" id="privacy_link" name="cookieconsent_options[privacy_link]" value="%s" />',
            isset( $this->options['privacy_link'] ) ? esc_attr( $this->options['privacy_link']) : ''
        );
    }
}

/*
 * Create Cookie Consent Settings Page
 */

if( is_admin() ) $my_settings_page = new CookieConsentSettingsPage();

/**
 * Adding Cookie Consent js
 */

function add_cookieconsent() 
{	
	wp_enqueue_script( 'cookieconsent2-js', wp_gogo_cookieconsent_get_plugin_url(). '/js/cookieconsent.min.js', array(), '1.0.10', true );
}

function add_cookieconsent_custom() 
{
	$cookieconsent_options = get_option('cookieconsent_options');
	
	$cookieCustom = 'window.cookieconsent_options = {
		"theme": "'.$cookieconsent_options["theme"].'",
		"message": "'.$cookieconsent_options["message"].'",
		"dismiss": "'.$cookieconsent_options["dismiss_message"].'"';
		
	if(isset($cookieconsent_options["privacy_link"]) && isset($cookieconsent_options["learn_more_message"])) {
		$cookieCustom .= ', 
		"learnMore":"'.$cookieconsent_options["learn_more_message"].'", 
		"link": "'.$cookieconsent_options["privacy_link"].'"';
	}		
	
	$cookieCustom .= '};';
	
   wp_enqueue_script( 'cookie-consent-custom-js', '/cookie-consent-custom.js', array(), '1.0' );
   wp_add_inline_script( 'cookie-consent-custom-js', $cookieCustom );
}

add_action( 'wp_enqueue_scripts', 'add_cookieconsent' );
add_action( 'wp_enqueue_scripts', 'add_cookieconsent_custom' );

/**
 * Get Plugin URL
 * @return string
 */
function wp_gogo_cookieconsent_get_plugin_url() 
{
    if ( !function_exists('plugins_url') )

        return get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));

    return plugins_url(plugin_basename(dirname(__FILE__)));
}

?>