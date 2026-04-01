<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$animalType = trim($_POST['animalType'] ?? '');
$breed = trim($_POST['breed'] ?? '');
$productivity = trim($_POST['productivity'] ?? '');
$age = trim($_POST['age'] ?? '');
$volume = trim($_POST['volume'] ?? '');
$deliveryRegion = trim($_POST['deliveryRegion'] ?? '');
$specialRequirements = trim($_POST['specialRequirements'] ?? '');
$contactName = trim($_POST['contactName'] ?? '');
$contactPhone = trim($_POST['contactPhone'] ?? '');
$contactEmail = trim($_POST['contactEmail'] ?? '');

$errors = [];

if (empty($animalType)) {
    $errors[] = 'Выберите вид животного';
}

if (empty($breed)) {
    $errors[] = 'Укажите породу';
}

if (empty($contactName)) {
    $errors[] = 'Имя обязательно';
}

if (empty($contactPhone)) {
    $errors[] = 'Телефон обязателен';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

$animalTypes = [
    'poultry' => 'Птица (куры, бройлеры)',
    'poultry_laying' => 'Птица (несушки)',
    'pigs' => 'Свиньи',
    'cattle' => 'КРС',
    'rabbits' => 'Кролики',
    'quail' => 'Перепела',
    'turkey' => 'Индейки',
    'fish' => 'Рыба'
];

$productivityLevels = [
    'high' => 'Высокий',
    'medium' => 'Средний',
    'low' => 'Низкий'
];

$agePeriods = [
    'starter' => 'Стартер',
    'grower' => 'Гровер',
    'finisher' => 'Финишер',
    'maintenance' => 'Поддерживающий'
];

$animalTypeText = $animalTypes[$animalType] ?? $animalType;
$productivityText = $productivityLevels[$productivity] ?? $productivity;
$ageText = $agePeriods[$age] ?? $age;

$to = 'kkxp@mail.ru';
$subject = 'Заявка на расчёт рецепта - Каскад-Агро';
$message = "Заявка на расчёт рецепта комбикорма\n\n";
$message .= "Вид животного: $animalTypeText\n";
$message .= "Порода: $breed\n";
$message .= "Уровень продуктивности: $productivityText\n";
$message .= "Возраст/период: $ageText\n";
$message .= "Объём: $volume тонн/месяц\n";
$message .= "Регион доставки: $deliveryRegion\n";
$message .= "Особые требования: $specialRequirements\n\n";
$message .= "Контактные данные:\n";
$message .= "Имя: $contactName\n";
$message .= "Телефон: $contactPhone\n";
$message .= "Email: $contactEmail\n";
$message .= "Дата: " . date('d.m.Y H:i');

$headers = "From: noreply@kaskad-agro.ru\r\nReply-To: $contactEmail\r\nContent-Type: text/plain; charset=UTF-8";

$mailSent = mail($to, $subject, $message, $headers);

$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
$logFile = $logDir . '/calculator_' . date('Y-m-d') . '.log';
$logEntry = date('Y-m-d H:i:s') . " | Animal: $animalTypeText | Breed: $breed | Volume: $volume t | Name: $contactName | Phone: $contactPhone\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

if ($mailSent) {
    echo json_encode(['success' => true, 'message' => 'Заявка отправлена! Наши технологи свяжутся с вами в ближайшее время.']);
} else {
    echo json_encode(['success' => false, 'errors' => ['Ошибка отправки. Попробуйте позже.']]);
}
