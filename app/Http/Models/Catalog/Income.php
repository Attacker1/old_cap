<?php

namespace App\Http\Models\Catalog;

use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Common\Lead;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Http\Models\Catalog\Income
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $amount
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Models\Catalog\Product|null $products
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Admin\CustomRevision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read AdminUser|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|Income newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Income newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Income query()
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Income whereUserId($value)
 * @mixin \Eloquent
 */
class Income extends Model
{
    use RevisionableTrait;

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
