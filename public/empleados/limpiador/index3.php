<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de limpiadoras</title>
</head>

<body>

    <h2>Panel de limpieza</h2>

    <p id="empleado"></p>

    <p id="estado">
        ⏳ Conectando...
    </p>

    <hr>

    <div id="habitaciones"></div>


    <script>

        // =====================================================
        // EMPLEADO
        // =====================================================

        let empleado = JSON.parse(sessionStorage.getItem("empleado"));

        if (!empleado) {
            window.location.href = "../index.html";
        }

        document.getElementById("empleado").innerHTML =
            "Bienvenido/a <b>" +
            empleado.nombre + " " +
            empleado.apellidos +
            "</b>";


        // =====================================================
        // PEDIR Y MOSTRAR HABITACIONES
        // =====================================================

        function cargarHabitaciones() {

            fetch("../../../src/Cliente.php?action=habitaciones")

                .then(res => {

                    if (!res.ok) {
                        throw new Error("Error del servidor");
                    }

                    return res.json();

                })

                .then(data => {

                    // Mostrar directamente los datos nuevos
                    mostrarHabitaciones(data);

                    document.getElementById("estado").innerHTML =
                        "✅ Datos actualizados a las " +
                        new Date().toLocaleTimeString();

                })

                .catch(error => {

                    document.getElementById("estado").innerHTML =
                        "⚠️ Sin conexión. Esperando cobertura...";

                    console.log(
                        "No se han podido obtener los datos:",
                        error
                    );

                });

        }


        // =====================================================
        // MOSTRAR HABITACIONES
        // =====================================================

        function mostrarHabitaciones(habitaciones) {

            let html = "";

            habitaciones.forEach(h => {

                html += `
                    <div style="
                        border:1px solid black;
                        padding:10px;
                        margin:10px;
                    ">

                        <h3>
                            Habitación ${h.numero}
                        </h3>

                        Estado:

                        ${
                            h.limpia
                            ? "✅ Limpia"
                            : "❌ Pendiente"
                        }

                        <br><br>

                        <button
                            onclick="limpiar('${h.numero}')">
                            Marcar limpia
                        </button>

                    </div>
                `;

            });

            document.getElementById("habitaciones").innerHTML = html;

        }


        // =====================================================
        // MARCAR HABITACIÓN COMO LIMPIA
        // =====================================================

        function limpiar(numero) {

            fetch("../../../src/Cliente.php?action=limpiar", {

                method: "POST",

                headers: {
                    "Content-Type": "application/json"
                },

                body: JSON.stringify({

                    numero: numero,

                    dni: empleado.dni

                })

            })

            .then(res => {

                if (!res.ok) {
                    throw new Error("Error al limpiar");
                }

                return res.json();

            })

            .then(data => {

                console.log(
                    "Habitación marcada como limpia:",
                    data
                );

                // Actualizamos inmediatamente
                // después de marcarla como limpia
                cargarHabitaciones();

            })

            .catch(error => {

                console.log("Error:", error);

            });

        }


        // =====================================================
        // CARGA INICIAL
        // =====================================================

        cargarHabitaciones();


        // =====================================================
        // ACTUALIZAR AUTOMÁTICAMENTE CADA 5 SEGUNDOS
        // =====================================================

        setInterval(() => {

            cargarHabitaciones();

        }, 5000);


    </script>

</body>

</html>