<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cliente</title>
</head>

<body>
    <h2>Estado de mi habitación</h2>
    <div id="datos"></div>
    <script>
        let cliente = JSON.parse(
            sessionStorage.getItem("cliente")
        );
        if (!cliente) {
            location.href = "../../index.html";
        }
        fetch("../../src/Cliente.php?action=habitaciones")
            .then(res => res.json())
            .then(habitaciones => {
                let habitacion = habitaciones.find(
                    h => h.numero == cliente.habitacion
                );
                let estado = habitacion.limpia ?
                    "✅ Limpia" :
                    "❌ Pendiente de limpieza";
                document.getElementById("datos").innerHTML = `
                    <h3>${cliente.nombre} ${cliente.apellidos}</h3>
                    <p><b>Habitación:</b> ${cliente.habitacion}</p>
                    <p><b>Estado:</b> ${estado}</p>
                    <p><b>Última limpieza:</b><br>
                    ${habitacion.fechaUltimaLimpieza}
                    </p>
                `;
            });
    </script>
</body>

</html>