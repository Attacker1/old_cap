<?php

namespace App\Http\Models\Common;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Common\MailTemplate
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property mixed|null $params
 * @property string|null $html
 * @property string|null $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MailTemplate extends Model
{
    //
}
