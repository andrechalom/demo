<?php

# Registra as classes pelo autoloader
require_once __DIR__.'/../vendor/autoload.php';

# Registra as rotas do app
require_once __DIR__.'/../app/routes.php';

$request = new Framework\Request($_REQUEST);
var_dump($_SERVER);
echo "OI";