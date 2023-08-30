<?php

namespace App\Http\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Catalog\NoteCustomAdvice
 *
 * @property int $id
 * @property int|null $note_id
 * @property array|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|NoteCustomAdvice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NoteCustomAdvice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NoteCustomAdvice query()
 * @method static \Illuminate\Database\Eloquent\Builder|NoteCustomAdvice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteCustomAdvice whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NoteCustomAdvice whereValue($value)
 * @mixin \Eloquent
 */
class NoteCustomAdvice extends Model
{
    protected $table = 'notes_custom_advice';

    protected $casts = [
        'value' => 'array'
    ];

    public $timestamps = false;
}
