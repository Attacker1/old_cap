<?php

namespace App\Http\Models\Vuex\Anketa;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\Vuex\Anketa\AnketaQuestion
 *
 * @property int $id
 * @property int $checked
 * @property int $is_sub
 * @property int|null $parent
 * @property string|null $old_key Ключ бывшего массива
 * @property string $slug Текстовый ID
 * @property string|null $question Вопрос
 * @property int $sort Сортировка
 * @property int $active
 * @property int $save
 * @property int $type_id Тип шаблона
 * @property int|null $multiple
 * @property string|null $style_image Подложка стиля
 * @property string|null $appeal Доп. поле
 * @property string|null $placeholder Подсказка
 * @property string|null $disclaimer
 * @property int $own_answer Свой Ответ
 * @property int|null $own_type Тип поля ответа
 * @property string|null $own_placeholder
 * @property int $select_all
 * @property int $unselect_all
 * @property string|null $labelC labelC
 * @property string|null $ya_track
 * @property string|null $fb_track
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $boxberryCityText
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $boxberryText
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $chooseCityText
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $cities
 * @property-read int|null $cities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $clientPhotos
 * @property-read int|null $client_photos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $colors
 * @property-read int|null $colors_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $columnListThin
 * @property-read int|null $column_list_thin_count
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $coupon
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $customForms
 * @property-read int|null $custom_forms_count
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $dadataCommentTxt
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $dadataText
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $dadataTxt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $dadataType
 * @property-read int|null $dadata_type_count
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $deliverBackTimeText
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $deliveryDateText
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $deliveryTimeText
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $deliveryTimes
 * @property-read int|null $delivery_times_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $deliveryType
 * @property-read int|null $delivery_type_count
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $deliveryTypeText
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $emojiStraitList
 * @property-read int|null $emoji_strait_list_count
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $likeAll
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $multiText
 * @property-read int|null $multi_text_count
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $nextQuestion
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionType|null $optionType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $options
 * @property-read int|null $options_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $optionsString
 * @property-read int|null $options_string_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $palletes
 * @property-read int|null $palletes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $personalData
 * @property-read int|null $personal_data_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $picturesGrid
 * @property-read int|null $pictures_grid_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $positions
 * @property-read int|null $positions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $recursiveOptions
 * @property-read int|null $recursive_options_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $selectStringOptions
 * @property-read int|null $select_string_options_count
 * @property-read \Illuminate\Database\Eloquent\Collection|AnketaQuestion[] $subQuestions
 * @property-read int|null $sub_questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaOptionsGroup[] $tables
 * @property-read int|null $tables_count
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionType|null $type
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestionOption|null $unlikeAll
 * @method static Builder|AnketaQuestion id($slug)
 * @method static Builder|AnketaQuestion newModelQuery()
 * @method static Builder|AnketaQuestion newQuery()
 * @method static \Illuminate\Database\Query\Builder|AnketaQuestion onlyTrashed()
 * @method static Builder|AnketaQuestion query()
 * @method static Builder|AnketaQuestion slug($id)
 * @method static Builder|AnketaQuestion whereActive($value)
 * @method static Builder|AnketaQuestion whereAppeal($value)
 * @method static Builder|AnketaQuestion whereChecked($value)
 * @method static Builder|AnketaQuestion whereCreatedAt($value)
 * @method static Builder|AnketaQuestion whereDeletedAt($value)
 * @method static Builder|AnketaQuestion whereDisclaimer($value)
 * @method static Builder|AnketaQuestion whereFbTrack($value)
 * @method static Builder|AnketaQuestion whereId($value)
 * @method static Builder|AnketaQuestion whereIsSub($value)
 * @method static Builder|AnketaQuestion whereLabelC($value)
 * @method static Builder|AnketaQuestion whereMultiple($value)
 * @method static Builder|AnketaQuestion whereOldKey($value)
 * @method static Builder|AnketaQuestion whereOwnAnswer($value)
 * @method static Builder|AnketaQuestion whereOwnPlaceholder($value)
 * @method static Builder|AnketaQuestion whereOwnType($value)
 * @method static Builder|AnketaQuestion whereParent($value)
 * @method static Builder|AnketaQuestion wherePlaceholder($value)
 * @method static Builder|AnketaQuestion whereQuestion($value)
 * @method static Builder|AnketaQuestion whereSave($value)
 * @method static Builder|AnketaQuestion whereSelectAll($value)
 * @method static Builder|AnketaQuestion whereSlug($value)
 * @method static Builder|AnketaQuestion whereSort($value)
 * @method static Builder|AnketaQuestion whereStyleImage($value)
 * @method static Builder|AnketaQuestion whereTypeId($value)
 * @method static Builder|AnketaQuestion whereUnselectAll($value)
 * @method static Builder|AnketaQuestion whereUpdatedAt($value)
 * @method static Builder|AnketaQuestion whereYaTrack($value)
 * @method static \Illuminate\Database\Query\Builder|AnketaQuestion withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AnketaQuestion withoutTrashed()
 * @mixin \Eloquent
 */
class AnketaQuestion extends Model
{
    use SoftDeletes;

    protected $table = 'anketa_questions';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('sort', 'asc');
        });
    }


    public function optionType(): HasOne
    {
        return $this->hasOne(AnketaQuestionType::class, 'id', 'type_id');
    }

    /** Секция подвопросов - те же вопросы но только относящиеся к комплесным (в которых есть ветвление вариантов, например: ДОСТАВКА)
     * @return HasMany
     */
    public function subQuestions(): HasMany
    {
        return $this->hasMany(self::class,'parent','id');
    }

    public function recursiveOptions(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')->with('recursiveQuestion')->whereNotNull('next_question');
    }

    /** Когда есть выбор мнежду разными $form->table, но ссылающихся на один и тот же вид отнощений
     * ему ($form->table) нужны разные названия методов отношений для корректной работы с этими виджетами
     * options() и positions()  и остальные одно и тоже
     * - этого нет в доках laravel-admin
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')->orderBy('sort','asc');
    }

    public function picturesGrid(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id');
    }

    public function columnListThin(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id');
    }

    public function optionsString(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'checkbox');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('anketa_question_options.type', 'checkbox');
    }

    public function palletes(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id');
    }

    public function multiText(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id');
    }

    public function emojiStraitList(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id');
    }

    public function customForms(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')
            ->whereNull('next_question')
//            ->whereIn('type',['text','textarea','date','checkbox','radio','dadata'])
            ;
    }

    public function personalData(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')
//            ->whereIn('type',['text','textarea','date','checkbox','radio','dadata'])
            ;
    }



    public function colors(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id');
    }

    public function tables(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
//        return $this->hasMany(AnketaQuestionOption::class,'question_id','id');
        return $this->belongsToMany(AnketaOptionsGroup::class, 'anketa_question_options', 'question_id', 'group_id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')->where('type', 'cities');
    }

    public function deliveryType(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')->where('type', 'deliveryType');
    }

    public function dadataType(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')->where('type', 'dadataType');
    }

    public function deliveryTimes(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')->where('type', 'deliveryTime');
    }

    public function clientPhotos(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')->where('type', 'clientPhotos');
    }

    public function selectStringOptions(): HasMany
    {
        return $this->hasMany(AnketaQuestionOption::class, 'question_id', 'id')->whereIn('type', ['selectAll', 'unselectAll']);
    }

//    public function deliveryDate(): HasOne
//    {
//        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
//            ->where('type', 'deliveryDate');
//    }
//
//    public function deliveryTime(): HasOne
//    {
//        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
//            ->where('type', 'deliveryTime');
//    }

    public function nextQuestion(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'nextQuestion');
    }

    public function dadataTxt(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'dadataTxt');
    }

    public function dadataCommentTxt(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'dadataCommentTxt');
    }

    public function coupon(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'coupon');
    }

    public function likeAll(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'likeAll');
    }

    public function unlikeAll(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'unlikeAll');
    }

//    // -----
//    public function deliverBackTime(): HasOne
//    {
//        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
//            ->where('type', 'deliverBackTime');
//    }

    /** Тип дизайна вопроса
     * @return HasOne
     */
    public function type(): HasOne
    {
        return $this->hasOne(AnketaQuestionType::class, 'id', 'type_id');
    }

    /**
     * Для блока доставки
     * @return HasOne
     */

    public function chooseCityText(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'chooseCityText');
    }

    public function deliveryTypeText(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'deliveryTypeText');
    }

    // -----
    public function deliveryTimeText(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'deliveryTimeText');
    }

    // -----
    public function deliverBackTimeText(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'deliverBackTimeText');
    }

    // -----
    public function dadataText(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'dadataText');
    }

    // -----
    public function boxberryCityText(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'boxberryCityText');
    }

    // -----
    public function boxberryText(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'boxberryText');
    }

    // -----
    public function deliveryDateText(): HasOne
    {
        return $this->hasOne(AnketaQuestionOption::class, 'question_id', 'id')
            ->where('type', 'deliveryDateText');
    }



    // Scopes

    public function scopeSlug($query, $id) {
        return $query->where('id', $id)->first()->slug;
    }

    public function scopeId($query, $slug) {
        return $query->where('slug', $slug)->first()->id;
    }




}
