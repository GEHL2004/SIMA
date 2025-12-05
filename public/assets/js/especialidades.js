const form = document.getElementById("form");
const nombre = document.getElementById("nombre");
const codigo = document.getElementById("codigo");
const categoria = document.getElementById("categoria");
const tipo_practica = document.getElementById("tipo_practica");
const sistema_corporal = document.getElementById("sistema_corporal");
const descripcion = document.getElementById("descripcion");
const contador1 = document.getElementById("contador1");
const contador2 = document.getElementById("contador2");

// Agregamos el evento 'input' (se dispara al escribir, borrar o pegar)

descripcion.addEventListener("input", function () {
    contador1.textContent = this.value.length;
});

codigo.addEventListener("input", function () {
    contador2.textContent = this.value.length;
});

// Validación del formulario 

form.addEventListener('submit', (evnt) => {
    evnt.preventDefault();
    if(validar_campo_vacio('nombre')){
        warning('Campo vacío', 'El campo de nombre de la especialidad se encuentra vacío, rellene lo para continuar.');
        nombre.focus();
        return ;
    } else if(validar_campo_vacio('codigo')){
        warning('Campo vacío', 'El campo de código de la especialidad se encuentra vacío, rellene lo para continuar.');
        codigo.focus();
        return ;
    } else if(validar_campo_vacio('categoria')){
        warning('Campo vacío', 'El campo de Categorpía de la especialidad se encuentra vacío, rellene lo para continuar.');
        categoria.focus();
        return ;
    } else if(validar_campo_vacio('tipo_practica')){
        warning('Campo vacío', 'El campo de Tipo Práctica de la especialidad se encuentra vacío, rellene lo para continuar.');
        tipo_practica.focus();
        return ;
    } else if(validar_campo_vacio('sistema_corporal')){
        warning('Campo vacío', 'El campo de Sistema Corporal de la especialidad se encuentra vacío, rellene lo para continuar.');
        sistema_corporal.focus();
        return ;
    } else if(validar_campo_vacio('descripcion')){
        warning('Campo vacío', 'El campo de Descripción de la especialidad se encuentra vacío, rellene lo para continuar.');
        descripcion.focus();
        return ;
    } else if (codigo.value <= 0 || codigo.value <= 21){
        warning('Campo vacío', 'El campo de Código contiene un numero de caracteres menor o mayor a los permitidos, corrija lo para continuar.');
        codigo.focus();
        return ;
    } else if (descripcion.value <= 0 || descripcion.value <= 1001){
        warning('Campo vacío', 'El campo de Descripción contiene un numero de caracteres menor o mayor a los permitidos, corrija lo para continuar.');
        descripcion.focus();
        return ;
    } else {
        form.submit();
    }
});