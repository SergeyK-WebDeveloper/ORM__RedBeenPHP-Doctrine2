Doctrine2 - PHP ORM
==============================

### Simple Connection

    <?php
    $config = new \Doctrine\DBAL\Configuration();
    //..
    $connectionParams = array(
        'dbname' => 'mydb',
        'user' => 'user',
        'password' => 'secret',
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
    );
    $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

### Simple Queries and Dynamic Parameters

    <?php
    // $conn instanceof Doctrine\DBAL\Connection
    $sql = "SELECT * FROM articles WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $id);
    $stmt->execute();
    
    // Named parameters
    $sql = "SELECT * FROM users WHERE name = :name OR username = :name";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue("name", $name);
    $stmt->execute();

### Binding Types

    <?php
    /*
    Doctrine DBAL extends PDOs handling of binding types in prepared statement considerably. Besides the well known \PDO::PARAM_* constants you can make use of two very powerful additional features.
    */
    $date = new \DateTime("2011-03-05 14:00:21");
    $stmt = $conn->prepare("SELECT * FROM articles WHERE publish_date > ?");
    $stmt->bindValue(1, $date, "datetime");
    $stmt->execute();

### Prepare

    <?php
    $statement = $conn->prepare('SELECT * FROM user');
    $statement->execute();
    $users = $statement->fetchAll();
    
    /*
    array(
      0 => array(
        'username' => 'jwage',
        'password' => 'changeme'
      )
    )
    */

### Execute Update

    <?php
    // $sql, $params, $types
    $count = $conn->executeUpdate('UPDATE user SET username = ? WHERE id = ?', array('jwage', 1));
    echo $count; // 1

### Execute Query

    <?php
    // $sql, $params, $types
    $statement = $conn->executeQuery('SELECT * FROM user WHERE username = ?', array('jwage'));
    $user = $statement->fetch();
    
    /*
    array(
      0 => 'jwage',
      1 => 'changeme'
    )
    */

### Fetch All

    <?php
    $users = $conn->fetchAll('SELECT * FROM user');
    
    /*
    array(
      0 => array(
        'username' => 'jwage',
        'password' => 'changeme'
      )
    )
    */

### Fetch Array

    <?php
    $user = $conn->fetchArray('SELECT * FROM user WHERE username = ?', array('jwage'));
    
    /*
    array(
      0 => 'jwage',
      1 => 'changeme'
    )
    */

### Fetch Column

    <?php
    $username = $conn->fetchColumn('SELECT username FROM user WHERE id = ?', array(1), 0);
    echo $username; // jwage

### Fetch Assoc

    <?php
    $user = $conn->fetchAssoc('SELECT * FROM user WHERE username = ?', array('jwage'));
    /*
    array(
      'username' => 'jwage',
      'password' => 'changeme'
    )
    */

### Delete

    <?php
    $conn->delete('user', array('id' => 1));
    // DELETE FROM user WHERE id = ? (1)

### Insert

    <?php
    $conn->insert('user', array('username' => 'jwage'));
    // INSERT INTO user (username) VALUES (?) (jwage)

### Update

    <?php
    $conn->update('user', array('username' => 'jwage'), array('id' => 1));
    // UPDATE user (username) VALUES (?) WHERE id = ? (jwage, 1)

### Quote

    <?php
    $quoted = $conn->quote('value');
    $quoted = $conn->quote('1234', \PDO::PARAM_INT);

### Quote Identifier

    <?php
    $quoted = $conn->quoteIdentifier('id');

### Query Builder

    <?php
    // $conn instanceof Doctrine\DBAL\Connection
    
    // Query
    $query = $conn->createQueryBuilder()
                  ->select('*')
                  ->from('users')
                  ->orderBy('id', 'DESC')
                  ->where('id = :id')
                  ->setParameter('id', 1))
                  ->setMaxResults(10)
                  ->setFirstResult(1);
    // Results
    $rows = $query->execute()->fetchAll();

### Query Builder w/count

    <?php
    // $conn instanceof Doctrine\DBAL\Connection
    
    $query = $conn->createQueryBuilder()
                  ->select('COUNT(id) as count')
                  ->from($table)
                  ->where('id = :id')
                  ->setParameter('id', 1);
    $rowcount = $query->execute()->fetch();

### Query Builder w/multiple joins

    <?php
    // $conn instanceof Doctrine\DBAL\Connection
    
    $query = $conn->createQueryBuilder()
                  ->select('U.*')
                  ->from('users', 'U')
                  ->leftJoin('U', 'genders', 'G', 'U.gender_id = G.id')
                  ->leftJoin('U', 'houses', 'H', 'U.house_id = H.id')
                  ->innerJoin('H', 'kindoms', 'K', 'H.kindom_id = K.id')
                  ->where('U.id = ?')
                  ->setParameter(0, 1);