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
        array(
            'sparkling-child-bootstrap',
            'sparkling-child-icons',
            'sparkling-child-style'
        ),
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
        $instance                     = array();
        $instance['hosted_button_id'] = (!empty($newInstance['hosted_button_id'])) ? strip_tags($newInstance['hosted_button_id']) : '';
        $instance['title']            = (!empty($newInstance['title'])) ? strip_tags($newInstance['title']) : '';
        $instance['description']      = (!empty($newInstance['description'])) ? strip_tags($newInstance['description']) : '';
        $instance['amount_title']     = (!empty($newInstance['amount_title'])) ? strip_tags($newInstance['amount_title']) : '';
        $instance['amount_comment']   = (!empty($newInstance['amount_comment'])) ? strip_tags($newInstance['amount_comment']) : '';
        $instance['pm_title']         = (!empty($newInstance['pm_title'])) ? strip_tags($newInstance['pm_title']) : '';
        $instance['pm_comment']       = (!empty($newInstance['pm_comment'])) ? strip_tags($newInstance['pm_comment']) : '';
        $instance['button_value']     = (!empty($newInstance['button_value'])) ? strip_tags($newInstance['button_value']) : '';
        $instance['after_form']       = (!empty($newInstance['after_form'])) ? strip_tags($newInstance['after_form']) : '';
        
        return $instance;
    }
}