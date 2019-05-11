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

R::debug(1);

//var_dump(R::exec("UPDATE book SET author = ? WHERE id = ?", ['New Author', 5]));
//var_dump(R::exec("UPDATE book SET author = ? WHERE id IN (?,?)", ['Test Author', 3,7]));

//$books = R::getAll("SELECT * FROM book");
//$books = R::getAll("SELECT * FROM book WHERE id < ?", [3]);

//$book = R::getRow("SELECT * FROM book LIMIT 2,1");
//$books = R::getCol('SELECT title FROM book');
//$book = R::getCell("SELECT title FROM book LIMIT 1");

//$books = R::getAll("SELECT id, title FROM book");
//$books = R::getAssoc("SELECT id, title, price FROM book");

/*$res = R::exec("INSERT INTO book (title, author, price) VALUES (?,?,?)", ['New Book', 'New Author', 10]);
//debug($res);
$id = R::getInsertID();
debug($id);*/

$books = R::getAll("SELECT * FROM book");
$books = R::convertToBeans('book', $books);

debug($books);



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











