<?php

require_once __DIR__ . '/vendor/autoload.php';

function debug($data){
    echo '<pre>' . print_r($data, 1) . '</pre>';
}

class_alias('\RedBeanPHP\R', '\R');

R::setup( 'mysql:host=localhost;dbname=test', 'root', '', false );

if( !R::testConnection() ){
    die('No DB Connection');
}

R::debug(1, 3);


/*$category1 = R::dispense('category');
$category1->title = 'Футболки мужские';

$category2 = R::dispense('category');
$category2->title = 'Футболки женские';*/

/*$category1 = R::load('category', 1);
//$category2 = R::load('category', 2);

$product = R::dispense('product');
$product->title = 'Футболка мужская';

$category1->sharedProductList[] = $product;
//$category2->sharedProductList[] = $product;

R::storeAll([$category1]);*/

/*$category2 = R::load('category', 2);
$category2->sharedProductList;
debug($category2);*/

/*$product = R::load('product', 3);
R::trash($product);*/

$category2 = R::load('category', 2);
R::trash($category2);

$logs = R::getDatabaseAdapter()
    ->getDatabase()
    ->getLogger();

debug( $logs->grep('INSERT') );
debug( $logs->grep('SELECT') );
debug( $logs->grep('UPDATE') );
debug( $logs->grep('DELETE') );


