<?php

use Framework\Router;

Router::register('GET', 'users', 'UsersController@index');
Router::register('POST', 'users', 'UsersController@create');
Router::register('PUT', 'users/:email', 'UsersController@update');
Router::register('DELETE', 'users/:email', 'UsersController@delete');