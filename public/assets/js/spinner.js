const spinner = document.getElementById("spinner");
const overlay = document.getElementById("overlay");

// Muestra el spinner y la superposición cuando se cambia de ventana
window.addEventListener("beforeunload", function () {
    spinner.classList.remove("d-none"); // Muestra el spinner
    overlay.style.display = "block"; // Muestra la superposición
});

// Oculta el spinner y la superposición una vez que la nueva página ha terminado de cargar
window.addEventListener("load", function () {
    spinner.classList.add("d-none"); // Oculta el spinner
    overlay.style.display = "none"; // Oculta la superposición
});