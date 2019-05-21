<?php

require_once __DIR__ . '/vendor/autoload.php';

function debug($data){
    echo '<pre>' . print_r($data, 1) . '</pre>';
}

class_alias('\RedBeanPHP\R', '\R');

R::setup( 'mysql:host=localhost;dbname=test', 'root', '', true );
R::addDatabase('db2', 'mysql:host=localhost;dbname=bt_loc', 'root', '', true);

if( !R::testConnection() ){
    die('No DB Connection');
}

//$fields = R::inspect('book');
/*$tables = R::inspect();
debug($tables);

R::selectDatabase('db2');

$tables = R::inspect();
debug($tables);

R::selectDatabase('default');

$tables = R::inspect();
debug($tables);*/

$cat = R::dispense('category');
$book = R::dispense('book');

$cat->title = 'test cat';
$cat->book_id = 1;

$book->title = '111';
$book->author = '222';
$book->price = 20;

//R::store($cat);
//R::store($book);

R::begin();
try{
    R::store($cat);
    R::store($book);
    R::commit();
}catch (Exception $e){
    R::rollback();
    echo $e->getMessage();
}


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











