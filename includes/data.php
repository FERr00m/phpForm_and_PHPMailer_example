<?php

$fields = [
  'name' => [
    'field_name' => 'Имя',
    'required' => true,
  ],
  'email' => [
    'field_name' => 'Email',
    'required' => true,
  ],
  'address' => [
    'field_name' => 'Адрес',
    'required' => false,
  ],
  'phone' => [
    'field_name' => 'Телефон',
    'required' => true,
  ],
  'comment' => [
    'field_name' => 'Комментарий',
    'required' => false,
  ],
  'checkbox' => [
    'field_name' => 'Согласие на обработку данных',
    'required' => true,
    'mailable' => false, //Ключ указывает надо ли поле отправлять в письме
  ],
  'captcha' => [
    'field_name' => 'Captcha',
    'required' => true,
    'mailable' => false,
  ],
];

//Данные из mailtrap.io для тестов
$mail_settings = [
  'host' => 'smtp.mailtrap.io',
  'smtp_auth' => true,
  'username' => '',
  'password' => '',
  'smtp_secure' => null, //ssl (нужно установить, когда реальная почта)
  'port' => 2525,
  'from_email' => '',
  'from_name' => 'My Site',
  'to_email' => 'user@mail.com',
];
