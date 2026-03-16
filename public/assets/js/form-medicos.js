// Variables globales actualizadas
let especialidadCount = 0;
let documentoCount = 0;
let cursoCount = 0;
let diplomadoCount = 0;
let cedulaValida = false;
let rifValido = false;
let impreValido = false;
let especialidadesSeleccionadas = new Set();

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
        ".especialidad-search-input"
    );
    const clearButton = searchContainer.querySelector(
        ".especialidad-search-clear"
    );
    const optionsList = searchContainer.querySelector(
        ".especialidad-options-list"
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
                esp.id.toString()
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
                (esp) => esp.tipo === "especialidad"
            );
            const subespecialidades = opcionesFiltradas.filter(
                (esp) => esp.tipo === "subespecialidad"
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

// Inicialización al cargar la página
document.addEventListener("DOMContentLoaded", function () {
    // Ocultar mensajes de error iniciales
    document.getElementById("cedula-feedback").style.display = "none";
    document.getElementById("cedula-valid-feedback").style.display = "none";
    document.getElementById("rif-feedback").style.display = "none";
    document.getElementById("rif-valid-feedback").style.display = "none";
    document.getElementById("impre-feedback").style.display = "none";
    document.getElementById("impre-valid-feedback").style.display = "none";

    // Configurar evento blur para cédula
    document.getElementById("cedula").addEventListener("blur", function () {
        validarCedula(this, false);
    });

    // Configurar evento blur para RIF
    document.getElementById("rif").addEventListener("blur", function () {
        validarRIF(this, false);
    });

    // Configurar evento blur para IMPRE
    document.getElementById("impre").addEventListener("blur", function () {
        validarIMPRE(this, false);
    });

    // Actualizar resumen en tiempo real
    setupRealTimeUpdates();

    // Cargar todas las especialidades desde PHP
    cargarTodasEspecialidades();

    // Inicializar eventos para cursos y diplomados
    inicializarCursosDiplomados();

    // Configurar fecha máxima para fechas (hoy)
    const hoy = new Date().toISOString().split("T")[0];
    document.getElementById("fecha_nacimiento").max = hoy;
    document.getElementById("fecha_egreso_universidad").max = hoy;
    document.getElementById("fecha_incripcion").max = hoy;

    // Si ya hay especialidades cargadas inicialmente
    setTimeout(() => {
        actualizarOpcionesEspecialidades();
    }, 100);
});

// Función para inicializar eventos de cursos y diplomados
function inicializarCursosDiplomados() {
    // Evento para agregar curso
    document.getElementById("btn-agregar-curso").addEventListener("click", agregarCurso);

    // Evento para agregar diplomado
    document.getElementById("btn-agregar-diplomado").addEventListener("click", agregarDiplomado);

    // Evento para deportes (actualizar contador)
    document.getElementById("deportes").addEventListener("change", actualizarContadores);
}

// Función para cargar todas las especialidades y subespecialidades
function cargarTodasEspecialidades() {
    // 'todasEspecialidades' ya está definida en el script de create.php
    if (typeof todasEspecialidades === "undefined") {
        console.error("No se pudieron cargar las especialidades");
        todasEspecialidades = [];
    }

    // Ordenar por tipo primero (especialidades primero) y luego por nombre
    todasEspecialidades.sort((a, b) => {
        if (a.tipo !== b.tipo) {
            return a.tipo === "especialidad" ? -1 : 1;
        }
        return a.nombre.localeCompare(b.nombre);
    });
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
        "cedula-invalid"
    );

    // Ocultar todos los mensajes de feedback inicialmente
    document.getElementById("cedula-feedback").style.display = "none";
    document.getElementById("cedula-valid-feedback").style.display = "none";

    // Si el campo está vacío
    if (cedula === "") {
        cedulaValida = false;

        // Solo mostrar error si es requerido y estamos validando para avanzar
        if (esRequerido) {
            input.classList.add("is-invalid");
            document.getElementById("cedula-feedback").style.display = "block";
            document.getElementById("cedula-feedback").textContent =
                "La cédula es requerida.";
        }
        return false;
    }

    // Si hay contenido pero no cumple con el formato
    if (!cedulaRegex.test(cedula)) {
        cedulaValida = false;
        input.classList.add("is-invalid");
        input.classList.add("cedula-invalid");
        document.getElementById("cedula-feedback").style.display = "block";
        document.getElementById("cedula-feedback").textContent =
            "La cédula debe tener entre 7 y 9 dígitos numéricos.";
        return false;
    }

    // Cédula válida
    cedulaValida = true;
    input.classList.add("is-valid");
    input.classList.add("cedula-valid");
    document.getElementById("cedula-valid-feedback").style.display = "block";
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
        "rif-invalid"
    );

    // Ocultar todos los mensajes de feedback inicialmente
    document.getElementById("rif-feedback").style.display = "none";
    document.getElementById("rif-valid-feedback").style.display = "none";

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
        document.getElementById("rif-feedback").style.display = "block";
        document.getElementById("rif-feedback").textContent =
            "Formato inválido. Use: J-12345678-9 (letra J, V, E, G, P seguida de guión, 7-9 dígitos, guión y 1 dígito)";
        return false;
    }

    // RIF válido
    rifValido = true;
    input.classList.add("is-valid");
    input.classList.add("rif-valid");
    document.getElementById("rif-valid-feedback").style.display = "block";
    
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
        "impre-invalid"
    );

    // Ocultar todos los mensajes de feedback inicialmente
    document.getElementById("impre-feedback").style.display = "none";
    document.getElementById("impre-valid-feedback").style.display = "none";

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
        document.getElementById("impre-feedback").style.display = "block";
        document.getElementById("impre-feedback").textContent =
            "El IMPRE debe tener entre 6 y 20 dígitos numéricos.";
        return false;
    }

    // IMPRE válido
    impreValido = true;
    input.classList.add("is-valid");
    input.classList.add("impre-valid");
    document.getElementById("impre-valid-feedback").style.display = "block";
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
    // Obtener todos los selects de especialidades
    const selectsEspecialidades = document.querySelectorAll(
        ".especialidad-select"
    );

    // Crear un array con los IDs ya seleccionados (excepto vacíos)
    const idsSeleccionados = [];
    selectsEspecialidades.forEach((select) => {
        if (select.value) {
            idsSeleccionados.push(select.value);
        }
    });

    // Actualizar cada select
    selectsEspecialidades.forEach((select) => {
        const valorActual = select.value;
        const selectName = select.getAttribute("name");
        let selectId = "";

        // Extraer el ID del nombre del campo
        if (selectName) {
            const match = selectName.match(/\[(\d+)\]/);
            if (match) {
                selectId = match[1];
            }
        }

        // Guardar opciones originales si aún no están guardadas
        if (!select.dataset.originalOptions) {
            select.dataset.originalOptions = select.innerHTML;
        }

        // Parsear las opciones originales
        const parser = new DOMParser();
        const originalDoc = parser.parseFromString(
            select.dataset.originalOptions,
            "text/html"
        );
        const originalOptions = originalDoc.querySelectorAll("option");

        // Limpiar el select
        select.innerHTML = "";

        // Agregar opción por defecto
        const defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "- Seleccione -";
        defaultOption.disabled = true;
        defaultOption.selected = !valorActual;
        select.appendChild(defaultOption);

        // Agregar cada especialidad, excluyendo las ya seleccionadas en otros selects
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

        // Si el valor actual fue excluido (porque otro select lo tomó), resetear
        if (valorActual && !idsSeleccionados.includes(valorActual)) {
            select.value = "";
            // Actualizar la fecha correspondiente
            const fechaInput = document.querySelector(
                `input[name="especialidades[${selectId}][fecha_obtencion]"]`
            );
            if (fechaInput) {
                fechaInput.value = "";
                fechaInput.classList.remove("is-valid", "is-invalid");
            }
            select.classList.remove("is-valid", "is-invalid");
        }
    });

    // Actualizar el Set de especialidades seleccionadas
    especialidadesSeleccionadas = new Set(idsSeleccionados);

    // Actualizar resumen
    actualizarResumen();
}

// Navegación entre pasos
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

    // Actualizar resumen si estamos en el paso 4
    if (nextStepNumber === 4) {
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
        // Validar cédula (siempre requerida cuando se intenta avanzar)
        const cedulaInput = document.getElementById("cedula");
        if (!validarCedula(cedulaInput, true)) {
            esValido = false;
        }

        // Validar RIF (opcional, pero si tiene contenido debe ser válido)
        const rifInput = document.getElementById("rif");
        if (rifInput.value.trim() !== "" && !validarRIF(rifInput, false)) {
            esValido = false;
        }

        // Validar correo (opcional, pero si tiene contenido debe ser válido)
        const correoInput = document.getElementById("correo");
        if (correoInput.value.trim() !== "") {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(correoInput.value)) {
                correoInput.classList.add("is-invalid");
                esValido = false;
            } else {
                correoInput.classList.remove("is-invalid");
            }
        }

        // Validar información personal (otros campos requeridos)
        const camposRequeridos = [
            "nombres",
            "apellidos",
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
            }
        });

        // Validar que se haya seleccionado parroquia
        const parroquia = document.getElementById("id_parroquia");
        if (parroquia.disabled || !parroquia.value) {
            parroquia.classList.add("is-invalid");
            esValido = false;
        }
    }

    if (paso === 2) {

        // Validar IMPRE (opcional, pero si tiene contenido debe ser válido)
        const impreInput = document.getElementById("impre");
        if (impreInput.value.trim() !== "" && !validarIMPRE(impreInput, false)) {
            esValido = false;
        }

        // Validar información profesional
        const camposRequeridos = [
            "numero_colegio",
            "matricula_ministerio",
            "universidad_graduado",
            "fecha_egreso_universidad",
            "fecha_incripcion",
            "lugar_de_trabajo",
            "id_grado_academico",
            "estado"
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
        const fechaEgreso = new Date(
            document.getElementById("fecha_egreso_universidad").value
        );
        const hoy = new Date();
        if (fechaEgreso > hoy) {
            document
                .getElementById("fecha_egreso_universidad")
                .classList.add("is-invalid");
            esValido = false;
            warning(
                "Fecha inválida",
                "La fecha de egreso no puede ser una fecha futura."
            );
        }
        
        // Validar cursos individuales
        document.querySelectorAll('.curso-item').forEach((curso) => {
            const nombre = curso.querySelector('input[name$="[nombre]"]');
            const fecha = curso.querySelector('input[name$="[fecha_obtencion]"]');
            const universidad = curso.querySelector('input[name$="[universidad_obtenido]"]');
            
            if (nombre && !nombre.value.trim()) {
                nombre.classList.add('is-invalid');
                esValido = false;
            }
            if (fecha && !fecha.value) {
                fecha.classList.add('is-invalid');
                esValido = false;
            }
            if (universidad && !universidad.value.trim()) {
                universidad.classList.add('is-invalid');
                esValido = false;
            }
        });
        
        // Validar diplomados individuales
        document.querySelectorAll('.diplomado-item').forEach((diplomado) => {
            const nombre = diplomado.querySelector('input[name$="[nombre]"]');
            const fecha = diplomado.querySelector('input[name$="[fecha_obtencion]"]');
            const universidad = diplomado.querySelector('input[name$="[universidad_obtenido]"]');
            
            if (nombre && !nombre.value.trim()) {
                nombre.classList.add('is-invalid');
                esValido = false;
            }
            if (fecha && !fecha.value) {
                fecha.classList.add('is-invalid');
                esValido = false;
            }
            if (universidad && !universidad.value.trim()) {
                universidad.classList.add('is-invalid');
                esValido = false;
            }
        });
    }

    if (!esValido) {
        warning(
            "Campos vacíos",
            "Por favor complete todos los campos requeridos correctamente."
        );
    }

    return esValido;
}

// Función para validar una especialidad individual
function validarEspecialidadIndividual(input) {
    const itemDiv = input.closest(".especialidad-item");
    if (!itemDiv) {
        console.error("No se encontró el contenedor especialidad-item");
        return;
    }
    const hiddenInput = itemDiv.querySelector(".especialidad-id-input");
    const universidadInput = itemDiv.querySelector(".universidad-obtenido-input");
    
    if (!hiddenInput) {
        console.error("No se encontró el input hidden");
        return;
    }
    
    // Limpiar clases previas
    hiddenInput.classList.remove("is-valid", "is-invalid");
    input.classList.remove("is-valid", "is-invalid");
    if (universidadInput) universidadInput.classList.remove("is-valid", "is-invalid");

    if (hiddenInput.value && input.value && universidadInput && universidadInput.value) {
        hiddenInput.classList.remove("is-invalid");
        input.classList.remove("is-invalid");
        universidadInput.classList.remove("is-invalid");
        input.classList.add("is-valid");
        universidadInput.classList.add("is-valid");
    } else if (!hiddenInput.value || !input.value || !universidadInput || !universidadInput.value) {
        if (!hiddenInput.value) hiddenInput.classList.add("is-invalid");
        if (!input.value) input.classList.add("is-invalid");
        if (universidadInput && !universidadInput.value) universidadInput.classList.add("is-invalid");
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
        const hiddenInput = item.querySelector(".especialidad-id-input");
        if (hiddenInput.value) {
            if (valoresSeleccionados.includes(hiddenInput.value)) {
                tieneDuplicados = true;
                todasValidas = false;
                hiddenInput.classList.add("is-invalid");
            } else {
                valoresSeleccionados.push(hiddenInput.value);
            }
        }
    });

    if (tieneDuplicados) {
        warning(
            "Especialidades duplicadas",
            "No puede seleccionar la misma especialidad/subespecialidad más de una vez."
        );
        return false;
    }

    // Validar cada item
    items.forEach((item) => {
        const hiddenInput = item.querySelector(".especialidad-id-input");
        const fecha = item.querySelector(".fecha-especialidad");
        const universidad = item.querySelector(".universidad-obtenido-input");

        if (!hiddenInput.value || !fecha.value || !universidad.value) {
            if (!hiddenInput.value) hiddenInput.classList.add("is-invalid");
            if (!fecha.value) fecha.classList.add("is-invalid");
            if (!universidad.value) universidad.classList.add("is-invalid");
            todasValidas = false;
        } else {
            hiddenInput.classList.remove("is-invalid");
            fecha.classList.remove("is-invalid");
            universidad.classList.remove("is-invalid");
        }
    });

    if (!todasValidas) {
        warning(
            "Campos vacíos",
            "Por favor complete o elimine todas las especialidades/subespecialidades creadas."
        );
    }

    return todasValidas;
}

// Modificar la función para agregar especialidad/subespecialidad
document
    .getElementById("btn-agregar-especialidad")
    .addEventListener("click", function () {
        if (especialidadCount >= todasEspecialidades.length) {
            warning(
                "Límite alcanzado",
                `Ha alcanzado el límite máximo de ${todasEspecialidades.length} especialidades/subespecialidades.`
            );
            return;
        }

        especialidadCount++;
        const container = document.getElementById("especialidades-container");

        // Crear el elemento de especialidad/subespecialidad
        const itemDiv = document.createElement("div");
        itemDiv.className = "especialidad-item";
        itemDiv.id = `especialidad-${especialidadCount}`;

        itemDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-start mb-3">
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
        
        <div class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label">Seleccionar <span class="required-asterisk">*</span></label>
                <div id="buscador-container-${especialidadCount}">
                    <!-- Aquí se insertará el buscador dinámicamente -->
                </div>
                <input type="hidden" 
                       class="especialidad-id-input" 
                       name="especialidades[${especialidadCount}][id_especialidad]" 
                       value="">
                <div class="selected-especialidad-info mt-2" style="display: none;" id="SelectedEspecialidadInfo">
                    <div class="alert alert-light py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Seleccionado:</strong> 
                            <span id="selected-name-${especialidadCount}"></span>
                            <span class="badge bg-primary ms-2" id="selected-tipo-${especialidadCount}">Esp</span>
                        </div>
                    </div>
                </div>
                <div class="invalid-feedback">
                    Por favor seleccione una especialidad o subespecialidad.
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
                <div class="invalid-feedback">
                    Por favor seleccione la fecha.
                </div>
            </div>
            
            <div class="col-lg-1">
                <button type="button" 
                        class="btn btn-danger remove-specialty-btn w-100" 
                        onclick="eliminarEspecialidad(${especialidadCount})">
                    <i class="fas fa-trash"></i>
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
        actualizarContadores();
    });

function crearBuscadorParaItem(itemId) {
    const container = document.getElementById(`buscador-container-${itemId}`);
    const selectedInfo = document.querySelector(
        `#especialidad-${itemId} .selected-especialidad-info`
    );
    const hiddenInput = document.querySelector(
        `#especialidad-${itemId} .especialidad-id-input`
    );
    const selectedNameSpan = document.getElementById(`selected-name-${itemId}`);
    const selectedTipoSpan = document.getElementById(`selected-tipo-${itemId}`);

    // Limpiar contenedor si ya tiene contenido
    container.innerHTML = "";

    // Crear buscador
    const buscador = crearBuscadorEspecialidades(
        `buscador-container-${itemId}`,
        function (id, nombre, tipo, especialidadAnterior) {
            // 1. Primero, remover la especialidad anterior del Set (si existe)
            if (especialidadAnterior && especialidadAnterior.id) {
                especialidadesSeleccionadas.delete(especialidadAnterior.id);
            }

            // 2. Agregar la nueva especialidad al Set
            if (id) {
                especialidadesSeleccionadas.add(id);
            }

            // 3. Actualizar los elementos del formulario
            hiddenInput.value = id;
            selectedNameSpan.textContent = nombre;
            selectedTipoSpan.textContent = tipo === "subespecialidad" ? "Sub" : "Esp";
            selectedTipoSpan.className = `badge ${
                tipo === "subespecialidad" ? "bg-purple" : "bg-primary"
            } ms-2`;

            // 4. Mostrar información de selección
            selectedInfo.style.display = "block";

            // 5. Actualizar tipo radio button
            const tipoRadio = document.querySelector(
                `#especialidad-${itemId} input[name="especialidades[${itemId}][tipo]"][value="${tipo}"]`
            );
            if (tipoRadio && !tipoRadio.checked) {
                tipoRadio.checked = true;
                actualizarTipoEspecialidad(itemId);
            }

            // 6. Actualizar otros buscadores
            actualizarTodosLosBuscadores();

            // 7. Validar
            const fechaInput = document.querySelector(
                `#especialidad-${itemId} .fecha-especialidad`
            );
            const universidadInput = document.querySelector(
                `#especialidad-${itemId} .universidad-obtenido-input`
            );
            
            if (fechaInput && universidadInput) {
                validarEspecialidadIndividual(fechaInput);
            }

            // 8. Actualizar resumen
            actualizarResumen();
            actualizarContadores();
        }
    );

    // Agregar botón para limpiar selección en el selected-info
    const clearBtn = document.createElement("button");
    clearBtn.type = "button";
    clearBtn.className = "btn btn-sm btn-outline-danger ms-2";
    clearBtn.id = `clear-selection-${itemId}`;
    clearBtn.innerHTML = '<i class="fas fa-times"></i>';
    clearBtn.title = "Quitar selección";

    clearBtn.addEventListener("click", function () {
        // Limpiar la selección
        const especialidadActual = buscador.getEspecialidadActual();

        if (especialidadActual && especialidadActual.id) {
            // Remover del Set
            especialidadesSeleccionadas.delete(
                especialidadActual.id.toString()
            );

            // Limpiar inputs
            hiddenInput.value = "";
            selectedInfo.style.display = "none";
            // Limpiar validación
            hiddenInput.classList.remove("is-valid", "is-invalid");

            // Actualizar buscador
            buscador.limpiarSeleccion();

            // Actualizar otros buscadores
            actualizarTodosLosBuscadores();

            // Actualizar resumen
            actualizarResumen();
            actualizarContadores();
        }
    });

    // Agregar botón al selected-info
    selectedInfo.querySelector(".alert-light").appendChild(clearBtn);

    return buscador;
}

// Función para actualizar el tipo de especialidad
function actualizarTipoEspecialidad(itemId) {
    const itemDiv = document.getElementById(`especialidad-${itemId}`);
    const tipoBadge = itemDiv.querySelector(".tipo-badge");
    const tipoRadio = itemDiv.querySelector(
        'input[name="especialidades[' + itemId + '][tipo]"]:checked'
    );

    if (tipoRadio) {
        const tipo = tipoRadio.value;

        // Actualizar clase del item
        if (tipo === "subespecialidad") {
            itemDiv.classList.add("subespecialidad");
            itemDiv.classList.remove("especialidad");
            tipoBadge.textContent = "Subespecialidad";
            tipoBadge.className = "tipo-badge badge bg-purple";
        } else {
            itemDiv.classList.add("especialidad");
            itemDiv.classList.remove("subespecialidad");
            tipoBadge.textContent = "Especialidad";
            tipoBadge.className = "tipo-badge badge bg-primary";
        }

        // Si ya hay una selección, actualizar el badge del tipo
        const selectedTipoSpan = document.getElementById(
            `selected-tipo-${itemId}`
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

// Función modificada para eliminar especialidad
function eliminarEspecialidad(id) {
    const elemento = document.getElementById(`especialidad-${id}`);
    if (elemento) {
        // Remover del Set de especialidades seleccionadas
        const hiddenInput = elemento.querySelector(".especialidad-id-input");
        if (hiddenInput.value) {
            especialidadesSeleccionadas.delete(hiddenInput.value);
        }

        elemento.remove();
        especialidadCount--;

        // Actualizar opciones disponibles en otros buscadores
        actualizarOpcionesDisponibles();

        // Actualizar resumen
        actualizarResumen();
        actualizarContadores();
    }
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
    const contadorCursos = document.getElementById('contador-cursos');
    if (contadorCursos) {
        contadorCursos.textContent = `${cursoCount} curso${cursoCount !== 1 ? 's' : ''}`;
    }
    
    // Contador de diplomados
    const contadorDiplomados = document.getElementById('contador-diplomados');
    if (contadorDiplomados) {
        contadorDiplomados.textContent = `${diplomadoCount} diplomado${diplomadoCount !== 1 ? 's' : ''}`;
    }
}

// Gestión de documentos individuales
document
    .getElementById("btn-agregar-documento-unico")
    .addEventListener("click", function () {
        documentoCount++;
        const container = document.getElementById("documentos-container");

        const documentoDiv = document.createElement("div");
        documentoDiv.className = "mb-3 documento-item";
        documentoDiv.id = `documento-${documentoCount}`;

        documentoDiv.innerHTML = `
        <div class="input-group">
            <input type="file" class="form-control documento-file" name="documentos[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
            <button class="btn btn-outline-danger" type="button" onclick="eliminarDocumento(${documentoCount})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="invalid-feedback">
            Por favor seleccione un archivo.
        </div>
    `;

        container.appendChild(documentoDiv);
        actualizarResumen();
    });

// Función para eliminar un documento
function eliminarDocumento(id) {
    const elemento = document.getElementById(`documento-${id}`);
    if (elemento) {
        elemento.remove();
        documentoCount--;
        actualizarResumen();
    }
}

// Configurar actualizaciones en tiempo real para el resumen
function setupRealTimeUpdates() {
    // Campos de información personal
    ["nombres", "apellidos", "cedula", "correo", "rif", "impre"].forEach((campoId) => {
        const campo = document.getElementById(campoId);
        if (campo) {
            campo.addEventListener("input", actualizarResumen);
        }
    });

    // Campos de municipio y parroquia
    document
        .getElementById("id_municipio")
        .addEventListener("change", actualizarResumen);
    document
        .getElementById("id_parroquia")
        .addEventListener("change", actualizarResumen);

    // Campos de información profesional
    ["numero_colegio", "universidad_graduado"].forEach((campoId) => {
        const campo = document.getElementById(campoId);
        if (campo) {
            campo.addEventListener("input", actualizarResumen);
        }
    });

    // Archivos múltiples
    document
        .getElementById("documentos-multiples")
        .addEventListener("change", actualizarResumen);
}

// Actualizar el resumen
function actualizarResumen() {
    // Información personal
    const nombres = document.getElementById("nombres").value || "No ingresado";
    const apellidos =
        document.getElementById("apellidos").value || "No ingresado";
    document.getElementById(
        "resumen-nombre"
    ).innerHTML = `<strong>Nombre:</strong> ${nombres} ${apellidos}`;

    const cedula = document.getElementById("cedula").value || "No ingresada";
    document.getElementById(
        "resumen-cedula"
    ).innerHTML = `<strong>Cédula:</strong> ${cedula}`;

    const correo = document.getElementById("correo").value || "No ingresado";
    document.getElementById(
        "resumen-correo"
    ).innerHTML = `<strong>Correo:</strong> ${correo === "No ingresado" ? "No proporcionado" : correo}`;

    const rif = document.getElementById("rif").value || "No ingresado";
    document.getElementById(
        "resumen-rif"
    ).innerHTML = `<strong>RIF:</strong> ${rif === "No ingresado" ? "No proporcionado" : rif}`;

    const impre = document.getElementById("impre").value || "No ingresado";
    document.getElementById(
        "resumen-impre"
    ).innerHTML = `<strong>IMPRE:</strong> ${impre === "No ingresado" ? "No proporcionado" : impre}`;

    // Municipio
    const municipioSelect = document.getElementById("id_municipio");
    const municipioNombre =
        municipioSelect.options[municipioSelect.selectedIndex]?.text ||
        "No seleccionado";
    document.getElementById(
        "resumen-municipio"
    ).innerHTML = `<strong>Municipio:</strong> ${municipioNombre}`;

    // Información profesional
    const numeroColegio =
        document.getElementById("numero_colegio").value || "No ingresado";
    document.getElementById(
        "resumen-colegio"
    ).innerHTML = `<strong>N° Colegio:</strong> ${numeroColegio}`;

    const universidad =
        document.getElementById("universidad_graduado").value || "No ingresada";
    document.getElementById(
        "resumen-universidad"
    ).innerHTML = `<strong>Universidad:</strong> ${universidad}`;

    // Contar especialidades/subespecialidades
    const itemsSeleccionados = Array.from(
        document.querySelectorAll(".especialidad-item")
    ).filter((item) => {
        const hiddenInput = item.querySelector(".especialidad-id-input");
        return hiddenInput && hiddenInput.value;
    }).length;

    document.getElementById(
        "resumen-especialidades"
    ).innerHTML = `<strong>Especialidades/Subespecialidades:</strong> ${itemsSeleccionados}`;

    // Contar cursos
    const cursosCount = document.querySelectorAll('.curso-item').length;
    const resumenCursos = document.getElementById('resumen-cursos');
    if (resumenCursos) {
        resumenCursos.innerHTML = `<strong>Cursos:</strong> <span class="text-muted">${cursosCount}</span>`;
    }

    // Contar diplomados
    const diplomadosCount = document.querySelectorAll('.diplomado-item').length;
    const resumenDiplomados = document.getElementById('resumen-diplomados');
    if (resumenDiplomados) {
        resumenDiplomados.innerHTML = `<strong>Diplomados:</strong> <span class="text-muted">${diplomadosCount}</span>`;
    }

    // Contar deportes
    const deportesSelect = document.getElementById('deportes');
    const deportesCount = deportesSelect ? deportesSelect.selectedOptions.length : 0;
    const resumenDeportes = document.getElementById('resumen-deportes');
    if (resumenDeportes) {
        resumenDeportes.innerHTML = `<strong>Deportes:</strong> <span class="text-muted">${deportesCount}</span>`;
    }

    // Contar documentos individuales
    const documentosIndividuales =
        document.querySelectorAll(".documento-item").length;
    document.getElementById(
        "resumen-documentos-individuales"
    ).innerHTML = `<strong>Documentos individuales:</strong> ${documentosIndividuales}`;

    // Contar archivos múltiples
    const archivosMultiples = document.getElementById("documentos-multiples")
        .files.length;
    document.getElementById(
        "resumen-documentos-multiples"
    ).innerHTML = `<strong>Archivos múltiples:</strong> ${archivosMultiples}`;
}

// Resetear formulario
function resetForm() {
    Swal.fire({
        icon: "question",
        title: "¿Está seguro de que desea limpiar todo el formulario? Se perderán todos los datos ingresados.",
        showDenyButton: true,
        confirmButtonText: "Si, limpiar formulario",
        confirmButtonColor: "#28A745",
        denyButtonText: "Cancelar",
        reverseButtons: "true",
    }).then((respuesta) => {
        if (respuesta.isConfirmed) {
            document.getElementById("medicoForm").reset();

            // Limpiar especialidades
            document.getElementById("especialidades-container").innerHTML = "";
            especialidadCount = 0;
            especialidadesSeleccionadas.clear();

            // Limpiar cursos
            document.getElementById("cursos-container").innerHTML = "";
            cursoCount = 0;

            // Limpiar diplomados
            document.getElementById("diplomados-container").innerHTML = "";
            diplomadoCount = 0;

            // Limpiar documentos
            document.getElementById("documentos-container").innerHTML = "";
            documentoCount = 0;

            // Resetear select de parroquias
            const selectParroquia = document.getElementById("id_parroquia");
            selectParroquia.innerHTML =
                '<option value="">Primero seleccione un municipio</option>';
            selectParroquia.disabled = true;
            selectParroquia.classList.remove("is-valid", "is-invalid");

            // Resetear deportes
            const deportesSelect = document.getElementById('deportes');
            if (deportesSelect) {
                for (let i = 0; i < deportesSelect.options.length; i++) {
                    deportesSelect.options[i].selected = false;
                }
            }

            // Resetear validación de cédula
            const cedulaInput = document.getElementById("cedula");
            cedulaInput.classList.remove(
                "is-valid",
                "is-invalid",
                "cedula-valid",
                "cedula-invalid"
            );
            document.getElementById("cedula-feedback").style.display = "none";
            document.getElementById("cedula-valid-feedback").style.display =
                "none";
            cedulaValida = false;

            // Resetear validación de RIF
            const rifInput = document.getElementById("rif");
            rifInput.classList.remove(
                "is-valid",
                "is-invalid",
                "rif-valid",
                "rif-invalid"
            );
            document.getElementById("rif-feedback").style.display = "none";
            document.getElementById("rif-valid-feedback").style.display = "none";
            rifValido = false;

            // Resetear validación de IMPRE
            const impreInput = document.getElementById("impre");
            impreInput.classList.remove(
                "is-valid",
                "is-invalid",
                "impre-valid",
                "impre-invalid"
            );
            document.getElementById("impre-feedback").style.display = "none";
            document.getElementById("impre-valid-feedback").style.display = "none";
            impreValido = false;

            // Limpiar el Set de especialidades seleccionadas
            especialidadesSeleccionadas.clear();

            // Remover clases de error de otros campos
            [
                "nombres",
                "apellidos",
                "fecha_nacimiento",
                "tipo_sangre",
                "correo",
                "telefono_inicio",
                "telefono_restante",
                "direccion",
                "id_municipio",
                "numero_colegio",
                "matricula_ministerio",
                "universidad_graduado",
                "lugar_de_trabajo",
                "estado"
            ].forEach((campoId) => {
                const campo = document.getElementById(campoId);
                if (campo) {
                    campo.classList.remove("is-valid", "is-invalid");
                }
            });

            // Resetear pasos
            document.querySelectorAll(".form-section").forEach((section) => {
                section.classList.remove("active");
            });
            document.getElementById("step1").classList.add("active");

            // Resetear indicadores de pasos
            document
                .querySelectorAll(".step-indicator .step")
                .forEach((step, index) => {
                    step.classList.remove("active", "completed");
                    if (index === 0) {
                        step.classList.add("active");
                    }
                });

            // Actualizar resumen y contadores
            actualizarResumen();
            actualizarContadores();
        }
    });
}

// Función para actualizar todos los buscadores
function actualizarTodosLosBuscadores() {
    const buscadores = document.querySelectorAll(
        ".especialidad-search-container"
    );

    buscadores.forEach((container) => {
        const searchInput = container.querySelector(
            ".especialidad-search-input"
        );
        if (searchInput) {
            const event = new Event("input", { bubbles: true });
            searchInput.dispatchEvent(event);
        }
    });
}

// Función para procesar los IDs antes de enviar
function procesarIDsParaEnviar() {
    // Crear inputs hidden para los IDs originales
    document.querySelectorAll('.especialidad-id-input').forEach(input => {
        const idCompleto = input.value;
        
        if (idCompleto) {
            // Extraer el ID original (sin el prefijo)
            const partes = idCompleto.split('_');
            const tipo = partes[0]; // 'esp' o 'sub'
            const idOriginal = partes[1];
            
            // Crear un campo hidden para el tipo
            const tipoInput = document.createElement('input');
            tipoInput.type = 'hidden';
            tipoInput.name = input.name.replace('id_especialidad', 'tipo_real');
            tipoInput.value = tipo;
            input.parentNode.appendChild(tipoInput);
            
            // Crear un campo hidden para el ID original
            const idOriginalInput = document.createElement('input');
            idOriginalInput.type = 'hidden';
            idOriginalInput.name = input.name.replace('id_especialidad', 'id_original');
            idOriginalInput.value = idOriginal;
            input.parentNode.appendChild(idOriginalInput);
        }
    });
}

// Validación en tiempo real para campos específicos
document.getElementById("cedula").addEventListener("input", function () {
    validarCedula(this, false);
    actualizarResumen();
});

document.getElementById("correo").addEventListener("input", function () {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (this.value && !emailRegex.test(this.value)) {
        this.classList.add("is-invalid");
    } else {
        this.classList.remove("is-invalid");
    }
    actualizarResumen();
});

// Validar RIF en tiempo real
document.getElementById("rif").addEventListener("input", function () {
    validarRIF(this, false);
    actualizarResumen();
});

// Validar IMPRE en tiempo real
document.getElementById("impre").addEventListener("input", function () {
    validarIMPRE(this, false);
    actualizarResumen();
});

// Validar formato de teléfono en tiempo real
document
    .getElementById("telefono_inicio")
    .addEventListener("input", function () {
        if (this.value && !/^\d{0,4}$/.test(this.value)) {
            this.value = this.value.slice(0, -1);
        }
        if (this.value.length === 4) {
            document.getElementById("telefono_restante").focus();
        }
    });

document
    .getElementById("telefono_restante")
    .addEventListener("input", function () {
        if (this.value && !/^\d{0,7}$/.test(this.value)) {
            this.value = this.value.slice(0, -1);
        }
    });

// Manejar envío del formulario
document.getElementById("medicoForm").addEventListener("submit", function (e) {
    e.preventDefault();
    
    // 1. Procesar los IDs
    procesarIDsParaEnviar();
    
    // 2. Validaciones
    if (!validarPasoActual(1) || !validarPasoActual(2)) {
        warning(
            "Campos vacíos",
            "Por favor complete todos los campos requeridos correctamente."
        );
        return;
    }

    // Validar cédula
    if (!cedulaValida) {
        warning(
            "Formato inválido",
            "Por favor ingrese una cédula válida (7-9 dígitos)."
        );
        document.getElementById("cedula").focus();
        return;
    }

    // Validar RIF si tiene contenido
    const rifInput = document.getElementById("rif");
    if (rifInput.value.trim() !== "" && !rifValido) {
        warning(
            "Formato de RIF inválido",
            "Por favor ingrese un RIF válido (ej: J-12345678-9)."
        );
        rifInput.focus();
        return;
    }

    // Validar IMPRE si tiene contenido
    const impreInput = document.getElementById("impre");
    if (impreInput.value.trim() !== "" && !impreValido) {
        warning(
            "Formato de IMPRE inválido",
            "El IMPRE debe tener entre 6 y 20 dígitos numéricos."
        );
        impreInput.focus();
        return;
    }

    // Validar correo si tiene contenido
    const correoInput = document.getElementById("correo");
    if (correoInput.value.trim() !== "") {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(correoInput.value)) {
            warning(
                "Correo inválido",
                "Por favor ingrese un correo electrónico válido."
            );
            correoInput.focus();
            return;
        }
    }

    // Validar especialidades si hay alguna creada
    if (especialidadCount > 0 && !validarEspecialidades()) {
        return;
    }

    // Si todo está bien, enviar el formulario
    e.target.submit();
});