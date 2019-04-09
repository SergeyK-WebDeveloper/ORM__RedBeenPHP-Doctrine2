<?php

//require_once __DIR__ . '/rb.php';
//R::setup();

require_once __DIR__ . '/vendor/autoload.php';

function debug($data){
    echo '<pre>' . print_r($data, 1) . '</pre>';
}

//\RedbeanPHP\R::setup();
//\RedBeanPHP\R::setup();
//use \RedBeanPHP\R as R;
class_alias('\RedBeanPHP\R', '\R');

//R::setup( 'mysql:host=localhost;dbname=test', 'root', '', true );
R::setup( 'mysql:host=localhost;dbname=test', 'root', '' );

//R::freeze(true);

if( !R::testConnection() ){
    die('No DB Connection');
}

// CRUD: Create
$book = R::dispense('book');
//$book->title = 'Три мушкетера';
//$book->price = 29.99;

//$book->title = 'Пикник на обочине';
//$book['price'] = 25;
//$book->author = 'Братья Стругацкие';

R::ext('xdispense', function( $type ){
    return R::getRedBean()->dispense( $type );
});

$test = R::xdispense('test_table');
$test->category_id = 5;
$test->title = 'Test Title 4';
$test->tT2 = 'Test Title 4';
R::store($test);

//R::store($book);
//debug($book);