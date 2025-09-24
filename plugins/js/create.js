var KTModalNewTicket = function() {
    var t, e, n, i, o, a, dropzone;

    return {
        init: function() {
            (a = document.querySelector("#kt_modal_new_ticket")) &&
            (o = new bootstrap.Modal(a),
                i = document.querySelector("#kt_modal_new_ticket_form"),
                t = document.getElementById("kt_modal_new_ticket_submit"),
                e = document.getElementById("kt_modal_new_ticket_cancel"),
                dropzone = new Dropzone("#kt_modal_create_ticket_attachments", {
                    url: urlTicket,
                    paramName: "file",
                    maxFiles: 10,
                    maxFilesize: 10,
                    addRemoveLinks: !0,
                    accept: function(file, done) {
                        if (file.name === "justinbieber.jpg") {
                            done("Naha, you don't.");
                        } else {
                            done();
                        }
                    },
                    autoProcessQueue: false // Desactivar la carga automática
                }),
                $(i.querySelector('[name="user"]')).on("change", function() {
                    n.revalidateField("user");
                }),
                $(i.querySelector('[name="status"]')).on("change", function() {
                    n.revalidateField("status");
                }),
                n = FormValidation.formValidation(i, {
                    fields: {
                        subject: {
                            validators: {
                                notEmpty: {
                                    message: "El asunto del ticket es requerido"
                                }
                            }
                        },
                        product: {
                            validators: {
                                notEmpty: {
                                    message: "El producto es requerido"
                                }
                            }
                        },
                        description: {
                            validators: {
                                notEmpty: {
                                    message: "La descripcion del ticket es requerida"
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: ""
                        })
                    }
                }),
                t.addEventListener("click", function(e) {
                    e.preventDefault();
                    n && n.validate().then(function(e) {
                        if (e === "Valid") {
                            t.setAttribute("data-kt-indicator", "on");
                            t.disabled = !0;

                            // Procesar manualmente los archivos de Dropzone
                            dropzone.processQueue();

                            // Aquí continúas con el envío del formulario por AJAX
                            fetch(i.action, {
                                    method: 'POST',
                                    body: new FormData(i)
                                })
                                .then(response => response.json())
                                .then(data => {
                                    t.removeAttribute("data-kt-indicator");
                                    t.disabled = !1;
                                    if (data.success) {
                                        Swal.fire({
                                            text: "El formulario ha sido enviado con éxito!",
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, entendido!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function(t) {
                                            t.isConfirmed && o.hide();
                                        });
                                    } else {
                                        Swal.fire({
                                            text: "Lo siento, hubo un error. Por favor intenta de nuevo.",
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, entendido!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        text: "Lo siento, hubo un error. Por favor intenta de nuevo.",
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, entendido!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    });
                                    t.removeAttribute("data-kt-indicator");
                                    t.disabled = !1;
                                });
                        } else {
                            Swal.fire({
                                text: "Lo siento, parece que se han detectado algunos errores, por favor intentalo de nuevo",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, entendido!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    });
                }),
                e.addEventListener("click", function(t) {
                    t.preventDefault();
                    Swal.fire({
                        text: "Estas seguro que quieres cancelar?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Si, cancelar!",
                        cancelButtonText: "No, regresar",
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then(function(t) {
                        t.value ? (i.reset(), o.hide()) : t.dismiss === "cancel" && Swal.fire({
                            text: "Tu formulario no ha sido cancelado!.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, entendido!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    });
                })
            );
        }
    };
}();

KTUtil.onDOMContentLoaded(function() {
    KTModalNewTicket.init();
});