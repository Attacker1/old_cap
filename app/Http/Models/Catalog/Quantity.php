<?php

namespace App\Http\Models\Catalog;

use App\Http\Models\Admin\AdminUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Http\Models\Catalog\Quantity
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $amount
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Http\Models\Catalog\Product|null $products
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Admin\CustomRevision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read AdminUser|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|Quantity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quantity newQuery()
 * @method static \Illuminate\Database\Query\Builder|Quantity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Quantity query()
 * @method static \Illuminate\Database\Eloquent\Builder|Quantity whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quantity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quantity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quantity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quantity whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quantity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quantity whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Quantity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Quantity withoutTrashed()
 * @mixin \Eloquent
 */
class Quantity extends Model
{
    use RevisionableTrait, SoftDeletes;

    protected $revisionFormattedFieldNames = [
        'amount'      => 'Кол-во',
    ];

    /**
     * Связь с таблицей Продукции
     * @return BelongsTo
     */
    public function products(){

        return $this->belongsTo(Product::class,'product_id','id');
    }

    /**
     * Связь с таблицей пользователей
     * @return BelongsTo
     */
    public function users(){

        return $this->belongsTo(AdminUser::class,'user_id','id');
    }
}
