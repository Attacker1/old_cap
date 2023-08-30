<?php

namespace App\Http\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Catalog\CategoriesTranslator
 *
 * @property int $id
 * @property string|null $ms_name
 * @property string|null $cap_name
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriesTranslator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriesTranslator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriesTranslator query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriesTranslator whereCapName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriesTranslator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriesTranslator whereMsName($value)
 * @mixin \Eloquent
 */
class CategoriesTranslator extends Model
{
    protected $table = 'categories_translator';
    protected $fillable = ["ms_name","cap_name"];
    public $timestamps = false;

    public static function translate($name){

        try {
            if (!$name = self::where('ms_name', "like" , "%$name%")->first())
                return '';

            if (!isset($name->cap_name))
                return '';

            return $name->cap_name;
        }
        catch (\Exception $e){
            dd($e);
        }

    }
}
