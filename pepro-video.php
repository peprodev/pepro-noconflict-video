<?php
/*
Plugin Name: PeproDev noConflict Video
Description: noConflict WordPress Video widget for WPBakery Page Builder
Contributors: amirhosseinhpv
Tags: Functionality, Visual Composer, WPBakery Page Builder, Video, Video Widget
Author: Pepro Dev. Group
Developer: Amirhosseinhpv
Author URI: https://pepro.dev/
Developer URI: https://hpv.im/
Plugin URI: https://pepro.dev/vc-video
Version: 1.2.0
Stable tag: 1.2.0
Requires at least: 5.0
Tested up to: 5.8
Requires PHP: 5.6
Text Domain: pepro-video
Domain Path: /languages
Copyright: (c) Pepro Dev. Group, All rights reserved.
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
# @Last modified time: 2021/07/15 12:09:46

defined("ABSPATH") or die("Pepro noConflict Video :: Unauthorized Access!");

if (!class_exists("pepro_vc_video")) {
    class pepro_vc_video
    {
        private static $_instance = null;
        public $td;
        public $url;
        public $version;
        public $title;
        public $title_w;
        public $db_slug;
        private $plugin_dir;
        private $plugin_url;
        private $assets_url;
        private $plugin_basename;
        private $plugin_file;
        private $db_table = null;
        private $manage_links = array();
        private $meta_links = array();
        public function __construct()
        {
            global $wpdb;
            $this->td = "pepro-video";
            self::$_instance = $this;
            $this->db_slug = $this->td;
            $this->db_table = $wpdb->prefix . $this->db_slug;
            $this->plugin_dir = plugin_dir_path(__FILE__);
            $this->plugin_url = plugins_url("", __FILE__);
            $this->assets_url = plugins_url("/assets/", __FILE__);
            $this->plugin_basename = plugin_basename(__FILE__);
            $this->url = admin_url("admin.php?page={$this->db_slug}");
            $this->plugin_file = __FILE__;
            $this->version = "1.2.0";
            $this->title = __("noConflict Video", $this->td);
            $this->title_w = sprintf(__("%2\$s ver. %1\$s", $this->td), $this->version, $this->title);
            add_action("init", array($this, 'init_plugin'));
        }
        public function init_plugin()
        {
            load_plugin_textdomain($this->td, false, dirname(plugin_basename(__FILE__)) . "/languages/");
            add_shortcode("video2", array($this, "handle_video2_shortcode"));
            add_action("vc_before_init", array($this, "pepro_vc_video_integrate"));
            if (function_exists('vc_add_shortcode_param')) {
                vc_add_shortcode_param('pepro_mediapicker', '__return_empty_string', plugins_url("/assets/js/vc.init.js", __FILE__));
            }
            add_action("admin_enqueue_scripts", array($this, "admin_enqueue_scripts"));
            add_filter("plugin_action_links_{$this->plugin_basename}", array($this, "plugins_row_links"));
        }
        public function pepro_vc_video_integrate()
        {
          vc_map(
            array(
              'name'                    => __('noConflict Video', $this->td),
              'description'             => __('noConflict WordPress Video widget', $this->td),
              'base'                    => 'video2',
              'class'                   => "video2_class",
              'icon'                    => plugin_dir_url(__file__) . "assets/img/peprodev.svg",
              'admin_enqueue_css'       => "{$this->assets_url}css/vc.init.css",
              'class'                   => 'PeproVideo',
              'show_settings_on_create' => true,
              'category'                => __("Pepro Elements", "pepro-video"),
              'params'                  => array(
                array(
                  'heading'             => __('Source URL (Mp4)', $this->td),
                  'type'                => 'textfield',
                  'param_name'          => 'src',
                  'edit_field_class'    => 'vc_filepicker vc_column vc_col-sm-6',
                ),
                array(
                  'heading'             => __('Source URL (Webm)', $this->td),
                  'type'                => 'textfield',
                  'param_name'          => 'webm',
                  'edit_field_class'    => 'vc_filepicker vc_column vc_col-sm-6'
                ),
                array(
                  'heading'             => __('Width', $this->td),
                  'type'                => 'textfield',
                  'param_name'          => 'width',
                  'value'               => '1080',
                  'edit_field_class'    => 'vc_column vc_col-sm-6',
                ),
                array(
                  'heading'             => __('Height', $this->td),
                  'type'                => 'textfield',
                  'param_name'          => 'height',
                  'value'               => '720',
                  'edit_field_class'    => 'vc_column vc_col-sm-6',
                ),
                array(
                  'heading'             => __('Poster', $this->td),
                  'type'                => 'attach_image',
                  'param_name'          => 'poster',
                  'edit_field_class' => 'vc_column vc_col-sm-6',
                ),
                array(
                  'heading'             => __('Play Icon', $this->td),
                  'type'                => 'attach_image',
                  'param_name'          => 'playicon',
                  'edit_field_class' => 'vc_column vc_col-sm-6',
                ),
                array(
                  'heading'             => __('Play Icon Width (e.g. 80px)', $this->td),
                  'type'                => 'textfield',
                  'param_name'          => 'play_w',
                  'value'               => '80px',
                  'edit_field_class'    => 'vc_column vc_col-sm-6',
                ),
                array(
                  'heading'             => __('Play Icon Height (e.g. 80px)', $this->td),
                  'type'                => 'textfield',
                  'param_name'          => 'play_h',
                  'value'               => '80px',
                  'edit_field_class'    => 'vc_column vc_col-sm-6',
                ),
                array(
                  'heading'             => __('Extra class', $this->td),
                  'type'                => 'textfield',
                  'param_name'          => 'class',
                  'edit_field_class'    => 'vc_column vc_col-sm-6',
                ),
                array(
                  'heading'             => __('Border radius (e.g. 10px)', $this->td),
                  'type'                => 'textfield',
                  'param_name'          => 'border',
                  'edit_field_class'    => 'vc_column vc_col-sm-6',
                ),
                array(
                  'heading'             => __('Loop', $this->td),
                  'type'                => 'checkbox',
                  'param_name'          => 'loop',
                  'edit_field_class'    => 'vc_column vc_col-sm-6',
                ),
                array(
                  'heading'             => __('Autoplay', $this->td),
                  'type'                => 'checkbox',
                  'param_name'          => 'autoplay',
                  'edit_field_class'    => 'vc_column vc_col-sm-6',
                ),
                array(
                  'type'                => 'pepro_mediapicker',
                  'param_name'          => 'tmp',
                ),
              ),
            )
          );
        }
        public function handle_video2_shortcode($atts)
        {
            // Attributes
            $atts = shortcode_atts(
              array(
             'width'       => '1080',
                'height'   => '720',
                'src'      => '',
                'webm'     => '',
                'poster'   => '',
                'loop'     => '',
                'autoplay' => '',
                'play_w'   => '80px',
                'play_h'   => '80px',
                'class'    => '',
                'border'   => '',
                'playicon' => '',
              ),
              $atts,
              'video2'
            );

            $uid               = uniqid("pepro-video-");
            $width             = "width='{$atts['width']}'";
            $height            = "height='{$atts['height']}'";

            $src               = is_numeric($atts['src']) ? wp_get_attachment_url((int) $atts['src']) : $atts['src'];
            $src               = (!empty($atts['src']) ? "src='$src'" : "");

            $webm              = is_numeric($atts['webm']) ? wp_get_attachment_url((int) $atts['webm']) : $atts['webm'];
            $webm              = (!empty($atts['webm']) ? "webm='$webm'" : "");

            $poster            = is_numeric($atts['poster']) ? wp_get_attachment_url((int) $atts['poster'], 'full') : $atts['poster'];
            $poster            = (!empty($atts['poster']) ? "poster='$poster'" : "");

            $playicon          = is_numeric($atts['playicon']) ? wp_get_attachment_url((int) $atts['playicon'], 'full') : $atts['playicon'];

            $loop              = ($atts['loop'] == "true") ? "loop='on'" : "loop='off'";
            $autoplay          = ($atts['autoplay'] == "true") ? "autoplay='on'" : "autoplay='off'";

            $class             = (!empty($atts['class']) ? $atts['class'] : "");

            $class             = "class='wp-video-shortcode pepro-video $uid $class'";
            $border            = (!empty($atts['border']) ? $atts['border'] : 0);
            $playicon          = (!empty($atts['playicon']) ? $playicon : '');
            $play_w            = (!empty($atts['play_w']) ? $atts['play_w'] : '80px');
            $play_h            = (!empty($atts['play_h']) ? $atts['play_h'] : '80px');

            wp_register_style($uid, false);
            wp_enqueue_style($uid);
            wp_add_inline_style($uid, ".$uid, .$uid .mejs-poster.mejs-layer, .$uid .mejs-overlay.mejs-layer.mejs-overlay-play{border-radius: $border !important;}
            .$uid .mejs-controls{ border-radius: 0 0 $border $border !important;}
            ");
            if (!empty($atts['playicon'])){
              wp_add_inline_style($uid, ".$uid .mejs-overlay-button {background: url($playicon) no-repeat center/contain;	height: $play_h;	width: $play_w;}
              .$uid .mejs-overlay:hover>.mejs-overlay-button{background-position: center;}");
            }

            if (!is_admin()) {
                return do_shortcode("[video $width $height $poster $loop $autoplay $class $src $webm]");
            }
            else {
                return "";
            }
        }
        public function admin_enqueue_scripts($hook)
        {
            $uid = uniqid("peprovcvideo-");
            wp_register_style($uid, false);
            wp_enqueue_style($uid);
            wp_add_inline_style($uid, ".wpb_video2.PeproVideo .wpb_vc_param_value {display: none;}");
        }
        public function plugins_row_links($links)
        {
            if (isset($links["deactivate"])) {
                $getManageLinks = array(__("Support", $this->td) => "mailto:support@pepro.dev?subject={$this->title}");
                foreach ($getManageLinks as $title => $href) {
                    array_unshift($links, "<a href='$href' target='_self'>$title</a>");
                }
            }
            return $links;
        }
    }
    /**

    */
    add_action(
        "plugins_loaded",
        function () {
            global $pepro_vc_video;
            $pepro_vc_video = new pepro_vc_video();
        }
    );
}
/*##################################################
Lead Developer: [amirhosseinhpv](https://hpv.im/)
##################################################*/
