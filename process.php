<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $imageData = $_POST['image_data'] ?? '';

    // Проверка данных
    if (empty($fullName) || empty($email) || empty($imageData) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Некорректные данные.');
    }

    // Декодирование изображения
    $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $image = base64_decode($imageData);

    // Сохранение изображения
    $filePath = 'snapshots/' . uniqid() . '.jpg';
    if (!file_exists('snapshots')) {
        mkdir('snapshots', 0777, true);
    }
    file_put_contents($filePath, $image);

    // Отправка e-mail
    $subject = 'Ваш снимок с веб-камеры';
    $message = "Здравствуйте, $fullName!\n\nВаш снимок приложен к этому письму.";
    $headers = "From: no-reply@example.com";

    // Добавление вложения
    $boundary = md5(uniqid());
    $headers .= "\nMIME-Version: 1.0\nContent-Type: multipart/mixed; boundary=\"$boundary\"";

    $body = "--$boundary\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\n\n";
    $body .= "$message\n\n";

    $body .= "--$boundary\n";
    $body .= "Content-Type: image/jpeg; name=\"" . basename($filePath) . "\"\n";
    $body .= "Content-Transfer-Encoding: base64\n";
    $body .= "Content-Disposition: attachment; filename=\"" . basename($filePath) . "\"\n\n";
    $body .= chunk_split(base64_encode(file_get_contents($filePath))) . "\n";
    $body .= "--$boundary--";

    // Отправка письма
    if (mail($email, $subject, $body, $headers)) {
        echo 'Снимок успешно отправлен!';
    } else {
        echo 'Ошибка отправки письма.';
    }
}
