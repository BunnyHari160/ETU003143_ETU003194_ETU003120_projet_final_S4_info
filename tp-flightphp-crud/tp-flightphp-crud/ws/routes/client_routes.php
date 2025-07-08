<?php
require_once __DIR__ . '/../controllers/ClientController.php';

Flight::route('GET /clients', ['ClientController', 'getAll']);
Flight::route('GET /clients/email', ['ClientController', 'getByEmail']);
Flight::route('POST /clients', ['ClientController', 'create']);
