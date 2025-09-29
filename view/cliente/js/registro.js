$(document).ready(function () {

    // ---------------- RESTRICCIONES DE CAMPOS ---------------- //

    // Solo números en documento y celular
    $('#PassportNumber, #CellularPhoneNumber').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Solo letras en nombres y apellidos, sin espacios y en mayúscula
    $('#FirstName, #LastName').on('input', function () {
        this.value = this.value
            .replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ]/g, '')
            .toUpperCase();                        
    });

    // Email: quitar espacios
    $('#Email').on('input', function () {
        this.value = this.value.replace(/\s+/g, '');
    });

    // Solo números en día y año
    $('#BirthDateDay, #BirthDateYear').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Evitar año mayor al actual
    $('#BirthDateYear').on('input', function () {
        let currentYear = new Date().getFullYear();
        if (parseInt(this.value, 10) > currentYear) {
            this.value = currentYear;
        }
    });

    // Validar día máximo según el mes y año
    function diasEnMes(mes, año) {
        return new Date(año, mes, 0).getDate(); // Ejemplo: feb 2024 → 29
    }

    $('#BirthDateDay, #BirthDateMonth, #BirthDateYear').on('input change', function () {
        let day = parseInt($('#BirthDateDay').val(), 10);
        let month = parseInt($('#BirthDateMonth').val(), 10);
        let year = parseInt($('#BirthDateYear').val(), 10);

        if (!month || !year) return; // Solo validar si mes y año existen

        let maxDias = diasEnMes(month, year);

        if (day > maxDias) {
            $('#BirthDateDay').val(maxDias);
        } else if (day < 1 && $('#BirthDateDay').val() !== "") {
            $('#BirthDateDay').val(1);
        }
    });

    // ---------------- VALIDACIONES AL ENVIAR ---------------- //
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

        // Validaciones de campos vacíos
        if (!formData.tDocumento) return Swal.fire({ text: "El campo de tipo documento no puede estar vacío.", icon: "warning" });
        if (!formData.PassportNumber) return Swal.fire({ text: "El campo de documento no puede estar vacío.", icon: "warning" });
        if (!formData.FirstName) return Swal.fire({ text: "El campo de primer nombre no puede estar vacío.", icon: "warning" });
        if (!formData.LastName) return Swal.fire({ text: "El campo de primer apellido no puede estar vacío.", icon: "warning" });
        if (!formData.Sex) return Swal.fire({ text: "El campo de género no puede estar vacío.", icon: "warning" });
        if (!formData.BirthDateDay || !formData.BirthDateMonth || !formData.BirthDateYear) {
            return Swal.fire({ text: "Debe completar la fecha de nacimiento.", icon: "warning" });
        }

        // Validar fecha real
        let day = parseInt(formData.BirthDateDay, 10);
        let month = parseInt(formData.BirthDateMonth, 10);
        let year = parseInt(formData.BirthDateYear, 10);
        let today = new Date();
        let birthDate = new Date(year, month - 1, day);

        if (birthDate.getFullYear() !== year ||
            birthDate.getMonth() + 1 !== month ||
            birthDate.getDate() !== day) {
            return Swal.fire({ text: "La fecha de nacimiento no es válida.", icon: "error" });
        }

        if (birthDate > today) {
            return Swal.fire({ text: "La fecha de nacimiento no puede ser superior a la fecha actual.", icon: "error" });
        }

        // Validar correo
        if (!formData.Email) return Swal.fire({ text: "El campo de correo no puede estar vacío.", icon: "warning" });
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.Email)) {
            return Swal.fire({ text: "El correo no tiene un formato válido.", icon: "error" });
        }

        // Validar celular
        if (!formData.CellularPhoneNumber) return Swal.fire({ text: "El campo de celular no puede estar vacío.", icon: "warning" });

        // Validar ubicación
        if (!formData.RegionId) return Swal.fire({ text: "El campo de departamento no puede estar vacío.", icon: "warning" });
        if (!formData.City) return Swal.fire({ text: "El campo de ciudad no puede estar vacío.", icon: "warning" });

        // Validar términos
        if (!formData.TermsAccepted) {
            return Swal.fire({ text: "Por favor, acepte los Términos y Condiciones antes de continuar.", icon: "info" });
        }

        // ---------------- ENVIAR FORMULARIO ---------------- //
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

        if (registrar != 1) {
            urlEnviar = urlActualizar;
        } else {
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
