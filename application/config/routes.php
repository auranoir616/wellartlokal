<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['login']                         = 'authentication/login';
$route['signup']                        = 'authentication/register';
$route['logout']                        = 'authentication/logout';
$route['signup/(:any)/(:any)']          = 'authentication/register/$1/$2';

$route['dashboard']                     = 'dashboard/view_page/dashboard';
$route['level/(:any)']                  = 'dashboard/view_gen/$1';
$route['admin/(:any)']                  = 'administrator/index/$1';
$route['(:any)']                        = 'dashboard/view_page/$1';

$route['default_controller']            = 'frontpage/index';
$route['404_override']                  = '';
$route['translate_uri_dashes']          = FALSE;
