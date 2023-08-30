<?php
namespace App\Http\Models\AdminClient;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Models\Catalog\Product;

/**
 * App\Http\Models\AdminClient\FeedbackQuize
 *
 * @property int $id
 * @property int|null $product_id
 * @property string|null $client_uuid
 * @property int|null $amo_id
 * @property int|null $order
 * @property int|null $feedbackgeneral_quize_id
 * @property string|null $action_result
 * @property string|null $price
 * @property string|null $discount_price
 * @property string|null $size_result
 * @property string|null $quality_opinion
 * @property string|null $price_opinion
 * @property string|null $style_opinion
 * @property string|null $comments
 * @property int|null $old_id
 * @property string|null $old_code
 * @property string|null $old_url
 * @property string|null $old_cost
 * @property array|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize newQuery()
 * @method static \Illuminate\Database\Query\Builder|FeedbackQuize onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereActionResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereAmoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereClientUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereFeedbackgeneralQuizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereOldCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereOldCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereOldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereOldUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize wherePriceOpinion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereQualityOpinion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereSizeResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereStyleOpinion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackQuize whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|FeedbackQuize withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FeedbackQuize withoutTrashed()
 * @mixin \Eloquent
 */
class FeedbackQuize extends Model
{
    use SoftDeletes;
    protected $fillable = ['product_id', 'client_uuid', 'amo_id', 'feedbackgeneral_quize_id', 'action_result', 'price', 'discount_price', 'size_result', 'quality_opinion', 'price_opinion', 'style_opinion', 'comments', 'order', 'old_id', 'old_code', 'old_url', 'old_cost', 'data'];

    protected $casts = [
        'data' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}