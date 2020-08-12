<?php

namespace App\Controller;

use Framework\Request;
use Framework\Response;
use App\Model\User;

class UsersController
{
    public function index()
    {
        $users = User::all();
        return new Response(200, $users);
    }

    public function create(Request $request)
    {
        $ruleset = [
            "name" => ["exists"],
            "lname" => ["exists"],
            "email" => ["exists", "email", "unique"],
            "phone" => ["phone"]
        ];
        $request->validate($ruleset);
        User::create($request->data());
        return new Response(201, ["Success"]);
    }

    public function update(Request $request, string $email)
    {
        User::delete($email);
        $ruleset = [
            "name" => ["exists"],
            "lname" => ["exists"],
            "email" => ["exists", "email", "unique"],
            "phone" => ["phone"]
        ];
        $request->validate($ruleset);
        User::create($request->data());
        return new Response(200, ["Success"]);
    }

    public function delete(Request $request, string $email)
    {
        User::delete($email);
        return new Response(200, ["Success"]);
    }
}