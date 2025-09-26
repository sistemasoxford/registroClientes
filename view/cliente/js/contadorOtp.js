const reenviarEl = document.getElementById("reenviarOtp");
const contadorEl = document.getElementById("contador");

// Duración en segundos (5 minutos)
const DURACION = 5 * 60;

// Estado guardado en localStorage
let expiracion = localStorage.getItem("otpExpiracion");
let otpActivo = localStorage.getItem("otpActivo") === "true";

function actualizarContador() {
    const ahora = Date.now();
    const restante = Math.floor((expiracion - ahora) / 1000);

    // 🚀 Recuperamos el contador actual en el DOM
    const contadorEl = document.getElementById("contador");

    if (restante > 0 && contadorEl) {
        let minutos = Math.floor(restante / 60);
        let segundos = restante % 60;
        contadorEl.textContent =
            (minutos < 10 ? "0" : "") + minutos + ":" + (segundos < 10 ? "0" : "") + segundos;
    } else {
        clearInterval(intervalo);
        if (contadorEl) contadorEl.textContent = "";
        reenviarEl.textContent = "Reenviar OTP";
        reenviarEl.classList.remove("disabled");
        reenviarEl.style.pointerEvents = "auto";
        reenviarEl.style.opacity = "1";

        localStorage.setItem("otpActivo", "false");
    }
}

let intervalo = null;

// 🔹 Al cargar la página
if (otpActivo && expiracion && Date.now() < expiracion) {
    // OTP activo → deshabilitamos botón y corremos el contador
    reenviarEl.innerHTML = `Reenviar OTP en <span id="contador"></span>`;
    reenviarEl.classList.add("disabled");
    reenviarEl.style.pointerEvents = "none";
    reenviarEl.style.opacity = "0.6";

    intervalo = setInterval(actualizarContador, 1000);
    actualizarContador();
} else {
    // OTP vencido o no iniciado → botón habilitado
    localStorage.setItem("otpActivo", "false");
    localStorage.removeItem("otpExpiracion");

    reenviarEl.textContent = "Reenviar OTP";
    reenviarEl.classList.remove("disabled");
    reenviarEl.style.pointerEvents = "auto";
    reenviarEl.style.opacity = "1";
}

// Evento al hacer clic en "Reenviar OTP"
reenviarEl.addEventListener("click", function (e) {
    if (reenviarEl.classList.contains("disabled")) {
        e.preventDefault();
        return;
    }

    e.preventDefault();

    var formData = { enviar: true };

    Swal.fire({
        title: "Cargando...",
        text: "Por favor, espere mientras procesamos su solicitud.",
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    $.ajax({
        url: urlReenviarOtp,
        method: "POST",
        dataType: "json",
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(formData),
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    text: response.message,
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                });

                // Reiniciar contador solo si fue exitoso
                expiracion = Date.now() + DURACION * 1000;
                localStorage.setItem("otpExpiracion", expiracion);
                localStorage.setItem("otpActivo", "true");

                reenviarEl.innerHTML = `Reenviar OTP en <span id="contador"></span>`;
                reenviarEl.classList.add("disabled");
                reenviarEl.style.pointerEvents = "none";
                reenviarEl.style.opacity = "0.6";

                clearInterval(intervalo);
                intervalo = setInterval(actualizarContador, 1000);
                actualizarContador();
            } else {
                Swal.fire({
                    text: response.message,
                    icon: "error",
                    showConfirmButton: true,
                });

                reenviarEl.textContent = "Reenviar OTP";
                reenviarEl.classList.remove("disabled");
                reenviarEl.style.pointerEvents = "auto";
                reenviarEl.style.opacity = "1";
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                text: "Error de comunicación con el servidor: " + error,
                icon: "error",
                showConfirmButton: true,
            });
        },
    });
});
