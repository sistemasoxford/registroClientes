$(document).ready(function() {

    $('#clienteForm').on('submit', function(e) {
        e.preventDefault(); // Evita el envío normal del formulario

        // Construir un objeto JSON a partir del formulario
        var formData = {
            tDocumento: $('#tDocumento').val(),
            PassportNumber: $('input[name="PassportNumber"]').val(),
            FirstName: $('input[name="FirstName"]').val(),
            LastName: $('input[name="LastName"]').val(),
            Sex: $('#Sex').val(),
            BirthDateDay: $('input[name="BirthDateDay"]').val(),
            BirthDateMonth: $('#BirthDateMonth').val(),
            BirthDateYear: $('input[name="BirthDateYear"]').val(),
            Email: $('input[name="Email"]').val(),
            CellularPhoneNumber: $('input[name="CellularPhoneNumber"]').val(),
            RegionId: $('#RegionId').val(),
            City: $('#City').val(),
            CityText: $('#City option:selected').text(), // 👈 texto visible
            TermsAccepted: $('input[name="TextValue"]').is(':checked') ? 1 : 0
        };

        // Mostrar el SweetAlert de carga
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
            url: urlEnviar, // Cambia esto por tu archivo PHP
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify(formData),
            beforeSend: function() {
                // Aquí puedes deshabilitar el botón y mostrar un loader
                $('#kt_sign_up_submit').attr('disabled', true);
            },
            success: function(response) {
                if(response.success){
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        timer: 2000, // 2 segundos
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = urlOtp;
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
                console.error(xhr.responseText);
                Swal.fire({
                    title: "Error",
                    text: "Ocurrió un error al procesar la solicitud. Inténtelo nuevamente más tarde.",
                    icon: "error",
                });
            }
        });

    });

});