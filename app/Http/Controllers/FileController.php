<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\File;
use App\Models\User;
use Storage;

class FileController extends Controller
{

  //Загрузка файла на сервер
  public function upload(Request $request)
  {
    // имя файла - обязательное поле, вес не более 2М
      $request->validate([
        'file' => 'required|max:2048'
      ]);

      //Подготавливаем будущий ответ от сервера
      $resultArray = [
        'error' => [
          'status'=>false,
          'message'=>'',
        ],
        'file' => []
      ];

      //получалем информацию о пользователе
      $user = User::find(Auth::id());

      //генерируем имя файла под который файл будет лежать на сервере
      $newFileName = rand(1, 9999).'-'.time().'.'.$request->file->getClientOriginalExtension();
      $fileName = $request->file->getClientOriginalName();

      //Пытаемся перенести файл в локальное хранилище
      try {
        //переносим в коробку по умолчанию
        $request->file->store($user->default_box_id, 'local');
      } catch (\Exception $error) {
        $resultArray['error']['status'] = true;
        $resultArray['error'] = $error;
      }

      //если не было ошибок создаем запись с БД
      if(!$errorArray['error']['status']){
        $fileDB = new File;
        $fileDB->path = $newFileName;
        $fileDB->name = $fileName;
        $fileDB->box_id = $user->default_box_id;
        $fileDB->user_id = $user->id;
        $fileDB->save();
        $resultArray['file'] = $fileDB;
      }
      //Возврощаем ответ
      return response()->json($resultArray);
  }

  //Получаем список файлов пользователя
  public function my()
  {
    $user = User::find(Auth::id());
    $myFiles = File::select('id', 'name')->where('user_id', $user->id)->where('box_id', $user->default_box_id)->get();
    return $myFiles;
  }

  //получаем информацию файле
  public function file($id)
  {
    $file = File::find($id);
    return $file;
  }
}
