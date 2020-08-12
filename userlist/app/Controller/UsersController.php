<?php

namespace App\Controller;
use Framework\Request;

class UsersController
{
    public function index()
    {
        var_dump("BINGPOT!!");
    }

    public function create(Request $request)
    {
        var_dump($request);
    }

    public function update(Request $request, string $email)
    {

    }

    public function delete(Request $request, string $email)
    {

    }
}