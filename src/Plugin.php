<?php

namespace InfiniteEye\Modal;

use InfiniteEye\Modal\Modal\Modal;

class Plugin
{
    protected $_version = '0.0.1';
    /**
     * @var Plugin
     */
    protected static $_instance = null;

    /**
     * @var Modal[]
     */
    protected $_modals = [];

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_footer', [$this, 'render_modals']);
    }

    /**
     * @param string $id 
     * @return Modal 
     */
    public function create($id)
    {
        $this->_modals[$id] = new Modal($id);
        return $this->_modals[$id];
    }

    public function enqueue_scripts()
    {
        $modals = $this->get_active_modals();
        if (empty($modals)) {
            return;
        }

        $asset_url = $this->get_asset_url();

        // vendor
        wp_enqueue_style('jquery-modal', $asset_url . '/js/jquery.modal.min.css', $this->_version);
        wp_enqueue_script('jquery-modal', $asset_url . '/js/jquery.modal.min.js', ['jquery'], $this->_version);
        wp_enqueue_script('js-cookie', $asset_url . '/js/js-cookie.js', [], $this->_version);

        // plugin
        wp_enqueue_style('infinite-eye-modal', $asset_url . '/js/modal.css', $this->_version);
        wp_enqueue_script('infinite-eye-modal', $asset_url . '/js/modal.js', ['jquery-modal', 'js-cookie'], $this->_version);

        $args = [];
        foreach ($modals as $modal) {

            if (!$modal->is_visible()) {
                continue;
            }

            $args[] = [
                'id' => $modal->get_id(),
                'cookie' => $modal->get_cookie(),
                'cookie_value' => $modal->get_cookie_value(),
                'schedule_from' => $modal->get_schedule_from(),
                'schedule_to' => $modal->get_schedule_to(),
                'mode' => $modal->get_mode(),
                'args' => (object)$modal->get_plugin_args()
            ];
        }
        wp_localize_script('infinite-eye-modal', 'modal_config', $args);
    }

    public function render_modals()
    {
        $modals = $this->get_active_modals();
        if (empty($modals)) {
            return;
        }

        foreach ($modals as $modal) {

            if (!$modal->is_visible()) {
                continue;
            }

            echo $this->get_template_part('modal-open', ['id' => $modal->get_id()]);
            $modal->the_content();
            echo $this->get_template_part('modal-close');
        }
    }

    public function get_active_modals()
    {
        return $this->_modals;
    }

    public function get_template_part($template, $args = [])
    {
        $found = locate_template('template-parts/modal/' . $template . '.php', true, false, $args);
        if (!$found) {
            load_template(dirname($this->get_asset_path()) . '/templates/' . $template . '.php', true, $args);
        }
    }

    public function get_asset_path()
    {
        return dirname(__DIR__) . '/assets/';
    }

    public function get_asset_url()
    {
        $asset_path = $this->get_asset_path();
        return $this->abs_path_to_url($asset_path);
    }

    function abs_path_to_url($path = '')
    {
        $url = str_replace(
            wp_normalize_path(untrailingslashit(ABSPATH)),
            site_url(),
            wp_normalize_path($path)
        );
        return esc_url_raw($url);
    }
}
