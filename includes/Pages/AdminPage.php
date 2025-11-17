<?php


namespace Emassa\Pages;


if (!class_exists("Emassa\Pages\AdminPage")) {

    /**
     * Class BackendPage
     */
    abstract class AdminPage
    {
        /**
         * @var string
         */
        protected $name = 'admin_page';

        /**
         * @var string
         */
        protected $title = '';

        /**
         * @var string
         */
        protected $capability = 'manage_options';


        /**
         * @var string
         */
        protected $parent_slug = false;

        /**
         * @var string
         */
        protected $menu_slug = '';

        /**
         * @var string
         */
        protected $icon_path = '';

        /**
         * @var string
         */
        protected $menu_icon = 'dashicons-screenoptions';

        /**
         * @var int
         */
        protected $position = 6;

        /**
         * @var string
         */
        protected $template_name = 'default_backend_page';

        /**
         * BackendPage constructor.
         */
        public function __construct()
        {
            add_action('admin_menu', [$this, 'setup']);
        }

        public function setup()
        {
            if (!empty($this->parent_slug)) {
                return $this->setup_submenu_page();
            }
            return $this->setup_menu_page();
        }

        /**
         *
         */
        private function setup_menu_page()
        {
            \add_menu_page(
                $this->get_title(),
                ucwords($this->get_title()),
                $this->capability,
                $this->get_menu_slug(),
                [$this, 'get_content'],
                $this->get_menu_icon(),
                $this->position
            );
        }

        /**
         *
         */
        private function setup_submenu_page()
        {
            if (is_string($this->parent_slug))
                \add_submenu_page(
                    $this->parent_slug,
                    $this->get_title(),
                    ucwords($this->get_title()),
                    $this->capability,
                    $this->get_menu_slug(),
                    [$this, 'get_content'],
                    $this->position);
        }

        abstract public function get_content();

        /**
         * @return string
         */
        public function get_name(): string
        {
            return $this->name;
        }

        /**
         * @return string
         */
        public function get_title(): string
        {
            if (empty($this->title)) {
                return preg_replace('#[-_]#', ' ', $this->name);
            }
            return $this->title;
        }

        /**
         * @return string
         */
        public function get_menu_slug(): string
        {
            if (empty($this->menu_slug)) {
                return "{$this->name}/{$this->name}.php";
            }
            return $this->menu_slug;
        }

        /**
         * @return Template|string
         */
        protected function get_template()
        {
            $template = new \Emassa\Src\Template($this->template_name);
            return $template->is_valid() ? $template : '';
        }

        /**
         * @return string
         */
        protected function get_menu_icon()
        {
            return !empty($this->icon_path)
                ? EMASSA_URI . '/' . $this->icon_path
                : $this->menu_icon;
        }

    }
}