<?php


class DB_Config
{
    static $m_array;

    public static function Read($name)
    {
        return self::$m_array[$name];
    }
 
    public static function Write($name, $value)
    {
        self::$m_array[$name] = $value;
    }
}



// default database settings db
DB_Config::write('db.host', '127.0.0.1');
DB_Config::write('db.port', '5432');
DB_Config::write('db.basename', 'gngr');
DB_Config::write('db.user', 'root'/*'webmaster'*/);
DB_Config::write('db.password','6vsacFG6U2Z3LvY8' /*'FSuBATyhmTAhT3XN'*/);