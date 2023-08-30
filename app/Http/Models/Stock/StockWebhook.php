<?php

namespace App\Http\Models\Stock;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Stock\StockWebhook
 *
 * @property int $id
 * @property string $uuid
 * @property string $external_id
 * @property string $entity
 * @property string $action
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockWebhook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockWebhook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockWebhook query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockWebhook whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockWebhook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockWebhook whereEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockWebhook whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockWebhook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockWebhook whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockWebhook whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockWebhook whereUuid($value)
 * @mixin \Eloquent
 */
class StockWebhook extends Model
{
    //
}
