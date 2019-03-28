<?php

//require_once __DIR__ . '/rb.php';
//R::setup();

require_once __DIR__ . '/vendor/autoload.php';
//\RedbeanPHP\R::setup();
//\RedBeanPHP\R::setup();
//use \RedBeanPHP\R as R;
class_alias('\RedBeanPHP\R', '\R');

R::setup( 'mysql:host=localhost;dbname=test', 'root', '' );
//R::setup( 'sqlite:dbfile.db' );

if( !R::testConnection() ){
    die('No DB Connection');
}

echo 'OK';