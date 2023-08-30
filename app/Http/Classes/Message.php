<?php

namespace App\Http\Classes;

/**
 * Класс транслитерации ошибок при валидации данных
 * Class Message
 * @package App\Http\Classes
 */
class Message
{

    /**
     * Описание ошибок валидации для всех основных параметров
     * @return array
     */
    public static function messages()
    {
        return [
            'phone.required' => '*Ошибка ввода: Номер телефона',
            'phone.max' => '*не может быть более :max символов',
            'phone.min' => '*не может быть менее :min символов',
            'phone.regex' => 'Телефон не соответствует формату',
            'name.regex' => 'Ф.И.О не соответствует формату',
             'message.required' => '*Ошибка ввода: текст сообщения',
            'file1.mimes' => '*Поддерживаемые типы фалов: gif,jpg,png,doc,docx,xls,xlsx,pdf',
            'file1.required' => '*Прикрепите файл',
            'file1.max' => '*Файл не должен быть размером более 1Мб',
            'file2.mimes' => '*Поддерживаемые типы фалов: gif,jpg,png,doc,docx,xls,xlsx,pdf',
            'attach_1.mimes' => '*Поддерживаемые типы фалов: jpeg,jpg,gif,png,doc,docx,xlsx,xls,ods,odt,odp',
            'attach_2.mimes' => '*Поддерживаемые типы фалов: jpeg,jpg,gif,png,doc,docx,xlsx,xls,ods,odt,odp',
            'file2.max' => '*Файл не должен быть размером более 1Мб',
            'file.mimes' => 'Поддерживаемые типы фалов: gif,jpg,png',
            'file.required' => 'Прикрепите файл',
            'file.max' => 'Файл не должен быть размером более :max',
            'number.required' => 'Номер  заполнено неверно',
            'number.min' => 'Номер  не может быть менее :min',
            'number.max' => 'Номер  не может быть более :max',
            'color.required' => 'Цвет  заполнено неверно',
            'color.min' => 'Номер  не может быть менее :min',
            'color.max' => 'Номер  не может быть более :max',
            'brand.required' => 'Марка не выбрана',
            'model.required' => 'Марка  не выбрана',
            '*.required' => '*поле обязательно к заполнению',
            '*.max' => '*не может быть более :max символов',
            '*.min' => '*не может  быть менее :min символов',
            '*.date' => '*некорректная дата',
            '*.mimes' => '*разрешенные форматы: :mimes',
            'agreed.required' => 'Не подтверждено пользовательское соглашение!',
            'login.unique' => 'Логин уже занят!',
            '*.between' => 'Должен быть в пределах от :min до :max',
            '*.unique' => 'Должно быть уникальным значением, уже присутствует в БД',
        ];
    }


    /**
     * @deprecated
     *
     * @return array
     */
    public static function messages_()
    {
        return [
            '*.required' => '*поле обязательно к заполнению',
            '*.max' => '*не может быть более :max символов',
            '*.min' => '*не может  быть менее :min символов',
            '*.date' => '*некорректная дата',
            '*.mimes' => '*не разрешенный формат'
        ];
    }

    /**
     * Дополнительное описание ошибок валидации при сбросе пароля
     * @return array
     */
    public static function ResetPassword()
    {
        return [
            '*.required' => 'Пароль обязательно к заполнению!',
            '*.max' => 'Пароль не может быть более :max символов!',
            '*.min' => 'Пароль не может  быть менее :min символов!',
            '*.confirmed' => 'Пароли должны быть индентичны!'
        ];
    }

    /**
     * Описание ошибок валидации при авторизации
     * @return array
     */
    public static function login()
    {
        return [
            'locked' => 'Слишком много попыток авторизации! Вход в аккаунт заблокирован на 10 минут',
            'error' => 'Пользователя не существует или ошибка при вводе данных!'
        ];
    }

}
