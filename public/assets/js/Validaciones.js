function validar_campo_vacio(id_campo){
    let validacion = document.getElementById(id_campo);
    if(validacion.value == ''){
        return true;
    }
    return false;
}

function validar_campo_longitud_cedula(id_campo){
    let validacion = document.getElementById(id_campo);
    if(validacion.value.length <= 0){
        return true;
    }
    return false;
}

function validar_campo_numerico(id_campo){
    let validacion = document.getElementById(id_campo);
    if(isNaN(parseInt(validacion.value))){
        return true;
    }
    return false;
}

function validar_numero_entero(id_campo){
    let validacion = document.getElementById(id_campo);
    if(validacion.value.includes('.') || validacion.value.includes(',')){
        return true;
    }
    return false;
}

function validar_contraseña(password) {
    if (password.length < 6 || password.length > 16) {
      return true;
    } else if (!/[A-Z]/.test(password)) {
      return true;
    } else if (!/[a-z]/.test(password)) {
      return true;
    } else if (!/[0-9]/.test(password)) {
      return true;
    } else if (!/[^a-zA-Z0-9]/.test(password)) {
      return true;
    }
    return false;
  }