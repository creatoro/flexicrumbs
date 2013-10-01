<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Breadcrumbs module for Kohana.
 *
 * @package Flexicrumbs
 * @category Base
 * @author creatoro
 * @copyright (c) 2013 creatoro
 * @license MIT
 */
abstract class Breadcrumbs_Core {

    /**
     * Factory method
     *
     * @param   NULL $options
     * @return  Breadcrumbs
     */
    public static function factory($options = NULL)
    {
        return new Breadcrumbs($options);
    }

    /**
     * @var  array  configuration
     */
    protected $config = array();

    /**
     * @var  array  pages
     */
    protected $pages = array();

    /**
     * Loads configuration and pages, adds pages from request if needed
     *
     * $options can be an array that will be merged to the default configuration
     * or a string that is the name of a custom configuration.
     *
     * @param  NULL  $options
     * @uses   Kohana::$config
     * @uses   Arr::get
     * @uses   Arr::merge
     * @uses   Breadcrumbs::add_request
     */
    public function __construct($options = NULL)
    {
        // Load config
        $config = Kohana::$config->load('breadcrumbs');

        // Set pages
        $this->pages = Arr::get($config, 'pages', array());

        // Configurations
        $configurations = Arr::get($config, 'configurations', array());

        if (is_string($options))
        {
            if ( ! isset($configurations[$options]))
            {
                // Config not found
                throw new Kohana_Exception('No such breadcrumb configuration [ :config ] can be found', array(
                    ':config' => $options,
                ));
            }

            // Use set config
            $config = $configurations[$options];
        }
        else
        {
            // Use default config
            $config = $configurations['default'];
        }

        if (is_array($options))
        {
            // Merge given options to config
            $config = Arr::merge($config, $options);
        }

        // Set config
        $this->config = $config;

        if (Arr::get($this->config, 'auto', TRUE))
        {
            // Add root page
            $this->add('/');

            // Add pages from request
            $this->add_request();
        }
    }

    /**
     * Builds breadcrumbs from current URI
     *
     * @return  $this
     * @uses    Request::current
     * @uses    Arr::get
     * @uses    Breadcrumbs::add
     */
    public function add_request()
    {
        // Use current request
        $request = Request::current();

        // Pages
        $pages = explode('/', $request->uri());

        // Check if query string should be used
        if ($query_keys = Arr::get($this->config, 'query_keys', FALSE) AND $query = $request->query())
        {
            // Query string
            $query_string = array();

            foreach ($query as $key => $value)
            {
                if (in_array($key, $query_keys))
                {
                    // Add to query string
                    $query_string[] = $key.'='.$value;
                }
            }

            // Implode query string
            $query_string = '?'.implode('&', $query_string);

            // Set pointer to last page
            end($pages);

            // Get last page
            $last_page = key($pages);

            // Add query string to last page
            $pages[$last_page] .= $query_string;
        }

        foreach ($pages as $page)
        {
            if (empty($page))
            {
                // No empty pages are needed
                continue;
            }

            // Page is not the active one by default
            $active = FALSE;

            if ($page === end($pages))
            {
                // The last page is active
                $active = TRUE;
            }

            // Add page
            $this->add($page, $active);
        }

        return $this;
    }

    /**
     * @var  array  breadcrumbs
     */
    public $breadcrumbs = array();

    /**
     * Adds breadcrumb
     *
     * @param   string  $page      the page from URI
     * @param   bool    $active    is this page active
     * @param   NULL    $position  the position of the breadcrumb
     * @return          $this
     * @uses    Arr::get
     * @uses    URL::site
     */
    public function add($page, $active = FALSE, $position = NULL)
    {
        // Set up breadcrumb
        $breadcrumb = Arr::get($this->pages, $page, array());

        // Add title and URL if missing
        $breadcrumb['title'] = Arr::get($breadcrumb, 'title', $page);
        $breadcrumb['url']   = Arr::get($breadcrumb, 'url', URL::site($page, 'http'));

        // Add active setting
        $breadcrumb['active'] = $active;

        if (Arr::get($breadcrumb, 'exclude') === TRUE)
        {
            // Page is excluded from breadcrumbs
            return $this;
        }

        if ($parent = Arr::get($breadcrumb, 'parent'))
        {
            // Add forced parent
            $this->add($parent);
        }

        if ($position === NULL OR ! isset($this->breadcrumbs[$position]))
        {
            // Default to last position
            $position = count($this->breadcrumbs);
        }
        else
        {
            // Add to specified position
            array_splice($this->breadcrumbs, $position, 0, array($breadcrumb));
        }

        // Add to breadcrumbs
        $this->breadcrumbs[$position] = $breadcrumb;

        return $this;
    }

    /**
     * Renders HTML for breadcrumbs
     *
     * @return  bool|View
     * @uses    View::factory
     * @uses    Breadcrumbs::breadcrumb_tree
     * @uses    Arr::get
     */
    public function render()
    {
        // Number of breadcrumbs
        $number_of_breadcrumbs = count($this->breadcrumbs);

        if ($number_of_breadcrumbs < 1)
        {
            // No breadcrumbs are available
            return FALSE;
        }

        return View::factory(Arr::get($this->config, 'view', 'breadcrumbs/microdata'), array(
            'breadcrumbs'  => $this->breadcrumbs,
            'children'     => ($number_of_breadcrumbs - 1),
            'active_class' => Arr::get($this->config, 'active_class', 'active'),
        ));
    }

}