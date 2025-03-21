<?php
require "app.php";
require "controller.php";
require "config.php";
require "database.php";
require 'model.php';
require 'helper.php';

// Load models
spl_autoload_register(function ($class_name) {
    require ("../private/models/" . ucfirst($class_name) . ".php");
});