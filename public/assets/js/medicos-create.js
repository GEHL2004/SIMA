// Variables globales
let especialidadCount = 0;
let documentoCount = 0;
let cedulaValida = false;
let especialidadesSeleccionadas = new Set(); // Para rastrear especialidades ya seleccionadas

// Inicialización al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    
    // Ocultar mensajes de error iniciales
    document.getElementById('cedula-feedback').style.display = 'none';
    document.getElementById('cedula-valid-feedback').style.display = 'none';
    
    // Configurar evento blur para cédula (cuando pierde el foco)
    document.getElementById('cedula').addEventListener('blur', function() {
        // Al salir del campo, validar pero no forzar como requerido
        validarCedula(this, false);
    });
    
    // Actualizar resumen en tiempo real
    setupRealTimeUpdates();
});

// Función para validar cédula (7-9 dígitos) - VERSIÓN CORREGIDA
function validarCedula(input, esRequerido = true) {
    const cedula = input.value.trim();
    const cedulaRegex = /^\d{7,9}$/;
    
    // Remover clases previas
    input.classList.remove('is-valid', 'is-invalid', 'cedula-valid', 'cedula-invalid');
    
    // Ocultar todos los mensajes de feedback inicialmente
    document.getElementById('cedula-feedback').style.display = 'none';
    document.getElementById('cedula-valid-feedback').style.display = 'none';
    
    // Si el campo está vacío
    if (cedula === '') {
        cedulaValida = false;
        
        // Solo mostrar error si es requerido y estamos validando para avanzar
        if (esRequerido) {
            input.classList.add('is-invalid');
            document.getElementById('cedula-feedback').style.display = 'block';
            document.getElementById('cedula-feedback').textContent = 'La cédula es requerida.';
        }
        return false;
    }
    
    // Si hay contenido pero no cumple con el formato
    if (!cedulaRegex.test(cedula)) {
        cedulaValida = false;
        input.classList.add('is-invalid');
        input.classList.add('cedula-invalid');
        document.getElementById('cedula-feedback').style.display = 'block';
        document.getElementById('cedula-feedback').textContent = 'La cédula debe tener entre 7 y 9 dígitos numéricos.';
        return false;
    }
    
    // Cédula válida
    cedulaValida = true;
    input.classList.add('is-valid');
    input.classList.add('cedula-valid');
    document.getElementById('cedula-valid-feedback').style.display = 'block';
    return true;
}

// Función para traer parroquias según el municipio seleccionado
function traerParroquias(select) {
    const municipioId = select.value;
    const selectParroquia = document.getElementById('id_parroquia');
    
    if (!municipioId) {
        selectParroquia.innerHTML = '<option value="">Primero seleccione un municipio</option>';
        selectParroquia.disabled = true;
        selectParroquia.classList.remove('is-valid');
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
        selectParroquia.classList.remove('is-valid', 'is-invalid');
        
        // Actualizar resumen
        actualizarResumen();
    });
}

// Función para actualizar opciones de especialidades excluyendo las ya seleccionadas
function actualizarOpcionesEspecialidades() {
    // Obtener todos los selects de especialidades
    const selectsEspecialidades = document.querySelectorAll('.especialidad-select');
    
    // Crear un array con los IDs ya seleccionados (excepto vacíos)
    const idsSeleccionados = [];
    selectsEspecialidades.forEach(select => {
        if (select.value) {
            idsSeleccionados.push(select.value);
        }
    });
    
    // Actualizar cada select
    selectsEspecialidades.forEach(select => {
        const valorActual = select.value;
        const selectName = select.getAttribute('name');
        let selectId = '';
        
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
        const originalDoc = parser.parseFromString(select.dataset.originalOptions, 'text/html');
        const originalOptions = originalDoc.querySelectorAll('option');
        
        // Limpiar el select
        select.innerHTML = '';
        
        // Agregar opción por defecto
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = '- Seleccione -';
        defaultOption.disabled = true;
        defaultOption.selected = !valorActual;
        select.appendChild(defaultOption);
        
        // Agregar cada especialidad, excluyendo las ya seleccionadas en otros selects
        originalOptions.forEach(option => {
            if (option.value && option.value !== '') {
                const esSeleccionadoEnOtro = idsSeleccionados.includes(option.value) && option.value !== valorActual;
                
                if (!esSeleccionadoEnOtro) {
                    const newOption = document.createElement('option');
                    newOption.value = option.value;
                    newOption.textContent = option.textContent;
                    newOption.selected = (option.value === valorActual);
                    select.appendChild(newOption);
                }
            }
        });
        
        // Si el valor actual fue excluido (porque otro select lo tomó), resetear
        if (valorActual && !idsSeleccionados.includes(valorActual)) {
            select.value = '';
            // Actualizar la fecha correspondiente
            const fechaInput = document.querySelector(`input[name="especialidades[${selectId}][fecha_obtencion]"]`);
            if (fechaInput) {
                fechaInput.value = '';
                fechaInput.classList.remove('is-valid', 'is-invalid');
            }
            select.classList.remove('is-valid', 'is-invalid');
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
        return; // El mensaje de error ya se muestra en validarEspecialidades()
    }
    
    // Actualizar indicadores de pasos
    document.querySelectorAll('.step-indicator .step').forEach((step, index) => {
        step.classList.remove('active');
        if (index + 1 < nextStepNumber) {
            step.classList.add('completed');
        }
        if (index + 1 === nextStepNumber) {
            step.classList.add('active');
        }
    });
    
    // Mostrar el siguiente paso y ocultar los demás
    document.querySelectorAll('.form-section').forEach(section => {
        section.classList.remove('active');
    });
    document.getElementById(`step${nextStepNumber}`).classList.add('active');
    
    // Actualizar resumen si estamos en el paso 4
    if (nextStepNumber === 4) {
        actualizarResumen();
    }
}

function prevStep(currentStepNumber) {
    const prevStepNumber = currentStepNumber - 1;
    
    // Actualizar indicadores de pasos
    document.querySelectorAll('.step-indicator .step').forEach((step, index) => {
        step.classList.remove('active');
        if (index + 1 === prevStepNumber) {
            step.classList.add('active');
        }
        if (index + 1 >= currentStepNumber) {
            step.classList.remove('completed');
        }
    });
    
    // Mostrar el paso anterior y ocultar los demás
    document.querySelectorAll('.form-section').forEach(section => {
        section.classList.remove('active');
    });
    document.getElementById(`step${prevStepNumber}`).classList.add('active');
}

// Validación del paso actual - VERSIÓN CORREGIDA
function validarPasoActual(paso) {
    let esValido = true;
    
    if (paso === 1) {
        // Validar cédula (siempre requerida cuando se intenta avanzar)
        const cedulaInput = document.getElementById('cedula');
        if (!validarCedula(cedulaInput, true)) {
            esValido = false;
        }
        
        // Validar información personal (otros campos)
        const camposRequeridos = ['nombres', 'apellidos', 'fecha_nacimiento', 'tipo_sangre', 'correo', 'telefono_inicio', 'telefono_restante', 'direccion', 'id_municipio', 'id_parroquia'];
        
        camposRequeridos.forEach(campoId => {
            const campo = document.getElementById(campoId);
            if (campo && !campo.value.trim()) {
                campo.classList.add('is-invalid');
                esValido = false;
            } else if (campo) {
                campo.classList.remove('is-invalid');
                
                // Validaciones específicas para campos no vacíos
                if (campoId === 'telefono_inicio' && campo.value && !/^\d{4}$/.test(campo.value)) {
                    campo.classList.add('is-invalid');
                    esValido = false;
                }
                
                if (campoId === 'telefono_restante' && campo.value && !/^\d{7}$/.test(campo.value)) {
                    campo.classList.add('is-invalid');
                    esValido = false;
                }
                
                if (campoId === 'correo' && campo.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(campo.value)) {
                        campo.classList.add('is-invalid');
                        esValido = false;
                    }
                }
            }
        });
        
        // Validar que se haya seleccionado parroquia
        const parroquia = document.getElementById('id_parroquia');
        if (parroquia.disabled || !parroquia.value) {
            parroquia.classList.add('is-invalid');
            esValido = false;
        }
    }
    
    if (paso === 2) {
        // Validar información profesional
        const camposRequeridos = ['numero_colegio', 'matricula_ministerio', 'universidad_graduado', 'fecha_egreso_universidad', 'fecha_incripcion', 'id_grado_academico', 'lugar_de_trabajo'];
        
        camposRequeridos.forEach(campoId => {
            const campo = document.getElementById(campoId);
            if (campo && !campo.value.trim()) {
                campo.classList.add('is-invalid');
                esValido = false;
            } else if (campo) {
                campo.classList.remove('is-invalid');
            }
        });
        
        // Validar que la fecha de egreso no sea futura
        const fechaEgreso = new Date(document.getElementById('fecha_egreso_universidad').value);
        const hoy = new Date();
        if (fechaEgreso > hoy) {
            document.getElementById('fecha_egreso_universidad').classList.add('is-invalid');
            esValido = false;
            warning('Fecha inválida', 'La fecha de egreso no puede ser una fecha futura.');
        }
    }
    
    if (!esValido) {
        warning('Campos vacíos', 'Por favor complete todos los campos requeridos correctamente.');
    }
    
    return esValido;
}

// Función para validar una especialidad individual
function validarEspecialidadIndividual(input) {
    const select = input.closest('.row').querySelector('.especialidad-select');
    if (select.value && input.value) {
        select.classList.remove('is-invalid');
        select.classList.add('is-valid');
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    } else if (!select.value && input.value) {
        select.classList.add('is-invalid');
        input.classList.add('is-invalid');
    }
}

// Validar especialidades
function validarEspecialidades() {
    const especialidadesSelects = document.querySelectorAll('.especialidad-select');
    const fechasEspecialidades = document.querySelectorAll('.fecha-especialidad');
    let todasValidas = true;
    
    // Verificar duplicados
    const valoresSeleccionados = [];
    let tieneDuplicados = false;
    
    especialidadesSelects.forEach(select => {
        if (select.value) {
            if (valoresSeleccionados.includes(select.value)) {
                select.classList.add('is-invalid');
                tieneDuplicados = true;
                todasValidas = false;
            } else {
                valoresSeleccionados.push(select.value);
            }
        }
    });
    
    if (tieneDuplicados) {
        warning('Especialidades duplicadas', 'No puede seleccionar la misma especialidad más de una vez.');
        return false;
    }
    
    // Validar cada grupo de especialidad
    especialidadesSelects.forEach((select, index) => {
        const fecha = fechasEspecialidades[index];
        
        if (!select.value || !fecha.value) {
            select.classList.add('is-invalid');
            if (fecha) fecha.classList.add('is-invalid');
            todasValidas = false;
        } else {
            select.classList.remove('is-invalid');
            if (fecha) fecha.classList.remove('is-invalid');
        }
    });
    
    if (!todasValidas) {
        warning('Campos vacíos', 'Por favor complete o elimine todas las especialidades creadas.');
    }
    
    return todasValidas;
}

// Gestión de especialidades
document.getElementById('btn-agregar-especialidad').addEventListener('click', function() {
    if (especialidadCount >= 10) {
        warning('Limite alcanzado', 'Ha alcanzado el límite máximo de 10 especialidades.');
        return;
    }
    
    especialidadCount++;
    const container = document.getElementById('especialidades-container');
    
    // Crear el elemento de especialidad
    const especialidadDiv = document.createElement('div');
    especialidadDiv.className = 'specialty-item';
    especialidadDiv.id = `especialidad-${especialidadCount}`;
    
    especialidadDiv.innerHTML = `
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Especialidad <span class="required-asterisk">*</span></label>
                <select class="form-select especialidad-select" name="especialidades[${especialidadCount}][id_especialidad]" required onchange="actualizarOpcionesEspecialidades()">
                    <option value="" selected disabled>- Seleccione -</option>
                    ${especialidades.map(esp => `<option value="${esp.id}">${esp.nombre}</option>`).join('')}
                </select>
                <div class="invalid-feedback">
                    Por favor seleccione una especialidad.
                </div>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Fecha de Obtención <span class="required-asterisk">*</span></label>
                <input type="date" class="form-control fecha-especialidad" name="especialidades[${especialidadCount}][fecha_obtencion]" required max="${new Date().toISOString().split('T')[0]}" onchange="validarEspecialidadIndividual(this)">
                <div class="invalid-feedback">
                    Por favor seleccione la fecha.
                </div>
            </div>
            
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-specialty-btn w-100" onclick="eliminarEspecialidad(${especialidadCount})">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(especialidadDiv);
    
    // Actualizar opciones de todos los selects
    actualizarOpcionesEspecialidades();
    
    // Actualizar resumen
    actualizarResumen();
});

// Función para eliminar una especialidad
function eliminarEspecialidad(id) {
    const elemento = document.getElementById(`especialidad-${id}`);
    if (elemento) {
        // Remover del Set de especialidades seleccionadas
        const select = elemento.querySelector('.especialidad-select');
        if (select.value) {
            especialidadesSeleccionadas.delete(select.value);
        }
        
        elemento.remove();
        especialidadCount--;
        
        // Actualizar opciones de los selects restantes
        actualizarOpcionesEspecialidades();
        
        // Actualizar resumen
        actualizarResumen();
    }
}

// Gestión de documentos individuales
document.getElementById('btn-agregar-documento-unico').addEventListener('click', function() {
    documentoCount++;
    const container = document.getElementById('documentos-container');
    
    const documentoDiv = document.createElement('div');
    documentoDiv.className = 'mb-3 documento-item';
    documentoDiv.id = `documento-${documentoCount}`;
    
    documentoDiv.innerHTML = `
        <div class="input-group">
            <input type="file" class="form-control documento-file" name="documentos[${documentoCount}][archivo]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
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
    ['nombres', 'apellidos', 'cedula', 'correo'].forEach(campoId => {
        document.getElementById(campoId).addEventListener('input', actualizarResumen);
    });
    
    // Campos de municipio y parroquia
    document.getElementById('id_municipio').addEventListener('change', actualizarResumen);
    document.getElementById('id_parroquia').addEventListener('change', actualizarResumen);
    
    // Campos de información profesional
    ['numero_colegio', 'universidad_graduado'].forEach(campoId => {
        document.getElementById(campoId).addEventListener('input', actualizarResumen);
    });
    
    // Archivos múltiples
    document.getElementById('documentos-multiples').addEventListener('change', actualizarResumen);
}

// Actualizar el resumen
function actualizarResumen() {
    // Información personal
    const nombres = document.getElementById('nombres').value || 'No ingresado';
    const apellidos = document.getElementById('apellidos').value || 'No ingresado';
    document.getElementById('resumen-nombre').innerHTML = `<strong>Nombre:</strong> ${nombres} ${apellidos}`;
    
    const cedula = document.getElementById('cedula').value || 'No ingresada';
    document.getElementById('resumen-cedula').innerHTML = `<strong>Cédula:</strong> ${cedula}`;
    
    const correo = document.getElementById('correo').value || 'No ingresado';
    document.getElementById('resumen-correo').innerHTML = `<strong>Correo:</strong> ${correo}`;
    
    // Municipio
    const municipioSelect = document.getElementById('id_municipio');
    const municipioNombre = municipioSelect.options[municipioSelect.selectedIndex]?.text || 'No seleccionado';
    document.getElementById('resumen-municipio').innerHTML = `<strong>Municipio:</strong> ${municipioNombre}`;
    
    // Información profesional
    const numeroColegio = document.getElementById('numero_colegio').value || 'No ingresado';
    document.getElementById('resumen-colegio').innerHTML = `<strong>N° Colegio:</strong> ${numeroColegio}`;
    
    const universidad = document.getElementById('universidad_graduado').value || 'No ingresada';
    document.getElementById('resumen-universidad').innerHTML = `<strong>Universidad:</strong> ${universidad}`;
    
    // Contar especialidades (solo las que tienen valor seleccionado)
    const especialidadesSeleccionadasCount = Array.from(document.querySelectorAll('.especialidad-select')).filter(select => select.value).length;
    document.getElementById('resumen-especialidades').innerHTML = `<strong>Especialidades:</strong> ${especialidadesSeleccionadasCount}`;
    
    // Contar documentos individuales
    const documentosIndividuales = document.querySelectorAll('.documento-item').length;
    document.getElementById('resumen-documentos-individuales').innerHTML = `<strong>Documentos individuales:</strong> ${documentosIndividuales}`;
    
    // Contar archivos múltiples
    const archivosMultiples = document.getElementById('documentos-multiples').files.length;
    document.getElementById('resumen-documentos-multiples').innerHTML = `<strong>Archivos múltiples:</strong> ${archivosMultiples}`;
}

// Resetear formulario
function resetForm() {
    Swal.fire({
        icon: "question",
        title: "¿Está seguro de que desea limpiar todo el formulario? Se perderán todos los datos ingresados.",
        showDenyButton: true,
        confirmButtonText: "Si",
        confirmButtonColor: "#28A745",
        denyButtonText: "No",
        reverseButtons: "true",
    }).then((respuesta) => {
        if (respuesta.isConfirmed) {
            document.getElementById('medicoForm').reset();
        
            // Limpiar especialidades
            document.getElementById('especialidades-container').innerHTML = '';
            especialidadCount = 0;
            
            // Limpiar documentos
            document.getElementById('documentos-container').innerHTML = '';
            documentoCount = 0;
            
            // Resetear select de parroquias
            const selectParroquia = document.getElementById('id_parroquia');
            selectParroquia.innerHTML = '<option value="">Primero seleccione un municipio</option>';
            selectParroquia.disabled = true;
            selectParroquia.classList.remove('is-valid', 'is-invalid');
            
            // Resetear validación de cédula
            const cedulaInput = document.getElementById('cedula');
            cedulaInput.classList.remove('is-valid', 'is-invalid', 'cedula-valid', 'cedula-invalid');
            document.getElementById('cedula-feedback').style.display = 'none';
            document.getElementById('cedula-valid-feedback').style.display = 'none';
            cedulaValida = false;
            
            // Limpiar el Set de especialidades seleccionadas
            especialidadesSeleccionadas.clear();
            
            // Remover clases de error de otros campos
            ['nombres', 'apellidos', 'fecha_nacimiento', 'tipo_sangre', 'correo', 'telefono_inicio', 'telefono_restante', 'direccion', 'id_municipio'].forEach(campoId => {
                const campo = document.getElementById(campoId);
                if (campo) {
                    campo.classList.remove('is-valid', 'is-invalid');
                }
            });
            
            // Resetear pasos
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById('step1').classList.add('active');
            
            // Resetear indicadores de pasos
            document.querySelectorAll('.step-indicator .step').forEach((step, index) => {
                step.classList.remove('active', 'completed');
                if (index === 0) {
                    step.classList.add('active');
                }
            });
            
            // Actualizar resumen
            actualizarResumen();
        }
    });
}

// Manejar envío del formulario
document.getElementById('medicoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar todos los pasos antes de enviar
    if (!validarPasoActual(1) || !validarPasoActual(2)) {
        warning('Campos vacíos', 'Por favor complete todos los campos requeridos antes de enviar.');
        return;
    }
    
    // Validar cédula
    if (!cedulaValida) {
        warning('Formato inválido', 'Por favor ingrese una cédula válida (7-9 dígitos).');
        document.getElementById('cedula').focus();
        return;
    }
    
    // Validar especialidades si hay alguna creada
    if (!validarEspecialidades()) {
        // El mensaje de error ya se muestra en validarEspecialidades()
        return;
    }
    
    e.target.submit();
});

// Validación en tiempo real para campos específicos
document.getElementById('cedula').addEventListener('input', function() {
    // En tiempo real, no forzar como requerido (solo validar formato si hay contenido)
    validarCedula(this, false);
    actualizarResumen();
});

document.getElementById('correo').addEventListener('input', function() {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (this.value && !emailRegex.test(this.value)) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

// Validar formato de teléfono en tiempo real
document.getElementById('telefono_inicio').addEventListener('input', function() {
    if (this.value && !/^\d{0,4}$/.test(this.value)) {
        this.value = this.value.slice(0, -1);
    }
    if (this.value.length === 4) {
        document.getElementById('telefono_restante').focus();
    }
});

document.getElementById('telefono_restante').addEventListener('input', function() {
    if (this.value && !/^\d{0,7}$/.test(this.value)) {
        this.value = this.value.slice(0, -1);
    }
});

// Configurar fecha máxima para fechas (hoy)
const hoy = new Date().toISOString().split('T')[0];
document.getElementById('fecha_nacimiento').max = hoy;
document.getElementById('fecha_egreso_universidad').max = hoy;
document.getElementById('fecha_incripcion').max = hoy;

// Inicializar actualización de opciones si hay especialidades pre-cargadas
document.addEventListener('DOMContentLoaded', function() {
    // Si ya hay especialidades cargadas inicialmente
    setTimeout(() => {
        actualizarOpcionesEspecialidades();
    }, 100);
});