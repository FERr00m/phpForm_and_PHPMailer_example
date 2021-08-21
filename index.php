<?php

session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/functions.php';

if (!empty($_POST)) {
  $fields = load($fields);
  if($errors = validate($fields)) {
    $res = ['answer' => 'error', 'errors' => $errors];
  } else {
    if (!send_mail($fields, $mail_settings)) {
      $res = ['answer' => 'error', 'errors' => 'Ошибка отправки письма'];
    } else {
      $res = ['answer' => 'ok', 'captcha' => set_captcha()];
    }
  }
  exit(json_encode($res));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/main.css">
  <title>Document</title>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <form method="post" id="form" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="name" class="form-label">Имя</label>
            <input type="text" name="name" class="form-control" id="name" required>
            <div class="invalid-feedback">
              Пожалуйста заполните имя.
            </div>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" required>
            <div class="invalid-feedback">
              Пожалуйста заполните email.
            </div>
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Адрес</label>
            <input type="text" name="address" class="form-control" id="address">
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Телефон</label>
            <input type="text" name="phone" class="form-control" id="phone" required>
            <div class="invalid-feedback">
              Пожалуйста заполните телефон.
            </div>
          </div>
          <div class="mb-3">
            <label for="comment" class="form-label">Сообщение</label>
            <textarea name="comment" class="form-control" id="comment" rows="3"></textarea>
          </div>
          <?//один из вариантов защиты от ботов добавить скрытое поле и проверять его на заполнение в $_POST['test']
            //<input type="hidden" name="test">
            /**
             * if (!empty($_POST['test'])) {
             *    die('BOT');
             * }
             */

             //Другой вариант это captcha ниже
          ?>

          <div class="mb-3">
            <label for="captcha" id="label-captcha" class="form-label"><?= set_captcha();?></label>
            <input type="text" name="captcha" class="form-control" id="captcha" required>
            <div class="invalid-feedback">
              Пожалуйста заполните captcha.
            </div>
          </div>


          <div class="mb-3 form-check">
            <input type="checkbox" name="checkbox" class="form-check-input" id="exampleCheck1" required>
            <label class="form-check-label" for="exampleCheck1">Я согласен на обработку данных</label>
            <div class="invalid-feedback">
              Пожалуйста дайте согласие.
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Отправить</button>

          <div class="mt-3" id="answer"></div>

          <div class="loader">
            <img src="/img/radio.svg" alt="loader">
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="/js/main.js"></script>
</body>
</html>
