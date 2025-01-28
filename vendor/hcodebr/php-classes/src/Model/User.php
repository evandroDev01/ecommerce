<?php

namespace Hcode;


use \Hcode\DB\Sql;
use Hcode\Model\Model;

class User extends Model{

    public static function login($login,$password)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN",array(
            ":LOGIN" => $login
        ));

        if(count($results) === 0)
        {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }
        
        $data = $results[0];

        $verifica = password_verify($password,$data["despassword"]); // verificado se a senha recebida da function e equivalente a da consulta do banco

        if($verifica == true)
        {
            $user = new User();
            $user->setiduser($data['iduser']);
        }
        else
        {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }

    }

}



?>