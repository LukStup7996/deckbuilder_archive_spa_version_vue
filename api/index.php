<?php
use deckbuilder_archive_spa_version_vue\api\controller\NavigationController;

error_reporting(E_ERROR);
ini_set("display_errors", 1);

include 'src/config/config.php';
require 'vendor/autoload.php';

$controller = new NavigationController();
$controller->route();