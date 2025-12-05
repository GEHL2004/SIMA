const form = document.getElementById("form");
const nombre = document.getElementById("nombre");
const codigo = document.getElementById("codigo");
const selectRequiereEspecialidad = document.getElementById("RequiereEspecialidad");
const especialidad = document.getElementById("especialidad");
const especialidadContainer = document.getElementById("especialidadContainer");
const noEspecialidadContainer = document.getElementById("noEspecialidadContainer");
const categoria = document.getElementById("categoria");
const tipo_practica = document.getElementById("tipo_practica");
const sistema_corporal = document.getElementById("sistema_corporal");
const descripcion = document.getElementById("descripcion");
const contador1 = document.getElementById("contador1");
const contador2 = document.getElementById("contador2");
const TRANSITION_DURATION = 300; // 0.3s

// Agregamos el evento 'input' (se dispara al escribir, borrar o pegar)

descripcion.addEventListener("input", function () {
    contador1.textContent = this.value.length;
});

codigo.addEventListener("input", function () {
    contador2.textContent = this.value.length;
});

/**
 * Función genérica para mostrar un contenedor con fade-in.
 * @param {HTMLElement} container - El contenedor a mostrar.
 */
function showContainer(container) {
    // 1. Cambiar display a block
    container.style.display = 'block';
    // 2. Forzar reflow para que la transición funcione
    void container.offsetWidth;
    // 3. Añadir la clase para animar opacity
    container.classList.add('visible');
}

/**
 * Función genérica para ocultar un contenedor con fade-out y resetear valores.
 * @param {HTMLElement} container - El contenedor a ocultar.
 * @param {HTMLElement[]} fieldsToReset - Array de campos de formulario a resetear.
 */
function hideContainer(container, fieldsToReset = []) {
    // 1. Animar opacity (fade-out)
    container.classList.remove('visible');

    // 2. Esperar a que termine la transición para ocultar display y resetear campos
    setTimeout(() => {
        container.style.display = 'none';
        // Resetear los valores de los campos
        fieldsToReset.forEach(field => {
            if (field) { // Asegurarse de que el elemento existe
                field.value = "";
                // Opcional: Si usas select, podrías forzar a seleccionar la opción deshabilitada
                // field.selectedIndex = 0; 
            }
        });
    }, TRANSITION_DURATION);
}


selectRequiereEspecialidad.addEventListener('change', (event) => {
    const value = event.target.value;

    if (value == 1) { // Seleccionó "SI" (Requiere Especialidad)
        // MOSTRAR: Especialidad
        showContainer(especialidadContainer);

        // OCULTAR: Categoría/Práctica/Sistema y resetear sus valores
        hideContainer(noEspecialidadContainer, [categoria, tipo_practica, sistema_corporal]);
        
    } else if (value == 0) { // Seleccionó "NO" (NO Requiere Especialidad)
        // MOSTRAR: Categoría/Práctica/Sistema
        showContainer(noEspecialidadContainer);

        // OCULTAR: Especialidad y resetear su valor
        hideContainer(especialidadContainer, [especialidad]);
        
    } else { // Si vuelve a seleccionar "- Seleccione -" o similar
        // OCULTAR AMBOS
        hideContainer(especialidadContainer, [especialidad]);
        hideContainer(noEspecialidadContainer, [categoria, tipo_practica, sistema_corporal]);
    }
});

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
                condicion: selectRequiereEspecialidad.value === '' || selectRequiereEspecialidad.value === null,
                mensaje: 'Debe seleccionar si la subespecialidad requiere especialidad o no. Por favor, complete este campo.',
                elemento: selectRequiereEspecialidad
            },
            {
                condicion: selectRequiereEspecialidad.value == 1 && validar_campo_vacio('especialidad'),
                mensaje: 'Cuando se selecciona "Sí requiere especialidad", debe especificar la especialidad correspondiente. Por favor, seleccione una especialidad.',
                elemento: especialidad || selectRequiereEspecialidad // Cambié a especialidad si existe ese elemento
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
        
        return true; // Todas las validaciones pasaron
    }
    
    // Uso en tu evento
    if (validarFormulario()) {
        evnt.target.submit();
    }
});
