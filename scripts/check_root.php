<?php
$ctx = stream_context_create(["http"=>["ignore_errors"=>true]]);
$r = @file_get_contents('http://127.0.0.1:8000', false, $ctx);
if (isset($http_response_header)) {
    foreach ($http_response_header as $h) {
        echo $h . PHP_EOL;
    }
} else {
    echo "NO_RESPONSE\n";
}
