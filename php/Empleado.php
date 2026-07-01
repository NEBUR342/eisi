<?php

class Empleado {

    public static function login($dni, $pass) {

        $data = json_decode(
            file_get_contents(__DIR__ . "/../json/empleados.json"),
            true
        );

        foreach ($data as $emp) {
            if ($emp["dni"] === $dni && $emp["pass"] === $pass) {
                return $emp;
            }
        }

        return null;
    }

    public static function obtenerEmpleados() {
        return json_decode(
            file_get_contents(__DIR__ . "/../json/empleados.json"),
            true
        );
    }
}

// ============================
// API para responder al fetch()
// ============================

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $datos = json_decode(file_get_contents("php://input"), true);

    if ($datos) {

        $empleado = Empleado::login(
            $datos["dni"],
            $datos["pass"]
        );

        echo json_encode($empleado);
    } else {
        echo json_encode(null);
    }
}