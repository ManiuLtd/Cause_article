<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30 0030
 * Time: 上午 11:17
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleType extends Model
{
    protected $fillable = ['name'];

    use SoftDeletes;
}