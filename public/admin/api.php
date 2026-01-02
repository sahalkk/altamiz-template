<?php
/**
 * Admin API for Our Mission Content
 * Handles saving and loading of Our Mission page content
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$contentFile = __DIR__ . '/content.json';

// GET request - Load content
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($contentFile)) {
        $content = file_get_contents($contentFile);
        echo $content;
    } else {
        // Return default content if file doesn't exist
        $defaultContent = [
            'pageSubtitle' => 'Focused on service excellence',
            'pageTitle' => 'Our Mission',
            'missionStatement' => 'To provide professional services that consistently meet our clients\' expectations through safety, quality and on‑time delivery — honoring commitments and being a partner they can rely on across Saudi Arabia.',
            'howWeDeliverTitle' => 'How We Deliver',
            'howWeDeliverPara1' => 'Our mission is to deliver safe, high‑quality and on‑schedule work by integrating disciplined planning, skilled manpower and a well‑maintained fleet — enabling fast mobilization and predictable outcomes.',
            'howWeDeliverPara2' => 'We communicate clearly, supervise rigorously and resolve issues early so our clients experience a smooth, reliable project from start to finish.',
            'benefit1' => 'Service excellence and client focus',
            'benefit2' => 'Quality workmanship, verified and documented',
            'benefit3' => 'On‑time delivery with transparent reporting',
            'chairmanMessage' => 'Al Tameez Al Thaqib began with a dream and has stood the test of time. By putting people and customers at the center, adopting world‑class technology and simple, reliable processes, we deliver consistent results. Our commitment to quality, cost‑competitiveness and continuous improvement has earned the trust of our clients. We do not just create the best solutions — we build lasting relationships.',
            'chairmanMessageLabel' => 'Chairman\'s Message'
        ];
        echo json_encode($defaultContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    exit();
}

// POST request - Save content
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if ($data === null) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
        exit();
    }
    
    // Validate required fields
    $requiredFields = ['pageSubtitle', 'missionStatement', 'howWeDeliverTitle', 'howWeDeliverPara1', 'howWeDeliverPara2'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Field '$field' is required"]);
            exit();
        }
    }
    
    // Sanitize data
    $sanitized = [];
    foreach ($data as $key => $value) {
        $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
    
    // Save to file
    $jsonContent = json_encode($sanitized, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    // Create directory if it doesn't exist
    $dir = dirname($contentFile);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    if (file_put_contents($contentFile, $jsonContent) !== false) {
        echo json_encode(['success' => true, 'message' => 'Content saved successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to save content']);
    }
    exit();
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
?>

