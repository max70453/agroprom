<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');

$errors = [];

if (empty($name)) {
    $errors[] = 'Имя обязательно';
}

if (empty($phone)) {
    $errors[] = 'Телефон обязателен';
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Неверный формат email';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

$to = 'kkxp@mail.ru';
$subject = 'Заявка с сайта Каскад-Агро';
$message = "Имя: $name\nТелефон: $phone\nEmail: $email\nДата: " . date('d.m.Y H:i');
$headers = "From: noreply@kaskad-agro.ru\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";

$mailSent = mail($to, $subject, $message, $headers);

$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
$logFile = $logDir . '/contacts_' . date('Y-m-d') . '.log';
$logEntry = date('Y-m-d H:i:s') . " | Name: $name | Phone: $phone | Email: $email\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

if ($mailSent) {
    echo json_encode(['success' => true, 'message' => 'Заявка отправлена! Мы свяжемся с вами в ближайшее время.']);
} else {
    echo json_encode(['success' => false, 'errors' => ['Ошибка отправки. Попробуйте позже.']]);
}
