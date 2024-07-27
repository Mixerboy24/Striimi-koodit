<?php
$api_key = 'API KEY';
$octoprint_url = 'http://URL-OR-IP:PORT/api/job'; // example: 192.168.1.2:5000

function fetch_octoprint_data($url, $api_key) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Api-Key: ' . $api_key));
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPGET, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }

    curl_close($ch);

    return json_decode($response, true);
}

$data = fetch_octoprint_data($octoprint_url, $api_key);

if ($data === null) {
    die('Failed to fetch data from Octoprint API.');
}

$filename = isset($data['job']['file']['name']) ? $data['job']['file']['name'] : 'Unknown';
$progress = isset($data['progress']['completion']) ? (float)$data['progress']['completion'] : 0;

$progress = min(max($progress, 0), 100);
$progress_formatted = number_format($progress, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Octoprint Status</title>
    <style>
        .progress-bar-container {
            width: 50%;
            max-width: 300px;
            background-color: #f3f3f3;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
            margin-left: 0;
            margin-right: auto;
        }
        .progress-bar-fill {
            height: 20px;
            background-color: #00D4FF; /* Filling color */
            width: <?= $progress ?>%;
            position: relative;
        }
        .progress-bar-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #000;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <p>Printing: <?= htmlspecialchars($filename) ?></p>
    <div class="progress-bar-container">
        <div class="progress-bar-fill">
            <div class="progress-bar-text"><?= $progress_formatted ?>%</div>
        </div>
    </div>
</body>
</html>
