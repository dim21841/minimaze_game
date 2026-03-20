<?php
// save_config.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Обработка OPTIONS запроса (для CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Проверяем, что это POST запрос
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

// Получаем данные из тела запроса
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['config'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No config data received']);
    exit();
}

// Путь к файлу конфигурации
$configFile = __DIR__ . '/maze_config.json';

// Сохраняем файл
try {
    $jsonString = json_encode($input['config'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    if (file_put_contents($configFile, $jsonString)) {
        // Устанавливаем права
        chmod($configFile, 0644);
        
        echo json_encode([
            'success' => true,
            'message' => 'Configuration saved successfully',
            'file' => 'maze_config.json',
            'path' => $configFile
        ]);
    } else {
        throw new Exception('Failed to write file');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
