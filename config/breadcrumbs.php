<?php defined('SYSPATH') OR die('No direct script access.');

return array(
    /**
     * Add configurations for different breadcrumb trails
     */
    'configurations'    => array(
        // Default configuration
        'default' => array(
            // If TRUE breadcrumbs will be automatically generated from the current URI
            'auto'         => TRUE,
            // The view to generate HTML code
            'view'         => 'breadcrumbs/microdata',
            // The class to use for active page
            'active_class' => 'active',
            // Supply an array of query keys that should be added to the page URI
            'query_keys'   => FALSE,
        ),
    ),
    /**
     * List and setup pages here
     *
     * Possible options (set only those that are needed):
     *
     *  title:   the title of the page (anchor text of link), default is the page URI
     *  url:     the URL of the page, default is the page URI
     *  exclude: is set to TRUE page is excluded , default is FALSE
     *  parent:  forced parent, default is unset
     */
    'pages'             => array(
        '/' => array(
            'title' => 'root',
            'url'   => URL::base('http'),
        ),
    ),
);