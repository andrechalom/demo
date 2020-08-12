<?php
# Registra as classes pelo autoloader
require_once __DIR__.'/../vendor/autoload.php';

# Registra as rotas do app
require_once __DIR__.'/../app/routes.php';

return App\App::run($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"], $_REQUEST);