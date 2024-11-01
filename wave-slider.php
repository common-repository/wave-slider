<?php
/*
  Plugin Name: Wave Slider
  Plugin URI: http://ipsrsolutions.com/
  Description: Simple Creative responsive Slider, Get in motion !!!!
  Version: 1.1
  Author: IPSR Team
  Author URI:  http://www.ipsr.org/branches.asp
  License: GPLv2
 */

class wave_slider {

    public function __construct() {

        // Config
        $this->config['version'] = '0.1';
        $this->config['title'] = 'Wave Slider';
        $this->config['name'] = 'wave-slider';
        $this->config['shortcode'] = 'wave-slider';
        $this->config['current_file'] = __FILE__;
        $this->config['current_folder'] = dirname($_o_config['current_file']);
        $this->config['plugins_url'] = plugins_url() . '/wave-slider/';
 
        // Register
        add_action('init', array($this, 'register'));
        add_action('add_meta_boxes', array($this, 'image_meta_box'));
        add_action('add_meta_boxes', array($this, 'upload_meta_box'));
        add_action('admin_enqueue_scripts', array($this, 'assets'), 2);
        add_action('save_post', array($this, 'save'));
        add_shortcode($this->config['shortcode'], array($this, 'output_view'));
        add_action('init', array($this, 'output_style'));
        add_action('post_submitbox_misc_actions', array($this, 'short_code'));
    }

    public function register() {

        $slider_labels = array(
            'name' => _x($this->config['title'], 'post type general name'),
            'singular_name' => _x($this->config['title'], 'post type singular name'),
            'add_new' => _x('Add New', $this->config['name']),
            'add_new_item' => __('New Slider'),
            'edit_item' => __('Edit Slider'),
            'new_item' => __('New Slider'),
            'view_item' => __('View Slides'),
            'search_items' => __('Search Slider'),
            'not_found' => __('No Slider Found'),
            'not_found_in_trash' => __('No Slider Found in Trash'),
            'parent_item_colon' => ''
        );

        $slider_args = array(
            'labels' => $slider_labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'query_var' => false,
            'rewrite' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'capability_type' => 'post',
            'supports' => array('title'),
        );

        register_post_type($this->config['name'], $slider_args);
    }

    function assets() {
        wp_enqueue_media();

        wp_register_style($this->config['name'] . '_admin_style', $this->config['plugins_url'] . 'assets/backend/css/style.css');
        wp_enqueue_style($this->config['name'] . '_admin_style');

        wp_register_script($this->config['name'] . '_admin_script', $this->config['plugins_url'] . 'assets/backend/js/script.js', array('jquery'));
        wp_enqueue_script($this->config['name'] . '_admin_script');
    }

    function image_meta_box($post) {

        add_meta_box(
                'image_meta_box', 'Slides List', array($this, 'image_meta_box_content'), $this->config['name'], 'normal', 'high'
        );
    }

    function image_meta_box_content($post) {
        ?>
        <div id="slide-contanier">
            <table id="slides">
                <?php
                $count = 0;
                $data = json_decode(get_post_meta($post->ID, '_' . $this->config['name'] . '_data', true), true);
                if (is_array($data)) {
                    foreach ($data as $row) {
                        $count++;
                        ?>    
                        <tr>
                            <td> 
                                <?php echo $count; ?> 
                            </td>
                            <td><img src="<?php echo wp_get_attachment_url($row) ?>"/></td>
                            <td>
                                <input type="button"  value="Delete"  class="del-button button button-large">
                                <input type="hidden" value="<?php echo $row; ?>" name="data[]">
                            </td>
                        </tr>     
                        <?php
                    }
                }
                ?>

            </table>
        </div>
        <?php
    }

    function upload_meta_box($post) {
        add_meta_box(
           'upload_meta_box', 'Add Slides', array($this, 'upload_meta_box_content'), $this->config['name'], 'side', 'high'
        );
    }

    function upload_meta_box_content($post) {
        ?>
        <input id="add_slides" class="button" type="button" value="Upload Image" />
        <?php
    }

    function save() {
        global $post;
        if (get_post_type($post) === $this->config['name']) {
            if (!empty($_POST['data'])) :
                update_post_meta($post->ID, '_' . $this->config['name'] . '_data', json_encode(array_map('mysql_real_escape_string', $_POST['data'])));
            endif;           
        }
    }

    function output_view($atts) {
        add_action('wp_footer', array($this, 'output_script'));
        $data = shortcode_atts(array('id' => '0'), $atts);
        ?>
        <div id="slide-row">
            <?php
            $data = json_decode(get_post_meta($data['id'], '_' . $this->config['name'] . '_data', true), true);
            if (is_array($data)) {
                foreach ($data as $row) {
                    ?>   
                    <div class="slide-cell">
                        <img src="<?php echo wp_get_attachment_url($row) ?>" width="400" alt="" />
                    </div>
                    <?php
                }
            }
            ?>   
        </div>

        <?php
    }

    function output_style() {
        wp_register_style($this->config['name'] . '_style', $this->config['plugins_url'] . 'assets/frontend/css/style.css');
        wp_enqueue_style($this->config['name'] . '_style');
    }

    function output_script() {
        wp_register_script($this->config['name'] . '_script', $this->config['plugins_url'] . 'assets/frontend/js/script.js', array('jquery'));
        wp_enqueue_script($this->config['name'] . '_script');
    }

    function short_code() {
        global $post;
        $html = '<div id="major-publishing-actions" style="overflow:hidden">';
        $html .= 'Short Code:   [wave-slider  id="' . $post->ID . '"]';
        $html .= '</div>';
        if ($this->config['name'] == get_post_type())
            echo $html;
    }
}

new wave_slider();