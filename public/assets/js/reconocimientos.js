// ============================================
// FUNCIÓN: Calcular progreso basado en hitos (GLOBAL)
// ============================================
function calcularProgresoPorHito(años) {
    const hitos = [
        { años: 0, porcentaje: 0 },
        { años: 30, porcentaje: 25 },
        { años: 40, porcentaje: 50 },
        { años: 50, porcentaje: 75 },
        { años: 60, porcentaje: 100 },
    ];

    if (años >= 60) return 100;

    let hitoAnterior = hitos[0];
    let hitoSiguiente = hitos[hitos.length - 1];

    for (let i = 0; i < hitos.length - 1; i++) {
        if (años >= hitos[i].años && años < hitos[i + 1].años) {
            hitoAnterior = hitos[i];
            hitoSiguiente = hitos[i + 1];
            break;
        }
    }

    const rangoAños = hitoSiguiente.años - hitoAnterior.años;
    const rangoPorcentaje = hitoSiguiente.porcentaje - hitoAnterior.porcentaje;
    const avanceAños = años - hitoAnterior.años;

    const progreso =
        hitoAnterior.porcentaje + (avanceAños * rangoPorcentaje) / rangoAños;

    return Math.min(progreso, 100);
}

// ============================================
// FUNCIÓN: Formatear fecha para mostrar
// ============================================
function formatearFechaShow(fecha) {
    if (!fecha || fecha === "0000-00-00") return "No otorgado";
    try {
        // Reemplazamos "-" por "/" para evitar el desfase UTC
        const fechaLocal = fecha.replace(/-/g, "\/");
        const date = new Date(fechaLocal);

        return date.toLocaleDateString("es-ES", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    } catch (e) {
        return fecha;
    }
}

// ============================================
// FUNCIÓN: Cargar datos en el modal de visualización
// ============================================
function cargarModalShow(reconocimientos, data) {
    console.log("Cargando modal show con datos:", reconocimientos);

    // Obtener años de ejercicio
    const añosEjercicio = data.años_transcurridos_graduado || 0;

    // Actualizar información del médico
    const infoMedicoElement = document.getElementById("info-medico-show");
    if (infoMedicoElement) {
        infoMedicoElement.innerHTML = `
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-user-doctor fa-2x me-3"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>${data.nombres_apellidos || "Nombre no disponible"}</strong><br>
                        <small>
                            <i class="fa-regular fa-calendar me-1"></i> Fecha de Egreso: ${formatearFechaShow(data.fecha_egreso_universidad)} | 
                            <i class="fa-regular fa-clock me-1"></i> Años de ejercicio: ${añosEjercicio} años
                        </small>
                    </div>
                </div>
            </div>
        `;
    }

    // Actualizar contadores
    const aniosEjercicioShow = document.getElementById("anios-ejercicio-show");
    if (aniosEjercicioShow) aniosEjercicioShow.textContent = añosEjercicio;

    const aniosActualesShow = document.getElementById("anios-actuales-show");
    if (aniosActualesShow)
        aniosActualesShow.textContent = añosEjercicio + " años";

    // Calcular progreso para la barra (usando la función global)
    const progreso = calcularProgresoPorHito(añosEjercicio);
    const progressBar = document.getElementById("barra-progreso-show");
    if (progressBar) {
        progressBar.style.width = progreso + "%";
        progressBar.setAttribute("aria-valuenow", añosEjercicio);
        progressBar.innerHTML = `<span class="fw-bold text-white">${añosEjercicio} años</span>`;
    }

    // Mapeo de tipo_reconocimiento a años
    const tipoToAñosMap = {
        1: 30,
        2: 40,
        3: 50,
        4: 60,
    };

    const añosToColorMap = {
        30: "primary",
        40: "warning",
        50: "success",
        60: "info",
    };

    // Crear mapa de reconocimientos por tipo
    const reconocimientosMap = {};
    let recibidos = 0;

    reconocimientos.forEach((rec) => {
        if (rec && rec.tipo_reconocimiento) {
            reconocimientosMap[rec.tipo_reconocimiento] = rec;
            // Contar reconocimientos recibidos (fecha válida)
            if (rec.fecha_de_entrega && rec.fecha_de_entrega !== "0000-00-00") {
                recibidos++;
            }
        }
    });

    // Actualizar contador de recibidos
    const recibidosCount = document.getElementById("recibidos-count");
    if (recibidosCount) recibidosCount.textContent = recibidos;

    // Procesar cada hito
    [30, 40, 50, 60].forEach((hito) => {
        const tipoReconocimiento =
            hito === 30 ? 1 : hito === 40 ? 2 : hito === 50 ? 3 : 4;
        const rec = reconocimientosMap[tipoReconocimiento];

        const estadoElement = document.getElementById(`estado-show-${hito}`);
        const fechaElement = document.getElementById(`fecha-show-${hito}`);
        const cardElement = document.getElementById(`card-show-${hito}`);

        if (!estadoElement || !fechaElement || !cardElement) return;

        const tieneFechaValida =
            rec &&
            rec.fecha_de_entrega &&
            rec.fecha_de_entrega !== "0000-00-00";
        const fechaEntrega = tieneFechaValida ? rec.fecha_de_entrega : null;

        if (tieneFechaValida) {
            // Reconocimiento RECIBIDO
            estadoElement.className = "badge bg-success text-white p-2 w-100";
            estadoElement.innerHTML =
                '<i class="fa-solid fa-circle-check me-1"></i>RECIBIDO';
            fechaElement.textContent = formatearFechaShow(fechaEntrega);
            fechaElement.className = "fw-bold text-success";

            // Cambiar color de la card
            cardElement.className = `card mb-3 border-start border-${añosToColorMap[hito]} border-4 bg-success-subtle`;
        } else if (añosEjercicio >= hito) {
            // Elegible pero NO RECIBIDO (cumple años pero no se le ha otorgado)
            estadoElement.className = "badge bg-warning text-dark p-2 w-100";
            estadoElement.innerHTML =
                '<i class="fa-solid fa-hourglass-half me-1"></i>PENDIENTE DE OTORGAR';
            fechaElement.textContent = "No otorgado";
            fechaElement.className = "fw-bold text-warning";

            // Cambiar color de la card
            cardElement.className = `card mb-3 border-start border-${añosToColorMap[hito]} border-4 bg-warning-subtle`;
        } else {
            // NO ELEGIBLE (no cumple años suficientes)
            const añosFaltantes = hito - añosEjercicio;
            estadoElement.className = "badge bg-secondary text-white p-2 w-100";
            estadoElement.innerHTML = `<i class="fa-regular fa-clock me-1"></i>FALTAN ${añosFaltantes} AÑOS`;
            fechaElement.textContent = "No elegible";
            fechaElement.className = "fw-bold text-secondary";

            // Cambiar color de la card
            cardElement.className = `card mb-3 border-start border-${añosToColorMap[hito]} border-4 opacity-50`;
        }
    });
}

// ============================================
// FUNCIÓN: Formatear fecha
// ============================================
function formatearFecha(fecha) {
    if (!fecha) return "No disponible";
    try {
        // Forzamos interpretación local
        const date = new Date(fecha.replace(/-/g, "\/"));
        return date.toLocaleDateString("es-ES", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    } catch (e) {
        return fecha;
    }
}

// ============================================
// FUNCIÓN: Mostrar mensaje de error
// ============================================
function mostrarError(mensaje) {
    console.error("Error:", mensaje);
    if (typeof warning === "function") {
        warning("Error", mensaje);
    } else {
        alert(mensaje);
    }
}

// ============================================
// FUNCIÓN: Validar campos del formulario de reconocimientos
// ============================================
function validarFormularioReconocimientos() {
    const hitos = [30, 40, 50, 60];
    let errores = [];

    hitos.forEach((hito) => {
        const switch_ = document.getElementById(`reconocimiento-${hito}-anos`);
        const fechaInput = document.getElementById(`fecha-${hito}-anos`);

        if (switch_ && switch_.checked) {
            // Si el switch está activo, la fecha no debe estar vacía
            if (!fechaInput || !fechaInput.value) {
                errores.push(
                    `Reconocimiento de ${hito} años: La fecha es obligatoria cuando el switch está activo.`,
                );
            }
        }
    });

    if (errores.length > 0) {
        const mensajeError = errores.join("\n");
        mostrarError(
            "Por favor corrija los siguientes errores:\n\n" + mensajeError,
        );
        return false;
    }

    return true;
}

// ============================================
// FUNCIÓN: Preparar datos del formulario para enviar al servidor
// ============================================
function prepararDatosReconocimientos() {
    // Obtener ID del médico (debes tenerlo disponible en el modal)
    const modalEdit = document.getElementById("modalEdit");
    const idMedicoInput = modalEdit
        ? modalEdit.querySelector('input[name="id_medico"]')
        : null;
    let idMedico = null;

    if (idMedicoInput) {
        idMedico = idMedicoInput.value;
    } else {
        // Si no hay input oculto, intentar obtenerlo del atributo data del botón
        const botonActivo = document.querySelector(
            '[data-bs-target="#modalEdit"].active',
        );
        if (botonActivo) {
            const data = JSON.parse(botonActivo.getAttribute("data") || "{}");
            idMedico = data.id_medico;
        }
    }

    if (!idMedico) {
        console.error("No se pudo obtener el ID del médico");
        return null;
    }

    // Mapeo de años a IDs de reconocimiento
    const añosToIdMap = {
        30: 1,
        40: 2,
        50: 3,
        60: 4,
    };

    // Array para almacenar los reconocimientos
    const reconocimientos = [];

    // Procesar cada hito
    [30, 40, 50, 60].forEach((hito) => {
        const switch_ = document.getElementById(`reconocimiento-${hito}-anos`);
        const fechaInput = document.getElementById(`fecha-${hito}-anos`);

        if (switch_ && fechaInput) {
            const idReconocimiento = añosToIdMap[hito];
            const switchActivo = switch_.checked;
            const fecha = switchActivo ? fechaInput.value : null;

            reconocimientos.push({
                id_medico: parseInt(idMedico),
                id_medico_reconocimiento: idReconocimiento,
                switch_activo: switchActivo,
                fecha_entrega: fecha,
                años_hito: hito, // Opcional, para referencia
            });
        }
    });

    console.log("Datos preparados para enviar:", reconocimientos);
    return reconocimientos;
}

// ============================================
// FUNCIÓN: Preparar campos ocultos en el formulario
// ============================================
function prepararCamposOcultosReconocimientos() {
    // Obtener el formulario
    const form = document.getElementById("form-update");

    // Eliminar campos ocultos anteriores de reconocimientos (si existen)
    const camposAnteriores = form.querySelectorAll(
        ".reconocimiento-hidden-field",
    );
    camposAnteriores.forEach((campo) => campo.remove());

    // Obtener ID del médico
    const modalEdit = document.getElementById("modalEdit");
    const idMedicoInput = modalEdit
        ? modalEdit.querySelector('input[name="id_medico"]')
        : null;
    let idMedico = null;

    if (idMedicoInput) {
        idMedico = idMedicoInput.value;
    } else {
        // Si no hay input oculto, intentar obtenerlo de los datos del botón
        const botonActivo = document.querySelector(
            '[data-bs-target="#modalEdit"].active',
        );
        if (botonActivo) {
            const data = JSON.parse(botonActivo.getAttribute("data") || "{}");
            idMedico = data.id_medico;
        }
    }

    if (!idMedico) {
        console.error("No se pudo obtener el ID del médico");
        return false;
    }

    // Agregar campo oculto con el ID del médico
    const idMedicoHidden = document.createElement("input");
    idMedicoHidden.type = "hidden";
    idMedicoHidden.name = "id_medico";
    idMedicoHidden.value = idMedico;
    idMedicoHidden.classList.add("reconocimiento-hidden-field");
    form.appendChild(idMedicoHidden);

    // Mapeo de años a tipo_reconocimiento
    const añosToTipoMap = {
        30: 1, // 30 años = tipo_reconocimiento 1
        40: 2, // 40 años = tipo_reconocimiento 2
        50: 3, // 50 años = tipo_reconocimiento 3
        60: 4, // 60 años = tipo_reconocimiento 4
    };

    // Obtener los datos actuales de reconocimientos (cargados previamente)
    // Estos datos deberían estar disponibles globalmente después de cargar el modal
    const reconocimientosData = window.reconocimientosActuales || [];

    // Procesar cada hito
    [30, 40, 50, 60].forEach((hito) => {
        const switch_ = document.getElementById(`reconocimiento-${hito}-anos`);
        const fechaInput = document.getElementById(`fecha-${hito}-anos`);

        if (switch_ && fechaInput) {
            const tipoReconocimiento = añosToTipoMap[hito];
            const switchActivo = switch_.checked;
            const fecha = switchActivo ? fechaInput.value : "";

            // Buscar el id_medico_reconocimiento correspondiente a este tipo
            // Buscamos en los datos cargados del servidor
            const reconocimientoExistente = reconocimientosData.find(
                (r) => r.tipo_reconocimiento === tipoReconocimiento,
            );

            // Si encontramos un registro existente, usamos su id_medico_reconocimiento
            // Si no, usamos el tipo como fallback (asumiendo que son iguales para nuevos registros)
            const idMedicoReconocimiento = reconocimientoExistente
                ? reconocimientoExistente.id_medico_reconocimiento
                : tipoReconocimiento;

            console.log(
                `Procesando reconocimiento - Hito: ${hito} años, Tipo: ${tipoReconocimiento}, ID: ${idMedicoReconocimiento}, Activo: ${switchActivo}, Fecha: ${fecha}`,
            );

            // Campo oculto para id_medico_reconocimiento (clave primaria)
            const idHidden = document.createElement("input");
            idHidden.type = "hidden";
            idHidden.name = `reconocimientos[${idMedicoReconocimiento}][id_medico_reconocimiento]`;
            idHidden.value = idMedicoReconocimiento;
            idHidden.classList.add("reconocimiento-hidden-field");
            form.appendChild(idHidden);

            // Campo oculto para tipo_reconocimiento
            const tipoHidden = document.createElement("input");
            tipoHidden.type = "hidden";
            tipoHidden.name = `reconocimientos[${idMedicoReconocimiento}][tipo_reconocimiento]`;
            tipoHidden.value = tipoReconocimiento;
            tipoHidden.classList.add("reconocimiento-hidden-field");
            form.appendChild(tipoHidden);

            // Campo oculto para switch_activo
            const switchHidden = document.createElement("input");
            switchHidden.type = "hidden";
            switchHidden.name = `reconocimientos[${idMedicoReconocimiento}][switch_activo]`;
            switchHidden.value = switchActivo ? "1" : "0";
            switchHidden.classList.add("reconocimiento-hidden-field");
            form.appendChild(switchHidden);

            // Campo oculto para fecha_entrega
            const fechaHidden = document.createElement("input");
            fechaHidden.type = "hidden";
            fechaHidden.name = `reconocimientos[${idMedicoReconocimiento}][fecha_entrega]`;
            fechaHidden.value = fecha;
            fechaHidden.classList.add("reconocimiento-hidden-field");
            form.appendChild(fechaHidden);
        }
    });

    console.log("Campos ocultos preparados correctamente");
    return true;
}

// ============================================
// FUNCIÓN: Validar campos del formulario de reconocimientos
// ============================================
function validarFormularioReconocimientos() {
    const hitos = [30, 40, 50, 60];
    let errores = [];

    hitos.forEach((hito) => {
        const switch_ = document.getElementById(`reconocimiento-${hito}-anos`);
        const fechaInput = document.getElementById(`fecha-${hito}-anos`);

        if (switch_ && switch_.checked) {
            // Si el switch está activo, la fecha no debe estar vacía
            if (!fechaInput || !fechaInput.value) {
                errores.push(
                    `Reconocimiento de ${hito} años: La fecha es obligatoria cuando el switch está activo.`,
                );
            }
        }
    });

    if (errores.length > 0) {
        const mensajeError = errores.join("\n");
        mostrarError(
            "Por favor corrija los siguientes errores:\n\n" + mensajeError,
        );
        return false;
    }

    return true;
}

// ============================================
// EVENTO SUBMIT DEL FORMULARIO
// ============================================
document.getElementById("form-update").addEventListener("submit", (e) => {
    e.preventDefault();
    e.stopPropagation();

    console.log("Formulario submit iniciado");

    // Validar campos
    if (!validarFormularioReconocimientos()) {
        console.log("Validación falló");
        return false;
    }

    // Preparar campos ocultos
    const camposPreparados = prepararCamposOcultosReconocimientos();

    if (!camposPreparados) {
        console.log("Error al preparar campos");
        mostrarError(
            "Error al preparar los datos. ID de médico no encontrado.",
        );
        return false;
    }

    // Mostrar los datos que se van a enviar (para depuración)
    const formData = new FormData(e.target);
    console.log("Datos a enviar:");
    for (let pair of formData.entries()) {
        console.log(pair[0] + ": " + pair[1]);
    }

    // Enviar el formulario
    console.log("Enviando formulario...");
    e.target.submit();
});

// ============================================
// FUNCIÓN: Verificar campos ocultos (opcional)
// ============================================
function verificarCamposOcultos() {
    const form = document.getElementById("form-update");
    const campos = form.querySelectorAll(".reconocimiento-hidden-field");
    console.log(`Total campos ocultos: ${campos.length}`);
    campos.forEach((campo, index) => {
        console.log(`Campo ${index + 1}: ${campo.name} = ${campo.value}`);
    });
}

// ============================================
// FUNCIÓN: Inicializar reconocimientos vacíos
// ============================================
function inicializarReconocimientosVacios(esEdicion) {
    console.log(
        "Inicializando reconocimientos vacíos, modo edición:",
        esEdicion,
    );

    const hitos = [30, 40, 50, 60];

    hitos.forEach((hito) => {
        const switch_ = document.getElementById(`reconocimiento-${hito}-anos`);
        const fechaInput = document.getElementById(`fecha-${hito}-anos`);

        if (!switch_ || !fechaInput) {
            console.warn(`Elementos no encontrados para ${hito} años`);
            return;
        }

        if (esEdicion) {
            // Modo edición: switches habilitados pero sin marcar, fechas deshabilitadas
            switch_.checked = false;
            switch_.disabled = false;
            fechaInput.value = "";
            fechaInput.disabled = true;
        } else {
            // Modo visualización: todo deshabilitado
            switch_.checked = false;
            switch_.disabled = true;
            fechaInput.value = "";
            fechaInput.disabled = true;
        }
    });
}

// ============================================
// FUNCIÓN: Cargar datos básicos del médico
// ============================================
function cargarDatosMedico(registro, esEdicion) {
    console.log("Cargando datos del médico:", registro);

    // Validar que registro existe
    if (!registro) {
        console.error("Registro de médico es null o undefined");
        return;
    }

    // Actualizar título del modal
    const modalTitulo = esEdicion
        ? "Editar Reconocimientos"
        : "Ver Reconocimientos";
    const modalElement = esEdicion
        ? document.getElementById("modalEdit")
        : document.getElementById("modalShow");

    if (modalElement) {
        const tituloElement = modalElement.querySelector(".modal-title");
        if (tituloElement) {
            tituloElement.textContent = `${modalTitulo} - ${registro.nombres_apellidos || "Médico"}`;
        }

        // Agregar input oculto con el ID del médico (solo en modal de edición)
        if (esEdicion) {
            let idMedicoInput = modalElement.querySelector(
                'input[name="id_medico"]',
            );
            if (!idMedicoInput) {
                idMedicoInput = document.createElement("input");
                idMedicoInput.type = "hidden";
                idMedicoInput.name = "id_medico";
                modalElement
                    .querySelector(".modal-body")
                    .appendChild(idMedicoInput);
            }
            idMedicoInput.value = registro.id_medico;
        }
    }

    // Mostrar información adicional
    const infoMedicoElement = document.getElementById("info-medico");
    if (infoMedicoElement) {
        infoMedicoElement.innerHTML = `
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-user-doctor fa-2x me-3"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>${registro.nombres_apellidos || "Nombre no disponible"}</strong><br>
                        <small>
                            <i class="fa-regular fa-calendar me-1"></i> Egreso: ${formatearFecha(registro.fecha_egreso_universidad)} | 
                            <i class="fa-regular fa-clock me-1"></i> Años de ejercicio: ${registro.años_transcurridos_graduado || 0} años
                        </small>
                    </div>
                </div>
            </div>
        `;
    }
}

// ============================================
// FUNCIÓN: Cargar reconocimientos del médico
// ============================================
function cargarReconocimientosMedico(reconocimientos, esEdicion) {
    console.log("Cargando reconocimientos:", reconocimientos);

    // Validación más robusta
    if (!reconocimientos) {
        console.error("reconocimientos es null o undefined");
        inicializarReconocimientosVacios(esEdicion);
        return;
    }

    // Si no es array, intentar convertirlo
    if (!Array.isArray(reconocimientos)) {
        console.warn("reconocimientos no es un array, intentando convertir...");
        if (typeof reconocimientos === "object") {
            if (reconocimientos.id_medico_reconocimiento) {
                reconocimientos = [reconocimientos];
                console.log("Convertido a array con 1 elemento");
            } else {
                console.error(
                    "No se puede convertir a array:",
                    reconocimientos,
                );
                inicializarReconocimientosVacios(esEdicion);
                return;
            }
        } else {
            console.error("Tipo no manejado:", typeof reconocimientos);
            inicializarReconocimientosVacios(esEdicion);
            return;
        }
    }

    if (reconocimientos.length === 0) {
        console.log("Array de reconocimientos vacío");
        inicializarReconocimientosVacios(esEdicion);
        return;
    }

    // Mapeo de id_medico_reconocimiento a años
    const idToAñosMap = {
        1: 30,
        2: 40,
        3: 50,
        4: 60,
    };

    const hitosMap = {
        30: "reconocimiento-30-anos",
        40: "reconocimiento-40-anos",
        50: "reconocimiento-50-anos",
        60: "reconocimiento-60-anos",
    };

    const fechasMap = {
        30: "fecha-30-anos",
        40: "fecha-40-anos",
        50: "fecha-50-anos",
        60: "fecha-60-anos",
    };

    // Primero, inicializar todos como no otorgados
    inicializarReconocimientosVacios(esEdicion);

    // Función para verificar si una fecha es válida (no es 0000-00-00)
    function fechaEsValida(fecha) {
        return (
            fecha &&
            fecha !== "0000-00-00" &&
            fecha !== "null" &&
            fecha.trim() !== ""
        );
    }

    // Luego, marcar los que están otorgados
    let reconocimientosCargados = 0;

    reconocimientos.forEach((rec, index) => {
        console.log(`Procesando reconocimiento ${index + 1}:`, rec);

        // Validar que el registro tenga id_medico_reconocimiento
        if (!rec || !rec.id_medico_reconocimiento) {
            console.warn(
                "Registro inválido o sin id_medico_reconocimiento:",
                rec,
            );
            return;
        }

        const años = idToAñosMap[rec.id_medico_reconocimiento];

        if (!años) {
            console.warn(
                `ID de reconocimiento no mapeado: ${rec.id_medico_reconocimiento}`,
            );
            return;
        }

        const switchId = hitosMap[años];
        const fechaId = fechasMap[años];

        if (!switchId || !fechaId) {
            console.warn(`No se encontraron IDs para ${años} años`);
            return;
        }

        const switch_ = document.getElementById(switchId);
        const fechaInput = document.getElementById(fechaId);

        if (!switch_ || !fechaInput) {
            console.warn(`Elementos DOM no encontrados para ${años} años`);
            return;
        }

        // Verificar si el reconocimiento está otorgado (fecha_de_entrega válida)
        const otorgado = fechaEsValida(rec.fecha_de_entrega);

        console.log(
            `Reconocimiento de ${años} años - otorgado: ${otorgado}, fecha_de_entrega: ${rec.fecha_de_entrega}`,
        );

        if (esEdicion) {
            // Modo edición
            if (otorgado) {
                console.log(
                    `✓ Reconocimiento de ${años} años OTORGADO en modo edición con fecha: ${rec.fecha_de_entrega}`,
                );
                switch_.checked = true;
                fechaInput.value = rec.fecha_de_entrega;
                fechaInput.disabled = false;
                switch_.disabled = false;
            } else {
                console.log(
                    `✗ Reconocimiento de ${años} años NO OTORGADO en modo edición`,
                );
                switch_.checked = false;
                fechaInput.value = "";
                fechaInput.disabled = true;
                switch_.disabled = false;
            }
        } else {
            // Modo visualización
            if (otorgado) {
                console.log(
                    `✓ Reconocimiento de ${años} años OTORGADO en modo visualización con fecha: ${rec.fecha_de_entrega}`,
                );
                switch_.checked = true;
                fechaInput.value = rec.fecha_de_entrega;
                fechaInput.disabled = true;
                switch_.disabled = true;
            } else {
                console.log(
                    `✗ Reconocimiento de ${años} años NO OTORGADO en modo visualización`,
                );
                switch_.checked = false;
                fechaInput.value = "";
                fechaInput.disabled = true;
                switch_.disabled = true;
            }
        }

        reconocimientosCargados++;
    });

    console.log(
        `Total de reconocimientos procesados: ${reconocimientosCargados}`,
    );
}

// ============================================
// FUNCIÓN: Limpiar modal (modificada para limpiar también los datos globales)
// ============================================
function limpiar_modal() {
    console.log("Limpiando modal...");

    // Limpiar datos globales
    window.reconocimientosActuales = [];

    // Limpiar fecha de egreso
    const fechaEgresoInput = document.getElementById("fecha-egreso");
    if (fechaEgresoInput) {
        fechaEgresoInput.value = "";
        fechaEgresoInput.disabled = false;
    }

    // Resetear todos los switches y fechas
    inicializarReconocimientosVacios(true);

    // Limpiar información del médico
    const infoMedicoElement = document.getElementById("info-medico");
    if (infoMedicoElement) {
        infoMedicoElement.innerHTML = "";
    }

    // Eliminar input oculto del ID del médico
    const modalEdit = document.getElementById("modalEdit");
    if (modalEdit) {
        const idMedicoInput = modalEdit.querySelector(
            'input[name="id_medico"]',
        );
        if (idMedicoInput) {
            idMedicoInput.remove();
        }
    }

    // Resetear títulos del modal
    const modalShow = document.getElementById("modalShow");

    if (modalEdit) {
        const tituloEdit = modalEdit.querySelector(".modal-title");
        if (tituloEdit) tituloEdit.textContent = "Editar Reconocimientos";
    }

    if (modalShow) {
        const tituloShow = modalShow.querySelector(".modal-title");
        if (tituloShow) tituloShow.textContent = "Ver Reconocimientos";
    }

    // Resetear antigüedad
    if (typeof actualizarAntiguedad === "function") {
        actualizarAntiguedad();
    }
}

// ============================================
// FUNCIÓN: Cargar modal (modificada para manejar edición y visualización)
// ============================================
async function cargar_modal(button, tipo) {
    try {
        // Obtener datos del botón
        let data = JSON.parse(button.getAttribute("data"));
        let id_medico = JSON.parse(button.getAttribute("id-medico"));

        console.log(
            `Cargando datos para médico ID: ${id_medico}, tipo: ${tipo}`,
        );
        console.log("Data del botón:", data);

        // Determinar qué modal estamos abriendo
        const esModalEdicion = tipo === 1; // 1 = edición, 2 = visualización

        // Hacer petición GET al servidor para obtener los reconocimientos
        const response = await fetch(
            `/SIMA/reconocimientos-medico-buscar/${id_medico}`,
            {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            },
        );

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const responseText = await response.text();
        console.log("Respuesta del servidor (texto):", responseText);

        // Parsear el texto a JSON
        let responseData;
        try {
            responseData = JSON.parse(responseText);
            console.log("Respuesta parseada:", responseData);
        } catch (parseError) {
            console.error("Error al parsear JSON:", parseError);
            throw new Error("La respuesta del servidor no es JSON válido");
        }

        // Guardar los datos de reconocimientos globalmente para usarlos después
        if (Array.isArray(responseData)) {
            window.reconocimientosActuales = responseData;
            console.log(
                "Datos guardados globalmente:",
                window.reconocimientosActuales,
            );
        } else {
            window.reconocimientosActuales = [];
        }

        // Validar que responseData sea un array
        let reconocimientos = [];

        if (responseData && Array.isArray(responseData)) {
            reconocimientos = responseData;
            console.log(
                "Es un array directo, longitud:",
                reconocimientos.length,
            );
        } else if (responseData && typeof responseData === "object") {
            if (responseData.data && Array.isArray(responseData.data)) {
                reconocimientos = responseData.data;
                console.log(
                    "Tiene propiedad data array, longitud:",
                    reconocimientos.length,
                );
            } else if (
                responseData.reconocimientos &&
                Array.isArray(responseData.reconocimientos)
            ) {
                reconocimientos = responseData.reconocimientos;
                console.log(
                    "Tiene propiedad reconocimientos array, longitud:",
                    reconocimientos.length,
                );
            } else if (responseData.id_medico_reconocimiento) {
                reconocimientos = [responseData];
                console.log("Convertido a array con 1 elemento");
            }
        }

        console.log("Reconocimientos procesados:", reconocimientos);
        console.log("Longitud final:", reconocimientos.length);

        // SI ES MODAL DE EDICIÓN (tipo 1)
        if (esModalEdicion) {
            // Cargar datos básicos desde el botón
            cargarDatosMedico(data, true);

            // Establecer la fecha de egreso desde el botón
            if (data.fecha_egreso_universidad) {
                const fechaEgresoInput =
                    document.getElementById("fecha-egreso");
                if (fechaEgresoInput) {
                    fechaEgresoInput.value = data.fecha_egreso_universidad;
                    const event = new Event("change", { bubbles: true });
                    fechaEgresoInput.dispatchEvent(event);
                }
            }

            // Cargar los reconocimientos en el modal de edición
            if (reconocimientos.length > 0) {
                console.log(
                    "Cargando reconocimientos desde el servidor para EDICIÓN",
                );
                cargarReconocimientosMedico(reconocimientos, true);
            } else {
                console.log(
                    "No hay reconocimientos, inicializando vacíos para EDICIÓN",
                );
                inicializarReconocimientosVacios(true);
            }
        }
        // SI ES MODAL DE VISUALIZACIÓN (tipo 2)
        else {
            console.log("Preparando modal de VISUALIZACIÓN");

            // Si no hay reconocimientos, crear estructura vacía para mostrar
            if (reconocimientos.length === 0) {
                reconocimientos = [
                    {
                        tipo_reconocimiento: 1,
                        fecha_de_entrega: "0000-00-00",
                        id_medico_reconocimiento: 1,
                    },
                    {
                        tipo_reconocimiento: 2,
                        fecha_de_entrega: "0000-00-00",
                        id_medico_reconocimiento: 2,
                    },
                    {
                        tipo_reconocimiento: 3,
                        fecha_de_entrega: "0000-00-00",
                        id_medico_reconocimiento: 3,
                    },
                    {
                        tipo_reconocimiento: 4,
                        fecha_de_entrega: "0000-00-00",
                        id_medico_reconocimiento: 4,
                    },
                ];
            }

            // Cargar datos en el modal de visualización
            cargarModalShow(reconocimientos, data);
        }
    } catch (error) {
        console.error("Error en cargar_modal:", error);
        mostrarError(
            "Error al cargar los datos del médico. Por favor, intente nuevamente.",
        );

        // Intentar cargar al menos los datos básicos
        try {
            let data = JSON.parse(button.getAttribute("data"));
            const esModalEdicion = tipo === 1;

            if (data) {
                if (esModalEdicion) {
                    console.log(
                        "Cargando datos de respaldo desde el botón para EDICIÓN",
                    );
                    cargarDatosMedico(data, true);

                    if (data.fecha_egreso_universidad) {
                        const fechaEgresoInput =
                            document.getElementById("fecha-egreso");
                        if (fechaEgresoInput) {
                            fechaEgresoInput.value =
                                data.fecha_egreso_universidad;
                            const event = new Event("change", {
                                bubbles: true,
                            });
                            fechaEgresoInput.dispatchEvent(event);
                        }
                    }

                    inicializarReconocimientosVacios(true);
                } else {
                    console.log(
                        "Cargando datos de respaldo desde el botón para VISUALIZACIÓN",
                    );
                    // Crear estructura vacía para visualización
                    const reconocimientosVacios = [
                        {
                            tipo_reconocimiento: 1,
                            fecha_de_entrega: "0000-00-00",
                        },
                        {
                            tipo_reconocimiento: 2,
                            fecha_de_entrega: "0000-00-00",
                        },
                        {
                            tipo_reconocimiento: 3,
                            fecha_de_entrega: "0000-00-00",
                        },
                        {
                            tipo_reconocimiento: 4,
                            fecha_de_entrega: "0000-00-00",
                        },
                    ];
                    cargarModalShow(reconocimientosVacios, data);
                }
            }
        } catch (e) {
            console.error("Error al cargar datos de respaldo:", e);
        }
    }
}

// ============================================
// FUNCIÓN: Calcular fecha de hito
// ============================================
function calcularFechaHito(fechaEgreso, años) {
    if (!fechaEgreso) return null;
    const fecha = new Date(fechaEgreso);
    fecha.setFullYear(fecha.getFullYear() + años);
    return fecha.toISOString().split("T")[0];
}

// ============================================
// DOCUMENT READY - Inicialización
// ============================================
document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM Cargado - Inicializando...");

    // Configurar eventos de cierre de modales
    const cerrarEdit = document.getElementById("cerrar-modal-edit");
    const cerrarShow = document.getElementById("cerrar-modal-show");

    if (cerrarEdit) {
        cerrarEdit.addEventListener("click", limpiar_modal);
        console.log("Evento de cierre configurado para modalEdit");
    } else {
        console.warn("Botón cerrar-modal-edit no encontrado");
    }

    if (cerrarShow) {
        cerrarShow.addEventListener("click", limpiar_modal);
        console.log("Evento de cierre configurado para modalShow");
    } else {
        console.warn("Botón cerrar-modal-show no encontrado");
    }

    // También limpiar cuando se cierra el modal con el botón X o haciendo clic fuera
    const modalEdit = document.getElementById("modalEdit");
    const modalShow = document.getElementById("modalShow");

    if (modalEdit) {
        modalEdit.addEventListener("hidden.bs.modal", limpiar_modal);
        console.log("Evento hidden configurado para modalEdit");
    }

    if (modalShow) {
        modalShow.addEventListener("hidden.bs.modal", limpiar_modal);
        console.log("Evento hidden configurado para modalShow");
    }

    // Configurar el botón de guardar del modal de edición
    const btnGuardarEdit = document.querySelector("#modalEdit .btn-primary");
    if (btnGuardarEdit) {
        btnGuardarEdit.addEventListener("click", async (e) => {
            e.preventDefault();
            await enviarReconocimientos();
        });
    }
});

// ============================================
// SEGUNDO DOM CONTENT LOADED - Tabla
// ============================================
document.addEventListener("DOMContentLoaded", () => {
    var data = [];
    var i = 0;
    dataD.forEach((elemento, index) => {
        acciones = `
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit" data='${elemento.data}' id-medico="${elemento["id_medico"]}" onclick="cargar_modal(this, 1);" ${puedeActualizar ? "" : "disabled"}>
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalShow" data='${elemento.data}' id-medico="${elemento["id_medico"]}" onclick="cargar_modal(this, 2);">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>`;

        data[i] = {
            contador: i + 1,
            Nombre: elemento["nombres_apellidos"],
            cant_recibidos: elemento["cant_recibidos"],
            cant_faltantes: elemento["cant_faltantes"],
            Acciones: acciones,
        };
        i++;
    });

    const datos = {
        id_tabla: "#tabla",
        data: data,
        columns: [
            {
                data: "contador",
                title: "#",
                className: "text-center",
            },
            {
                data: "Nombre",
                title: "Nombre del Médico",
                className: "text-center td-datatable",
            },
            {
                data: "cant_recibidos",
                title: "Cant. de Reconocimientos Recibidos",
                className: "text-center td-datatable",
            },
            {
                data: "cant_faltantes",
                title: "Cant. de Reconocimientos Faltantes",
                className: "text-center td-datatable",
            },
            {
                data: "Acciones",
                title: "Acciones",
                className: "text-center",
            },
        ],
    };

    cargar_tabla(datos);

    document
        .getElementById("cerrar-modal-edit")
        .addEventListener("click", limpiar_modal);
    document
        .getElementById("cerrar-modal-show")
        .addEventListener("click", limpiar_modal);
});

// ============================================
// TERCER DOM CONTENT LOADED - Funcionalidad del modal de reconocimientos
// ============================================
document.addEventListener("DOMContentLoaded", function () {
    // ============================================
    // OBTENER ELEMENTOS DEL DOM
    // ============================================
    const fechaEgresoInput = document.getElementById("fecha-egreso");
    const aniosEjercicioInput = document.getElementById("anios-ejercicio");
    const aniosActualesSpan = document.getElementById("anios-actuales");
    const progressBar = document.getElementById("barra-progreso-principal");

    if (
        !fechaEgresoInput ||
        !aniosEjercicioInput ||
        !aniosActualesSpan ||
        !progressBar
    ) {
        console.error(
            "No se encontraron todos los elementos necesarios en el DOM",
        );
        return;
    }

    // ============================================
    // FUNCIÓN: Calcular años de ejercicio
    // ============================================
    function calcularAniosEjercicio(fechaEgreso) {
        if (!fechaEgreso) return 0;

        const egreso = new Date(fechaEgreso.replace(/-/g, '\/'));
        const hoy = new Date();

        if (isNaN(egreso.getTime())) return 0;

        let años = hoy.getFullYear() - egreso.getFullYear();
        const mes = hoy.getMonth() - egreso.getMonth();

        if (mes < 0 || (mes === 0 && hoy.getDate() < egreso.getDate())) {
            años--;
        }

        return Math.max(0, años);
    }

    // ============================================
    // FUNCIÓN: Actualizar badges de elegibilidad
    // ============================================
    function actualizarElegibilidades(años) {
        const hitos = [30, 40, 50, 60];

        hitos.forEach((hito) => {
            const elegibilidadBadge = document.getElementById(
                `elegibilidad-${hito}`,
            );
            const switch_ = document.getElementById(
                `reconocimiento-${hito}-anos`,
            );
            const card = document.getElementById(`card-${hito}-anos`);
            const fechaInput = document.getElementById(`fecha-${hito}-anos`);

            if (!elegibilidadBadge || !switch_ || !card) return;

            if (años >= hito) {
                elegibilidadBadge.className =
                    "badge bg-success-subtle text-success-emphasis elegibilidad-badge";
                elegibilidadBadge.innerHTML =
                    '<i class="fa-solid fa-circle-check me-1"></i>Elegible';
                card.classList.remove("opacity-50", "text-muted");
                switch_.disabled = false;

                if (fechaInput && switch_.checked) {
                    fechaInput.disabled = false;
                }
            } else {
                const añosFaltantes = hito - años;
                elegibilidadBadge.className =
                    "badge bg-secondary-subtle text-secondary-emphasis elegibilidad-badge";
                elegibilidadBadge.innerHTML = `<i class="fa-solid fa-hourglass-half me-1"></i>Faltan ${añosFaltantes} años`;
                card.classList.add("opacity-50", "text-muted");
                switch_.disabled = true;
                switch_.checked = false;

                if (fechaInput) {
                    fechaInput.disabled = true;
                    fechaInput.value = "";
                }
            }
        });
    }

    // ============================================
    // FUNCIÓN: Actualizar todo
    // ============================================
    function actualizarAntiguedad() {
        const fechaEgreso = fechaEgresoInput.value;

        if (fechaEgreso) {
            const años = calcularAniosEjercicio(fechaEgreso);

            aniosEjercicioInput.value = años + " años";
            aniosActualesSpan.textContent = años + " años";

            const progreso = calcularProgresoPorHito(años);
            progressBar.style.width = progreso + "%";
            progressBar.setAttribute("aria-valuenow", años);
            progressBar.setAttribute("aria-valuemax", 60);
            progressBar.innerHTML = `<span class="fw-bold">${años} años</span>`;

            actualizarElegibilidades(años);
            actualizarFechasSugeridas(años);

            console.log(`Años: ${años}, Progreso: ${progreso.toFixed(2)}%`);
        } else {
            aniosEjercicioInput.value = "0 años";
            aniosActualesSpan.textContent = "0 años";
            progressBar.style.width = "0%";
            progressBar.innerHTML = "";
            actualizarElegibilidades(0);
        }
    }

    // ============================================
    // FUNCIÓN: Actualizar fechas sugeridas
    // ============================================
    function actualizarFechasSugeridas(años) {
        const hitos = [30, 40, 50, 60];

        hitos.forEach((hito) => {
            const switch_ = document.getElementById(
                `reconocimiento-${hito}-anos`,
            );
            const fechaInput = document.getElementById(`fecha-${hito}-anos`);

            if (
                switch_ &&
                switch_.checked &&
                fechaInput &&
                !fechaInput.value &&
                fechaEgresoInput.value
            ) {
                sugerirFechaHito(hito, fechaInput);
            }
        });
    }

    // ============================================
    // FUNCIÓN: Sugerir fecha de hito
    // ============================================
    function sugerirFechaHito(hito, fechaInput) {
        const fechaEgreso = fechaEgresoInput.value;
        if (!fechaEgreso) return;

        const fecha = new Date(fechaEgreso);
        if (!isNaN(fecha.getTime())) {
            fecha.setFullYear(fecha.getFullYear() + hito);
            fechaInput.value = fecha.toISOString().split("T")[0];
        }
    }

    // ============================================
    // FUNCIÓN: Configurar toggle de reconocimiento
    // ============================================
    function setupReconocimientoToggle(switchId, dateId, hito) {
        const switch_ = document.getElementById(switchId);
        const dateInput = document.getElementById(dateId);

        if (!switch_ || !dateInput) {
            console.warn(
                `No se encontraron elementos para ${switchId} o ${dateId}`,
            );
            return;
        }

        switch_.addEventListener("change", function () {
            const años = calcularAniosEjercicio(fechaEgresoInput.value);

            if (this.checked && años < hito) {
                this.checked = false;
                alert(
                    `No es elegible para este reconocimiento. Faltan ${hito - años} años.`,
                );
                return;
            }

            dateInput.disabled = !this.checked;

            if (this.checked && !dateInput.value && fechaEgresoInput.value) {
                sugerirFechaHito(hito, dateInput);
            }

            if (!this.checked) {
                dateInput.value = "";
            }
        });

        const años = calcularAniosEjercicio(fechaEgresoInput.value);
        if (años < hito) {
            switch_.disabled = true;
            dateInput.disabled = true;
        }
    }

    // ============================================
    // FUNCIÓN: Validar fechas
    // ============================================
    function setupValidacionFechas() {
        const hitos = [30, 40, 50, 60];

        hitos.forEach((hito) => {
            const fechaInput = document.getElementById(`fecha-${hito}-anos`);

            if (fechaInput) {
                fechaInput.addEventListener("change", function () {
                    const fechaEgreso = fechaEgresoInput.value;
                    if (!fechaEgreso || !this.value) return;

                    const fechaEgresoDate = new Date(fechaEgreso);
                    const fechaHitoDate = new Date(this.value);

                    if (
                        isNaN(fechaEgresoDate.getTime()) ||
                        isNaN(fechaHitoDate.getTime())
                    )
                        return;

                    const añosDiferencia =
                        fechaHitoDate.getFullYear() -
                        fechaEgresoDate.getFullYear();

                    if (añosDiferencia !== hito) {
                        console.warn(
                            `La fecha sugerida para ${hito} años debería ser aproximadamente ${hito} años después del egreso`,
                        );
                    }
                });
            }
        });
    }

    // ============================================
    // CONFIGURAR EVENTOS INICIALES
    // ============================================

    if (fechaEgresoInput) {
        fechaEgresoInput.addEventListener("change", actualizarAntiguedad);
        fechaEgresoInput.addEventListener("blur", actualizarAntiguedad);
    }

    setupReconocimientoToggle("reconocimiento-30-anos", "fecha-30-anos", 30);
    setupReconocimientoToggle("reconocimiento-40-anos", "fecha-40-anos", 40);
    setupReconocimientoToggle("reconocimiento-50-anos", "fecha-50-anos", 50);
    setupReconocimientoToggle("reconocimiento-60-anos", "fecha-60-anos", 60);

    setupValidacionFechas();

    if (fechaEgresoInput.value) {
        actualizarAntiguedad();
    }

    window.resetearReconocimientos = function () {
        const hitos = [30, 40, 50, 60];

        hitos.forEach((hito) => {
            const switch_ = document.getElementById(
                `reconocimiento-${hito}-anos`,
            );
            const fechaInput = document.getElementById(`fecha-${hito}-anos`);

            if (switch_) {
                switch_.checked = false;
                switch_.disabled = true;
            }

            if (fechaInput) {
                fechaInput.value = "";
                fechaInput.disabled = true;
            }
        });

        if (fechaEgresoInput) {
            fechaEgresoInput.value = "";
        }

        actualizarAntiguedad();
    };
});
