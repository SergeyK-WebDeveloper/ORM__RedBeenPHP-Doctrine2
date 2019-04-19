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


/*$book = R::dispense('book');
$book->title = 'Test Book 4';
$book->price = 10;
$book->author = 'Author 4';
R::store($book);*/

/*$book = R::load('book', 4);
R::trash($book);*/
/*$books = R::loadAll('book', [3,5]);
R::trashAll($books);*/

//R::trashBatch('book', [6,7]);

//R::wipe('book');

//R::freeze(false);
//R::nuke();

/*
 *
 * CREATE TABLE `book` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `author` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `book`
--

INSERT INTO `book` (`id`, `title`, `price`, `author`) VALUES
(1, 'Три мушкетера', '29.99', 'А. Дюма'),
(2, 'Пикник на обочине', '25.00', 'Братья Стругацкие');
 *
 *
 */











