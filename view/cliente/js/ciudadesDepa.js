$(document).ready(function() {
    let dataCiudades = [];

    // Cargar el JSON una sola vez
    // Cargar el JSON una sola vez
    $.getJSON(urlCiudadesDepa, function(data) {
        dataCiudades = data;

        // Extraer departamentos únicos
        let departamentos = [...new Map(data.map(item => [item.codigo, {
            id: item.codigo,
            text: item.departamento
        }])).values()];

        // Inicializar departamentos
        $('#RegionId').select2({
            data: departamentos,
            placeholder: "Selecciona un departamento"
        });

        // Preseleccionar departamento si existe en sesión
        if (userDepto) {
            $('#RegionId').val(userDepto).trigger('change');
        }
    });

    // Cuando se selecciona un departamento, cargar ciudades filtradas
    $('#RegionId').on('change', function() {
        let codigoDepto = $(this).val();

        // Filtrar ciudades del departamento
        let ciudadesFiltradas = dataCiudades
            .filter(c => c.codigo === codigoDepto)
            .map(c => ({
                id: c.postal,
                text: c.Ciudad
            }));

        // Reinicializar select2 de ciudades
        $('#City').empty().select2({
            data: ciudadesFiltradas,
            placeholder: "Selecciona una ciudad"
        });

        // Si hay ciudad guardada en sesión y corresponde al depto → seleccionarla
        if (userCity) {
            let ciudadExiste = ciudadesFiltradas.find(c => c.id === userCity);
            if (ciudadExiste) {
                $('#City').val(userCity).trigger('change');
            }
        }
    });
});