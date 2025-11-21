<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];
$db = new SQLite3('../db/albuaves.db');

switch ($method) {
    case 'GET':
        if (isset($_GET['bird_id'])) {
            $id = $_GET['bird_id'];
            $stmt = $db->prepare("SELECT * FROM birds WHERE bird_id = :bird_id");
            $stmt->bindValue(':bird_id', $id, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $ave = $result->fetchArray(SQLITE3_ASSOC);
            echo json_encode($ave);
        } else {
            $result = $db->query("SELECT * FROM birds");
            $aves = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $aves[] = $row;
            }
            echo json_encode($aves);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("INSERT INTO birds (common_name, scientific_name, description, img_url) VALUES (:common_name, :scientific_name, :description, :img_url)");
        $stmt->bindValue(':common_name', $data['common_name'], SQLITE3_TEXT);
        $stmt->bindValue(':scientific_name', $data['scientific_name'], SQLITE3_TEXT);
        $stmt->bindValue(':description', $data['description'], SQLITE3_TEXT);
        $stmt->bindValue(':img_url', $data['img_url'], SQLITE3_TEXT);
        $stmt->execute();
        echo json_encode(["message" => "Ave creada correctamente"]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("UPDATE birds SET 
            common_name = :common_name, 
            scientific_name = :scientific_name, 
            description = :description, 
            img_url = :img_url
            WHERE bird_id = :bird_id");
        $stmt->bindValue(':bird_id', $data['bird_id'], SQLITE3_INTEGER);
        $stmt->bindValue(':common_name', $data['common_name'], SQLITE3_TEXT);
        $stmt->bindValue(':scientific_name', $data['scientific_name'], SQLITE3_TEXT);
        $stmt->bindValue(':description', $data['description'], SQLITE3_TEXT);
        $stmt->bindValue(':img_url', $data['img_url'], SQLITE3_TEXT);
        $stmt->execute();
        echo json_encode(["message" => "Ave actualizada correctamente"]);
        break;

    case 'DELETE':
        if (isset($_GET['bird_id'])) {
            $bird_id = $_GET['bird_id'];
            $stmt = $db->prepare("DELETE FROM birds WHERE bird_id = :bird_id");
            $stmt->bindValue(':bird_id', $bird_id, SQLITE3_INTEGER);
            $stmt->execute();
            echo json_encode(["message" => "Ave eliminada correctamente"]);
        } else {
            echo json_encode(["error" => "bird_id requerido para eliminar"]);
        }
        break;

    default:
        echo json_encode(["message" => "Método no soportado"]);
        break;
}

$db->close();
?>