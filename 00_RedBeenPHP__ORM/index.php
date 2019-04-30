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


/*for($i = 1; $i < 11; $i++){
    $book = R::dispense('book');
    $book->title = "Test Book $i";
    $book->price = $i;
    $book->author = "Author $i";
    R::store($book);
}*/

//$books = R::find('book', 'price > ?', [9]);
//$books = R::find('book', 'author LIKE ?', ['%th%']);
//$books = R::find('book', 'id > ? AND price < ?', [2, 6]);
//$books = R::find('book', 'id > :id AND price < :price', [':price' => 6, ':id' => 2]);

//$ids = [1,3,5];
//$books = R::find('book', 'id IN (?, ?, ?)', $ids);
//$books = R::find('book', 'id IN (' . R::genSlots($ids) . ')', $ids);
//echo count($books);
//debug($books);

//$book = R::findOne('book', 'title = ?', ['Пикник на обочине']);
//debug($book);

//$books = R::findAll('book');
/*$books = R::findAll('book', 'ORDER BY id DESC LIMIT 3,3');
echo count($books);
debug($books);*/

R::hunt('book', 'id > ?', [2]);

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

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;
 *
 *
 */











