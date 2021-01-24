<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Box;
use Auth;
use Session;
use App\Models\User;
class BoxController extends Controller
{
  //Создаем коробку для файлов
    public function store(Request $request)
    {
      //Проверяем есть ли имя коробки, оно обязательно
      $validated = $request->validate([
        'title' => 'required|max:255',
      ]);
      //Подготавливаем будущий ответ от сервера
      $resultArray = [
        'error' => [
          'status'=>false,
          'message'=>'',
        ],
        'box' => []
      ];

      //Пытаемся создать коробку
      try {
        $box = Box::create([
                'title'=>$request->title,
                'user_id'=>Auth::id()
              ]);
              $resultArray['box'] = $box;
      } catch (\Exception $e) {
        $resultArray['error']['status'] = true;
        $resultArray['error']['message'] = $error;
      }

      //отдаем ответ
      return $resultArray;
    }

    //получаем список коробок пользователя
    public function my(){
      $myBox = Box::where('user_id', Auth::id())->get();
      return $myBox;
    }

    //Устанавливаем коробку по умолчанию.
    public function setDefault(Request $request){

      //проверяем передан ли ID коробки.
      $validated = $request->validate([
        'box_id' => 'required|numeric',
      ]);

      //Подготавливаем будущий ответ от сервера
      $resultArray = [
        'error' => [
          'status'=>false,
          'message'=>'',
        ],
        'box' => []
      ];

      //Проверяем есть ли коробка с таким ID
      $box = Box::find($request->id);
      if(!$box){
        $resultArray['error']['status'] = true;
        $resultArray['error']['message'] = 'No box';
      }
      //Получаем информацию по пользователю
      $user = User::find(Auth::id());
      $user->default_box_id = $box->id;

      //Проверяем не было ли ошибок ранее
      if(!$resultArray['error']['status']){
        //пробуем записать данные
        try {
          $User->save();
          $resultArray['box'] = $box;
        } catch (\Exception $error) {
          $resultArray['error']['status'] = true;
          $resultArray['error']['message'] = $error;
        }
      }
      //отдаем ответ
      return $resultArray;
    }

    //Получаем коробку по умолчанию
    public function getDefault(){
      $user = User::find(Auth::id());
      return  ['boxId'=>$user->default_box_id];
    }
}
