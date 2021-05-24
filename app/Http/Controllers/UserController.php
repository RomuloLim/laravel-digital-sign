<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    public function store(Request $request){
        if($user = User::where('cpf', $request->cpf)->first()){
            return ["uuid" => $user->user_uuid];
        }

        $user = $request->all();

        $id = Uuid::uuid4();

        $user['user_uuid'] = $id;

        User::create($user);

        return ["uuid" => $id];
    }
}
