$(document).ready(function () {

    // Restringir a solo números el input de documento
    $('#PassportNumber, #CellularPhoneNumber').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, ''); 
    });

    $('#FirstName, #LastName').on('input', function () {
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    });

    $('#Email').on('input', function () {
    this.value = this.value.replace(/\\s+/g, '');
    });
    
    $('#clienteForm').on('submit', function (e) {
        e.preventDefault();

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
            CityText: $('#City option:selected').text(),
            TermsAccepted: $('input[name="TextValue"]').is(':checked') ? 1 : 0
        };

        if (!formData.tDocumento) {
            Swal.fire({
                text: "El campo de tipo documento no puede estar vacío.",
                icon: "warning",
            });
            return; // Detener el proceso si la cédula está vacía
        } else if (!formData.PassportNumber) {
            Swal.fire({
                text: "El campo de documento no puede estar vacío.",
                icon: "warning",
            });
            return;
        } else if (!formData.FirstName) {
            Swal.fire({
                text: "El campo de primer nombre no puede estar vacío.",
                icon: "warning",
            });
            return;
        } else if (!formData.LastName) {
            Swal.fire({
                text: "El campo de primer apellido no puede estar vacío.",
                icon: "warning",
            });
            return;
        } else if (!formData.Sex) {
            Swal.fire({
                text: "El campo de genero no puede estar vacío.",
                icon: "warning",
            });
            return;
        } else if (!formData.BirthDateDay) {
            Swal.fire({
                text: "El campo dia de nacimiento no puede estar vacío.",
                icon: "warning",
            });
            return;
        } else if (!formData.BirthDateMonth) {
            Swal.fire({
                text: "El campo mes de nacimiento no puede estar vacío.",
                icon: "warning",
            });
            return;
        } else if (!formData.BirthDateYear) {
            Swal.fire({
                text: "El campo año de nacimiento no puede estar vacío.",
                icon: "warning",
            });
            return;
        } else if (!formData.Email) {
            Swal.fire({
                text: "El campo de correo no puede estar vacío.",
                icon: "warning",
            });
            return;
        } else if (!formData.CellularPhoneNumber) {
            Swal.fire({
                text: "El campo de celular no puede estar vacío.",
                icon: "warning",
            });
            return;
        } else if (!formData.RegionId) {
            Swal.fire({
                text: "El campo de departamento no puede estar vacío.",
                icon: "warning",
            });
            return;
        } else if (!formData.City) {
            Swal.fire({
                text: "El campo de ciudad no puede estar vacío.",
                icon: "warning",
            });
            return;
        } else if (!formData.TermsAccepted) {
            Swal.fire({
                text: "Por favor, acepte los Términos y Condiciones antes de continuar.",
                icon: "info",
            });
            return;
        }


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

        if(registrar != 1){
            urlEnviar = urlActualizar;
        }else{
            urlEnviar = urlRegistrar;
        }

        $.ajax({
            url: urlEnviar,
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify(formData),
            beforeSend: function () {
                $('#kt_sign_up_submit').attr('disabled', true);
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        timer: 2000,
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
            error: function (xhr, status, error) {
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
