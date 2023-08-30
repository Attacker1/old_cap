<?php

namespace App\Http\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Catalog\NotesAdvice
 *
 * @property int $id
 * @property int|null $note_id
 * @property array|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|NotesAdvice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotesAdvice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotesAdvice query()
 * @method static \Illuminate\Database\Eloquent\Builder|NotesAdvice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotesAdvice whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotesAdvice whereValue($value)
 * @mixin \Eloquent
 */
class NotesAdvice extends Model
{

    protected $casts = [
        'value' => 'array'
    ];

    public $timestamps = false;
}
