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
$page = trim($_POST['page'] ?? 'unknown');

$errors = [];

if (empty($name)) {
    $errors[] = 'Имя обязательно';
}

if (empty($phone)) {
    $errors[] = 'Телефон обязателен';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

$to = 'kkxp@mail.ru';
$subject = 'Заказ звонка - Каскад-Агро';
$message = "Заказ звонка с сайта Каскад-Агро\n\n";
$message .= "Имя: $name\n";
$message .= "Телефон: $phone\n";
$message .= "Страница: $page\n";
$message .= "Дата: " . date('d.m.Y H:i');

$headers = "From: noreply@kaskad-agro.ru\r\nContent-Type: text/plain; charset=UTF-8";

$mailSent = mail($to, $subject, $message, $headers);

$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
$logFile = $logDir . '/callback_' . date('Y-m-d') . '.log';
$logEntry = date('Y-m-d H:i:s') . " | Name: $name | Phone: $phone | Page: $page\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

if ($mailSent) {
    echo json_encode(['success' => true, 'message' => 'Спасибо! Мы перезвоним вам в ближайшее время.']);
} else {
    echo json_encode(['success' => false, 'errors' => ['Ошибка отправки. Попробуйте позже.']]);
}
