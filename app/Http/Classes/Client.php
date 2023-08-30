<?php

namespace App\Http\Classes;

use \App\Http\Models\AdminClient\Client as ModalClient;
use App\Http\Models\AdminClient\ClientStatus;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


/**
 * App\Http\Classes\Client
 *
 * @property string $uuid
 * @property string|null $login
 * @property string|null $password
 * @property string|null $name
 * @property string|null $second_name
 * @property string|null $patronymic
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $comments
 * @property string|null $socialmedia_links
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $client_status_id
 * @property int|null $amo_client_id
 * @property string|null $referal_code
 * @property string|null $address
 * @property-read \App\Http\Models\Common\Bonus $bonuses
 * @property-read ClientStatus|null $client_status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\AdminClient\FeedbackgeneralQuize[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Common\Lead[] $leads
 * @property-read int|null $leads_count
 * @method static \Illuminate\Database\Eloquent\Builder|Client fio($uuid)
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAmoClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereClientStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereReferalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereSecondName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereSocialmediaLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUuid($value)
 * @mixin \Eloquent
 * @property string|null $auth_token Токе авторизации клиента
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Classes\Client whereAuthToken($value)
 */
class Client extends ModalClient
{
    public $name;
    public $second_name;
    public $phone;
    public $email;
    public $uuid;
    public $comments;
    public $search_text;
    public $referal_code;
    public $status;
    public $client_status_id;

    public $rules= [

        'add' => [
            'name' => 'required | string| max:255',
            'second_name' => 'string | nullable | max:255 | nullable',
            'phone' => 'required | regex:/[0-9_]+/i | unique:clients',
            'email' => 'string | max:255 | email | nullable | unique:clients',
            'comments' => 'string | max:2000 | nullable',
            'referal_code' => 'string | nullable | max:15 | unique:clients',
            'socialmedia_links' => 'string | max:255 | nullable',
            'status' => 'exists:client_statuses,id| nullable'

        ],

        'edit' => [
            'name' => 'required | string| max:255',
            'second_name' => 'string | max:255 | nullable',
            'phone' => ['required', 'regex:/[0-9_]+/i'],
            'email' => ['string', 'max:255', 'email', 'nullable'],
            'comments' => 'string | max:2000 | nullable',
            'referal_code' => ['string', 'nullable', 'max:15'],
            'socialmedia_links' => 'string | max:255 | nullable',
            'status' => 'exists:client_statuses,id| nullable'
        ],

        'uuid' => ['uuid' => 'required | uuid'],
        'search' =>['search_text'=>'required | string| max:255'],
        'search_advanced' => [
            'name' => 'nullable | string| max:255',
            'second_name' => 'nullable  | string | max:255',
            'phone' => 'nullable | numeric ',
            'email' => 'nullable | string | max:255 | email',
            'uuid' => 'nullable | string | uuid'
        ],
    ];

    public $messages = [
        'add' => [
            'phone.unique' => 'Клиент с таким номером телефона уже существеут',
            'email.unique' => 'Клиент с таким email уже существеут',
            'referal_code.unique' => 'Такой реферальный код уже существует',
            'phone.required' => 'Ошибка ввода для номера телефона',
            'phone.numeric' => 'Ошибка ввода для номера телефона',
            'email.email' => 'email не соответствует формату',
            'status.exists' => 'статус клиента не соответствует значению из справочника'
        ],
        'edit' => [
            'phone.required' => 'Ошибка ввода для номера телефона',
            'phone.numeric' => 'Ошибка ввода для номера телефона',
            'email.email' => 'email не соответствует формату',
            'status.exists' => 'статус клиента не соответствует значению из справочника'
        ],
        'search_advanced' => [
            'phone.numeric' => 'Ошибка ввода для номера телефона',
            'email.email' => 'email не соответствует формату',
            'uuid' => 'ID не соответствует формату'
        ]
    ];

    public function __construct(array $attributes = [])
    {
        $this->name = $attributes['name'] ??  null;
        $this->second_name = $attributes['second_name'] ??  null;
        $this->phone = $attributes['phone'] ??  null;
        $this->email = $attributes['email'] ??  null;
        $this->search_text = $attributes['search_text'] ??  null;
        $this->uuid = $attributes['uuid'] ??  null;
        $this->comments = $attributes['comments'] ??  null;
        $this->client_status_id = $attributes['status'] ??  null;
        $this->socialmedia_links = $attributes['socialmedia_links'] ??  null;
        $this->referal_code = $attributes['referal_code'] ??  null;
        if($this->referal_code !== null) $this->referal_code = (string)$this->referal_code;
        parent::__construct($attributes);
    }

    /**
     * Добавление нового клиента
     *
     * @param bool $need_validate
     * @return array
     */

    public function add( $need_validate = true )
    {
        try {
            $this->create([
                'name' => $this->name,
                'second_name' => $this->second_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'comments' => $this->comments,
                'referal_code' => $this->referal_code,
                'socialmedia_links' => $this->socialmedia_links,
                'client_status_id' => $this->client_status_id
            ]);
        }

        catch (QueryException $ex) {

            return [
                'result' => false,
                'errors' => $ex->errorInfo
            ];
        }

        return [
            'result' => true,
            'new_client' => $this->toArray()
        ];
    }

    /**
     * Удаление клиента
     *
     * @param  string uuid
     * @return array
     */

    public function drop($uuid)
    {
        $this->uuid = $uuid;

        $validate_res = $this->validate('uuid', ['uuid'=> $this->uuid]);

        if ($validate_res !== true) {
            return [
                'result' => false,
                'errors' => $validate_res->toArray()
            ];
        }

        $res = $this->where('uuid',$this->uuid)->delete();

        switch($res)
        {
            case false:
                return [
                    'result' => false,
                    'errors' => 'запись уже удалена или не существует'
                ];

            case true:
                return [
                    'result' => true,
                ];
        }
    }

    /**
     * Редактирование существующего Клиента
     *
     * @param string $uuid
     * @param bool $need_validate
     * @return array
     */

    public function edit( $uuid, $need_validate = true)
    {
        if(parent::find($uuid) === null) return  [
            'result' => false,
            'errors' => 'id '.$uuid.' not found'
        ];

        $this->rules['edit']['phone'][] = Rule::unique('clients', 'phone')->ignore($uuid,'uuid');
        $this->rules['edit']['email'][] = Rule::unique('clients', 'email')->ignore($uuid,'uuid');
        $this->rules['edit']['referal_code'][] = Rule::unique('clients', 'referal_code')->ignore($uuid,'uuid');

        if($need_validate)
        {
            $validate_res = $this->validate('edit');

            if ($validate_res !== true) {
                return [
                    'result' => false,
                    'errors' => $validate_res->toArray()
                ];
            }

        }

        try {
            $this->where('uuid', $uuid)->update([
                'name' => $this->name,
                'second_name' => $this->second_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'comments' => $this->comments,
                'referal_code' => $this->referal_code,
                'socialmedia_links' => $this->socialmedia_links,
                'client_status_id' => $this->client_status_id,
            ]);
        }
        catch (QueryException $ex)
        {
            return [
                'result' => false,
                'errors' => $ex->errorInfo
            ];
        }


        return [
            'result' => true
        ];
    }

    /**
     * поиск Клиента по всем полям
     *
     * @param bool $need_validate
     * @return array
     */

    public function search( $need_validate = true)
    {
        if($need_validate)
        {
            $validate_res = $this->validate('search');

            if ($validate_res !== true) {
                return [
                    'result' => false,
                    'errors' => $validate_res->toArray()
                ];
            }
        }

        $search_text = $this->search_text;
        $res= $this
            ->where('uuid',  'LIKE' , "%{$this->search_text}%")
            ->orWhere('name', 'LIKE' , "%{$this->search_text}%")
            ->orWhere('second_name', 'LIKE' , "%{$this->search_text}%")
            ->orWhere('phone', 'LIKE' , "%{$this->search_text}%")
            ->orWhere('email', 'LIKE' , "%{$this->search_text}%")
            ->orderBy('updated_at')
            ->limit(100)
            ->get()->toArray();

        switch($res)
        {
            case []:
                return [
                    'result' => false,
                    'search_result' => []
                ];

            case true:
                return [
                    'result' => true,
                    'search_result' => $res
                ];
        }
    }

    /**
     * поиск Клиента по выбранным
     *
     * @param bool $need_validate
     * @return array
     */

    public function searchAdvanced( $need_validate = true)
    {
        if($need_validate)
        {
            $validate_res = $this->validate('search_advanced', [
                'name' => $this->name,
                'second_name' => $this->second_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'uuid' => $this->uuid
                ]);

            if ($validate_res !== true) {
                return [
                    'result' => false,
                    'errors' => $validate_res->toArray()
                ];
            }
        }

        $res = $this;
        if($this->uuid)
            $res = $this->where('uuid', $this->uuid);
        if($this->name)
            $res = $this->where('name', $this->name);
        if($this->second_name)
            $res = $res->where('second_name', $this->second_name);
        if($this->phone)
            $res = $res->where('phone', $this->phone);
        if($this->email)
            $res = $res->where('email', $this->email);

        $res = $res->limit(20)->get()->toArray();

        switch($res)
        {
            case []:
                return [
                    'result' => false,
                    'search_advanced_result' => []
                ];

            case true:
                return [
                    'result' => true,
                    'search_advanced_result' => $res
                ];
        }
    }

    public function prepareForValidation()
    {
        $this->name = $this->formatAttribute($this->name);
        $this->second_name = $this->formatAttribute($this->second_name);
        if($this->phone!= '') $this->formatPhone();
        $this->email = $this->formatAttribute($this->email);
        $this->uuid = $this->formatAttribute($this->uuid);
        $this->search_text = $this->formatAttribute($this->search_text);
        $this->comments = $this->formatAttribute($this->comments);
        $this->referal_code = $this->formatAttribute($this->referal_code);
        $this->socialmedia_links = $this->formatAttribute($this->socialmedia_links);
        $this->status = $this->formatAttribute($this->status);
    }

    public function formatPhone()
    {
        $this->phone = trim($this->phone);
        $this->phone = Common::format_phone($this->phone);
    }

    public function formatAttribute($value)
    {
        return trim($value);
    }

    public function validate($rule_name)
    {
        $this->prepareForValidation();

        $validator = Validator::make([
            'name' => $this->name,
            'second_name' => $this->second_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'uuid' => $this->uuid,
            'comments' => $this->comments,
            'search_text' => $this->search_text,
            'referal_code' => $this->referal_code,
            'client_status_id' => $this->client_status_id,
            'socialmedia_links' => $this->socialmedia_links,
            'status' => $this->status,
        ], $this->rules[$rule_name]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return true;
    }

    static function createReferalCode()
    {
        $code = null;

        do {
            $code = mt_rand(100000, 999999);
            $res = DB::table('clients')->select('referal_code')->where('referal_code', $code)->first();
        } while($res!==null);

        return (string)$code;
    }

}