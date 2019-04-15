<?php

require_once __DIR__ . '/vendor/autoload.php';

function debug($data){
    echo '<pre>' . print_r($data, 1) . '</pre>';
}

class_alias('\RedBeanPHP\R', '\R');

R::setup( 'mysql:host=localhost;dbname=test', 'root', '', true );

if( !R::testConnection() ){
    die('No DB Connection');
}

// CRUD: Read
//$book = R::load('book', 1);
//echo $book['title'];
//echo $book->title;
/*debug($book);
$book = $book->export();
debug($book);*/

/*$books = R::loadAll('book', [1,2]);
//debug($books);
foreach($books as $book){
    echo $book->title . '<br>';
}*/

// CRUD: Update
$book = R::load('book', 1);
$book->author = 'А. Дюма';
R::store($book);














