<?php
/**
 * Plugin Name: Buy Me A Beer Widget
 * Plugin URI:
 * Description:
 * Version: 1.0
 * Author: Daan van den Bergh
 * Author URI: https://daan.dev
 * License: GPL2v2 or later
 */
define('BUY_ME_A_BEER_STATIC_VERSION', '1.0.0');

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
        $hostedButtonId = (isset($instance['hosted_button_id'])) ? $instance['hosted_button_id'] : '1XXX234XXX4X5X';
        ?>
        <p>
            <label for="<?= $this->get_field_id('hosted_button_id'); ?>">
                <?php _e('Hosted Button ID:'); ?>
            </label>
            <input class="widefat"
                   id="<?= $this->get_field_id('hosted_button_id'); ?>"
                   name="<?= $this->get_field_name('hosted_button_id'); ?>"
                   type="text" value="<?= esc_attr($hostedButtonId); ?>"/>
        </p>
        <?php
        $title = (isset($instance['title'])) ? $instance['title'] : __('Buy Me A Beer!', 'bmab_widget');
        ?>
        <p>
            <label for="<?= $this->get_field_id('title'); ?>">
                <?php _e('Title:'); ?>
            </label>
            <input class="widefat"
                   id="<?= $this->get_field_id('title'); ?>"
                   name="<?= $this->get_field_name('title'); ?>"
                   type="text" value="<?= esc_attr($title); ?>"/>
        </p>
        <?php
        $description = (isset($instance['description'])) ? $instance['description'] : __('Please consider donating.', 'bmab_widget');
        ?>
        <p>
            <label for="<?= $this->get_field_id('description'); ?>">
                <?php _e('Description:'); ?>
            </label>
            <textarea class="widefat"
                      id="<?= $this->get_field_id('description'); ?>"
                      name="<?= $this->get_field_name('description'); ?>"><?= esc_attr($description); ?></textarea>
        </p>
        <?php
        $amountTitle = (isset($instance['amount_title'])) ? $instance['amount_title'] : __('Choose Amount:', 'bmab_widget');
        ?>
        <p>
            <label for="<?= $this->get_field_id('amount_title'); ?>">
                <?php _e('Amount Title:'); ?>
            </label>
            <input class="widefat"
                   id="<?= $this->get_field_id('amount_title'); ?>"
                   name="<?= $this->get_field_name('amount_title'); ?>"
                   type="text" value="<?= esc_attr($amountTitle); ?>"/>
        </p>
        <?php
        $amountComment = (isset($instance['amount_comment'])) ? $instance['amount_comment'] : __('How much do you want to donate?', 'bmab_widget');
        ?>
        <p>
            <label for="<?= $this->get_field_id('amount_comment'); ?>">
                <?php _e('Amount Comment:'); ?>
            </label>
            <textarea class="widefat"
                      id="<?= $this->get_field_id('amount_comment'); ?>"
                      name="<?= $this->get_field_name('amount_comment'); ?>"><?= esc_attr($amountComment); ?></textarea>
        </p>
        <?php
        $pmTitle = (isset($instance['pm_title'])) ? $instance['pm_title'] : __('Personal Message:', 'bmab_widget');
        ?>
        <p>
            <label for="<?= $this->get_field_id('pm_title'); ?>">
                <?php _e('Personal Message Title:'); ?>
            </label>
            <input class="widefat"
                   id="<?= $this->get_field_id('pm_title'); ?>"
                   name="<?= $this->get_field_name('pm_title'); ?>"
                   type="text" value="<?= esc_attr($pmTitle); ?>"/>
        </p>
        <?php
        $pmComment = (isset($instance['pm_comment'])) ? $instance['pm_comment'] : __('Leave a message!', 'bmab_widget');
        ?>
        <p>
            <label for="<?= $this->get_field_id('pm_comment'); ?>">
                <?php _e('Personal Message Comment:'); ?>
            </label>
            <textarea class="widefat"
                      id="<?= $this->get_field_id('pm_comment'); ?>"
                      name="<?= $this->get_field_name('pm_comment'); ?>"><?= esc_attr($pmComment); ?></textarea>
        </p>
        <?php
        $buttonValue = (isset($instance['button_value'])) ? $instance['button_value'] : __('Donate', 'bmab_widget');
        ?>
        <p>
            <label for="<?= $this->get_field_id('button_value'); ?>">
                <?php _e('Donate Button Value:'); ?>
            </label>
            <input class="widefat"
                   id="<?= $this->get_field_id('button_value'); ?>"
                   name="<?= $this->get_field_name('button_value'); ?>"
                   type="text" value="<?= esc_attr($buttonValue); ?>"/>
        </p>
        <?php
        $afterForm = (isset($instance['after_form'])) ? $instance['after_form'] : __('Thank you for donating!', 'bmab_widget');
        ?>
        <p>
            <label for="<?= $this->get_field_id('after_form'); ?>">
                <?php _e('After form message:'); ?>
            </label>
            <textarea class="widefat"
                      id="<?= $this->get_field_id('after_form'); ?>"
                      name="<?= $this->get_field_name('after_form'); ?>"><?= esc_attr($afterForm); ?></textarea>
        </p>
        <?php
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