<?php

namespace App\Http\Models\Common;

use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Catalog\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadCorrectionsProducts extends Model
{

    protected $fillable = ["lead_uuid","note_id","data","user_id","deleted_at",];




    /**
     * Связь с таблицей Сделок
     * @return BelongsTo
     */
    public function leads(){

        return $this->belongsTo(Lead::class,'lead_uuid','uuid');
    }

    /**
     * Связь с таблицей Сделок
     * @return BelongsTo
     */
    public function notes(){

        return $this->belongsTo(Note::class);
    }

    /**
     * Связь с таблицей пользователей
     * @return BelongsTo
     */
    public function users(){

        return $this->belongsTo(AdminUser::class,'user_id','id');
    }


    public function products(){

        return $this->hasManyThrough(Product::class,'lead_corrections_products','product_id','correction_id');
    }
}
