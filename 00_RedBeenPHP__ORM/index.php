<?php

//require_once __DIR__ . '/rb.php';
//R::setup();

require_once __DIR__ . '/vendor/autoload.php';
//\RedbeanPHP\R::setup();
//\RedBeanPHP\R::setup();
//use \RedBeanPHP\R as R;
class_alias('\RedBeanPHP\R', '\R');
R::setup();