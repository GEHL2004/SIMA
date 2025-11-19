const form = document.getElementById("form");

form.addEventListener('submit', function(event){
    event.preventDefault();
    if(validar_campo_vacio('user')){
        warning('Campo vacío', 'El campo de usuario se encuentra vacío, rellene lo para continuar por favor.');
        this.user.focus();
        return ;
    } else if(validar_campo_vacio('password')){
        warning('Campo vacío', 'El campo de contraseña se encuentra vacío, rellene lo para continuar por favor.');
        this.password.focus();
        return ;
    } else{
        this.submit();
    }
})