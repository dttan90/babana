<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/views', 'Home::views');
$routes->get('/load', 'Home::load');
$routes->get('/detailLoad', 'Home::detailLoad');
$routes->get('/getOptionsOfOrderGrid', 'Home::getOptionsOfOrderGrid');
$routes->get('/testData', 'Home::testData');
$routes->add('/getDataToAddForm', 'Home::getDataToAddForm');

$routes->add('/imports', 'Home::imports');
$routes->add('/printer', 'Home::printer');
$routes->add('/getDetailDataToAdd', 'Home::getDetailDataToAdd');
$routes->add('/saveOrder', 'Home::saveOrder');
$routes->add('/getAreaId', 'Home::getAreaId');
$routes->add('/getFoodPrice', 'Home::getFoodPrice');










/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

// $routes->get('/', 'Home::index');
// $routes->resource('products');