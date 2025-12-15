const form = document.getElementById("form");
const nombre = document.getElementById("nombre");
const codigo = document.getElementById("codigo");
const contador1 = document.getElementById("contador1");
const contador2 = document.getElementById("contador2");
let documentoCount = 0;
let cedulaValida = false;
let especialidadesSeleccionadas = new Set(); // Para rastrear especialidades ya seleccionadas

// Agregamos el evento 'input' (se dispara al escribir, borrar o pegar)

descripcion.addEventListener("input", function () {
    contador1.textContent = this.value.length;
});

codigo.addEventListener("input", function () {
    contador2.textContent = this.value.length;
});

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
    });
    
    // Actualizar el Set de especialidades seleccionadas
    especialidadesSeleccionadas = new Set(idsSeleccionados);

    console.log(especialidadesSeleccionadas);
}

function validarEspecialidadIndividual(input) {
    const select = input.closest('.row').querySelector('.especialidad-select');
    if (select.value && input.value) {
        select.classList.remove('is-invalid');
        select.classList.add('is-valid');
    } else if (!select.value && input.value) {
        select.classList.add('is-invalid');
    }
}

// Validar especialidades
function validarEspecialidades() {
    const especialidadesSelects = document.querySelectorAll('.especialidad-select');
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
        if (!select.value) {
            select.classList.add('is-invalid');
            todasValidas = false;
        } else {
            select.classList.remove('is-invalid');
        }
    });
    
    if (!todasValidas) {
        warning('Campos vacíos', 'Por favor complete o elimine todas las especialidades creadas.');
    }
    
    return todasValidas;
}

// Gestión de especialidades
document.getElementById('btn-agregar-especialidad').addEventListener('click', function() {
    if (especialidadCount >= especialidades.length) {
        warning('Limite alcanzado', 'Ha alcanzado el límite máximo de ' + especialidades.length + ' especialidades.');
        return;
    }
    
    especialidadCount++;
    const container = document.getElementById('especialidades-container');
    
    // Crear el elemento de especialidad
    const especialidadDiv = document.createElement('div');
    especialidadDiv.className = 'specialty-item';
    especialidadDiv.id = `especialidad-${especialidadCount}`;
    
    especialidadDiv.innerHTML = `
    <div class="row g-3 align-items-center mt-2">
        <div class="col-lg-12">
            <label class="form-label">Especialidad <span class="required-asterisk text-danger">*</span></label>
            <div class="input-group">
                <select class="form-select especialidad-select rounded-start-pill" name="especialidades[]" required onchange="actualizarOpcionesEspecialidades()">
                    <option value="" selected disabled>- Seleccione -</option>
                    ${especialidades.map(esp => `<option value="${esp.id}">${esp.nombre}</option>`).join('')}
                </select>
                <button type="button" class="btn btn-danger rounded-end-pill" onclick="eliminarEspecialidad(${especialidadCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    `;
    
    container.appendChild(especialidadDiv);
    
    // Actualizar opciones de todos los selects
    actualizarOpcionesEspecialidades();
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
        
    }
}

// Validación del formulario

form.addEventListener("submit", (evnt) => {
    evnt.preventDefault();
    function validarFormulario() {
        // Array de validaciones
        const validaciones = [
            {
                condicion: validar_campo_vacio('nombre'),
                mensaje: 'El campo "Nombre de la subespecialidad" está vacío. Por favor, complételo para continuar.',
                elemento: nombre
            },
            {
                condicion: validar_campo_vacio('codigo'),
                mensaje: 'El campo "Código de la subespecialidad" está vacío. Por favor, complételo para continuar.',
                elemento: codigo
            },
            {
                condicion: validar_campo_vacio('descripcion'),
                mensaje: 'El campo "Descripción de la subespecialidad" está vacío. Por favor, complételo para continuar.',
                elemento: descripcion
            },
            {
                condicion: codigo.value.length === 0,
                mensaje: 'El campo "Código" no puede estar vacío. Por favor, ingrese un código válido.',
                elemento: codigo
            },
            {
                condicion: codigo.value.length > 21,
                mensaje: 'El código no puede exceder los 21 caracteres. Por favor, reduzca la longitud del código.',
                elemento: codigo
            },
            {
                condicion: descripcion.value.length === 0,
                mensaje: 'El campo "Descripción" no puede estar vacío. Por favor, ingrese una descripción.',
                elemento: descripcion
            },
            {
                condicion: descripcion.value.length > 1000,
                mensaje: 'La descripción no puede exceder los 1000 caracteres. Por favor, reduzca la longitud de la descripción.',
                elemento: descripcion
            },
            {
                condicion: especialidadCount == 0,
                mensaje: 'La subespecialidad debe depender minimo 1 especialidad, agregue y seleccione una especialidad.'
            }
        ];
    
        // Recorrer validaciones
        for (let validacion of validaciones) {
            if (validacion.condicion) {
                warning('Validación requerida', validacion.mensaje);
                if (validacion.elemento) {
                    validacion.elemento.focus();
                }
                return false; // Detiene la validación
            }
        }
        
        return validarEspecialidades(); // Todas las validaciones pasaron
    }
    
    // Uso en tu evento
    if (validarFormulario()) {
        evnt.target.submit();
    }
});
