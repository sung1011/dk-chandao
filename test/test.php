<?php

class Test
{
    public static function wrap($title = '')
    {
        if (PHP_SAPI == 'cli') {
            $wrap = PHP_EOL . "--- $title ---" . PHP_EOL;
        } else {
            $wrap = "<br>--- $title ---<br>";
        }
        echo $wrap;
    }

    public static function random()
    {
        return mt_rand(1, 10000);
    }

    public static function mongo()
    {
        $manager = new MongoDB\Driver\Manager("mongodb://mongodb:27017/test");

        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->update(['_id' => 1], ['$set' => ['val' => self::random()]], ['multi' => false, 'upsert' => true]);
        $manager->executeBulkWrite('test.mycollection', $bulk);

        $query = new MongoDB\Driver\Query(['_id'=>1], []);
        $rows = $manager->executeQuery('test.mycollection', $query);

        self::wrap('mongo');
        foreach ($rows as $r) {
            echo $r->val;
        }
    }

    public static function redis()
    {
        $redis = new Redis();
        $redis->connect('tk_redis', 6379);
        $redis->set("test-redis", self::random());

        self::wrap('redis');
        echo $redis->get("test-redis");
    }

    public static function yac()
    {
        $yac = new Yac();
        $yac->set('test-yac', self::random());
        self::wrap('yac');
        echo $yac->get('test-yac');
    }

    public static function mysql_i()
    {
        $host = 'tk_mysql';
        $port = 3306;
        $user = 'root';
        $pass = 123456;
        $dbname = 'information_schema';

        $con = mysqli_connect($host, $user, $pass, $dbname);
        if (mysqli_connect_errno($con)) {
            echo "连接 MySQL 失败: " . mysqli_connect_error();
        }
        self::wrap('mysql_i');
        // $con-> set_charset('utf8');
        // $sql = "select * from CHARACTER_SETS";
        // $res = $con->query($sql);
        // while ($row = $res -> fetch_object()) {
        //     var_dump($row);
        // }

        // $res -> free();

        //mysqli_close($con);

        echo self::random();
    }

    public static function mysql_PDO()
    {
        $host = 'tk_mysql';
        $port = 3306;
        $user = 'root';
        $pass = 123456;
        $dbname = 'information_schema';

        $db = array(
            'host' => $host,         //设置服务器地址
            'port' => $port,              //设端口
            'dbname' => $dbname,             //设置数据库名
            'username' => $user,           //设置账号
            'password' => $pass,      //设置密码
            'charset' => 'utf8',             //设置编码格式
            'dsn' => "mysql:host=tk_mysql;dbname=$dbname;port=3306;charset=utf8",   //这里不知道为什么，也需要这样再写一遍。
        );

        //连接
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //默认是PDO::ERRMODE_SILENT, 0, (忽略错误模式)
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // 默认是PDO::FETCH_BOTH, 4
        );

        try {
            $pdo = new PDO($db['dsn'], $db['username'], $db['password'], $options);
            self::wrap('mysql_pdo');
        } catch (PDOException $e) {
            print_r('数据库连接失败:' . $e->getMessage());
        }
        // foreach ($pdo->query('SELECT * from CHARACTER_SETS') as $row) {
        //     print_r($row);
        // }
        //
        echo self::random();
    }
}

Test::mysql_i();
Test::mysql_pdo();
// Test::mongo();
Test::redis();
// Test::yac();
