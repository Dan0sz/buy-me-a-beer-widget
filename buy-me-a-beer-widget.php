<?php
/**
 * Plugin Name: Buy Me A Beer Widget
 * Description: Add a PayPay donation form widget to your WordPress blog.
 * Version: 1.0.1
 * Author: Daan van den Bergh
 * Author URI: https://daan.dev
 * License: GPL2v2 or later
 */

define('BUY_ME_A_BEER_STATIC_VERSION', '1.0.1');

/**
 * Register and Load Widget
 */
function bmab_load_widget()
{
    register_widget('BuyMeABeer_Widget');
}
add_action('widgets_init', 'bmab_load_widget');

/**
 * Load Stylesheet
 */
function bmab_load_styles()
{
    wp_register_style(
        'buy-me-a-beer-styles',
        plugins_url() . '/buy-me-a-beer-widget/css/bmab.min.css',
        array(),
        BUY_ME_A_BEER_STATIC_VERSION,
        'all'
    );
    wp_enqueue_style('buy-me-a-beer-styles');
    wp_register_script(
        'buy-me-a-beer-scripts',
        plugins_url() . '/buy-me-a-beer-widget/js/bmab.js',
        array(
            'jquery'
        ),
        BUY_ME_A_BEER_STATIC_VERSION,
        false
    );
    wp_enqueue_script('buy-me-a-beer-scripts');
}
add_action('wp_enqueue_scripts', 'bmab_load_styles');

/**
 * @param array $buttonIds
 * @param array $currencies
 * @param array $symbols
 *
 * @return mixed
 */
function bmab_generate_form_data(array $buttonIds, array $currencies, array $symbols)
{
    $i = 0;
    foreach ($buttonIds as $button) {
        $forms[$currencies[$i]] = array(
            'buttonId' => $button,
            'symbol'   => $symbols[$i]
        );
        $i++;
    }

    return $forms;
}

/**
 * @return string
 */
function bmab_get_user_ip_address()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

/**
 * @param      $token
 * @param null $ip
 *
 * @return string
 */
function bmab_get_currency_for_ip($token)
{
    include(plugin_dir_path(__FILE__) . 'includes/countries.php');

    $response = wp_remote_get("https://ipinfo.io/country?token=$token");

    $country  = rtrim($response['body']);

    if (in_array($country, $euCountries)) {
        return 'EUR';
    }

    if (in_array($country, $gbCountries)) {
        return 'GBP';
    }

    return 'USD';
}

/**
 * Class BuyMeABeer_Widget
 */
class BuyMeABeer_Widget extends WP_Widget
{
    /**
     * BuyMeABeer_Widget constructor.
     */
    function __construct()
    {
        parent::__construct(
            'bmab_widget',
            __('Buy Me A Beer widget', 'bmab_widget'),
            array(
                'classname'   => 'buy-me-a-beer-widget',
                'description' => __('Insert a Buy Me A Beer-form.', 'bmab_widget')
            )
        );
    }

    /**
     * Frontend Output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $widgets = [
            'buy-me-a-beer-widget',
            'buy-me-a-beer-widget-clone'
        ];

        foreach ($widgets as $widget) {
            $title = apply_filters('widget_title', $instance['title']);

            $args['before_widget'] = str_replace('buy-me-a-beer-widget', $widget, $args['before_widget']);

            // before and after widget arguments are defined by themes
            echo $args['before_widget'];

            if (!empty($title)) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            include(plugin_dir_path(__FILE__) . 'includes/widget.phtml');

            echo $args['after_widget'];
        }
    }

    /**
     * Backend Form
     *
     * @param array $instance
     *
     * @return string|void
     */
    public function form($instance)
    {
        include(plugin_dir_path(__FILE__) . 'includes/options.phtml');
    }

    /**
     * Process changes
     *
     * @param array $newInstance
     * @param array $oldInstance
     *
     * @return array
     */
    public function update($newInstance, $oldInstance)
    {
        $instance = array();
        $options  = array(
            'hosted_button_id',
            'currencies',
            'currency_symbols',
            'title',
            'description',
            'amount_title',
            'amount_comment',
            'pm_title',
            'pm_comment',
            'button_value',
            'after_form',
            'ip_info',
            'ip_info_token'
        );

        foreach ($options as $option) {
            $instance[$option] = (!empty($newInstance[$option])) ? strip_tags($newInstance[$option]) : '';
        }

        return $instance;
    }
}