<?php
class Cliente
{
    public static function getClientes()
    {
        return json_decode(
            file_get_contents(__DIR__ . "/../json/clientes.json"),
            true
        );
    }

    public static function getHabitaciones()
    {
        $hoteles = json_decode(
            file_get_contents(__DIR__ . "/../json/habitaciones.json"),
            true
        );

        $habitaciones = [];

        foreach ($hoteles as $hotel) {
            foreach ($hotel["habitaciones"] as $habitacion) {
                $habitaciones[] = $habitacion;
            }
        }

        return $habitaciones;
    }

    public static function login($dni, $pass)
    {
        $hoteles = self::getClientes();

        foreach ($hoteles as $hotel) {
            foreach ($hotel["clientes"] as $cliente) {
                if (
                    $cliente["dni"] === $dni &&
                    $cliente["pass"] === $pass
                ) {
                    return $cliente;
                }
            }
        }

        return null;
    }

    public static function clientesPorHabitacion($numero)
    {
        $hoteles = self::getClientes();
        $resultado = [];

        foreach ($hoteles as $hotel) {
            foreach ($hotel["clientes"] as $cliente) {
                if ($cliente["habitacion"] == $numero) {
                    $resultado[] = $cliente;
                }
            }
        }

        return $resultado;
    }

    public static function marcarLimpia($numero, $dniEmpleado)
    {
        $hoteles = json_decode(
            file_get_contents(__DIR__ . "/../json/habitaciones.json"),
            true
        );

        foreach ($hoteles as &$hotel) {
            foreach ($hotel["habitaciones"] as &$hab) {
                if ($hab["numero"] == $numero) {
                    $hab["limpia"] = true;
                    $hab["encargado"] = $dniEmpleado;
                    $hab["fechaUltimaLimpieza"] = date("Y-m-d H:i");
                }
            }
        }

        file_put_contents(
            __DIR__ . "/../json/habitaciones.json",
            json_encode(
                $hoteles,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
        );
    }
}

header("Content-Type: application/json");
$action = $_GET["action"] ?? "";
$data = json_decode(file_get_contents("php://input"), true);

switch ($action) {
    case "login":
        echo json_encode(
            Cliente::login(
                $data["dni"],
                $data["pass"]
            )
        );
        break;

    case "habitaciones":
        echo json_encode(
            Cliente::getHabitaciones()
        );
        break;

    case "limpiar":
        Cliente::marcarLimpia(
            $data["numero"],
            $data["dni"]
        );
        echo json_encode([
            "ok" => true
        ]);
        break;
}
