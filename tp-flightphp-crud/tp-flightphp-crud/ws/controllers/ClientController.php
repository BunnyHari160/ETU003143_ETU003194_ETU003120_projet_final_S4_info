<?php
require_once __DIR__ . '/../models/Client.php';

class ClientController {
    public static function getAll() {
        $clients = Client::getAll();
        Flight::json($clients);
    }

    public static function getByEmail() {
        $email = Flight::request()->query->email ?? null;
        if (!$email) {
            Flight::json(['error' => 'Email manquant'], 400);
            return;
        }
        $client = Client::getByEmail($email);
        Flight::json($client ?: []);
    }

    public static function create() {
        $data = Flight::request()->data;
        if (!$data->nom || !$data->prenom || !$data->email || !$data->date_naissance) {
            Flight::json(['error' => 'Données client incomplètes'], 400);
            return;
        }
        $existingClient = Client::getByEmail($data->email);
        if ($existingClient) {
            Flight::json(['error' => 'Client déjà existant'], 400);
            return;
        }
        $id = Client::create($data);
        Flight::json(['id' => $id, 'message' => 'Client créé']);
    }
}
