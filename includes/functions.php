<?php

function debug($data) {
  echo '<pre>' . print_r($data, true) . '</pre>';
}


/**
 *Загружает дополнительные поля с данными, которые ввёл пользователь в объект $fields из data.php.
 *Также попутно обрабатываем, например тримем
 * @param  array $data
 * @return array
 */
function load($data) {
  foreach ($_POST as $key => $value) {
    if (array_key_exists($key, $data)) {
      $data[$key]['value'] = trim($value);
    }
  }
  return $data;
}

/**
 * Валидирует полученные данные, если есть ошибка, то это поле добавляется в $errors
 *
 * @param  array $data
 * @return bool
 */
function validate($data) {
  $errors = '';
  foreach($data as $key => $value) {
    if($data[$key]['required'] && empty($data[$key]['value'])) {
      $errors .= "<li>Вы не заполнили поле {$data[$key]['field_name']}</li>";
    }
  }
  if (!check_captcha($data['captcha']['value'])) {
    $errors .= "<li>Неверно заполнено поле Captcha</li>";
  }
  return $errors;
}

/**
 * простенькая captcha
 */
function set_captcha() {
  $num1 = rand(0, 100);
  $num2 = rand(0, 100);
  $_SESSION['captcha'] = $num1 + $num2;
  return "Сколько будет {$num1} + {$num2}?";
}

function check_captcha($res) {
  return $_SESSION['captcha'] == $res;
}

function send_mail($fields, $mail_settings) {
  $mail = new \PHPMailer\PHPMailer\PHPMailer();

  try {
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = $mail_settings['host'];                     //Set the SMTP server to send through
    $mail->SMTPAuth   = $mail_settings['smtp_auth'];                                   //Enable SMTP authentication
    $mail->Username   = $mail_settings['username'];                     //SMTP username
    $mail->Password   = $mail_settings['password'];                               //SMTP password
    $mail->SMTPSecure = $mail_settings['smtp_secure'];            //Enable implicit TLS encryption
    $mail->Port       = $mail_settings['port'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($mail_settings['from_email'], $mail_settings['from_name']);
    $mail->addAddress($mail_settings['to_email']);     //Add a recipient

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Форма с сайта';


    $flag = true;
    $message = '';

    foreach ($fields as $key => $value) {
      if (isset($value['mailable']) && $value['mailable'] == 0) {
        continue;
      }
      $message .= (($flag = !$flag) ? '<tr>' : '<tr style="background-color: #f8f8f8;">') .
        "<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>{$value['field_name']}</b></td>
        <td style='padding: 10px; border: #e9e9e9 1px solid;'>{$value['value']}</td></tr>";
    }
    $mail->Body = "<table style='width: 100%;'>$message</table>";

    if (!$mail->send()) {
      return false;
    };
    return true;
  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      return false;
  }
}
