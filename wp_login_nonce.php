<?php
/**
 * Adds a WP nonce to the login form.
 *
 * Note that, because Wordpress doesn't support real nonces (i.e. they can be
 * used more than once, because WP doesn't store server side state), this is 
 * less secure than it ideally would be.  Instead a timeout is implemented to
 * significantly reduce the lifespan of nonces on the login and registration
 * forms.  A refresh meta is used to refresh the page so that users do not
 * experience nonce timeouts.
 *
 * @author Liam O'Boyle <liam@ontheroad.net.nz>
 * @see http://codex.wordpress.org/WordPress_Nonces
 * @see http://en.wikipedia.org/wiki/Cryptographic_nonce
 */
/*
 * Plugin Name: Login Nonce
 * Description: Adds a nonce to the login screen.
 */
call_user_func(function () {
    // Nonce "name"
    $nonceName    = 'liu5einaaw1Aeng';
    // Nonce "action"
    $nonceAction  = 'Eijaeg4ahSeeshe';
    // How long the nonce token is valid for
    $nonceTimeout = 30;
    // Whether to use a meta refresh to refresh the page when the nonce expires
    $doRefresh    = true;
    // Which pages to reduce the nonce lifetime on 
    $filterPages  = array('wp-login.php', 'wp-register.php');

    // Semi-randomise the nonce field name
    $parts = array($nonceName, site_url());
    foreach (array('REMOTE_ADDR', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP')
        as $key) {
        $parts[] = isset($_SERVER[$key]) ? $_SERVER[$key] : '';
    }
    $nonceName = md5(implode('-', $parts));


    // Adds the field to the login form
    add_action('login_form', function () use ($nonceName, $nonceAction) {
        wp_nonce_field($nonceAction, $nonceName);
    });


    // Reduce nonce lifetime if we're on the login page
    if (in_array($GLOBALS['pagenow'], $filterPages)
        && (empty($_GET['action']) || $_GET['action'] != 'logout')) {
            add_filter('nonce_life', function ($life) use ($nonceTimeout) {
                return $nonceTimeout;
            });
        }

    // Refresh the login page on nonce expiry, to avoid timeouts
    if ($doRefresh) {
        add_action('login_head', function () use ($nonceTimeout) {
            echo sprintf('<meta http-equiv="refresh" content="%d">',
                $nonceTimeout);
        });
    }

    // Check the nonce for authentication
    add_filter('authenticate',
        function ($user, $username, $password) use ($nonceName, $nonceAction) {
            if (empty($_POST)) {
                // Request not posted, so do nothing
                return;
            }

            $nonce = isset($_POST[$nonceName]) ? $_POST[$nonceName] : false;
            if (!($user || ($nonce && wp_verify_nonce($nonce, $nonceAction)))) {
                // Disable normal password authentication
                remove_action('authenticate',
                    'wp_authenticate_username_password', 20);

                // Return an error
                return new WP_Error('denied', 
                    __('<strong>ERROR</strong>: Invalid username or password.')
                );
            }
        }, 10, 3); 
});
