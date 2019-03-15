/**
 * @author Daan van den Bergh
 * @package buy-me-a-beer-widget
 * @url https://daan.dev
 * @copyright Daan van den Bergh (c) 2019
 */

let $window = jQuery(window);

$window.scroll(function() {
    /**
     * Selectors we're going to use.
     */
    widget = jQuery('.buy-me-a-beer-widget');
    widgetClone = jQuery('.buy-me-a-beer-widget-clone');
    
    /**
     * Make sure widgetClone has correct width, since its
     * position is fixed.
     */
    widgetWidth = widget.width();
    widgetClone.width(widgetWidth);
    
    /**
     * Only appear if widget reaches top of screen.
     */
    navbarHeight = jQuery('nav.navbar').height();
    widgetOffset = widget.offset().top - navbarHeight;
    
    if ($window.scrollTop() >= widgetOffset) {
        widgetClone.css('top', navbarHeight + 10);
        widgetClone.show();
    } else {
        widgetClone.hide();
    }
});
