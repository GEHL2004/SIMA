// form-medicos-edit.js - Versión completa actualizada para edición
// Variables globales actualizadas
let especialidadCount = 0;
let documentoCount = 0;
let cursoCount = 0;
let diplomadoCount = 0;
let cedulaValida = false;
let rifValido = false;
let impreValido = false;
let especialidadesSeleccionadas = new Set();

// Variables para el modo edición
let documentosAEliminar = new Set();
let documentosAConservar = new Set();

// Función para crear el buscador de especialidades
function crearBuscadorEspecialidades(containerId, onSelectCallback) {
    const container = document.getElementById(containerId);

    // Limpiar el contenedor si ya tiene contenido
    container.innerHTML = "";

    // Crear contenedor del buscador
    const searchContainer = document.createElement("div");
    searchContainer.className = "especialidad-search-container mb-3";

    searchContainer.innerHTML = `
        <div class="especialidad-search-wrapper">
            <input type="text" 
                   class="form-control especialidad-search-input" 
                   placeholder="Buscar especialidad o subespecialidad...">
            <button type="button" class="especialidad-search-clear" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="especialidad-options-list"></div>
    `;

    container.appendChild(searchContainer);

    const searchInput = searchContainer.querySelector(
        ".especialidad-search-input",
    );
    const clearButton = searchContainer.querySelector(
        ".especialidad-search-clear",
    );
    const optionsList = searchContainer.querySelector(
        ".especialidad-options-list",
    );

    // Variable para almacenar la especialidad actualmente seleccionada en ESTE buscador
    let especialidadActual = null;

    // Función para actualizar la opción seleccionada
    function actualizarSeleccion(id, nombre, tipo) {
        const especialidadAnterior = especialidadActual
            ? { ...especialidadActual }
            : null;
        especialidadActual = { id, nombre, tipo };

        // Llamar al callback para manejar la selección
        onSelectCallback(id, nombre, tipo, especialidadAnterior);
    }

    // Filtrar y mostrar opciones
    function filtrarOpciones() {
        const searchTerm = searchInput.value.toLowerCase();
        optionsList.innerHTML = "";

        // Filtrar especialidades NO seleccionadas en otros buscadores
        // O que sean la especialidad actual de ESTE buscador
        const opcionesFiltradas = todasEspecialidades.filter((esp) => {
            const estaSeleccionadaEnOtro = especialidadesSeleccionadas.has(
                esp.id.toString(),
            );
            const esLaActual =
                especialidadActual &&
                esp.id.toString() === especialidadActual.id.toString();

            // Mostrar si:
            // 1. NO está seleccionada en otro buscador, O
            // 2. ES la especialidad actual de este buscador
            return (
                (!estaSeleccionadaEnOtro || esLaActual) &&
                esp.nombre.toLowerCase().includes(searchTerm)
            );
        });

        if (opcionesFiltradas.length === 0) {
            optionsList.innerHTML =
                '<div class="no-results">No se encontraron resultados</div>';
        } else {
            // Separar especialidades y subespecialidades
            const especialidades = opcionesFiltradas.filter(
                (esp) => esp.tipo === "especialidad",
            );
            const subespecialidades = opcionesFiltradas.filter(
                (esp) => esp.tipo === "subespecialidad",
            );

            // Agregar título para especialidades si hay
            if (especialidades.length > 0) {
                agregarTitulo("Especialidades");
                especialidades.forEach(agregarOpcion);
            }

            // Agregar título para subespecialidades si hay
            if (subespecialidades.length > 0) {
                agregarTitulo("Subespecialidades");
                subespecialidades.forEach(agregarOpcion);
            }
        }
    }

    function agregarTitulo(texto) {
        const titulo = document.createElement("div");
        titulo.className = "especialidad-option-title";
        titulo.textContent = texto;
        optionsList.appendChild(titulo);
    }

    function agregarOpcion(opcion) {
        const optionDiv = document.createElement("div");
        optionDiv.className = "especialidad-option";

        // Marcar como seleccionada si es la actual
        if (
            especialidadActual &&
            opcion.id.toString() === especialidadActual.id.toString()
        ) {
            optionDiv.classList.add("selected");
        }

        optionDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <span>${opcion.nombre}</span>
                <span class="badge ${
                    opcion.tipo === "subespecialidad"
                        ? "bg-purple"
                        : "bg-primary"
                }">
                    ${opcion.tipo === "subespecialidad" ? "Sub" : "Esp"}
                </span>
            </div>
        `;

        optionDiv.addEventListener("click", function () {
            // Si ya está seleccionada, no hacer nada
            if (
                especialidadActual &&
                opcion.id.toString() === especialidadActual.id.toString()
            ) {
                return;
            }

            // Actualizar la selección
            actualizarSeleccion(opcion.id, opcion.nombre, opcion.tipo);

            // Limpiar y cerrar el buscador
            searchInput.value = "";
            clearButton.style.display = "none";
            optionsList.classList.remove("show");
        });

        optionsList.appendChild(optionDiv);
    }

    // Mostrar/ocultar lista al enfocar/blur
    searchInput.addEventListener("focus", function () {
        filtrarOpciones();
        optionsList.classList.add("show");
    });

    searchInput.addEventListener("input", function () {
        filtrarOpciones();
        clearButton.style.display = this.value ? "block" : "none";
    });

    // Limpiar búsqueda
    clearButton.addEventListener("click", function () {
        searchInput.value = "";
        filtrarOpciones();
        this.style.display = "none";
        searchInput.focus();
    });

    // Ocultar lista al hacer clic fuera
    document.addEventListener("click", function (event) {
        if (!searchContainer.contains(event.target)) {
            optionsList.classList.remove("show");
        }
    });

    // Función para limpiar la selección actual
    function limpiarSeleccion() {
        if (especialidadActual && especialidadActual.id) {
            const especialidadAnterior = { ...especialidadActual };
            especialidadActual = null;

            // Notificar que se limpió la selección
            onSelectCallback(null, null, null, especialidadAnterior);
        }
        filtrarOpciones();
    }

    return {
        searchInput,
        optionsList,
        clearButton,
        limpiarSeleccion,
        filtrarOpciones,
        getEspecialidadActual: () => especialidadActual,
    };
}

function actualizarOpcionesDisponibles() {
    // Esta función actualiza qué opciones están disponibles para seleccionar
}

// Función para validar cédula (7-9 dígitos)
function validarCedula(input, esRequerido = true) {
    const cedula = input.value.trim();
    const cedulaRegex = /^\d{7,9}$/;

    // Remover clases previas
    input.classList.remove(
        "is-valid",
        "is-invalid",
        "cedula-valid",
        "cedula-invalid",
    );

    // Ocultar todos los mensajes de feedback inicialmente
    const feedback = document.getElementById("cedula-feedback");
    const validFeedback = document.getElementById("cedula-valid-feedback");

    if (feedback) feedback.style.display = "none";
    if (validFeedback) validFeedback.style.display = "none";

    // Si el campo está vacío
    if (cedula === "") {
        cedulaValida = false;

        // Solo mostrar error si es requerido y estamos validando para avanzar
        if (esRequerido) {
            input.classList.add("is-invalid");
            if (feedback) {
                feedback.style.display = "block";
                feedback.textContent = "La cédula es requerida.";
            }
        }
        return false;
    }

    // Si hay contenido pero no cumple con el formato
    if (!cedulaRegex.test(cedula)) {
        cedulaValida = false;
        input.classList.add("is-invalid");
        input.classList.add("cedula-invalid");
        if (feedback) {
            feedback.style.display = "block";
            feedback.textContent =
                "La cédula debe tener entre 7 y 9 dígitos numéricos.";
        }
        return false;
    }

    // Cédula válida
    cedulaValida = true;
    input.classList.add("is-valid");
    input.classList.add("cedula-valid");
    if (validFeedback) validFeedback.style.display = "block";
    return true;
}

// Función para validar RIF
function validarRIF(input, esRequerido = false) {
    const rif = input.value.trim().toUpperCase();
    const rifRegex = /^([VJEGP])[-](\d{7,9})[-](\d)$/;

    // Remover clases previas
    input.classList.remove(
        "is-valid",
        "is-invalid",
        "rif-valid",
        "rif-invalid",
    );

    // Ocultar todos los mensajes de feedback inicialmente
    const feedback = document.getElementById("rif-feedback");
    const validFeedback = document.getElementById("rif-valid-feedback");

    if (feedback) feedback.style.display = "none";
    if (validFeedback) validFeedback.style.display = "none";

    // Si el campo está vacío (es opcional)
    if (rif === "") {
        rifValido = true; // Vacío es válido porque es opcional
        return true;
    }

    // Si hay contenido pero no cumple con el formato
    if (!rifRegex.test(rif)) {
        rifValido = false;
        input.classList.add("is-invalid");
        input.classList.add("rif-invalid");
        if (feedback) {
            feedback.style.display = "block";
            feedback.textContent =
                "Formato inválido. Use: J-12345678-9 (letra J, V, E, G, P seguida de guión, 7-9 dígitos, guión y 1 dígito)";
        }
        return false;
    }

    // RIF válido
    rifValido = true;
    input.classList.add("is-valid");
    input.classList.add("rif-valid");
    if (validFeedback) validFeedback.style.display = "block";

    // Convertir a mayúsculas
    if (rif !== input.value) {
        input.value = rif;
    }

    return true;
}

// Función para validar IMPRE
function validarIMPRE(input, esRequerido = false) {
    const impre = input.value.trim();
    const impreRegex = /^\d{6,20}$/;

    // Remover clases previas
    input.classList.remove(
        "is-valid",
        "is-invalid",
        "impre-valid",
        "impre-invalid",
    );

    // Ocultar todos los mensajes de feedback inicialmente
    const feedback = document.getElementById("impre-feedback");
    const validFeedback = document.getElementById("impre-valid-feedback");

    if (feedback) feedback.style.display = "none";
    if (validFeedback) validFeedback.style.display = "none";

    // Si el campo está vacío (es opcional)
    if (impre === "") {
        impreValido = true; // Vacío es válido porque es opcional
        return true;
    }

    // Si hay contenido pero no cumple con el formato
    if (!impreRegex.test(impre)) {
        impreValido = false;
        input.classList.add("is-invalid");
        input.classList.add("impre-invalid");
        if (feedback) {
            feedback.style.display = "block";
            feedback.textContent =
                "El IMPRE debe tener entre 6 y 20 dígitos numéricos.";
        }
        return false;
    }

    // IMPRE válido
    impreValido = true;
    input.classList.add("is-valid");
    input.classList.add("impre-valid");
    if (validFeedback) validFeedback.style.display = "block";
    return true;
}

// Función para traer parroquias según el municipio seleccionado
function traerParroquias(select) {
    const municipioId = select.value;
    const selectParroquia = document.getElementById("id_parroquia");

    if (!municipioId) {
        selectParroquia.innerHTML =
            '<option value="">Primero seleccione un municipio</option>';
        selectParroquia.disabled = true;
        selectParroquia.classList.remove("is-valid");
        return;
    }

    fetch(`/SIMA/BuscadorDeSitios/${select.value}`, {
        method: "GET",
        headers: {
            "content-type": "application/json",
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            console.log(data);
            let dataD = JSON.parse(data);
            selectParroquia.innerHTML = "";
            let option =
                '<option value="" selected disabled>- Seleccione -</option>';
            for (let i in dataD) {
                option += `<option value="${dataD[i].id_parroquia}">${dataD[i].nombre_parroquia}</option>`;
            }
            selectParroquia.innerHTML = option;

            selectParroquia.disabled = false;
            selectParroquia.classList.remove("is-valid", "is-invalid");

            // Actualizar resumen
            actualizarResumen();
        });
}

// Función para actualizar opciones de especialidades excluyendo las ya seleccionadas
function actualizarOpcionesEspecialidades() {
    const selectsEspecialidades = document.querySelectorAll(
        ".especialidad-select",
    );

    const idsSeleccionados = [];
    selectsEspecialidades.forEach((select) => {
        if (select.value) {
            idsSeleccionados.push(select.value);
        }
    });

    selectsEspecialidades.forEach((select) => {
        const valorActual = select.value;
        const selectName = select.getAttribute("name");
        let selectId = "";

        if (selectName) {
            const match = selectName.match(/\[(\d+)\]/);
            if (match) {
                selectId = match[1];
            }
        }

        if (!select.dataset.originalOptions) {
            select.dataset.originalOptions = select.innerHTML;
        }

        const parser = new DOMParser();
        const originalDoc = parser.parseFromString(
            select.dataset.originalOptions,
            "text/html",
        );
        const originalOptions = originalDoc.querySelectorAll("option");

        select.innerHTML = "";

        const defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "- Seleccione -";
        defaultOption.disabled = true;
        defaultOption.selected = !valorActual;
        select.appendChild(defaultOption);

        originalOptions.forEach((option) => {
            if (option.value && option.value !== "") {
                const esSeleccionadoEnOtro =
                    idsSeleccionados.includes(option.value) &&
                    option.value !== valorActual;

                if (!esSeleccionadoEnOtro) {
                    const newOption = document.createElement("option");
                    newOption.value = option.value;
                    newOption.textContent = option.textContent;
                    newOption.selected = option.value === valorActual;
                    select.appendChild(newOption);
                }
            }
        });

        if (valorActual && !idsSeleccionados.includes(valorActual)) {
            select.value = "";
            const fechaInput = document.querySelector(
                `input[name="especialidades[${selectId}][fecha_obtencion]"]`,
            );
            if (fechaInput) {
                fechaInput.value = "";
                fechaInput.classList.remove("is-valid", "is-invalid");
            }
            select.classList.remove("is-valid", "is-invalid");
        }
    });

    especialidadesSeleccionadas = new Set(idsSeleccionados);
    actualizarResumen();
}

// Navegación entre pasos para 5 pasos
function nextStep(nextStepNumber) {
    // Validar el paso actual antes de avanzar
    if (!validarPasoActual(nextStepNumber - 1)) {
        return;
    }

    // Validar especialidades si estamos en el paso 3
    if (nextStepNumber === 4 && !validarEspecialidades()) {
        return;
    }

    // Actualizar indicadores de pasos
    document
        .querySelectorAll(".step-indicator .step")
        .forEach((step, index) => {
            step.classList.remove("active");
            if (index + 1 < nextStepNumber) {
                step.classList.add("completed");
            }
            if (index + 1 === nextStepNumber) {
                step.classList.add("active");
            }
        });

    // Mostrar el siguiente paso y ocultar los demás
    document.querySelectorAll(".form-section").forEach((section) => {
        section.classList.remove("active");
    });
    document.getElementById(`step${nextStepNumber}`).classList.add("active");

    // Actualizar resumen si estamos en el paso 5
    if (nextStepNumber === 5) {
        actualizarResumen();
    }
}

function prevStep(currentStepNumber) {
    const prevStepNumber = currentStepNumber - 1;

    // Actualizar indicadores de pasos
    document
        .querySelectorAll(".step-indicator .step")
        .forEach((step, index) => {
            step.classList.remove("active");
            if (index + 1 === prevStepNumber) {
                step.classList.add("active");
            }
            if (index + 1 >= currentStepNumber) {
                step.classList.remove("completed");
            }
        });

    // Mostrar el paso anterior y ocultar los demás
    document.querySelectorAll(".form-section").forEach((section) => {
        section.classList.remove("active");
    });
    document.getElementById(`step${prevStepNumber}`).classList.add("active");
}

// Validación del paso actual
function validarPasoActual(paso) {
    let esValido = true;

    if (paso === 1) {
        // Validar cédula
        const cedulaInput = document.getElementById("cedula");
        if (!validarCedula(cedulaInput, true)) {
            esValido = false;
        }

        // Validar RIF (opcional, pero si tiene contenido debe ser válido)
        const rifInput = document.getElementById("rif");
        if (
            rifInput &&
            rifInput.value.trim() !== "" &&
            !validarRIF(rifInput, false)
        ) {
            esValido = false;
        }

        // Validar información personal
        const camposRequeridos = [
            "nombres",
            "apellidos",
            "nacionalidad",
            "lugar_nacimiento",
            "fecha_nacimiento",
            "tipo_sangre",
            "telefono_inicio",
            "telefono_restante",
            "direccion",
            "id_municipio",
            "id_parroquia",
        ];

        camposRequeridos.forEach((campoId) => {
            const campo = document.getElementById(campoId);
            if (campo && !campo.value.trim()) {
                campo.classList.add("is-invalid");
                esValido = false;
            } else if (campo) {
                campo.classList.remove("is-invalid");

                // Validaciones específicas para campos no vacíos
                if (
                    campoId === "telefono_inicio" &&
                    campo.value &&
                    !/^\d{4}$/.test(campo.value)
                ) {
                    campo.classList.add("is-invalid");
                    esValido = false;
                }

                if (
                    campoId === "telefono_restante" &&
                    campo.value &&
                    !/^\d{7}$/.test(campo.value)
                ) {
                    campo.classList.add("is-invalid");
                    esValido = false;
                }

                if (campoId === "correo" && campo.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(campo.value)) {
                        campo.classList.add("is-invalid");
                        esValido = false;
                    }
                }
            }
        });

        // Validar que se haya seleccionado parroquia
        const parroquia = document.getElementById("id_parroquia");
        if (parroquia && (parroquia.disabled || !parroquia.value)) {
            parroquia.classList.add("is-invalid");
            esValido = false;
        }
    }

    if (paso === 2) {
        // Validar IMPRE (opcional, pero si tiene contenido debe ser válido)
        const impreInput = document.getElementById("impre");
        if (
            impreInput &&
            impreInput.value.trim() !== "" &&
            !validarIMPRE(impreInput, false)
        ) {
            esValido = false;
        }

        // Validar información profesional
        const camposRequeridos = [
            "numero_colegio",
            "matricula_ministerio",
            "universidad_graduado",
            "fecha_egreso_universidad",
            "fecha_incripcion",
            "id_grado_academico",
            "lugar_de_trabajo",
            "estado",
        ];

        camposRequeridos.forEach((campoId) => {
            const campo = document.getElementById(campoId);
            if (campo && !campo.value.trim()) {
                campo.classList.add("is-invalid");
                esValido = false;
            } else if (campo) {
                campo.classList.remove("is-invalid");
            }
        });

        // Validar que la fecha de egreso no sea futura
        const fechaEgresoInput = document.getElementById(
            "fecha_egreso_universidad",
        );
        if (fechaEgresoInput && fechaEgresoInput.value) {
            const fechaEgreso = new Date(fechaEgresoInput.value);
            const hoy = new Date();
            if (fechaEgreso > hoy) {
                fechaEgresoInput.classList.add("is-invalid");
                esValido = false;
                if (typeof warning === "function") {
                    warning(
                        "Fecha inválida",
                        "La fecha de egreso no puede ser una fecha futura.",
                    );
                }
            }
        }

        // Validar cursos individuales
        document.querySelectorAll(".curso-item").forEach((curso) => {
            const nombre = curso.querySelector('input[name$="[nombre]"]');
            const fecha = curso.querySelector(
                'input[name$="[fecha_obtencion]"]',
            );
            const universidad = curso.querySelector(
                'input[name$="[universidad_obtenido]"]',
            );

            if (nombre && !nombre.value.trim()) {
                nombre.classList.add("is-invalid");
                esValido = false;
            }
            if (fecha && !fecha.value) {
                fecha.classList.add("is-invalid");
                esValido = false;
            }
            if (universidad && !universidad.value.trim()) {
                universidad.classList.add("is-invalid");
                esValido = false;
            }
        });

        // Validar diplomados individuales
        document.querySelectorAll(".diplomado-item").forEach((diplomado) => {
            const nombre = diplomado.querySelector('input[name$="[nombre]"]');
            const fecha = diplomado.querySelector(
                'input[name$="[fecha_obtencion]"]',
            );
            const universidad = diplomado.querySelector(
                'input[name$="[universidad_obtenido]"]',
            );

            if (nombre && !nombre.value.trim()) {
                nombre.classList.add("is-invalid");
                esValido = false;
            }
            if (fecha && !fecha.value) {
                fecha.classList.add("is-invalid");
                esValido = false;
            }
            if (universidad && !universidad.value.trim()) {
                universidad.classList.add("is-invalid");
                esValido = false;
            }
        });
    }

    if (!esValido && typeof warning === "function") {
        warning(
            "Campos vacíos",
            "Por favor complete todos los campos requeridos correctamente.",
        );
    }

    return esValido;
}

// Función para validar una especialidad individual
function validarEspecialidadIndividual(input) {
    const itemDiv = input.closest(".especialidad-item");
    if (!itemDiv) return;

    const hiddenInput =
        itemDiv.querySelector(".especialidad-id-input-nuevo") ||
        itemDiv.querySelector(".especialidad-id-input");
    const universidadInput = itemDiv.querySelector(
        ".universidad-obtenido-input",
    );

    // Limpiar clases previas
    if (hiddenInput) hiddenInput.classList.remove("is-valid", "is-invalid");
    input.classList.remove("is-valid", "is-invalid");
    if (universidadInput)
        universidadInput.classList.remove("is-valid", "is-invalid");

    if (
        hiddenInput &&
        hiddenInput.value &&
        input.value &&
        universidadInput &&
        universidadInput.value
    ) {
        hiddenInput.classList.remove("is-invalid");
        input.classList.remove("is-invalid");
        universidadInput.classList.remove("is-invalid");
        input.classList.add("is-valid");
        universidadInput.classList.add("is-valid");
    } else if (
        !hiddenInput.value ||
        !input.value ||
        !universidadInput ||
        !universidadInput.value
    ) {
        if (!hiddenInput.value) hiddenInput.classList.add("is-invalid");
        if (!input.value) input.classList.add("is-invalid");
        if (universidadInput && !universidadInput.value)
            universidadInput.classList.add("is-invalid");
    }
}

// Validar especialidades
function validarEspecialidades() {
    const items = document.querySelectorAll(".especialidad-item");
    let todasValidas = true;

    // Verificar duplicados
    const valoresSeleccionados = [];
    let tieneDuplicados = false;

    items.forEach((item) => {
        if (item.style.display === "none") return; // Saltar elementos ocultos

        const hiddenInput =
            item.querySelector(".especialidad-id-input-nuevo") ||
            item.querySelector(".especialidad-id-input");
        if (hiddenInput && hiddenInput.value) {
            if (valoresSeleccionados.includes(hiddenInput.value)) {
                tieneDuplicados = true;
                todasValidas = false;
                hiddenInput.classList.add("is-invalid");
            } else {
                valoresSeleccionados.push(hiddenInput.value);
            }
        }
    });

    if (tieneDuplicados && typeof warning === "function") {
        warning(
            "Especialidades duplicadas",
            "No puede seleccionar la misma especialidad/subespecialidad más de una vez.",
        );
        return false;
    }

    // Validar cada item
    items.forEach((item) => {
        if (item.style.display === "none") return; // Saltar elementos ocultos

        const hiddenInput =
            item.querySelector(".especialidad-id-input-nuevo") ||
            item.querySelector(".especialidad-id-input");
        const fecha = item.querySelector(".fecha-especialidad");
        const universidad = item.querySelector(".universidad-obtenido-input");

        if (
            !hiddenInput ||
            !hiddenInput.value ||
            !fecha ||
            !fecha.value ||
            !universidad ||
            !universidad.value
        ) {
            if (hiddenInput) hiddenInput.classList.add("is-invalid");
            if (fecha) fecha.classList.add("is-invalid");
            if (universidad) universidad.classList.add("is-invalid");
            todasValidas = false;
        } else {
            hiddenInput.classList.remove("is-invalid");
            fecha.classList.remove("is-invalid");
            universidad.classList.remove("is-invalid");
        }
    });

    if (!todasValidas && typeof warning === "function") {
        warning(
            "Campos incompletos",
            "Por favor complete o elimine todas las especialidades/subespecialidades creadas.",
        );
    }

    return todasValidas;
}

// ========== FUNCIONES ESPECÍFICAS PARA MODO EDICIÓN ==========

// Función para manejar la foto
function inicializarManejadorFoto() {
    const conservarFoto = document.getElementById("conservar_foto");
    const cambiarFoto = document.getElementById("cambiar_foto");
    const eliminarFoto = document.getElementById("eliminar_foto");
    const nuevaFotoContainer = document.getElementById("nueva-foto-container");

    if (conservarFoto) {
        conservarFoto.addEventListener("change", function () {
            if (nuevaFotoContainer) nuevaFotoContainer.style.display = "none";
        });
    }

    if (cambiarFoto) {
        cambiarFoto.addEventListener("change", function () {
            if (nuevaFotoContainer) nuevaFotoContainer.style.display = "block";
        });
    }

    if (eliminarFoto) {
        eliminarFoto.addEventListener("change", function () {
            if (nuevaFotoContainer) nuevaFotoContainer.style.display = "none";
        });
    }
}

// Función para precargar especialidades del médico
function precargarEspecialidades() {
    const container = document.getElementById("especialidades-container");

    if (!especialidadesMedico || !Array.isArray(especialidadesMedico)) return;

    especialidadesMedico.forEach((esp, index) => {
        especialidadCount++;

        // Determinar tipo basado en el ID
        const tipo =
            esp.id_especialidad && esp.id_especialidad.startsWith("sub_")
                ? "subespecialidad"
                : "especialidad";
        const nombre = esp.nombre || "Especialidad sin nombre";
        const universidad = esp.universidad_obtenido || "";

        const itemDiv = document.createElement("div");
        itemDiv.className = `especialidad-item ${tipo === "subespecialidad" ? "subespecialidad" : ""}`;
        itemDiv.id = `especialidad-${especialidadCount}`;

        itemDiv.innerHTML = `
            <input type="hidden" class="especialidad-id-input" 
                   name="especialidades[${especialidadCount}][id_original]" 
                   value="${esp.id_especialidad}">
            <input type="hidden" class="especialidad-id-input-nuevo" 
                   name="especialidades[${especialidadCount}][id_especialidad]" 
                   value="${esp.id_especialidad}">
            <input type="hidden" name="especialidades[${especialidadCount}][es_existente]" value="1">
            
            <div class="d-flex justify-content-center align-items-start mb-3">
                <div class="tipo-especialidad-group flex-grow-1">
                    <label class="form-label">Tipo <span class="required-asterisk">*</span></label>
                    <div class="radio-tipo-especialidad">
                        <div class="form-check">
                            <input class="form-check-input tipo-especialidad-radio" 
                                   type="radio" 
                                   name="especialidades[${especialidadCount}][tipo]" 
                                   id="tipo_esp_${especialidadCount}" 
                                   value="especialidad" 
                                   ${tipo === "especialidad" ? "checked" : ""}
                                   onchange="actualizarTipoEspecialidad(${especialidadCount})">
                            <label class="form-check-label" for="tipo_esp_${especialidadCount}">
                                Especialidad
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input tipo-especialidad-radio" 
                                   type="radio" 
                                   name="especialidades[${especialidadCount}][tipo]" 
                                   id="tipo_sub_${especialidadCount}" 
                                   value="subespecialidad"
                                   ${tipo === "subespecialidad" ? "checked" : ""}
                                   onchange="actualizarTipoEspecialidad(${especialidadCount})">
                            <label class="form-check-label" for="tipo_sub_${especialidadCount}">
                                Subespecialidad
                            </label>
                        </div>
                    </div>
                </div>
                <span class="tipo-badge badge ${tipo === "subespecialidad" ? "bg-purple" : "bg-primary"}">
                    ${tipo === "subespecialidad" ? "Subespecialidad" : "Especialidad"}
                </span>
            </div>
            
            <div class="row g-3 align-items-">
                <div class="col-lg-5">
                    <label class="form-label">Seleccionar <span class="required-asterisk">*</span></label>
                    <div id="buscador-container-${especialidadCount}">
                        <!-- Buscador se creará dinámicamente -->
                    </div>
                    <div class="selected-especialidad-info mt-2">
                        <div class="alert alert-light py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Seleccionado:</strong> 
                                <span id="selected-name-${especialidadCount}">${nombre}</span>
                                <span class="badge ${tipo === "subespecialidad" ? "bg-purple" : "bg-primary"} ms-2" 
                                      id="selected-tipo-${especialidadCount}">
                                    ${tipo === "subespecialidad" ? "Sub" : "Esp"}
                                </span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" 
                                    id="clear-selection-${especialidadCount}"
                                    data-item-id="${especialidadCount}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <label class="form-label">Universidad de Obtención <span class="required-asterisk">*</span></label>
                    <input type="text" 
                           class="form-control universidad-obtenido-input" 
                           name="especialidades[${especialidadCount}][universidad_obtenido]" 
                           value="${universidad}"
                           required>
                    <div class="invalid-feedback">
                        Por favor ingrese la universidad.
                    </div>
                </div>
                
                <div class="col-lg-2">
                    <label class="form-label">Fecha de Obtención <span class="required-asterisk">*</span></label>
                    <input type="date" 
                           class="form-control fecha-especialidad" 
                           name="especialidades[${especialidadCount}][fecha_obtencion]" 
                           value="${esp.fecha_obtencion}"
                           required 
                           max="${new Date().toISOString().split("T")[0]}"
                           onchange="validarEspecialidadIndividual(this)">
                </div>
                
                <div class="col-lg-2">
                    <button type="button" 
                            class="btn btn-danger remove-specialty-btn w-100" 
                            onclick="eliminarEspecialidad(${especialidadCount}, true)">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        `;

        container.appendChild(itemDiv);

        // Agregar al Set de especialidades seleccionadas
        if (esp.id_especialidad) {
            especialidadesSeleccionadas.add(esp.id_especialidad);
        }
    });
}

// Función para crear buscador para item
function crearBuscadorParaItem(
    itemId,
    esExistente = false,
    datosEspecialidad = null,
) {
    const container = document.getElementById(`buscador-container-${itemId}`);

    // Limpiar contenedor si ya tiene contenido
    if (container) {
        container.innerHTML = "";
    } else {
        // Si no existe el contenedor, no podemos continuar
        console.error(`Contenedor buscador-container-${itemId} no encontrado`);
        return null;
    }

    // Si es una especialidad existente y tiene datos, mostrar solo la información
    if (esExistente && datosEspecialidad) {
        return null;
    }

    // Crear buscador para nuevas especialidades o para reemplazar
    return crearBuscadorEspecialidades(
        `buscador-container-${itemId}`,
        function (id, nombre, tipo, especialidadAnterior) {
            const hiddenInput = document.querySelector(
                `#especialidad-${itemId} .especialidad-id-input-nuevo`,
            );
            const selectedInfo = container
                .closest(".row")
                .querySelector(".selected-especialidad-info");
            const selectedNameSpan = document.getElementById(
                `selected-name-${itemId}`,
            );
            const selectedTipoSpan = document.getElementById(
                `selected-tipo-${itemId}`,
            );

            // 1. Remover la especialidad anterior del Set
            if (especialidadAnterior && especialidadAnterior.id) {
                especialidadesSeleccionadas.delete(especialidadAnterior.id);
            }

            // 2. Agregar la nueva especialidad al Set
            if (id) {
                especialidadesSeleccionadas.add(id);
            }

            // 3. Actualizar los elementos del formulario
            if (hiddenInput) hiddenInput.value = id;
            if (selectedNameSpan) selectedNameSpan.textContent = nombre;
            if (selectedTipoSpan) {
                selectedTipoSpan.textContent =
                    tipo === "subespecialidad" ? "Sub" : "Esp";
                selectedTipoSpan.className = `badge ${tipo === "subespecialidad" ? "bg-purple" : "bg-primary"} ms-2`;
            }

            // 4. Mostrar información de selección y ocultar buscador
            if (selectedInfo) {
                selectedInfo.style.display = "block";
                // Mostrar el botón de limpiar si no está presente
                if (!selectedInfo.querySelector(`#clear-selection-${itemId}`)) {
                    const clearBtn = document.createElement("button");
                    clearBtn.type = "button";
                    clearBtn.className = "btn btn-sm btn-outline-danger ms-2";
                    clearBtn.id = `clear-selection-${itemId}`;
                    clearBtn.innerHTML = '<i class="fas fa-times"></i>';
                    clearBtn.title = "Quitar selección";
                    clearBtn.addEventListener("click", function () {
                        limpiarSeleccionEspecialidad(itemId);
                    });
                    selectedInfo
                        .querySelector(".alert-light")
                        .appendChild(clearBtn);
                }
            }

            // 5. Actualizar tipo radio button
            const tipoRadio = document.querySelector(
                `#especialidad-${itemId} input[name="especialidades[${itemId}][tipo]"][value="${tipo}"]`,
            );
            if (tipoRadio && !tipoRadio.checked) {
                tipoRadio.checked = true;
                actualizarTipoEspecialidad(itemId);
            }

            // 6. Actualizar otros buscadores
            actualizarTodosLosBuscadores();

            // 7. Validar
            const fechaInput = document.querySelector(
                `#especialidad-${itemId} .fecha-especialidad`,
            );
            const universidadInput = document.querySelector(
                `#especialidad-${itemId} .universidad-obtenido-input`,
            );

            if (fechaInput && universidadInput) {
                validarEspecialidadIndividual(fechaInput);
            }

            // 8. Actualizar resumen
            actualizarResumen();
        },
    );
}

// Función para actualizar el tipo de especialidad
function actualizarTipoEspecialidad(itemId) {
    const itemDiv = document.getElementById(`especialidad-${itemId}`);
    if (!itemDiv) return;

    const tipoBadge = itemDiv.querySelector(".tipo-badge");
    const tipoRadio = itemDiv.querySelector(
        'input[name="especialidades[' + itemId + '][tipo]"]:checked',
    );

    if (tipoRadio) {
        const tipo = tipoRadio.value;

        // Actualizar clase del item
        if (tipo === "subespecialidad") {
            itemDiv.classList.add("subespecialidad");
            itemDiv.classList.remove("especialidad");
            if (tipoBadge) {
                tipoBadge.textContent = "Subespecialidad";
                tipoBadge.className = "tipo-badge badge bg-purple";
            }
        } else {
            itemDiv.classList.add("especialidad");
            itemDiv.classList.remove("subespecialidad");
            if (tipoBadge) {
                tipoBadge.textContent = "Especialidad";
                tipoBadge.className = "tipo-badge badge bg-primary";
            }
        }

        // Si ya hay una selección, actualizar el badge del tipo
        const selectedTipoSpan = document.getElementById(
            `selected-tipo-${itemId}`,
        );
        if (selectedTipoSpan) {
            selectedTipoSpan.textContent =
                tipo === "subespecialidad" ? "Sub" : "Esp";
            selectedTipoSpan.className = `badge ${
                tipo === "subespecialidad" ? "bg-purple" : "bg-primary"
            } ms-2`;
        }
    }
}

// Función para limpiar selección de especialidad
function limpiarSeleccionEspecialidad(itemId) {
    const itemDiv = document.getElementById(`especialidad-${itemId}`);
    if (!itemDiv) return;

    const hiddenInput = itemDiv.querySelector(".especialidad-id-input-nuevo");
    const selectedInfo = itemDiv.querySelector(".selected-especialidad-info");
    const selectedNameSpan = document.getElementById(`selected-name-${itemId}`);
    const selectedTipoSpan = document.getElementById(`selected-tipo-${itemId}`);
    const buscadorContainer = document.getElementById(
        `buscador-container-${itemId}`,
    );

    // Remover del Set
    if (hiddenInput && hiddenInput.value) {
        especialidadesSeleccionadas.delete(hiddenInput.value);
    }

    // Limpiar inputs
    if (hiddenInput) hiddenInput.value = "";
    if (selectedNameSpan) selectedNameSpan.textContent = "";
    if (selectedTipoSpan) selectedTipoSpan.textContent = "";

    // Mostrar buscador nuevamente y ocultar info seleccionada
    if (selectedInfo) selectedInfo.style.display = "none";

    // Asegurarse de que el buscador esté visible
    if (buscadorContainer) {
        // Limpiar el contenedor y recrear el buscador
        buscadorContainer.innerHTML = "";

        // Recrear el buscador
        const nuevoBuscador = crearBuscadorParaItem(itemId, false);

        // Si hay un radio button de tipo, también reiniciarlo
        const tipoRadioEsp = itemDiv.querySelector(`#tipo_esp_${itemId}`);
        const tipoRadioSub = itemDiv.querySelector(`#tipo_sub_${itemId}`);

        if (tipoRadioEsp && tipoRadioSub) {
            tipoRadioEsp.checked = true; // Volver a especialidad por defecto
            actualizarTipoEspecialidad(itemId);
        }

        // Limpiar también el campo de universidad
        const universidadInput = itemDiv.querySelector(
            ".universidad-obtenido-input",
        );
        if (universidadInput) {
            universidadInput.value = "";
            universidadInput.classList.remove("is-valid", "is-invalid");
        }

        // Limpiar fecha
        const fechaInput = itemDiv.querySelector(".fecha-especialidad");
        if (fechaInput) {
            fechaInput.value = "";
            fechaInput.classList.remove("is-valid", "is-invalid");
        }
    }

    // Actualizar otros buscadores
    actualizarTodosLosBuscadores();
    actualizarResumen();
}

// Modificar función para eliminar especialidad
function eliminarEspecialidad(id, esExistente = false) {
    const elemento = document.getElementById(`especialidad-${id}`);
    if (elemento) {
        const hiddenInput = elemento.querySelector(".especialidad-id-input");
        const hiddenInputNuevo = elemento.querySelector(
            ".especialidad-id-input-nuevo",
        );

        // Si es una especialidad existente, marcar para eliminación
        if (esExistente && hiddenInput && hiddenInput.value) {
            const inputEliminar = document.createElement("input");
            inputEliminar.type = "hidden";
            inputEliminar.name = `especialidades[${id}][eliminar]`;
            inputEliminar.value = "1";
            elemento.appendChild(inputEliminar);

            // Ocultar en lugar de eliminar
            elemento.style.display = "none";
        } else {
            // Si es nueva, eliminar completamente
            if (hiddenInputNuevo && hiddenInputNuevo.value) {
                especialidadesSeleccionadas.delete(hiddenInputNuevo.value);
            }
            elemento.remove();
            especialidadCount--;
        }

        actualizarOpcionesDisponibles();
        actualizarResumen();
    }
}

// ========== FUNCIONES PARA CURSOS Y DIPLOMADOS ==========

// Función para precargar cursos del médico
function precargarCursos() {
    const container = document.getElementById("cursos-container");

    if (!cursosMedico || !Array.isArray(cursosMedico)) return;

    cursosMedico.forEach((curso, index) => {
        cursoCount++;

        const cursoDiv = document.createElement("div");
        cursoDiv.className = "curso-item mb-3 p-3 border rounded";
        cursoDiv.id = `curso-${cursoCount}`;

        cursoDiv.innerHTML = `
            <div class="row g-3">
                <div class="col-lg-4">
                    <label class="form-label">Nombre del Curso</label>
                    <input type="text" class="form-control" name="cursos[${cursoCount}][nombre]" 
                           value="${curso.nombre || ""}" required>
                    <div class="invalid-feedback">
                        Por favor ingrese el nombre del curso.
                    </div>
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Universidad de Obtención</label>
                    <input type="text" class="form-control" name="cursos[${cursoCount}][universidad_obtenido]" 
                           value="${curso.universidad_obtenido || ""}" required>
                    <div class="invalid-feedback">
                        Por favor ingrese la universidad.
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Fecha de Obtención</label>
                    <input type="date" class="form-control" name="cursos[${cursoCount}][fecha_obtencion]" 
                           value="${curso.fecha_obtencion || ""}" required max="${new Date().toISOString().split("T")[0]}">
                    <div class="invalid-feedback">
                        Por favor seleccione la fecha.
                    </div>
                </div>
                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger w-100 mt-4" onclick="eliminarCurso(${cursoCount})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        container.appendChild(cursoDiv);
    });

    actualizarContadores();
}

// Función para precargar diplomados del médico
function precargarDiplomados() {
    const container = document.getElementById("diplomados-container");

    if (!diplomadosMedico || !Array.isArray(diplomadosMedico)) return;

    diplomadosMedico.forEach((diplomado, index) => {
        diplomadoCount++;

        const diplomadoDiv = document.createElement("div");
        diplomadoDiv.className = "diplomado-item mb-3 p-3 border rounded";
        diplomadoDiv.id = `diplomado-${diplomadoCount}`;

        diplomadoDiv.innerHTML = `
            <div class="row g-3">
                <div class="col-lg-4">
                    <label class="form-label">Nombre del Diplomado</label>
                    <input type="text" class="form-control" name="diplomados[${diplomadoCount}][nombre]" 
                           value="${diplomado.nombre || ""}" required>
                    <div class="invalid-feedback">
                        Por favor ingrese el nombre del diplomado.
                    </div>
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Universidad de Obtención</label>
                    <input type="text" class="form-control" name="diplomados[${diplomadoCount}][universidad_obtenido]" 
                           value="${diplomado.universidad_obtenido || ""}" required>
                    <div class="invalid-feedback">
                        Por favor ingrese la universidad.
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Fecha de Obtención</label>
                    <input type="date" class="form-control" name="diplomados[${diplomadoCount}][fecha_obtencion]" 
                           value="${diplomado.fecha_obtencion || ""}" required max="${new Date().toISOString().split("T")[0]}">
                    <div class="invalid-feedback">
                        Por favor seleccione la fecha.
                    </div>
                </div>
                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger w-100 mt-4" onclick="eliminarDiplomado(${diplomadoCount})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        container.appendChild(diplomadoDiv);
    });

    actualizarContadores();
}

// Función para agregar curso
function agregarCurso() {
    cursoCount++;
    const container = document.getElementById("cursos-container");

    const cursoDiv = document.createElement("div");
    cursoDiv.className = "curso-item mb-3 p-3 border rounded";
    cursoDiv.id = `curso-${cursoCount}`;

    cursoDiv.innerHTML = `
        <div class="row g-3">
            <div class="col-lg-4">
                <label class="form-label">Nombre del Curso</label>
                <input type="text" class="form-control" name="cursos[${cursoCount}][nombre]" required>
                <div class="invalid-feedback">
                    Por favor ingrese el nombre del curso.
                </div>
            </div>
            <div class="col-lg-4">
                <label class="form-label">Universidad de Obtención</label>
                <input type="text" class="form-control" name="cursos[${cursoCount}][universidad_obtenido]" required>
                <div class="invalid-feedback">
                    Por favor ingrese la universidad.
                </div>
            </div>
            <div class="col-lg-3">
                <label class="form-label">Fecha de Obtención</label>
                <input type="date" class="form-control" name="cursos[${cursoCount}][fecha_obtencion]" required max="${new Date().toISOString().split("T")[0]}">
                <div class="invalid-feedback">
                    Por favor seleccione la fecha.
                </div>
            </div>
            <div class="col-lg-1">
                <button type="button" class="btn btn-danger w-100 mt-4" onclick="eliminarCurso(${cursoCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(cursoDiv);

    actualizarResumen();
    actualizarContadores();
}

// Función para agregar diplomado
function agregarDiplomado() {
    diplomadoCount++;
    const container = document.getElementById("diplomados-container");

    const diplomadoDiv = document.createElement("div");
    diplomadoDiv.className = "diplomado-item mb-3 p-3 border rounded";
    diplomadoDiv.id = `diplomado-${diplomadoCount}`;

    diplomadoDiv.innerHTML = `
        <div class="row g-3">
            <div class="col-lg-4">
                <label class="form-label">Nombre del Diplomado</label>
                <input type="text" class="form-control" name="diplomados[${diplomadoCount}][nombre]" required>
                <div class="invalid-feedback">
                    Por favor ingrese el nombre del diplomado.
                </div>
            </div>
            <div class="col-lg-4">
                <label class="form-label">Universidad de Obtención</label>
                <input type="text" class="form-control" name="diplomados[${diplomadoCount}][universidad_obtenido]" required>
                <div class="invalid-feedback">
                    Por favor ingrese la universidad.
                </div>
            </div>
            <div class="col-lg-3">
                <label class="form-label">Fecha de Obtención</label>
                <input type="date" class="form-control" name="diplomados[${diplomadoCount}][fecha_obtencion]" required max="${new Date().toISOString().split("T")[0]}">
                <div class="invalid-feedback">
                    Por favor seleccione la fecha.
                </div>
            </div>
            <div class="col-lg-1">
                <button type="button" class="btn btn-danger w-100 mt-4" onclick="eliminarDiplomado(${diplomadoCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(diplomadoDiv);

    actualizarResumen();
    actualizarContadores();
}

// Funciones para eliminar cursos y diplomados
function eliminarCurso(id) {
    const elemento = document.getElementById(`curso-${id}`);
    if (elemento) {
        elemento.remove();
        cursoCount--;
        actualizarResumen();
        actualizarContadores();
    }
}

function eliminarDiplomado(id) {
    const elemento = document.getElementById(`diplomado-${id}`);
    if (elemento) {
        elemento.remove();
        diplomadoCount--;
        actualizarResumen();
        actualizarContadores();
    }
}

// Función para actualizar contadores
function actualizarContadores() {
    // Contador de cursos
    const contadorCursos = document.getElementById("contador-cursos");
    if (contadorCursos) {
        const cursosCount = document.querySelectorAll(".curso-item").length;
        contadorCursos.textContent = `${cursosCount} curso${cursosCount !== 1 ? "s" : ""}`;
    }

    // Contador de diplomados
    const contadorDiplomados = document.getElementById("contador-diplomados");
    if (contadorDiplomados) {
        const diplomadosCount =
            document.querySelectorAll(".diplomado-item").length;
        contadorDiplomados.textContent = `${diplomadosCount} diplomado${diplomadosCount !== 1 ? "s" : ""}`;
    }
}

// Función para precargar documentos del médico
function precargarDocumentos() {
    const container = document.getElementById("documentos-existente-container");
    const sinDocumentos = document.getElementById("sin-documentos");

    if (!container) return;

    if (
        documentosMedico &&
        Array.isArray(documentosMedico) &&
        documentosMedico.length > 0
    ) {
        if (sinDocumentos) sinDocumentos.style.display = "none";

        documentosMedico.forEach((doc, index) => {
            const docId = doc.id_documento || index;
            const nombreDocumento =
                doc.nombre_documento || "Documento " + (index + 1);

            const docDiv = document.createElement("div");
            docDiv.className = "mb-3 documento-existente-item";
            docDiv.id = `documento-existente-${docId}`;

            docDiv.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-file-alt fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">${nombreDocumento}</h6>
                                    <p class="mb-0"><small>ID: ${docId}</small></p>
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="documento-check-${docId}" 
                                       name="documentos_existentes[${docId}][conservar]" 
                                       value="1" checked
                                       onchange="toggleDocumento('${docId}', this.checked)">
                                <label class="form-check-label" for="documento-check-${docId}">
                                    Conservar
                                </label>
                                <input type="hidden" name="documentos_existentes[${docId}][eliminar]" 
                                       value="0" id="eliminar-doc-${docId}">
                                <input type="hidden" name="documentos_existentes[${docId}][id]" 
                                       value="${docId}">
                                <input type="hidden" name="documentos_existentes[${docId}][nombre]" 
                                       value="${nombreDocumento}">
                            </div>
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(docDiv);
            documentosAConservar.add(docId.toString());
        });
    }
}

// Función para alternar estado de documento
function toggleDocumento(docId, conservar) {
    if (conservar) {
        documentosAConservar.add(docId.toString());
        documentosAEliminar.delete(docId.toString());
        const eliminarInput = document.getElementById(`eliminar-doc-${docId}`);
        if (eliminarInput) eliminarInput.value = "0";
    } else {
        documentosAConservar.delete(docId.toString());
        documentosAEliminar.add(docId.toString());
        const eliminarInput = document.getElementById(`eliminar-doc-${docId}`);
        if (eliminarInput) eliminarInput.value = "1";
    }
    actualizarResumen();
}

// Función para actualizar todos los buscadores
function actualizarTodosLosBuscadores() {
    const buscadores = document.querySelectorAll(
        ".especialidad-search-container",
    );

    buscadores.forEach((container) => {
        // Simular un input para forzar la actualización
        const searchInput = container.querySelector(
            ".especialidad-search-input",
        );
        if (searchInput) {
            const event = new Event("input", { bubbles: true });
            searchInput.dispatchEvent(event);
        }
    });
}

// Configurar actualizaciones en tiempo real para el resumen
function setupRealTimeUpdates() {
    // Campos de información personal
    [
        "nombres",
        "apellidos",
        "cedula",
        "correo",
        "rif",
        "impre",
        "nacionalidad",
        "lugar_nacimiento",
    ].forEach((campoId) => {
        const campo = document.getElementById(campoId);
        if (campo) {
            campo.addEventListener("input", actualizarResumen);
        }
    });

    // Campos de municipio y parroquia
    const municipioSelect = document.getElementById("id_municipio");
    const parroquiaSelect = document.getElementById("id_parroquia");

    if (municipioSelect)
        municipioSelect.addEventListener("change", actualizarResumen);
    if (parroquiaSelect)
        parroquiaSelect.addEventListener("change", actualizarResumen);

    // Campos de información profesional
    ["numero_colegio", "universidad_graduado", "matricula_ministerio"].forEach(
        (campoId) => {
            const campo = document.getElementById(campoId);
            if (campo) {
                campo.addEventListener("input", actualizarResumen);
            }
        },
    );

    // Archivos múltiples
    const nuevosDocumentos = document.getElementById("nuevos_documentos");
    if (nuevosDocumentos) {
        nuevosDocumentos.addEventListener("change", actualizarResumen);
    }

    // Deportes
    const deportesSelect = document.getElementById("deportes");
    if (deportesSelect) {
        deportesSelect.addEventListener("change", actualizarResumen);
    }

    // Estado
    const estadoSelect = document.getElementById("estado");
    if (estadoSelect) {
        estadoSelect.addEventListener("change", actualizarResumen);
    }
}

// Actualizar el resumen
function actualizarResumen() {
    // Información personal
    const nombresInput = document.getElementById("nombres");
    const apellidosInput = document.getElementById("apellidos");
    const nombres = nombresInput
        ? nombresInput.value || (medicoData && medicoData.nombres)
        : medicoData && medicoData.nombres;
    const apellidos = apellidosInput
        ? apellidosInput.value || (medicoData && medicoData.apellidos)
        : medicoData && medicoData.apellidos;

    const resumenNombre = document.getElementById("resumen-nombre");
    if (resumenNombre) {
        resumenNombre.innerHTML = `<strong>Nombre:</strong> ${nombres || "No ingresado"} ${apellidos || ""}`;
    }

    const cedulaInput = document.getElementById("cedula");
    const cedula = cedulaInput
        ? cedulaInput.value
        : medicoData && medicoData.cedula;
    const resumenCedula = document.getElementById("resumen-cedula");
    if (resumenCedula) {
        resumenCedula.innerHTML = `<strong>Cédula:</strong> ${cedula || "No ingresada"}`;
    }

    const rifInput = document.getElementById("rif");
    const rif = rifInput ? rifInput.value : medicoData && medicoData.rif;
    const resumenRif = document.getElementById("resumen-rif");
    if (resumenRif) {
        resumenRif.innerHTML = `<strong>RIF:</strong> ${rif || "No proporcionado"}`;
    }

    const impreInput = document.getElementById("impre");
    const impre = impreInput
        ? impreInput.value
        : medicoData && medicoData.impre;
    const resumenImpre = document.getElementById("resumen-impre");
    if (resumenImpre) {
        resumenImpre.innerHTML = `<strong>IMPRE:</strong> ${impre || "No proporcionado"}`;
    }

    const correoInput = document.getElementById("correo");
    const correo = correoInput
        ? correoInput.value
        : medicoData && medicoData.correo;
    const resumenCorreo = document.getElementById("resumen-correo");
    if (resumenCorreo) {
        resumenCorreo.innerHTML = `<strong>Correo:</strong> ${correo || "No proporcionado"}`;
    }

    // Contar cursos y diplomados
    const cursosCount = document.querySelectorAll(".curso-item").length;
    const resumenCursos = document.getElementById("resumen-cursos");
    if (resumenCursos) {
        resumenCursos.innerHTML = `<strong>Cursos:</strong> <span class="text-muted">${cursosCount}</span>`;
    }

    const diplomadosCount = document.querySelectorAll(".diplomado-item").length;
    const resumenDiplomados = document.getElementById("resumen-diplomados");
    if (resumenDiplomados) {
        resumenDiplomados.innerHTML = `<strong>Diplomados:</strong> <span class="text-muted">${diplomadosCount}</span>`;
    }

    // Contar deportes
    const deportesSelect = document.getElementById("deportes");
    const deportesCount = deportesSelect
        ? deportesSelect.selectedOptions.length
        : 0;
    const resumenDeportes = document.getElementById("resumen-deportes");
    if (resumenDeportes) {
        resumenDeportes.innerHTML = `<strong>Deportes:</strong> <span class="text-muted">${deportesCount}</span>`;
    }

    // Estado
    const estadoSelect = document.getElementById("estado");
    let estadoText = "No definido";
    if (estadoSelect && estadoSelect.value) {
        estadoText = estadoSelect.options[estadoSelect.selectedIndex].text;
    }
    const resumenEstado = document.getElementById("resumen-estado");
    if (resumenEstado) {
        resumenEstado.innerHTML = `<strong>Estado:</strong> ${estadoText}`;
    }

    // Contar especialidades nuevas
    const especialidadesNuevas = Array.from(
        document.querySelectorAll(".especialidad-item"),
    )
        .filter((item) => item.style.display !== "none")
        .filter((item) => {
            const inputExistente = item.querySelector(
                'input[name*="[es_existente]"]',
            );
            return !inputExistente || inputExistente.value !== "1";
        })
        .filter((item) => {
            const hiddenInput = item.querySelector(
                ".especialidad-id-input-nuevo",
            );
            return hiddenInput && hiddenInput.value;
        }).length;

    const resumenNuevasEspecialidades = document.getElementById(
        "resumen-nuevas-especialidades",
    );
    if (resumenNuevasEspecialidades) {
        resumenNuevasEspecialidades.innerHTML = `<strong>Nuevas especialidades a agregar:</strong> <span class="text-muted">${especialidadesNuevas}</span>`;
    }

    // Documentos
    const documentosConservar = documentosAConservar.size;
    const documentosEliminar = documentosAEliminar.size;
    const nuevosDocumentosInput = document.getElementById("nuevos_documentos");
    const nuevosDocumentos = nuevosDocumentosInput
        ? nuevosDocumentosInput.files.length
        : 0;

    const resumenDocConservar = document.getElementById(
        "resumen-documentos-conservar",
    );
    const resumenDocEliminar = document.getElementById(
        "resumen-documentos-eliminar",
    );
    const resumenDocNuevos = document.getElementById(
        "resumen-nuevos-documentos",
    );

    if (resumenDocConservar) {
        resumenDocConservar.innerHTML = `<strong>Documentos a conservar:</strong> <span class="text-muted">${documentosConservar}</span>`;
    }
    if (resumenDocEliminar) {
        resumenDocEliminar.innerHTML = `<strong>Documentos a eliminar:</strong> <span class="text-muted">${documentosEliminar}</span>`;
    }
    if (resumenDocNuevos) {
        resumenDocNuevos.innerHTML = `<strong>Nuevos documentos:</strong> <span class="text-muted">${nuevosDocumentos}</span>`;
    }
}

// Función para cargar todas las especialidades
function cargarTodasEspecialidades() {
    if (typeof todasEspecialidades === "undefined") {
        console.error("No se pudieron cargar las especialidades");
        return;
    }

    // Ordenar por tipo primero (especialidades primero) y luego por nombre
    todasEspecialidades.sort((a, b) => {
        if (a.tipo !== b.tipo) {
            return a.tipo === "especialidad" ? -1 : 1;
        }
        return a.nombre.localeCompare(b.nombre);
    });
}

// Modificar función resetForm para modo edición
function resetForm() {
    if (typeof Swal === "undefined") {
        if (confirm("¿Está seguro de que desea descartar todos los cambios?")) {
            window.location.reload();
        }
        return;
    }

    Swal.fire({
        icon: "question",
        title: "¿Está seguro de que desea descartar todos los cambios?",
        showDenyButton: true,
        confirmButtonText: "Si, descartar cambios",
        confirmButtonColor: "#28A745",
        denyButtonText: "Cancelar",
        reverseButtons: true,
    }).then((respuesta) => {
        if (respuesta.isConfirmed) {
            // Recargar la página para restaurar valores originales
            window.location.reload();
        }
    });
}

// Inicialización al cargar la página
document.addEventListener("DOMContentLoaded", function () {
    // Ocultar mensajes de error iniciales
    const cedulaFeedback = document.getElementById("cedula-feedback");
    const cedulaValidFeedback = document.getElementById(
        "cedula-valid-feedback",
    );
    const rifFeedback = document.getElementById("rif-feedback");
    const rifValidFeedback = document.getElementById("rif-valid-feedback");
    const impreFeedback = document.getElementById("impre-feedback");
    const impreValidFeedback = document.getElementById("impre-valid-feedback");

    if (cedulaFeedback) cedulaFeedback.style.display = "none";
    if (cedulaValidFeedback) cedulaValidFeedback.style.display = "none";
    if (rifFeedback) rifFeedback.style.display = "none";
    if (rifValidFeedback) rifValidFeedback.style.display = "none";
    if (impreFeedback) impreFeedback.style.display = "none";
    if (impreValidFeedback) impreValidFeedback.style.display = "none";

    // Configurar evento blur para cédula
    const cedulaInput = document.getElementById("cedula");
    if (cedulaInput) {
        cedulaInput.addEventListener("blur", function () {
            validarCedula(this, false);
        });
    }

    // Configurar evento blur para RIF
    const rifInput = document.getElementById("rif");
    if (rifInput) {
        rifInput.addEventListener("blur", function () {
            validarRIF(this, false);
        });
    }

    // Configurar evento blur para IMPRE
    const impreInput = document.getElementById("impre");
    if (impreInput) {
        impreInput.addEventListener("blur", function () {
            validarIMPRE(this, false);
        });
    }

    // Inicializar manejador de foto
    inicializarManejadorFoto();

    // Precargar especialidades si existen
    if (especialidadesMedico && especialidadesMedico.length > 0) {
        precargarEspecialidades();
    }

    // Precargar cursos si existen
    if (cursosMedico && cursosMedico.length > 0) {
        precargarCursos();
    }

    // Precargar diplomados si existen
    if (diplomadosMedico && diplomadosMedico.length > 0) {
        precargarDiplomados();
    }

    // Precargar documentos si existen
    if (documentosMedico && documentosMedico.length > 0) {
        precargarDocumentos();
    }

    // Configurar actualización en tiempo real
    setupRealTimeUpdates();

    // Cargar todas las especialidades
    cargarTodasEspecialidades();

    // Configurar fecha máxima para fechas (hoy)
    const hoy = new Date().toISOString().split("T")[0];
    [
        "fecha_nacimiento",
        "fecha_egreso_universidad",
        "fecha_incripcion",
    ].forEach((id) => {
        const input = document.getElementById(id);
        if (input) input.max = hoy;
    });

    // Inicializar eventos para cursos y diplomados
    const btnAgregarCurso = document.getElementById("btn-agregar-curso");
    if (btnAgregarCurso) {
        btnAgregarCurso.addEventListener("click", agregarCurso);
    }

    const btnAgregarDiplomado = document.getElementById(
        "btn-agregar-diplomado",
    );
    if (btnAgregarDiplomado) {
        btnAgregarDiplomado.addEventListener("click", agregarDiplomado);
    }

    // Evento para deportes (actualizar contador)
    const deportesSelect = document.getElementById("deportes");
    if (deportesSelect) {
        deportesSelect.addEventListener("change", actualizarContadores);
    }

    // Actualizar contadores iniciales
    actualizarContadores();
    actualizarResumen();
});

// Configurar el botón para agregar especialidad (modo edición)
document.addEventListener("DOMContentLoaded", function () {
    const btnAgregarEspecialidad = document.getElementById(
        "btn-agregar-especialidad",
    );
    if (btnAgregarEspecialidad) {
        btnAgregarEspecialidad.addEventListener("click", function () {
            if (especialidadCount >= todasEspecialidades.length) {
                if (typeof warning === "function") {
                    warning(
                        "Límite alcanzado",
                        `Ha alcanzado el límite máximo de ${todasEspecialidades.length} especialidades/subespecialidades.`,
                    );
                }
                return;
            }

            especialidadCount++;
            const container = document.getElementById(
                "especialidades-container",
            );

            // Crear el elemento de especialidad/subespecialidad
            const itemDiv = document.createElement("div");
            itemDiv.className = "especialidad-item";
            itemDiv.id = `especialidad-${especialidadCount}`;

            itemDiv.innerHTML = `
            <div class="d-flex justify-content-center align-items-start mb-3">
                <div class="tipo-especialidad-group flex-grow-1">
                    <label class="form-label">Tipo <span class="required-asterisk">*</span></label>
                    <div class="radio-tipo-especialidad">
                        <div class="form-check">
                            <input class="form-check-input tipo-especialidad-radio" 
                                   type="radio" 
                                   name="especialidades[${especialidadCount}][tipo]" 
                                   id="tipo_esp_${especialidadCount}" 
                                   value="especialidad" 
                                   checked
                                   onchange="actualizarTipoEspecialidad(${especialidadCount})">
                            <label class="form-check-label" for="tipo_esp_${especialidadCount}">
                                Especialidad
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input tipo-especialidad-radio" 
                                   type="radio" 
                                   name="especialidades[${especialidadCount}][tipo]" 
                                   id="tipo_sub_${especialidadCount}" 
                                   value="subespecialidad"
                                   onchange="actualizarTipoEspecialidad(${especialidadCount})">
                            <label class="form-check-label" for="tipo_sub_${especialidadCount}">
                                Subespecialidad
                            </label>
                        </div>
                    </div>
                </div>
                <span class="tipo-badge badge bg-primary">Especialidad</span>
            </div>
            
            <div class="row g-3 align-items-">
                <div class="col-lg-4">
                    <label class="form-label">Seleccionar <span class="required-asterisk">*</span></label>
                    <div id="buscador-container-${especialidadCount}">
                        <!-- Aquí se insertará el buscador dinámicamente -->
                    </div>
                    <input type="hidden" 
                           class="especialidad-id-input-nuevo" 
                           name="especialidades[${especialidadCount}][id_especialidad]" 
                           value="">
                    <div class="selected-especialidad-info mt-2" style="display: none;">
                        <div class="alert alert-light py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Seleccionado:</strong> 
                                <span id="selected-name-${especialidadCount}"></span>
                                <span class="badge bg-primary ms-2" id="selected-tipo-${especialidadCount}">Esp</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" 
                                    id="clear-selection-${especialidadCount}"
                                    data-item-id="${especialidadCount}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <label class="form-label">Universidad de Obtención <span class="required-asterisk">*</span></label>
                    <input type="text" 
                           class="form-control universidad-obtenido-input" 
                           name="especialidades[${especialidadCount}][universidad_obtenido]" 
                           required>
                    <div class="invalid-feedback">
                        Por favor ingrese la universidad.
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <label class="form-label">Fecha de Obtención <span class="required-asterisk">*</span></label>
                    <input type="date" 
                        class="form-control fecha-especialidad" 
                        name="especialidades[${especialidadCount}][fecha_obtencion]" 
                        required 
                        max="${new Date().toISOString().split("T")[0]}"
                        onchange="validarEspecialidadIndividual(this)">
                </div>
                
                <div class="col-lg-2">
                    <button type="button" 
                            class="btn btn-danger remove-specialty-btn w-100" 
                            onclick="eliminarEspecialidad(${especialidadCount})">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
            `;

            container.appendChild(itemDiv);

            // Crear el buscador para esta especialidad
            crearBuscadorParaItem(especialidadCount);

            // Actualizar opciones de todos los selects
            actualizarOpcionesDisponibles();

            // Actualizar resumen
            actualizarResumen();
        });
    }
});

// Manejar envío del formulario
document.addEventListener("DOMContentLoaded", function () {
    const medicoForm = document.getElementById("medicoForm");
    if (medicoForm) {
        medicoForm.addEventListener("submit", function (e) {
            e.preventDefault();

            // 1. Validaciones
            if (!validarPasoActual(1) || !validarPasoActual(2)) {
                if (typeof warning === "function") {
                    warning(
                        "Campos vacíos",
                        "Por favor complete todos los campos requeridos antes de enviar.",
                    );
                }
                return;
            }

            // Validar cédula
            if (!cedulaValida) {
                if (typeof warning === "function") {
                    warning(
                        "Formato inválido",
                        "Por favor ingrese una cédula válida (7-9 dígitos).",
                    );
                }
                const cedulaInput = document.getElementById("cedula");
                if (cedulaInput) cedulaInput.focus();
                return;
            }

            // Validar RIF si tiene contenido
            const rifInput = document.getElementById("rif");
            if (rifInput && rifInput.value.trim() !== "" && !rifValido) {
                if (typeof warning === "function") {
                    warning(
                        "Formato de RIF inválido",
                        "Por favor ingrese un RIF válido (ej: J-12345678-9).",
                    );
                }
                rifInput.focus();
                return;
            }

            // Validar IMPRE si tiene contenido
            const impreInput = document.getElementById("impre");
            if (impreInput && impreInput.value.trim() !== "" && !impreValido) {
                if (typeof warning === "function") {
                    warning(
                        "Formato de IMPRE inválido",
                        "El IMPRE debe tener entre 6 y 20 dígitos numéricos.",
                    );
                }
                impreInput.focus();
                return;
            }

            // Validar correo si tiene contenido
            const correoInput = document.getElementById("correo");
            if (correoInput && correoInput.value.trim() !== "") {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(correoInput.value)) {
                    if (typeof warning === "function") {
                        warning(
                            "Correo inválido",
                            "Por favor ingrese un correo electrónico válido.",
                        );
                    }
                    correoInput.focus();
                    return;
                }
            }

            // Validar especialidades si hay alguna creada
            if (especialidadCount > 0 && !validarEspecialidades()) {
                return;
            }

            // 2. Enviar formulario
            e.target.submit();
        });
    }
});

// Validación en tiempo real para campos específicos
document.addEventListener("DOMContentLoaded", function () {
    const cedulaInput = document.getElementById("cedula");
    if (cedulaInput) {
        cedulaInput.addEventListener("input", function () {
            validarCedula(this, false);
            actualizarResumen();
        });
    }

    const correoInput = document.getElementById("correo");
    if (correoInput) {
        correoInput.addEventListener("input", function () {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.classList.add("is-invalid");
            } else {
                this.classList.remove("is-invalid");
            }
            actualizarResumen();
        });
    }

    // Validar RIF en tiempo real
    const rifInput = document.getElementById("rif");
    if (rifInput) {
        rifInput.addEventListener("input", function () {
            validarRIF(this, false);
            actualizarResumen();
        });
    }

    // Validar IMPRE en tiempo real
    const impreInput = document.getElementById("impre");
    if (impreInput) {
        impreInput.addEventListener("input", function () {
            validarIMPRE(this, false);
            actualizarResumen();
        });
    }

    // Validar formato de teléfono en tiempo real
    const telefonoInicio = document.getElementById("telefono_inicio");
    if (telefonoInicio) {
        telefonoInicio.addEventListener("input", function () {
            if (this.value && !/^\d{0,4}$/.test(this.value)) {
                this.value = this.value.slice(0, -1);
            }
            if (this.value.length === 4) {
                const telefonoRestante =
                    document.getElementById("telefono_restante");
                if (telefonoRestante) telefonoRestante.focus();
            }
        });
    }

    const telefonoRestante = document.getElementById("telefono_restante");
    if (telefonoRestante) {
        telefonoRestante.addEventListener("input", function () {
            if (this.value && !/^\d{0,7}$/.test(this.value)) {
                this.value = this.value.slice(0, -1);
            }
        });
    }

    // Manejar eventos de los botones de limpiar selección
    document.addEventListener("click", function (e) {
        if (e.target.closest('[id^="clear-selection-"]')) {
            const button = e.target.closest('[id^="clear-selection-"]');
            const itemId =
                button.getAttribute("data-item-id") ||
                button.id.replace("clear-selection-", "");
            if (itemId) {
                limpiarSeleccionEspecialidad(parseInt(itemId));
            }
        }
    });
});
