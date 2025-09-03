<?php
session_start();
$_SESSION['photos_gate_agreed'] = true;
http_response_code(200);