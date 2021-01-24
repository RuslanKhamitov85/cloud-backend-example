<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;


class LoginController extends Controller
{
  //авторизация пользователя
  public function login(Request $request)
  {
    //проверяем передан ли email и пароль пользователя
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);
    //находим пользователя по email
    $user = User::where('email', $request->email)->first();
    //Если пользователя нет или хэш его пароль не совпадает с введенным то отдаем ответ с ошибкой
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response([
            'message' => ['These credentials do not match our records.']
        ], 404);
    }
    //Создаем токен
    $token = $user->createToken('token')->plainTextToken;

    //Массив ответа. передаем пользователя и его временный токен
    $response = [
        'user' => $user,
        'token' => $token
    ];
    //отдаем ответ
    return response($response, 201);
  }

  //Регистрация пользтвателя
  public function register(Request $request)
  {
    //Проверяем все обязательные поля
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|confirmed|min:8',
    ]);

    //Записываем пользователя и тут же его авторизуем
    Auth::login($user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]));

    event(new Registered($user));

    //создаем токен пользователя
    $token = $user->createToken('token')->plainTextToken;

    //Массив ответа. передаем пользователя и его временный токен
    $response = [
        'user' => $user,
        'token' => $token
    ];
    //отдаем ответ
    return response($response, 201);
  }
}
