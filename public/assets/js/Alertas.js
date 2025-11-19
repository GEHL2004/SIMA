function success(titulo) {
    Swal.fire({
        position: "center",
        icon: "success",
        title: titulo,
        showConfirmButton: false,
        timer: 2000
    });
}

function warning(titulo, texto) {
    Swal.fire({
        icon: "warning",
        title: titulo,
        text: texto,
        confirmButtonColor: '#F8BB86'
    });
}

function error(titulo, texto) {
    Swal.fire({
        icon: "error",
        title: titulo,
        text: texto,
        confirmButtonColor: '#F27474'
    });
}

function info(titulo, texto) {
    Swal.fire({
        icon: "info",
        title: titulo,
        text: texto,
        confirmButtonColor: '#3FC3EE'
    });
}