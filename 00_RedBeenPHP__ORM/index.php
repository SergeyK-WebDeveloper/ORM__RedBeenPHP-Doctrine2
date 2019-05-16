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

/*$data = R::getLook()->look(
    "SELECT * FROM book",
    [],
    ['id', 'title'],
    '<option value="%s">%s</option>', 'trim', "\n"
);*/

/*$data = R::getLook()->look(
    "SELECT * FROM book",
    [],
    ['id', 'title'],
    '<li data-id="%s">%s</li>'
);

echo "<ul>$data</ul>";*/

/*$didChangeAuthor = R::matchUp(
    'book',
    ' title = ? ',
    ['New Book'],
    [
        'author' => 'Author!!!',
        'title' => 'New Book!!!',
    ],
    [
        'title' => 'New Book!',
        'author' => 'Author!',
        'price' => 13,
    ],
    $book
);

debug($book);*/

R::csv('SELECT * FROM book',
        [],
        ['ID', 'Title', 'Author', 'Price'],
        __DIR__ . '/file.csv',
        false
);


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











