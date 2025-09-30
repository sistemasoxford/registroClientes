$(document).ready(function() {

    $("#clienteForm").on("submit", function(e) {
        e.preventDefault(); // Evita el envío tradicional

        // Serializar datos del formulario a objeto JS
        var formData = {};
        $(this).serializeArray().map(function(x){ formData[x.name] = x.value; });

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
            url: urlOtp, // archivo PHP donde recibes los datos
            type: "POST",
            contentType: "application/json; charset=UTF-8",
            data: JSON.stringify(formData), // Enviamos en formato JSON
            dataType: "json", // Esperamos JSON de respuesta
            success: function(response) {
                // Manejar respuesta de PHP
                if(response.success) {
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        timer: 2000, // 2 segundos
                        showConfirmButton: false
                    }).then(() => {
                        console.log('')
                        window.location.href = urlPasoFinal;
                    });
                } else {
                    Swal.fire({
                        text: response.message,
                        icon: "error",
                        showConfirmButton: true
                    }).then(() => {
                        $('#kt_sign_up_submit').attr('disabled', false);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en AJAX:", error);
                Swal.fire({
                    title: "Error",
                    text: "Ocurrió un error al procesar la solicitud. Inténtelo nuevamente más tarde.",
                    icon: "error",
                });
            }
        });
    });
});