<?php
include 'setup.php';

$response = handle();
print_r($response);
error_log($response);