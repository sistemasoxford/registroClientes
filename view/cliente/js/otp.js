$(document).ready(function() {

    $("#clienteForm").on("submit", function(e) {
        e.preventDefault(); // Evita el envío tradicional

        // Serializar datos del formulario a objeto JS
        var formData = {};
        $(this).serializeArray().map(function(x) { formData[x.name] = x.value; });

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
            url: urlOtp, // 👈 PHP que procesará los datos
            type: "POST",
            contentType: "application/json; charset=UTF-8",
            data: JSON.stringify(formData),
            dataType: "json", // Esperamos JSON de respuesta
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = urlPasoFinal; // 👈 redirección final
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
                console.log("Respuesta del servidor:", xhr.responseText);
                Swal.fire({
                    title: "Error en el servidor",
                    html: "<pre style='text-align:left;white-space:pre-wrap'>" + xhr.responseText + "</pre>",
                    icon: "error",
                    width: 600,
                });
            }
        });
    });

});