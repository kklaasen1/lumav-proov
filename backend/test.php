<?php
require __DIR__ . '/../vendor/autoload.php';

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];

function scrapeWebsite($url)
{
    $client = new Client(HttpClient::create(['timeout' => 30]));
    
    $crawler = $client->request('GET', $url);

    $categories = $crawler->filter('div.category-class')->each(function ($node) {
        return trim($node->text());
    });

    return $categories;
}

if ($requestMethod === 'GET') {
    if (isset($_GET['site'])) {
        $site = $_GET['site'];
        
        try {
            $categories = scrapeWebsite($site);

            if (!empty($categories)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => "Fetched data from $site",
                    'categories' => $categories
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => "No categories found on the site"
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No site parameter provided'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Only GET requests are supported'
    ]);
}
?>


<!-- http://localhost/lumav-proov/backend/api.php?site=example.com -->