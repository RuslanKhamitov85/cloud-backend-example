<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Модель работы с коробками
//Все файлы внутри системы хранятся по коробкам
//title  - Имя этой самой коробки
//user_id - ID пользователя создавшего ее
class Box extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'user_id',
    ];
}
