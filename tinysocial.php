<?php
/**
 * Plugin Name: tinySocial
 * Description: Easy way to insert lightweight social sharing links to your posts/pages via shortcodes.
 * Author: Arūnas Liuiza
 * Version: 1.3.2
 * Author URI: http://arunas.co/
 * Plugin URI: http://arunas.co/tinysocial
 * License: GPL2 or later
 * Text Domain: tinysocial
*/

add_action( 'plugins_loaded', array( 'tinySocial', 'init' ) );

class tinySocial {
	public static $asset_version     = '1.3.2';
	private static $network_defaults = array();
	public static $plugin_dir;

	public static $options          = array(
		'link_template'    => '<a href="{href}" class="tinysocial {class}"{analytics}{popup_dimensions}>{icon_template}<span class="tinysocial__title">{title}</span></a>',
		'icon_template'    => true,
		'append'           => array( 'post' ),
		'append_template'  => '',
		'active_networks'  => array(
			'facebook',
			'twitter',
			'google',
		),
		'facebook_appid'   => '',
		'twitter_via'      => '',
		'twitter_hashtags' => '',
	);
	protected static $icon_template = '<span class="tinysocial__icon">{svg_icon}</span>';

	public static function init() {
		self::$plugin_dir = plugin_dir_path( __FILE__ );
		$options          = get_option( 'tinysocial_options' );

		self::$options['append_template'] = __( '<i>Don\'t forget to share this via [tinysocial_all].</i>', 'tinysocial' );
		self::$network_defaults           = array(
			'facebook'    => array(
				'title' => __( 'Facebook', 'tinysocial' ),
				'href'  => 'https://www.facebook.com/dialog/share?app_id={app_id}&display=popup&href={url}&redirect_uri={redirect_url}',
				'class' => 'tinysocial-facebook',
				'icon'  => 'ico-facebook',
			),
			'twitter'     => array(
				'title' => __( 'Twitter', 'tinysocial' ),
				'href'  => 'https://twitter.com/share?url={url}&text={title}',
				'class' => 'tinysocial-twitter',
				'icon'  => 'ico-twitter',
			),
			'google'      => array(
				'title' => __( 'Google+', 'tinysocial' ),
				'href'  => 'https://plus.google.com/share?url={url}',
				'class' => 'tinysocial-google-plus',
				'icon'  => 'ico-google-plus',
			),
			'pinterest'   => array(
				'title' => __( 'Pinterest', 'tinysocial' ),
				'href'  => 'https://pinterest.com/pin/create/bookmarklet/?media={img}&url={url}&is_video=0&description={title}',
				'class' => 'tinysocial-pinterest',
				'icon'  => 'ico-pinterest',
			),
			'linkedin'    => array(
				'title' => __( 'LinkedIn', 'tinysocial' ),
				'href'  => 'http://www.linkedin.com/shareArticle?url={url}&title={title}',
				'class' => 'tinysocial-linkedin',
				'icon'  => 'ico-linkedin',
			),
			'buffer'      => array(
				'title'  => __( 'Buffer', 'tinysocial' ),
				'href'   => 'http://bufferapp.com/add?text={title}&url={url}',
				'class'  => 'tinysocial-buffer',
				'icon'   => 'ico-buffer',
				'height' => '600',
				'width'  => '600',
			),
			'digg'        => array(
				'title' => __( 'Digg', 'tinysocial' ),
				'href'  => 'http://digg.com/submit?url={url}&title={title}',
				'class' => 'tinysocial-digg',
				'icon'  => 'ico-digg',
			),
			'tumblr'      => array(
				'title' => __( 'Tumblr', 'tinysocial' ),
				'href'  => 'http://www.tumblr.com/share/link?url={url}&name={title}&description={desc}',
				'class' => 'tinysocial-tumblr',
				'icon'  => 'ico-tumblr',
			),
			'reddit'      => array(
				'title' => __( 'Reddit', 'tinysocial' ),
				'href'  => 'http://reddit.com/submit?url={url}&title={title}',
				'class' => 'tinysocial-reddit',
				'icon'  => 'ico-reddit',
			),
			'stumbleupon' => array(
				'title' => __( 'StumbleUpon', 'tinysocial' ),
				'href'  => 'http://www.stumbleupon.com/submit?url={url}&title={title}',
				'class' => 'tinysocial-stumbleupon',
				'icon'  => 'ico-stumbleupon',
			),
			'delicious'   => array(
				'title' => __( 'Delicious', 'tinysocial' ),
				'href'  => 'https://delicious.com/save?v=5&provider={provider}&noui&jump=close&url={url}&title={title}',
				'class' => 'tinysocial-delicious',
				'icon'  => 'ico-delicious',
			),
			'whatsapp'    => array(
				'title'  => __( 'WhatsApp', 'tinysocial' ),
				'href'   => 'https://api.whatsapp.com/send?text={plain_title}%20{url}',
				'class'  => 'tinysocial-whatsapp',
				'height' => '700',
				'width'  => '800',
				'icon'   => 'ico-whatsapp',
			),
			'line'        => array(
				'title'  => __( 'Line', 'tinysocial' ),
				'href'   => 'https://social-plugins.line.me/lineit/share?url={url}',
				'class'  => 'tinysocial-line',
				'height' => '600',
				'width'  => '650',
				'icon'   => 'ico-line',
			),
			'naver'       => array(
				'title'  => __( 'Naver', 'tinysocial' ),
				'href'   => 'https://share.naver.com/web/shareView.nhn?url={url}&title={title}',
				'class'  => 'tinysocial-naver',
				'height' => '600',
				'width'  => '600',
				'icon'   => 'ico-naver',
			),
		);

		// filter networks
		self::$network_defaults = apply_filters( 'tinysocial_networks', self::$network_defaults );

		self::$options['active_networks'] = array_keys( self::$network_defaults );

		if ( ! is_array( self::$options['append'] ) ) {
			if ( self::$options['append'] ) {
				self::$options['append'] = array( 'post' );
			} else {
				self::$options['append'] = array();
			}
		}

		self::$options = wp_parse_args( $options, self::$options );

		if ( ! self::$options['facebook_appid'] ) {
			unset( self::$network_defaults['facebook'] );
		}

		if ( is_admin() ) {
			add_action( 'admin_menu', array( 'tinySocial', 'admin_init' ) );
		}

		// network specific shortcodes and action hooks
		foreach ( array_keys( self::$network_defaults ) as $network ) {
			add_shortcode( $network, array( 'tinySocial', 'shortcode' ) );
			add_shortcode( "tiny_{$network}", array( 'tinySocial', 'shortcode' ) );
			add_action( "tiny_{$network}", array( 'tinySocial', 'action' ) );
		}
		// catch-all shortcode
		add_shortcode( 'tinysocial', array( 'tinySocial', 'real_shortcode' ) );
		add_shortcode( 'tinysocial_all', array( 'tinySocial', 'all_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( 'tinySocial', 'styles' ), 9 );
		// add javascript
		add_action( 'wp_enqueue_scripts', array( 'tinySocial', 'scripts' ) );
		if ( self::$options['append'] ) {
			add_filter( 'the_content', array( 'tinySocial', 'content' ), 1 );
		}
		add_filter( 'tinysocial_network_args', array( 'tinySocial', 'better_links' ) );
		add_filter( 'tinysocial_icon_template', array( 'tinySocial', 'hide_icon' ), 100 );
		add_action( 'init', array( 'tinySocial', 'init_filters' ) );
	}
	public function hide_icon( $icon_template ) {
		if ( self::$options['icon_template'] ) {
			return $icon_template;
		}
		return '';
	}

	public static function init_filters() {
		self::$icon_template = apply_filters( 'tinysocial_icon_template', self::$icon_template );
	}

	public static function better_links( $args ) {
		if ( 'twitter' == $args['network'] && ( self::$options['twitter_via'] || self::$options['twitter_hashtags'] ) ) {
			if ( self::$options['twitter_via'] ) {
				$args['href'] .= '&via={via}';
			}
			if ( self::$options['twitter_hashtags'] ) {
				$args['href'] .= '&hashtags={hashtags}';
			}
		}
		return $args;
	}

	public static function content( $content ) {
		if ( is_main_query() && is_singular( self::$options['append'] ) && ! doing_filter( 'get_the_excerpt' ) ) {
			$content .= "\r\n\r\n" . self::$options['append_template'];
		}
		return $content;
	}

	public static function admin_init() {
		require_once( self::$plugin_dir . 'includes/options.php' );
		$networks = array();

		foreach ( self::$network_defaults as $key => $value ) {
			$networks[ $key ] = $value['title'] . " <code>[{$key}]</code>";
		}

		$args      = array(
			'public' => true,
		);
		$posttypes = get_post_types( $args, 'object' );
		unset( $posttypes['attachment'] );

		foreach ( $posttypes as $key => $value ) {
			$posttypes[ $key ] = $value->label;
		}

		$fields = array(
			'general' => array(
				'title'    => '',
				'callback' => '',
				'options'  => array(
					'link_template'    => array(
						'title'    => __( 'Template for the social links', 'tinysocial' ),
						'args'     => array(
							'description' => __( 'Available placeholders: <code>{href}</code>, <code>{title}</code>, <code>{class}</code>, <code>{analytics}</code>, <code>{popup_dimensions}</code> and <code>{icon_template}</code>.', 'tinysocial' ),
							'cols'        => 80,
							'rows'        => 4,
						),
						'callback' => 'textarea',
					),
					'icon_template'    => array(
						'title'    => __( 'Display icons?', 'tinysocial' ),
						'callback' => 'checkbox',
					),
					'append'           => array(
						'title'    => __( 'Append automatically to', 'tinysocial' ),
						'args'     => array(
							'values' => $posttypes,
						),
						'callback' => 'checklist',
					),
					'append_template'  => array(
						'title'    => __( 'Template for appending', 'tinysocial' ),
						'args'     => array(
							'description' => __( 'This will be appended to the end of every Post. Use tinySocial shortcodes to insert social links: <code>[tinysocial_all]</code> for all the links, <code>[facebook]</code>, <code>[twitter]</code>, etc. for individual networks.', 'tinysocial' ),
						),
						'callback' => 'text',
					),
					'active_networks'  => array(
						'title'    => __( 'Enabled networks', 'tinysocial' ),
						'args'     => array(
							'values' => $networks,
						),
						'callback' => 'checklist',
					),
					'facebook_appid'   => array(
						'title'    => __( 'Facebook App ID', 'tinysocial' ),
						'args'     => array(
							'description' => __( 'This is required for Facebook sharing to work.', 'tinysocial' ),
						),
						'callback' => 'text',
					),
					'twitter_via'      => array(
						'title'    => __( 'Twitter handle', 'tinysocial' ),
						'args'     => array(
							'description' => __( 'Without <code>@</code> sign.', 'tinysocial' ),
						),
						'callback' => 'text',
					),
					'twitter_hashtags' => array(
						'title'    => __( 'Twitter hashtags', 'tinysocial' ),
						'args'     => array(
							'description' => __( 'Comma separated, without <code>#</code> sign.', 'tinysocial' ),
						),
						'callback' => 'text',
					),
				),
			),
		);

		tinySocial_Options::init(
			'tinysocial',
			__( 'tinySocial', 'tinysocial' ),
			__( 'tinySocial Settings', 'tinysocial' ),
			$fields,
			'tinysocial'
		);
	}

	public static function scripts() {
		wp_register_script( 'tinysocial', plugins_url( 'assets/js/tinysocial.js', __FILE__ ), array( 'jquery' ), self::$asset_version );
		wp_enqueue_script( 'tinysocial' );
	}

	public static function styles() {
		wp_register_style( 'tinysocial', plugins_url( 'assets/css/tinysocial.css', __FILE__ ), array(), self::$asset_version );
		wp_enqueue_style( 'tinysocial' );
	}

	public static function shortcode( $args = array(), $content = '', $tag = '' ) {
		$temp = array();
		if ( is_array( $args ) ) {
			foreach ( $args as $key => $value ) {
				$temp[] = "{$key}=>\"{$value}\"";
			}
		}
		$temp   = implode( ' ', $temp );
		$result = do_shortcode( "[tinysocial network=\"{$tag}\" {$temp}]{$content}[/tinysocial]" );
		return $result;
	}

	public static function real_shortcode( $args = array(), $content = '' ) {
		$content  = do_shortcode( $content );
		$defaults = array(
			'content' => $content,
			'context' => 'shortcode',
			'network' => 'facebook',
		);
		$args     = wp_parse_args( $args, $defaults );
		$context  = $args['context'];
		$link     = self::generate_link( $args, $context );
		return $link;
	}

	public static function all_shortcode( $args = array(), $content = '' ) {
		$defaults = array(
			'exclude'   => '',
			'include'   => '',
			'separator' => ', ',
			'last'      => __( ' and ', 'tinysocial' ),
		);
		$args     = wp_parse_args( $args, $defaults );
		$exclude  = $args['exclude'] ? explode( ',', $args['exclude'] ) : array();
		$include  = $args['include'] ? explode( ',', $args['include'] ) : array();
		if ( $include ) {
			$exclude = array();
		}
		$networks = array_keys( self::$network_defaults );
		$networks = array_intersect( $networks, self::$options['active_networks'] );
		$temp     = array();
		if ( is_array( $networks ) ) {
			foreach ( $networks as $value ) {
				if ( $exclude && in_array( $value, $exclude ) ) {
					continue;
				}
				if ( $include && ! in_array( $value, $include ) ) {
					continue;
				}
				$temp[] = "[{$value}]";
			}
		}
		if ( 1 < sizeof( $temp ) ) {
			$t = array_pop( $temp );
			$temp[ sizeof( $temp ) - 1 ] .= "{$args['last']}{$t}";
		}
		$temp = implode( $args['separator'], $temp );
		$link = do_shortcode( $temp );
		return $link;
	}

	public static function action( $args = array() ) {
		$defaults = array(
			'context' => 'hook',
			'network' => 'facebook',
		);
		$args     = wp_parse_args( $args, $defaults );
		$context  = $args['context'];
		$link     = self::generate_link( $args, $context );
		echo $link;
	}

	private static function generate_link( $args, $context = 'shortcode' ) {
		$network = $args['network'];
		if ( isset( $args['content'] ) && $args['content'] ) {
			$args['title'] = $args['content'];
		}
		$defaults = isset( self::$network_defaults[ $network ] ) ? self::$network_defaults[ $network ] : array();
		$args     = wp_parse_args( $args, $defaults );
		$args     = apply_filters( 'tinysocial_network_args', $args, $context );

		// url
		$replace      = self::generate_replacements( $args['href'], $args );
		$args['href'] = str_replace( array_keys( $replace ), array_values( $replace ), $args['href'] );

		// icon
		$icon_template = self::$icon_template;
		$replace       = self::generate_replacements( self::$icon_template, $args['icon'] );
		$icon_content  = str_replace( array_keys( $replace ), array_values( $replace ), $icon_template );

		// icon template
		$link_template = str_replace( '{icon_template}', $icon_content, self::$options['link_template'] );
		$link_template = apply_filters( 'tinysocial_link_template', $link_template, $context, $args );

		$replace = self::generate_link_replacements( $link_template, $args );
		$link    = str_replace( array_keys( $replace ), array_values( $replace ), $link_template );
		return $link;
	}

	private static function generate_link_replacements( $template, $args ) {
		preg_match_all( '/\{([^}]*)\}/ims', $template, $matches );
		$replacement = array();
		foreach ( $matches[1] as $key ) {
			$value = false;
			if ( isset( $args[ $key ] ) ) {
				$value = $args[ $key ];
			}
			if ( ! $value && 'icon' === $key ) {
				$value = $args['network'];
			}
			if ( ! $value && 'analytics' === $key ) {
				$url     = esc_attr( get_permalink() );
				$network = esc_attr( ucfirst( $args['network'] ) );
				$value   = " data-network=\"{$network}\" data-url=\"{$url}\"";
			}
			if ( ! $value && 'popup_dimensions' === $key ) {
				if ( isset( $args['width'] ) ) {
					$width  = esc_attr( $args['width'] );
					$value .= " data-width=\"{$width}\"";
				}
				if ( isset( $args['height'] ) ) {
					$height = esc_attr( $args['height'] );
					$value .= " data-height=\"{$height}\"";
				}
			}
			$value = apply_filters( 'tinysocial_link_replacement_value', $value, $key, $args );
			$replacement[ "{{$key}}" ] = $value;
		}
		return $replacement;
	}

	private static function generate_replacements( $template, $args ) {
		preg_match_all( '/\{([^}]*)\}/ims', $template, $matches );
		$replacement = array();
		foreach ( $matches[1] as $key ) {
			$replacement[ "{{$key}}" ] = self::get_replacement_value( $key, $args );
		}
		return $replacement;
	}

	private static function get_replacement_value( $key, $args ) {
		switch ( $key ) {
			case 'url':
			case 'redirect_url':
				$value = urlencode( get_permalink() );
				break;
			case 'title':
				$value = urlencode( get_the_title() );
				break;
			case 'plain_title':
				$value = get_the_title();
				break;
			case 'img':
				$img_id = get_post_thumbnail_id();
				$image  = wp_get_attachment_image_src( $img_id, array( 1200, 1200 ) );
				$image  = isset( $image[0] ) ? $image[0] : '';
				$value  = urlencode( $image );
				break;
			case 'desc':
				$value = urlencode( get_the_excerpt() );
				break;
			case 'app_id':
				$value = urlencode( self::$options['facebook_appid'] );
				break;
			case 'via':
				$value = urlencode( self::$options['twitter_via'] );
				break;
			case 'hashtags':
				$value = urlencode( self::$options['twitter_hashtags'] );
				break;
			case 'provider':
				$value = urlencode( get_bloginfo( 'title' ) );
				break;
			case 'svg_icon':
				$value = file_get_contents( self::$plugin_dir . 'assets/svg/' . $args . '.svg' );
				break;
			default:
				$value = false;
				break;
		}
		$value = apply_filters( 'tinysocial_replacement_value', $value, $key );
		return $value;
	}
}
