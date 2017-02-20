<?php
namespace alimmvc\backend\models;

session_start();

use alimmvc\core\registry;
use alimmvc\core\models;

class authUser extends models
{
	public static function security_mod()
	{
		//Авторизуем пользователя
		if (!self::isAuth())
		{
			$login = getPost('login');
			$pass = getPost('pass');
			$passMd5 = md5($pass);

			$query = registry::app()->db->query("SELECT * FROM users")->fetchAll(\PDO::FETCH_ASSOC);

			foreach ($query as $user) 
			{
				if ($user["login"] == $login && $user["pass"] == $passMd5)
				{
					unset($user["pass"]);
					$_SESSION["is_auth"] = true;
					$_SESSION["user"] = $user;
					return false;
				}
			}
		} else 
		{
			debug($_SESSION["user"]);
			return true;
		}

		return false;
	}

	public static function isAuth() 
	{
        if (isset($_SESSION["is_auth"])) 
        {
            return $_SESSION["is_auth"];
        }
        else 
        {
        	return false;
        }
    }

    public static function logout() 
	{
        $_SESSION = array();
        session_destroy();
    }
}