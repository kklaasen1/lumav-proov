<?php
header("Content-Type: application/json");

$apiKey = "your_api_key"; // Replace with a secure key
if (!isset($_GET['api_key']) || $_GET['api_key'] !== $apiKey) {
    http_response_code(403);
    echo json_encode(["error" => "Forbidden"]);
    exit();
}

function crawl($url) {
    $html = file_get_contents($url);
    if ($html === false) {
        return ["error" => "Could not fetch the URL"];
    }

    $doc = new DOMDocument();
    @$doc->loadHTML($html);

    $data = [
        'title' => $doc->getElementsByTagName('title')->item(0)->textContent,
        'products' => [],
        'categories' => [],
    ];

    $productElements = $doc->getElementsByTagName('h2');
    foreach ($productElements as $element) {
        $data['products'][] = $element->textContent;
    }
    
    return $data;
}

$urls = file('urls.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$results = [];

foreach ($urls as $url) {
    $results[$url] = crawl(trim($url));
}

echo json_encode($results);
