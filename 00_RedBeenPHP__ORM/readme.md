Manual #1
===========================================

**RedBeanPHP** - ORM для PHP.
[официальный сайт](https://redbeanphp.com/index.php)

RedBeanPHP (PHP >= 5.3.4)использует драйвер PDO, поэтому защита от SQL- инъекций при правильном применении гарантированна. 
Поддерживаемые СУБД: MySQL, MariaDB, PostgreSQL, SQLite, CUBRID.

Всего есть 2 модели ORM-систем: Data Mapper и Active Record. Так вот RedBeanPHP это Data Mapper и каждый объект записи здесь называется бином. Эти бины можно воспринимать как самые обычные объекты, свойства которых представляют собой записи в Ваших таблицах. То есть одна запись это один бин, а его свойства это поля Вашей записи. Работать с бином можно точно также как с обычным массивом.

Удобнейший инструмент в RedBeenPHP - правка "на лету" типов данных в БД на этапе разработки.
Установка
---------

Создать файл `composer.json` с таким содержимым:

```
{
  "require":  {
  "gabordemooij/redbean":  "dev-master"
  }
}
```

Из консоли выполнить:

```
composer install
```

Подключение
-----------

В корне проекта создать файл `index.php`, в нём подключить автозагрузчик композера `autoload.php` и подключиться к БД Mysql:


```
// Подключаем автозагрузчик composer
require_once __DIR__.'/vendor/autoload.php';

// Создаём псевдоним для указанного класса
class_alias('\RedBeanPHP\R',  '\R');

/*
 Подключаемся к базе данных
 Последний (4-й) параметр по умолчанию выставлен в FALSE
 Если нужно применить заморозку таблиц в БД (отменить создание на лету),
 то нужно данный параметр выставить в TRUE
 или так: R::freeze(true);
 */
R::setup(  'mysql:host=localhost;dbname=redbeanphp','root',  '',  false);

// Проверка подключения к БД
if(!R::testConnection())  die('No DB connection!');

 /*
   Если нужно работать с таблицами, в названии которых
   присутствует знак подчёркивания (_), то необходимо воспользоваться 
   таким методом
 */
  R::ext('xdispense',  function( $type ){
    return R::getRedBean()->dispense( $type );
  });
  // Использовать так:
  $test = R::xdispense('test_table');
  // Code...
  R::store($test);
```

CRUD: Create (Создание записи)
------------------------------

```
// Указываем, что будем работать с таблицей book
$book = R::dispense('book');
// Заполняем объект свойствами
$book->title =  'Призрак победы';
$book->price =  199;
// Можно обращаться как к массиву
$book['author']  =  'Макс Глебов';
// Сохраняем объект
R::store($book);

```

CRUD: Read (Чтение)
-------------------

Если нужно получить данные без каких-либо условий, то легче это сделать методами `load()` и `loadAll()`

```
// Получаем все записи, ID которых указаны в массиве ids
$ids =  [1,2,3];
$books = R::loadAll('book', $ids);
foreach  ($books as $book){
 echo $book->title.'<br>';
}

// Получаем одну запись по её ID
$id =  1;
$book = R::load('book', $id);
echo $book->title;

```
Если по каким-то причинам вам понадобится именно массив данных, то на этот случай есть метод `export()`:

```
$id =  1;
$book = R::load('book', $id);
$book = $book->export();
echo $book['title'];

```
CRUD: Update (Обновление записи)
--------------------------------

```
$id =  1;
// Загружаем объект с ID = 1
$book = R::load('book', $id);
// Обращаемся к свойству объекта и назначаем ему новое значение
$book->price =  210;
// Сохраняем объект
R::store($book);

```
CRUD: Delete (Удаление)
-----------------------

Удалить запись с `ID = 5`

```
$id =  5;
$book = R::load('book', $id);
R::trash($book);

```
Удалить записи с `ID = 6, 7`

```
$ids =  [6,  7];
$book = R::loadAll('book', $ids);
R::trashAll($book);

// Начиная с версии 5.1 данную задачу лучше выполнить методом R::trashBatch(). В таком случае нет необходимости создавать (получать) бин - объект RedBeanPHP
$ids =  [6,  7];
R::trashBatch('book', $ids);

// Удаление записи с ID = 3
$id =  3;
R::hunt('book',  'id = ?',  [$id]);

```
Метод `R::wipe()` полностью очищает указанную таблицу:

```
R::wipe('book');

```
Метод `R::nuke()` полностью очищает всю базу данных. Режим заморозки должен быть выключен:

```
R::freeze(false);
R::nuke();

```
Поиск данных: find(), findOne(), findAll()
------------------------------------------

Если вы не знаете идентификатор бина, вы можете искать бины, используя метод `find()`:

```
$min_price =  250;
$books = R::find('book',  'price > ?',  [$min_price]);

$search =  'строка';
$books = R::find('book',  'author LIKE ?',  ["%$search%"]);

$id =  1;
$min_price =  300;
$books = R::find('book',  'id > :id AND price < :price',  [':price'  => $min_price,  ':id'  => $id]);

$ids =  [1,  3,  5];
$books = R::find('book',  'id IN ('  . R::genSlots($ids)  .  ')', $ids);

```
Если необходимо получить только одну запись, используем метод `findOne()`:

```
$id =  1;
$book = R::findOne('book',  'id = ?',  [$id]);

$title =  'гостья из будущего';
$book = R::findOne('book',  'title = ?',  [$title]);

Если необходимо получить все данные без особых условий, используем метод `findAll()`:

$books = R::findAll('book');

$limit =  5;
$books = R::findAll('book',  'ORDER BY id ASC LIMIT ?',  [$limit]);

```
Метод findLike()
----------------

Данный метод предназначен для поиска по записям (однако, в нём существует проблема с биндингом):

```
$search_1 =  'Джон Пристли';
$search_2 =  'Сергей Тармашев';

$books = R::findLike('book',
  ['author'  =>  [$search_1, $search_2]],
  'ORDER BY title ASC'
);

```
Построение запросов (Querying)
------------------------------

При использовании RedBeanPHP (как и любой другой ORM) не всегда можно ограничится простыми методами поиска (Finding). Часто существует необходимость сделать более сложный запрос, который сделать простыми методами крайне проблематично. **Важно!** Рассмотренные выше методы Finding необходимо применять, если требуется сделать простой запрос, без каких-либо сложных условий. В рассмотренных ниже примерах всегда возвращается массив данных (а не объекты-бины), поэтому это тоже является плюсом ☺

Метод exec()
------------

Метод для произвольного SQL запроса (чаще всего применяется для добавления, изменения и удаления):

```
$id =  3;
$title =  'New title';

R::exec('UPDATE `book` SET `title` = :title WHERE id = :id',  [
  'id'  => $id,
  'title'  => $title
]);

```
Метод getAll()
--------------

Вернёт массив данных (все записи/несколько по условию) из указанной таблицы:

```
//$books = R::getAll('SELECT `title` FROM `book`');
$id =  1;
$books = R::getAll('SELECT `title` FROM `book` WHERE `id` > ?',  [$id]);

foreach  ($books as $book){
 echo $book['title'].'<br>';
}

```
Метод getRow()
--------------

Вернёт все записи, но выводит только одну. Рекомендуется добавлять `LIMIT 1`, чтобы и запрашивалась тоже только одна запись:

```
$search =  'поворот';
$book = R::getRow('SELECT FROM `book` WHERE `author` LIKE :search LIMIT 1',  [
  'search'  =>  "%$search%"  
]);

```
Метод getCol()
--------------

Вернёт колонку:

```
// Выбрать все названия всех книг
$books = R::getCol(  'SELECT `title` FROM book'  );

```
Метод getCell()
---------------

Вернёт ячейку одной записи:

```
$id =  5;
$title = R::getCell('SELECT `title` FROM book WHERE `id` = ? LIMIT 1',  [$id]);

```
Метод getAssoc()
----------------

Чтобы получить ассоциативный массив с указанным столбцом ключа и значения, используйте:

```
R::getAssoc('SELECT id, title FROM book');

```
Метод getInsertID()
-------------------

Вернёт ID последней вставленной записи:

```
$res = R::exec("INSERT INTO book (title, author, price) VALUES (?,?,?)",  ['New Book',  'New Author',  10]);
$id = R::getInsertID();

```
Методы convertToBean() и convertToBeans()
-----------------------------------------

Конвертация массива записей в бины или один бин (convertToBean())

```
$books = R::getAll("SELECT FROM book");
$books = R::convertToBeans('book', $books);

$book = R::getRow("SELECT FROM book WHERE `id` = ?",  [1]);
$book = R::convertToBean('book', $book);

```
Работа с Базами Данных и их таблицами
-------------------------------------

Метод `inspect()` возвращает названия таблиц в БД. Если параметром передать название таблицы, то он вернёт все поля этой таблицы:

```
// Какие таблицы есть в БД
$tables = R::inspect();

// Какие поля есть в указанной таблице
$fields = R::inspect('book');

```
Транзакции
----------

RedBeanPHP предлагает три простых метода для использования транзакций базы данных: `begin()`, `commit()` и `rollback()`. Использование:

```
$category = R::dispense('category');
$book = R::dispense('book');

$category->title =  'Фэнтези';

$book->title =  'Невольный брак';
$book->price =  200;
$book->author =  'Анастасия Маркова';
$book->category_id =  5;

R::begin();
try{
 R::store($category);
 R::store($book);
 R::commit();
}catch  (Exception $e){
 R::rollback();
18.   echo $e->getMessage();
19.  }

```
Связи (отношения) в RedBeanPHP
------------------------------

`One-to-many` (связь **один ко многим**). Достанем из БД все книги, у которых `category_id = 1`

```
$category_id =  1;
$category = R::load('category', $category_id);
$books = $category->ownBookList;

// Сортировка и лимит
$books = $category->with('ORDER BY `title` ASC LIMIT 3')->ownBookList;

// Но более предпочтительным способом является метод withCondition()
$status =  1;
$limit =  3;
$books = $category
  ->withCondition('status = ? ORDER BY title ASC LIMIT ?',  [$status, $limit])
  ->ownBookList;

foreach  ($books as $book){
 echo $book->title.'<br>';
}

```
`Many-to-one` (связь **Многие к одному**). Достанет из базы название категории, с которой связана книга

```
$book = R::load('book',  1);
$category = $book->category->title;

```
`Many-to-many` (связь **Многие к одному**). Достанет из базы (из связующей таблицы) все книги этой категории:

```
$category = R::load('category',  1);
$books = $category->sharedBookList;

print_r($books);

```

Методы подсчёта (Counting)
--------------------------

Простой подсчёт элементов:

```
// Сколько записей (элементов) в таблице book
$books = R::count(  'book'  );

// Сколько записей (элементов) в таблице book, у которых поле status = 1
$books = R::count(  'book',  'status = ?',  [1]  );

Подсчёт элементов связанных таблиц:

// Сколько записей (элементов) в таблице book, связанных с категорией с ID = 1
$category = R::load('category',  1);
$numBook = $category->countOwn('book');

```
Логирование и отладка в RedBeanPHP
----------------------------------

```
// Режим вывода дебагера
R::debug(1,  3);

$logs = R::getDatabaseAdapter()
  ->getDatabase()
  ->getLogger();

debug( $logs->grep('INSERT')  );
debug( $logs->grep('SELECT')  );

```
Manual #2
===========================================

#### Как подключить RedBean PHP

Подключить библиотеку можно через функцию require:

```require 'libs/rb.php';```

#### Как подключиться к базе данных посредством RedBean PHP?

Для подключения к базе данных в RedBeanPHP есть статичный метод setup, который принимает 4 опциональных аргумента. Опциональными они являются, потому что Вы можете никакой аргумент не задать и тогда RedBeanPHP создаст временную базу данных в формате SQLite в Вашей временной директории. Вызывается метод setup для MySQL следующим образом:

```php
R::setup( 'mysql:host=127.0.0.1;dbname=redbean','login', 'password' ); 
 
if ( !R::testConnection() )
{
        exit ('Нет соединения с базой данных');
}```

Метод testConnection проверяет есть ли у нас фактическое подключение к базе.

Подробнее о подключении к базам данных можно прочесть в разделе [Connection](https://redbeanphp.com/index.php?p=/connection).

#### Как закрыть соединение с базой данных?

Закрыть соединение с базой данных Вы можете при помощи метода close. Вызывается он вот так:

```php
R::close();
```

#### Как выполнить произвольный запрос к базе данных?

Какой в RedBeanPHP есть аналог функции mysqli\_query? Этим аналогом является метод exec. У него всего 2 аргумента: sql и bindings. Бинды (bindings) – это специальная техника подготовленных запросов, при помощи которых можно обезопасить себя от SQL-инъекций. Также бинды увеличивают производительность при частых запросах. Вызывается метод exec следующим образом:

**Пример #1**

Вторым элементом мы передаем массив с данными, которые будут подставлены вместо знака ? (плэйсхолдер). В данном случае это id.

```
$id = $_POST['id'];
R::exec('DELETE FROM `users` WHERE `id` = ?', array(
    $id
));
```

**Пример #2**

Плэйсхолдеров может быть много. В этом случае в качестве первого знака ? будет вставлена первая ячейка массива, то есть цифра 32. В качества второго ? слева на право будет вставлена цифра 51. В качестве последнего ? будет вставлена цифра 73.

```
R::exec('DELETE FROM `users` WHERE `id` = ? OR `id` = ? OR `id` = ?', array(
    32,
    51,
    73
));```

**Пример #3**

Если Вы уже работали с PDO, то возможно будет привычней использовать следующий подход:

```
$id = $_POST['id'];
R::exec('DELETE FROM `users` WHERE `id` = :id', array(
    ':id' => $id
));
```

* * *

### 4 операции CRUD (Create, Read, Update, Delete)

#### 1\. Как создавать данные (Create)

Метод dispense принимает всего 1 аргумент – название таблицы. RedBeanPHP умеет создавать таблицы налету. Достаточно вызвать dispense и указать какие поля будут у таблицы. После этого мы вызываем метод store и передаем в него бин user.

**Пример #1**

```
$user = R::dispense('users'); //передаем название таблицы users
 
//поле id можно не создавать, так как RedBeanPHP автоматически его создает с автоинкрементом
$user->login = $data['login'];
$user->email = $data['email'];
 
R::store($user); // сохраняем объект $user в таблице 

```

**Пример #2**

```
$user = R::dispense('users');
 
$user->name = 'Alex';
$user->age = 35;
 
R::store($user);
```

#### 2\. Как получать данные (Read/чтение)

Для чтения данных есть множество методов, например, метод load. Первым параметром мы передаем имя таблицы, из которой мы хотим прочесть данные. Второй параметр – id записи, которую мы хотим получить. С полученными данными мы можем работать, как с объектом или массивом.

```
$cat = R::load('category', 2);
echo $cat->title; // работаем с данными, как с объектом
echo $cat['title']; // работаем с данными, как с массивом
```

#### 3\. Как изменять данные (update)

Чтобы изменить запись в БД её нужно:  
1 - получить в виде объекта;  
2 – написать новое значение;  
3 – сохранить через метод store.

```
$cat = R::load('category', 2);
$cat->title = "Новое значение"; 
R::store($cat);
 
echo $cat->title; // выводим наше новое значение
```

#### 4\. Как удалять данные/записи (delete)

Чтобы удалить запись из БД мы её должны:  
1 - получить в виде объекта;  
2 – использовать метод trash, который удалит одну запись. Есть ещё метод trashAll, который может удалить сразу несколько объектов.

```
$cat = R::load('category', 2);
R::trash($cat); //удаляем запись с id=2 из таблицы category
```

#### Как полностью очистить таблицу

```
R::wipe('category'); // удаляем все записи из таблицы category

```

* * *

#### Режим заморозки

Режим заморозки в RedBeanPHP нужен для того, чтобы включить или выключить поведение автоматического создания и изменения таблиц в БД. Сервер сильно нагружается, поэтому, когда Вы разрабатываете сайт, то режим заморозки можно выключить (false). Тогда автоматическое создание таблиц будет работать. Когда Вы зальете готовый сайт на хостинг, то нужно поставить режим заморозки в значение true.

```
R::setup(); // тут подключение к БД
R::freeze( true ); // тут выключение режима заморозки
```

* * *

