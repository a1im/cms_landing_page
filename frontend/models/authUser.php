<?php
namespace alimmvc\frontend\models;

session_start();

use alimmvc\core\registry;
use alimmvc\core\models;

class authUser extends models
{
	// проверка авторизаии пользователя
	public static function security_mod()
	{
		//Авторизуем пользователя
		if (!self::isAuth())
		{
			$login = getPost('login');
			$pass = getPost('pass');
			$passMd5 = md5($pass);

			$query = registry::app()->db->query("SELECT * FROM users")
			->fetchAll(\PDO::FETCH_ASSOC);

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

    // регистрация пользователя
    public static function registryUser($user) 
	{
		$result = registry::app()->db->prepare("INSERT INTO `users`(login, pass, regdate, firstname, lastname, email, phone) VALUES(:login, :pass, :regdate, :firstname, :lastname, :email, :phone)")
		->execute($user);
		return ($result == 1)?true:false;
    }
}