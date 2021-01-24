<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Модель для файлов.
//Обладает
//path - путь до физического файла
//name - имя файла
//box_id - коробка в которой находиться файл
//user_id - имя пользователя загрузившего файл
class File extends Model
{
    use HasFactory;
}
