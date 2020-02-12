<?php
/**
 * annanzi.com functions and definitions
 * 本主题所用到的相关函数
 * 所有自定义函数请使用ANZ_为前缀
 *
 * @package annanzi
 * @author annanzi/910109610@qq.com
 */

/**
 * 重新定义fetch_feed函数
 *
 *
 * @param mixed $url URL of feed to retrieve. If an array of URLs, the feeds are merged
 * using SimplePie's multifeed feature.
 * See also {@link http://simplepie.org/wiki/faq/typical_multifeed_gotchas}
 *
 * @return WP_Error|SimplePie WP_Error object on failure or SimplePie object on success
 */
function ANZ_fetch_feed( $url ) {
    if ( ! class_exists( 'SimplePie', false ) ) {
        require_once( ABSPATH . WPINC . '/class-simplepie.php' );
    }

    require_once( ABSPATH . WPINC . '/class-wp-feed-cache.php' );
    require_once( ABSPATH . WPINC . '/class-wp-feed-cache-transient.php' );
    require_once( ABSPATH . WPINC . '/class-wp-simplepie-file.php' );
    require_once( ABSPATH . WPINC . '/class-wp-simplepie-sanitize-kses.php' );

    $feed = new SimplePie();

    $feed->set_sanitize_class( 'WP_SimplePie_Sanitize_KSES' );
    // We must manually overwrite $feed->sanitize because SimplePie's
    // constructor sets it before we have a chance to set the sanitization class
    $feed->sanitize = new WP_SimplePie_Sanitize_KSES();

    $feed->set_cache_class( 'WP_Feed_Cache' );
    $feed->set_file_class( 'WP_SimplePie_File' );

    $feed->set_feed_url( $url );
    /** This filter is documented in wp-includes/class-wp-feed-cache-transient.php */
    $feed->set_cache_duration( apply_filters( 'wp_feed_cache_transient_lifetime', 12 * HOUR_IN_SECONDS, $url ) );
    /**
     * Fires just before processing the SimplePie feed object.
     *
     * @since 3.0.0
     *
     * @param object $feed SimplePie feed object (passed by reference).
     * @param mixed  $url  URL of feed to retrieve. If an array of URLs, the feeds are merged.
     */
    do_action_ref_array( 'wp_feed_options', array( &$feed, $url ) );

    $feed->init();
    $feed->set_output_encoding( get_option( 'blog_charset' ) );

    if ( $feed->error() ) {
        return new WP_Error( 'simplepie-error', $feed->error() );
    }

    return $feed;
}

?>