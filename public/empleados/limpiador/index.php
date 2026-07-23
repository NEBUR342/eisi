<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de limpiadoras</title>
</head>
<body>
<h2>Panel de limpieza</h2>
<p id="empleado"></p>
<hr>
<div id="habitaciones"></div>
<script>
let empleado = JSON.parse(sessionStorage.getItem("empleado"));
if (!empleado) {
    window.location.href = "../index.html";
}
document.getElementById("empleado").innerHTML =
    "Bienvenido/a <b>" + empleado.nombre + " " + empleado.apellidos + "</b>";
cargarHabitaciones();

function cargarHabitaciones() {
    fetch("../php/Cliente.php?action=habitaciones")
    .then(res => res.json())
    .then(data => {
        let html = "";
        data.forEach(h => {
            html += `
            <div style="border:1px solid black;padding:10px;margin:10px">
                <h3>Habitación ${h.numero}</h3>
                Estado:
                ${h.limpia ? "✅ Limpia" : "❌ Pendiente"}
                <br><br>
                <button onclick="limpiar('${h.numero}')">
                    Marcar limpia
                </button>
            </div>
            `;
        });
        document.getElementById("habitaciones").innerHTML = html;
    });
}

function limpiar(numero){
    fetch("../php/Cliente.php?action=limpiar",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body:JSON.stringify({
            numero:numero,
            dni:empleado.dni
        })
    })
    .then(res=>res.json())
    .then(()=>{
        cargarHabitaciones();
    });
}
</script>
</body>
</html>