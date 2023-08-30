<?php

namespace App\Http\Models\Admin;

use Venturecraft\Revisionable\Revision;

/**
 * App\Http\Models\Admin\CustomRevision
 *
 * @property int $id
 * @property string $revisionable_type
 * @property string $revisionable_id
 * @property int|null $user_id
 * @property string $key
 * @property string|null $old_value
 * @property string|null $new_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $revisionable
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision whereNewValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision whereOldValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision whereRevisionableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision whereRevisionableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomRevision whereUserId($value)
 * @mixin \Eloquent
 */
class CustomRevision extends Revision
{
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

    }

    protected $casts = array(
        'revisionable_id' => 'string'
    );

    public function userResponsible()
    {

        if (empty($this->user_id)) {
            return false; //return auth()->guard('admin')->user()->id;
        }
        if (class_exists($class = '\Cartalyst\Sentry\Facades\Laravel\Sentry')
            || class_exists($class = '\Cartalyst\Sentinel\Laravel\Facades\Sentinel')
        ) {
            return $class::findUserById($this->user_id);
        } else {
            $user_model = app('config')->get('auth.model');

            if (empty($user_model)) {
                $user_model = app('config')->get('auth.providers.admin.model');
                if (empty($user_model)) {
                    return false;
                }
            }
            if (!class_exists($user_model)) {
                return false;
            }
            return $user_model::find($this->user_id);
        }
    }
}