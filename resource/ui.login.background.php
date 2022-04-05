<?php


$file = 'resource/login-bg.png';

if (file_exists($file)) {
	http_response_code(200);
    header('Content-Description: Image File');
    header("content-type: image/png");
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
} else {
	http_response_code(404);
}
?>