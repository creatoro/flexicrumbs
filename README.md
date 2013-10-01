# Flexicrumbs - breadcrumbs module for Kohana
Breadcrumbs module for Kohana based on [Google breadcrumb specification](https://support.google.com/webmasters/answer/185417?hl=en).

The generated HTML code is suitable for displaying multiple breadcrumb trails on one page.

**The basic workings:**

1. Check current URI and get the URI segments (pages) by exploding it via `/` (forward slash) delimiter
2. Look for each segment (page) in the config file and create each breadcrumb using the settings found in the config
3. Render the HTML code

The module has a good amount of flexibility, it can work without any configuration, but it is also highly customizable.
Read on to find out the possibilities.

## Installation
Enable the module in your **bootstrap.php**:

	Kohana::modules(array(
		'flexicrumbs' => MODPATH.'flexicrumbs',
		// ...
	));

## Configuration
Copy the **breadcrumbs.php** file from **flexicrumbs/config** to your **config** folder and set the configuration options.

### Basic configuration
See the `configurations` array for basic configuration. Edit the `default` array or create a new array with a new name, for example `custom`.
I will go through each possible option.

#### auto
If `TRUE` breadcrumbs will be automatically generated from the current URI. However, it is still possible to add custom
breadcrumbs later using the `add()` method. Automatic breadcrumb generation will add the root page as well. More info in
the **Page configuration** part.

#### view
The name of the view to generate HTML code. Two templates are available in the breadcrumbs directory: **microdata** (default) and **rdfa**. Both of them are based on Google's HTML code and can be used multiple times on one page.

#### active_class
The class that will be added to the active page's link in the breadcrumbs.

#### query_keys
This option is only used if the `auto` option is set to `TRUE`.

The default setting is `FALSE` which means query parameters are ignored when building breadcrumbs automatically. However, you can specify if there are certain query parameters that should be preserved for breadcrumbs.
This way you can have separate breadcrumbs for a page without query parameters and a page with query parameters. As an example let's supply the following array for this option:

    'query_keys' => array('search', 'type')

This means when the URI is parsed and a **search** or **type** query parameter is found it will be added to the last page of the URI.
An URI like **welcome/index?search=keyword&limit=100** would result in a breadcrumb with these two pages:

- welcome
- index?search=keyword

If `query_keys` was to `FALSE` the above link would result in a breadcrumb trail consisting of these two pages:

- welcome
- index

It is also possible to generate a breadcrumb trail with this setting that would contain these three pages:

- welcome
- index
- index?search=keyword

To achieve this read the **Page configuration** part.

### Page configuration

The `pages` array contains the configuration for the different pages. If the breadcrumbs are generated automatically the URI is exploded to individual segments with the use of `/` (forward slash) delimiter.
For example an URI like **welcome/index** means two pages:

- welcome
- index

The module looks for these two pages in the `pages` configuration (searching for the keys as the name of the pages), for example you can set the configuration for these like this:

    'pages' => array(
        'welcome' => array(
            'exclude' => TRUE,
        ),
        'index' => array(
            'title' => 'Index page',
            'url'   => Route::url('default', array('controller' => 'welcome', 'action' => 'index')),
        ),
    ),

The above configuration would mean that the **welcome** page would never be part of the breadcrumbs, while the **index** page would have a link titled **Index page**, using the specified URL.

#### Root page

The root page is included by default in the `pages` array. If breadcrumbs are generated automatically the module will
always look for an array with a `/` (forward slash) key to configure the root page. The following default
config can be customized to your liking:

    'pages'             => array(
        '/' => array(
            'title' => 'root',
            'url'   => URL::base('http'),
        ),
    ),

#### Page configuration with query parameters
If you set up to include query parameters in the URI, then you can have configuration for those pages like this:

    'pages' => array(
        'welcome' => array(
            'exclude' => TRUE,
        ),
        'index' => array(
            'title' => 'Index page',
            'url'   => Route::url('default', array('controller' => 'welcome', 'action' => 'index')),
        ),
        'index?search=keyword' => array(
            'title' => 'Search',
            'url'   => Route::url('default', array('controller' => 'welcome', 'action' => 'index')).'?search=keyword',
        ),
    ),

This way the URI with the **search=keyword** query string can have a different breadcrumb trail. If you want to include **index** and **index?search=keyword** as well in the breadcrumbs you can set a `parent` option in the array as follows:

    'pages' => array(
        'welcome' => array(
            'exclude' => TRUE,
        ),
        'index' => array(
            'title' => 'Index page',
            'url'   => Route::url('default', array('controller' => 'welcome', 'action' => 'index')),
        ),
        'index?search=keyword' => array(
            'title'  => 'Search',
            'url'    => Route::url('default', array('controller' => 'welcome', 'action' => 'index')).'?search=keyword',
            'parent' => 'index',
        ),
    ),

Now the breadcrumb trail would include these pages:

- index
- index?search=keyword

## Usage
All you have to do is call the `Breadcrumbs::factory();` method, where you can supply the name of the configuration to use or some custom options in an array:

    Breadcrumbs::factory('custom');
OR

    Breadcrumbs::factory(array('auto' => FALSE));

### Adding breadcrumbs manually
To add breadcrumbs manually first you should create an array in the configuration for the breadcrumb, for example:

    'pages' => array(
        'custom_page_name' => array(
            'title' => 'This was added manually',
            'url'   => URL::site('contact'),
        ),
    ),

Now you can add this breadcrumb like this:

    // Breadcrumbs
    $breadcrumbs = Breadcrumbs::factory();

    // Add breadcrumb manually
    $breadcrumbs->add('custom_page_name');

You can supply two more parameters when adding a breadcrumb:

- is it an active breadcrumb / page (boolean)
- the position of the breadcrumb (set it to **0** for the first position)

For example:

    // Add a currently active breadcrumb to the 4th position manually
    $breadcrumbs->add('custom_page_name', TRUE, 3);

### Rendering the HTML code
To render the HTML code call the `render()` method. For example:

    // Breadcrumbs
    $breadcrumbs = Breadcrumbs::factory();

    // Add breadcrumb manually
    $breadcrumbs->add('custom_page_name');

    // Render HTML for breadcrumbs
    echo $breadcrumbs->render();