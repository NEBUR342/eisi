<?php
class Empleado
{
    public static function login($dni, $pass)
    {
        $hoteles = json_decode(
            file_get_contents(__DIR__ . "/../json/empleados.json"),
            true
        );

        foreach ($hoteles as $hotel) {
            foreach ($hotel["empleados"] as $emp) {
                if (
                    $emp["dni"] === $dni &&
                    $emp["pass"] === $pass
                ) {
                    return $emp;
                }
            }
        }

        return null;
    }
    public static function obtenerEmpleados()
    {
        $hoteles = json_decode(
            file_get_contents(__DIR__ . "/../json/empleados.json"),
            true
        );

        $empleados = [];

        foreach ($hoteles as $hotel) {
            foreach ($hotel["empleados"] as $emp) {
                $empleados[] = $emp;
            }
        }

        return $empleados;
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
