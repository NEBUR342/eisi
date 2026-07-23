<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>EISI Hotel</title>
</head>

<body>
    <h2>Inicio de sesión</h2>
    <label>DNI</label><br>
    <input type="text" id="dni"><br><br>
    <label>Contraseña</label><br>
    <input type="password" id="pass"><br><br>
    <button onclick="login()">Entrar</button>
    <script>
        function login() {
            let dni = document.getElementById("dni").value;
            let pass = document.getElementById("pass").value;
            // Intentar login como empleado
            fetch("../../src/Empleado.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        dni,
                        pass
                    })
                })
                .then(res => res.json())
                .then(empleado => {
                    if (empleado) {
                        sessionStorage.setItem(
                            "empleado",
                            JSON.stringify(empleado)
                        );
                        switch (empleado.puesto.toLowerCase()) {
                            case "jefe":
                            case "jefa":
                                location.href = "../empleados/jefe/index.php";
                                break;

                            case "limpiadora":
                            case "limpiador":
                                location.href = "../empleados/limpiador/index.php";
                                break;

                            default:
                                alert("Este empleado no tiene panel.");

                        }

                    } else {
                        loginCliente(dni, pass);
                    }
                });
        }

        function loginCliente(dni, pass) {
            fetch("../../src/Cliente.php?action=login", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        dni,
                        pass
                    })
                })
                .then(res => res.json())
                .then(cliente => {
                    if (cliente) {
                        sessionStorage.setItem(
                            "cliente",
                            JSON.stringify(cliente)
                        );
                        location.href = "../clientes/index.php";
                    } else {
                        alert("Usuario o contraseña incorrectos.");
                    }
                });
        }
    </script>
</body>

</html>