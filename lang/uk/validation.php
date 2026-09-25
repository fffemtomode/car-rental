<?php

return [
    'required' => 'Поле :attribute обов\'язкове для заповнення.',
    'date' => 'Поле :attribute повинно бути коректною датою.',
    'after' => 'Поле :attribute повинно бути пізніше за :date.',
    'after_or_equal' => 'Поле :attribute повинно бути не раніше :date.',
    'numeric' => 'Поле :attribute повинно бути числом.',
    'integer' => 'Поле :attribute повинно бути цілим числом.',
    'min' => [
        'numeric' => 'Поле :attribute повинно бути не менше :min.',
    ],
    'max' => [
        'numeric' => 'Поле :attribute повинно бути не більше :max.',
        'string' => 'Поле :attribute повинно бути не довше :max символів.',
        'file' => 'Розмір файлу :attribute не повинен перевищувати :max Кб.',
    ],
    'image' => 'Файл :attribute повинен бути зображенням.',
    'in' => 'Обране значення поля :attribute некоректне.',

    'attributes' => [
        'start_date' => 'дата початку',
        'end_date' => 'дата завершення',
        'amount' => 'сума',
        'leasing_months' => 'строк лізингу',
        'brand' => 'марка',
        'model' => 'модель',
        'year' => 'рік',
        'price_per_day' => 'ціна оренди',
        'buyout_price' => 'ціна викупу',
        'photos.*' => 'фото',
        'phone' => 'телефон',
        'email' => 'email',
        'password' => 'пароль',
    ],
];
