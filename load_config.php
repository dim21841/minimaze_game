<?php
// load_config.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$configFile = __DIR__ . '/maze_config.json';

if (file_exists($configFile)) {
    $content = file_get_contents($configFile);
    if ($content !== false) {
        echo $content;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to read config file']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Config file not found']);
}
?>
