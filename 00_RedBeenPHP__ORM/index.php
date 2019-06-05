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

// https://habr.com/post/193380/
// http://jtest.ru/bazyi-dannyix/sql-dlya-nachinayushhix-chast-3.html

// вариант без отношений RedBeanPHP
/*$category = R::dispense('category');
$category->title = 'Samsung';
$id = R::store($category);

$product = R::dispense('product');
$product->title = 'S8';
$product->category_id = $id;
R::store($product);*/

// реализация связи
/*$category = R::dispense('category');
$category->title = 'Apple';

$product1 = R::dispense('product');
$product1->title = 'iPhone 7';

$product2 = R::dispense('product');
$product2->title = 'iPhone 8';

$category->ownProductList = [$product1, $product2];

R::store($category);*/

// получение собственного списка свойств
/*$category = R::load('category', 1);
echo "<h3>{$category->title}</h3>";
foreach($category->ownProductList as $product){
    echo $product->title . '<br>';
}*/

// замена свойств
/*$category = R::load('category', 1);

$product1 = R::dispense('product');
$product1->title = 'S9';

$product2 = R::dispense('product');
$product2->title = 'S10';

$category->ownProductList = [$product1, $product2];
R::store($category);*/

// добавление свойств
/*$category = R::load('category', 1);

$product1 = R::dispense('product');
$product1->title = 'S9';

$product2 = R::dispense('product');
$product2->title = 'S10';

$category->ownProductList[] = $product1;
$category->ownProductList[] = $product2;
R::store($category);*/

// добавление свойств без загрузки имеющихся
$category = R::load('category', 2);

$product1 = R::dispense('product');
$product1->title = 'iPhone 6';

$product2 = R::dispense('product');
$product2->title = 'iPhone 10';

$category->noLoad()->ownProductList[] = $product1;
$category->noLoad()->ownProductList[] = $product2;
R::store($category);






$logs = R::getDatabaseAdapter()
    ->getDatabase()
    ->getLogger();

debug( $logs->grep('INSERT') );
debug( $logs->grep('SELECT') );
debug( $logs->grep('UPDATE') );
debug( $logs->grep('DELETE') );


