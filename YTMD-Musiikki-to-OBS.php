<?php

$curl = curl_init('http://YTMDESKTOP IP:9863');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$page = curl_exec($curl);
if (curl_errno($curl)) {
    echo 'Error: ' . curl_error($curl);
    exit;
}
curl_close($curl);

$DOM = new DOMDocument;
libxml_use_internal_errors(true);

if (!$DOM->loadHTML($page)) {
    $errors = '';
    foreach (libxml_get_errors() as $error) {
        $errors .= $error->message . "\r\n";
    }

    libxml_clear_errors();
    print "LibXML Errors: \r\n$errors";
    return;
}

$Xpath = new DOMXPath($DOM);

$content = $Xpath->query('//*[@class="truncate"]')->item(0);
?>
<html>
<head>
<meta http-equiv="refresh" content="5">

<style>
p {
  width: 1500px;
  border: 5px solid white;
  padding: 50px;
  margin: 20px;
  font-size: 50px;
  color: #ff7200;
}
</style>
</head>

<body style="background-color: black;">
<br>
<br>
<p>
Nyt Soi:<br>
<?php echo htmlspecialchars($content->textContent, ENT_QUOTES, 'UTF-8'); ?>
</p>
</body>
</html>
