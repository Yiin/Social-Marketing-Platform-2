<?php

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Template::class, 16)->create();
    }
}


/*
Project Database Include
Written by Dru Mundorff
Aug 16, 2013


Designed for easy database access across the website
*/

/*
Error Index:
191712 - SQL - Database Connection Error
171825 - QRY - Invalid MySQL Query
*/

class Database
{
    static $link;

    public static function connect()
    {
        self::$link = mysqli_connect('localhost', 'mmocode_Admin', 'K17cR}ULbVh7', 'mmocode_panel');

        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
    }

    public static function query($q)
    {
        $result = mysqli_query(self::$link, $q);
        if (!$result) {
            var_dump(self::$link);
            printf("Error: %s\n", mysqli_error(self::$link));
            exit;
            //echo('Error 171825. You Are Missing Information In Your SQL Query or Your SQL Query Is Invalid...'); // Use mysqli_error() to check real error
        }
        return $result;
    }
}


function query($sql)
{
    return Database::query($sql);
}

function escape($str)
{
    if (get_magic_quotes_gpc()) $str = stripslashes($str);
    return strtr($str, array(
        "\0" => "",
        "'" => "&#39;",
        "\"" => "&#34;",
        "\\" => "&#92;",
        // more secure
        "<" => "&lt;",
        ">" => "&gt;",
    ));
}


function getUser($user_name, $password)
{
    $user = mysqli_real_escape_string(Database::$link, $user_name);
    $result = query("SELECT * FROM `users` WHERE `user`='" . mysqli_real_escape_string($user) . "' and `pass` ='" . $password . "'");
    if (mysqli_num_rows($result) == 0) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row;
    }
}


function duplicateUser($username, $pass)
{
    $res = query("SELECT COUNT(*) FROM `users` WHERE `user`='" . $username . "' AND `pass`='" . $pass . "'");
    if (mysqli_num_rows($res) > 0)
        return true;
    return true;
}

function getMaxVal($t_name, $f_name = 'id')
{
    $res = query("SELECT MAX(`" . $f_name . "`) FROM `" . $t_name . "`");
    if (!$res) {
        return false;
    }
    $arr = mysqli_fetch_assoc($res);
    return $arr["MAX(`$f_name`)"];
}

function insertData($t_name, $data = array())
{
    $sqlStr = "INSERT INTO `" . $t_name . "` ( `" . implode('`,`', array_keys($data)) . "`) VALUES ( '" . implode('\',\'', $data) . "')";
    return query($sqlStr);
}

?>