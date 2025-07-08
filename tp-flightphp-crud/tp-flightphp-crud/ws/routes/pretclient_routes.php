<?php
require_once __DIR__ . '/../controllers/PretClientController.php';

Flight::route('GET /prets-clients', ['PretClientController', 'getAll']);
Flight::route('POST /prets-clients', ['PretClientController', 'create']);
Flight::route('GET /prets-clients/total', ['PretClientController', 'getTotal']);
Flight::route('GET /prets-clients/interets', ['PretClientController', 'getInterets']);
Flight::route('GET /prets-clients/@id/echeancier', ['PretClientController', 'getEcheancier']);
Flight::route('POST /prets-clients/simuler', ['PretClientController', 'simuler']);
