<?php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
define('WP_DISABLE_FATAL_ERROR_HANDLER', true);

@ini_set('display_errors', 0);
//@ini_set('memory_limit', -1);

/**
 * Disable sending emails
 * enable only if you are using "WP Mail SMTP" plugin
 */
//define('WPMS_ON', true);
//define('WPMS_DO_NOT_SEND', true);

/**
 * Docker redirection loop fix
 * use only if ssl certificate is working
 */
define('FORCE_SSL_ADMIN', true);
if(!defined('WP_CLI')){
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false){
        $_SERVER['HTTPS']='on';
    }
}
