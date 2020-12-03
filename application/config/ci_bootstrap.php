<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
/*
  | -------------------------------------------------------------------------
  | CI Bootstrap 3 Configuration
  | -------------------------------------------------------------------------
  | This file lets you define default values to be passed into views when calling
  | MY_Controller's render() function.
  |
  | Most of them can be overrided from child controllers, includes:
  | 	- $this->mSiteName
  | 	- $this->mPageTitlePrefix
  | 	- $this->mPageTitle
  | 	- $this->mBodyClass
  | 	- $this->mMetaData
  | 	- $this->mScripts
  | 	- $this->mStylesheets
  |	- $this->mMenu
  | 	- $this->mPageAuth
 */

$config['ci_bootstrap'] = array(

	/*
	| -------------------------------------------------------------------------
	| Common configuration
	| -------------------------------------------------------------------------
	| For both Frontend Website, Admin Panel and API Site
	*/

    // Site name
    //'site_name' => 'MyTechBus',
    // Default page title prefix
    'page_title_prefix' => 'MyTechBus - ',
    // Default page title
    // (set empty then MY_Controller will automatically generate one based on controller / action)
    'page_title' => '',
    // Default meta data
    // (name => content)
    'meta_data' => array(
        'author' => 'HPIE',
        'description' => 'MyTechBus',
        'keywords' => 'PHP,CodeIgniter,CRUD'
    ),

    // Default scripts to embed at page head or end
    // (position => script array)
    'scripts' => array(
        'head' => array(
            //'assets/dist/frontend/jquery-2.2.4.min.js',
            //'assets/dist/frontend/bootstrap.min.js',
			'assets/js/mytechbus.jquery-3.2.1.slim.min.js',
			'assets/js/mytechbus.bootstrap.min.js'
        ),
        'foot' => array(
            //'assets/dist/frontend/lib.min.js',
            //'assets/dist/frontend/app.min.js'
            //'assets/dist/frontend/form-scripts.js'
        ),
    ),

    // Default stylesheets to embed at page head
    // (media => stylesheet array)
    'stylesheets' => array(
        'screen' => array(
            // for screen display
            'assets/css/font-awesome.min.css',
            'assets/css/mytechbus.bootstrap.min.css',
			'https://fonts.googleapis.com/css?family=Lato:400,700,900',
			'assets/css/mytechbus.style.css',
			// 'assets/css/mytechbus.all.css'
        ),
        'print' => array(
        // for print media
        )
    ),

    // Default CSS class for <body> tag
    'body_class' => '',

    // Multilingual settings (set empty array to disable this)
    'languages' => '',
    /* 'languages' => array(
      'default'		=> 'en',				// to decide which of the "available" languages should be used
      'autoload'		=> array('general'),	// language files to autoload
      'available'		=> array(				// availabe languages with names to display on site (e.g. on menu)
      'en' => array(						// abbr. value to be used on URL, or linked with database fields
      'label'	=> 'English',			// label to be displayed on language switcher
      'value'	=> 'english'			// to match with CodeIgniter folders inside application/language/
      ),
      'zh' => array(
      'label'	=> '????',
      'value'	=> 'traditional-chinese'
      ),
      'cn' => array(
      'label'	=> '????',
      'value'	=> 'simplified-chinese'
      ),
      'es' => array(
      'label'	=> 'Español',
      'value' => 'spanish'
      )
      )
      ), */


    // Google Analytics User ID
    'ga_id' => 'UA-XXXXXXXX-X',

    // Menu items
    // (or directly update view file: /application/modules/admin/views/_partials/sidemenu.php)
    'menu'=> '',
	/*
	'menu' => array(
        //	'home' => array(
        //		'name'		=> 'Home',
        //		'url'		=> '',
        //		'icon'		=> 'fa fa-home',
        //	),
        'account' => array(
            'name' => 'My Account',
            'url' => 'account/',
            'icon' => 'fa fa-home',
        ),
        
        'auth' => array(
            'name' => 'Login / Logout',
            'url' => 'auth',
            'icon' => 'fa fa-users',
            'children' => array(
                'Login' => 'auth/login',
                'Sign Up' => 'auth/sign_up',
            )
        ),
        'operator' => array(
            'name' => 'Operator',
            'url' => 'operator/',
            'icon' => 'fa fa-home',
        ),
    ), */
	

    // Login page (to redirect non-logged-in users)
    'login_url' => 'auth/login',
    // Restricted pages to specific groups of users, which will affect sidemenu item as well
    // pages out of this array will have no restriction (except required admin user login)
    'page_auth' => array(
        // Example: Frontend Website pages for registered users
    ),
    // Email config (to be used in MY_Email library)
    'email' => array(
        'from_email' => 'noreply@email.com',
        'from_name' => 'MyTechBus',
        'subject_prefix' => '[MyTechBus Support] ',
        // Mailgun HTTP API
        'mailgun_api' => array(
            'domain' => '',
            'private_api_key' => ''
        ),
    ),
    // Debug tools (available only when ENVIRONMENT = 'development')
    'debug' => array(
        'view_data' => FALSE, // whether to display MY_Controller's mViewData at page end
        'profiler' => FALSE // whether to display CodeIgniter's profiler at page end
    ),
    /*
      | -------------------------------------------------------------------------
      | Configuration for Admin Panel only
      | -------------------------------------------------------------------------
     */

    // AdminLTE settings
    // (admin user group => configuration, e.g. CSS class for skin)
    'adminlte' => array(
        'body_class' => array(
            'webmaster' => 'skin-red',
            'admin' => 'skin-purple',
            'manager' => 'skin-black',
            'staff' => 'skin-blue',
        )
    ),
    // Useful links to display at bottom of sidemenu (e.g. to pages outside Admin Panel)
    'useful_links' => array(
        array(
            'auth' => array('webmaster', 'admin', 'manager', 'staff'),
            'name' => 'Frontend Website',
            'url' => '',
            'target' => '_blank',
            'color' => 'text-aqua'
        ),
        array(
            'auth' => array('webmaster', 'admin'),
            'name' => 'API Site',
            'url' => 'api',
            'target' => '_blank',
            'color' => 'text-orange'
        ),
        array(
            'auth' => array('webmaster', 'admin', 'manager', 'staff'),
            'name' => 'Github Repo',
            'url' => CI_BOOTSTRAP_REPO,
            'target' => '_blank',
            'color' => 'text-green'
        ),
    ),
    /*
      | -------------------------------------------------------------------------
      | Configuration for API Site only
      | -------------------------------------------------------------------------
     */

    // Raw PHP Headers (e.g. enable CORS or not) to send at page start
    'headers' => array(
        'Access-Control-Allow-Origin: *',
        'Access-Control-Request-Method: GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization',
    ),
);

/*
  | -------------------------------------------------------------------------
  | Override values from /application/config/config.php
  | -------------------------------------------------------------------------
 */

// Allow different modules to use different login sessions
$config['sess_cookie_name'] = 'ci_session_frontend';
