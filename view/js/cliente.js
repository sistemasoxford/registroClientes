$(document).ready(function(){

    $("#clienteForm").on("submit", function(e){
        e.preventDefault(); // evita que recargue la página

        let form = $(this);
        let formData = form.serialize(); // serializa los datos del formulario

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
            url: urlCliente, // archivo PHP que recibe los datos
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response){
                if(response.success != 1){
                    if(response.success){
                        Swal.fire({
                            text: response.message || "Cliente encontrado.",
                            icon: "success",
                            timer: 2000, // 2 segundos
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = urlActualizar;
                        });

                    } else {
                        Swal.fire({
                            text: response.message || "Cliente para registro.",
                            icon: "success",
                            timer: 2000, // 2 segundos
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = urlRegistrar;
                        });
                    }   
                } else {
                    Swal.fire({
                        text: response.message || "No se recibió documento.",
                        icon: "error",
                        timer: 2000, // 2 segundos
                        showConfirmButton: false
                    })
                }
            
            },
            error: function(xhr, status, error){
                console.error(error);
                Swal.fire({
                    title: "Error",
                    text: "Ocurrió un error al procesar la solicitud. Inténtelo nuevamente más tarde.",
                    icon: "error",
                });
            }
        });
    });

});