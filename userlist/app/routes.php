<?php

use Framework\Router;

// Registra as rotas existentes na aplicação
Router::register('GET', 'users', 'UsersController', 'index');
Router::register('POST', 'users', 'UsersController', 'create');
Router::register('PUT', 'users/(.*)', 'UsersController', 'update');
Router::register('DELETE', 'users/(.*)', 'UsersController', 'delete');
// Por conveniência, um request em branco é redirecionado para o index
Router::register('GET', '/', 'UsersController', 'index');