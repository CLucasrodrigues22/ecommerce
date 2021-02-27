<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model
{

    const SESSION = "User";

    public static function login($usuario, $senha)
    {
        $sql = new Sql();

        $result = $sql->select("SELECT * FROM tb_users WHERE deslogin = :usuario", array(
            ":usuario" => $usuario
        ));

        if (count($result) === 0) 
        {
            throw new \Exception("Usuário inexistente ou senha inválida");
        }

        $data = $result[0];

        if (password_verify($senha, $data["despassword"]) == true) {
            
            $user = new User();
            
            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();

            return $user;

        } else {
            throw new \Exception("Usuário inexistente ou senha inválida");
        }
    }

    public static function verifyLogin($inadmin = true)
    {
        if (
                !isset($_SESSION[User::SESSION]) 
                || 
                $_SESSION[User::SESSION] 
                || 
                !(int)$_SESSION[User::SESSION]["iduser"] > 0 
                || 
                (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
        ) {
                header("Location: /admin/login");
                exit;
        } else {
            header("Location: admin/inicio");
            exit;
        }
    }
}
