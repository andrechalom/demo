<?php

namespace App\Controller;

use Framework\Request;
use Framework\Response;
use App\Model\User;

class UsersController
{
    /**
     * Action que lista todos os usuários registrados
     */
    public function index()
    {
        $users = User::all();
        return new Response(200, $users);
    }

    /**
     * Action que cria novo usuário
     * 
     * @param Request $request Objeto representando os dados de entrada
     */
    public function create(Request $request)
    {
        $ruleset = [
            "name" => ["exists"],
            "lname" => ["exists"],
            "email" => ["exists", "email", "unique"],
            "phone" => ["phone"]
        ];
        $request->validate($ruleset, User::class);
        User::create($request->data());
        User::commit();
        return new Response(201, ["Success"]);
    }

    /**
     * Action que atualiza dados de um usuário a partir do e-mail
     * 
     * @param Request $request Objeto representando os dados de entrada
     * @param string $email E-mail identificando o usuário que deve ser alterado
     */
    public function update(Request $request, string $email)
    {
        //como temos controle de transação, essa deleção não é escrita em disco ainda!
        User::delete($email);
        $ruleset = [
            "name" => ["exists"],
            "lname" => ["exists"],
            "email" => ["exists", "email", "unique"],
            "phone" => ["phone"]
        ];
        $request->validate($ruleset, User::class);
        User::create($request->data());
        User::commit();
        return new Response(200, ["Success"]);
    }

    /**
     * Action que atualiza dados de um usuário a partir do e-mail
     * 
     * @param Request $request Objeto representando os dados de entrada
     * @param string $email E-mail identificando o usuário que deve ser alterado
     */
    public function delete(Request $request, string $email)
    {
        User::delete($email);
        User::commit();
        return new Response(200, ["Success"]);
    }
}