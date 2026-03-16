const iq_navbar_header = document.getElementById("iq-navbar-header");
iq_navbar_header.style.height = "0px";

// Función para actualizar estadísticas
function actualizarEstadisticas() {
    document.getElementById("totalMedicos").textContent =
        dashboardData.totalMedicos;
    document.getElementById("totalEspecialidades").textContent =
        dashboardData.totalEspecialidades;
    document.getElementById("totalSubespecialidades").textContent =
        dashboardData.totalSubespecialidades;

    // Actualizar estados específicos
    document.getElementById("medicosActivos").textContent =
        dashboardData.medicosPorEstado.activo;
    document.getElementById("medicosDesincorporados").textContent =
        dashboardData.medicosPorEstado.desincorporado;
    document.getElementById("medicosJubilados").textContent =
        dashboardData.medicosPorEstado.jubilado;
    document.getElementById("medicosFallecidos").textContent =
        dashboardData.medicosPorEstado.fallecido;
    document.getElementById("medicosTraslado").textContent =
        dashboardData.medicosPorEstado.traslado;
}

// Función para cargar actividades recientes
function cargarActividadesRecientes() {
    const recentActivityContainer = document.getElementById("recentActivity");
    recentActivityContainer.innerHTML = ""; // Limpiar contenido existente

    dashboardData.actividadesRecientes.forEach((actividad) => {
        const activityItem = document.createElement("div");
        activityItem.className = "activity-item";
        activityItem.innerHTML = `
                <p class="mb-1">${actividad.accion}</p>
                <small class="activity-time">${actividad.tiempo}</small>
            `;
        recentActivityContainer.appendChild(activityItem);
    });
}

// Función para inicializar gráficas
function inicializarGraficas() {
    // Gráfica de médicos por estado (geográfico)
    const medicosPorEstadoCtx = document.getElementById(
        "medicosPorEstadoChart",
    );
    if (medicosPorEstadoCtx) {
        new Chart(medicosPorEstadoCtx.getContext("2d"), {
            type: "bar",
            data: {
                labels: dashboardData.medicosPorEstadoGrafico.map(
                    (item) => item.estado,
                ),
                datasets: [
                    {
                        label: "Cantidad de Médicos",
                        data: dashboardData.medicosPorEstadoGrafico.map(
                            (item) => item.cantidad,
                        ),
                        backgroundColor: [
                            "rgba(52, 152, 219, 0.7)",
                            "rgba(155, 89, 182, 0.7)",
                            "rgba(46, 204, 113, 0.7)",
                            "rgba(241, 196, 15, 0.7)",
                            "rgba(231, 76, 60, 0.7)",
                        ],
                        borderColor: [
                            "rgb(52, 152, 219)",
                            "rgb(155, 89, 182)",
                            "rgb(46, 204, 113)",
                            "rgb(241, 196, 15)",
                            "rgb(231, 76, 60)",
                        ],
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10,
                        },
                    },
                },
            },
        });
    }

    // Gráfica de médicos por especialidad
    const medicosPorEspecialidadCtx = document.getElementById(
        "medicosPorEspecialidadChart",
    );
    if (medicosPorEspecialidadCtx) {
        new Chart(medicosPorEspecialidadCtx.getContext("2d"), {
            type: "doughnut",
            data: {
                labels: dashboardData.medicosPorEspecialidad.map(
                    (item) => item.especialidad,
                ),
                datasets: [
                    {
                        data: dashboardData.medicosPorEspecialidad.map(
                            (item) => item.cantidad,
                        ),
                        backgroundColor: [
                            "rgba(52, 152, 219, 0.7)",
                            "rgba(46, 204, 113, 0.7)",
                            "rgba(241, 196, 15, 0.7)",
                            "rgba(155, 89, 182, 0.7)",
                            "rgba(231, 76, 60, 0.7)",
                        ],
                        borderColor: [
                            "rgb(52, 152, 219)",
                            "rgb(46, 204, 113)",
                            "rgb(241, 196, 15)",
                            "rgb(155, 89, 182)",
                            "rgb(231, 76, 60)",
                        ],
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: "bottom",
                    },
                },
            },
        });
    }
}

// Función para simular actualización en tiempo real
function iniciarActualizacionTiempoReal() {
    setInterval(async () => {
        try {
            // Realizar solicitud PUT para obtener datos actualizados
            const response = await fetch("/SIMA/dataHomeActualziada", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.status}`);
            }

            const datosActualizados = await response.json();

            // Actualizar los datos del dashboard con la respuesta
            dashboardData.totalMedicos = datosActualizados.totalMedicos;
            dashboardData.totalEspecialidades = datosActualizados.totalEspecialidades;
            dashboardData.totalSubespecialidades = datosActualizados.totalSubespecialidades;
            
            // Actualizar estados de médicos
            dashboardData.medicosPorEstado.activo = datosActualizados.medicosPorEstado.activo;
            dashboardData.medicosPorEstado.desincorporado = datosActualizados.medicosPorEstado.desincorporado;
            dashboardData.medicosPorEstado.jubilado = datosActualizados.medicosPorEstado.jubilado;
            dashboardData.medicosPorEstado.fallecido = datosActualizados.medicosPorEstado.fallecido;
            dashboardData.medicosPorEstado.traslado = datosActualizados.medicosPorEstado.traslado;
            
            // Actualizar datos para gráficas
            dashboardData.medicosPorEstadoGrafico = datosActualizados.medicosPorEstadoGrafico;
            dashboardData.medicosPorEspecialidad = datosActualizados.medicosPorEspecialidad;
            
            // Actualizar actividades recientes
            dashboardData.actividadesRecientes = datosActualizados.actividadesRecientes;

            // Actualizar estadísticas en la interfaz
            actualizarEstadisticas();
            
            // Recargar actividades recientes
            cargarActividadesRecientes();
            
            // Recrear las gráficas con los nuevos datos
            const charts = Chart.instances;
            Object.keys(charts).forEach((key) => {
                charts[key].destroy();
            });
            
            inicializarGraficas();
            
            // Re-animar los contadores
            animarTodosLosContadores();

        } catch (error) {
            console.error("Error al obtener datos actualizados:", error);
            // Opcional: mostrar mensaje de error al usuario
            // o intentar nuevamente en el próximo intervalo
        }
    }, 30000); // Actualizar cada 30 segundos
}


// Inicializar el dashboard cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
    // Actualizar estadísticas iniciales
    actualizarEstadisticas();

    // Cargar actividades recientes
    cargarActividadesRecientes();

    // Inicializar gráficas
    inicializarGraficas();

    // Iniciar actualización en tiempo real
    iniciarActualizacionTiempoReal();

    // Añadir funcionalidad de actualización manual a las tarjetas
    const cards = document.querySelectorAll(".stat-card");
    cards.forEach((card) => {
        card.addEventListener("click", function () {
            this.style.transform = "scale(0.98)";
            setTimeout(() => {
                this.style.transform = "";
            }, 150);
        });
    });

    // Efecto hover para las tarjetas
    document.querySelectorAll(".stat-card").forEach((card) => {
        card.addEventListener("mouseenter", function () {
            this.style.boxShadow = "0 10px 20px rgba(0,0,0,0.1)";
        });

        card.addEventListener("mouseleave", function () {
            this.style.boxShadow = "0 2px 10px rgba(0,0,0,0.05)";
        });
    });

    // Animación para los contadores
    animarTodosLosContadores();
});

// Función para manejar el redimensionamiento de la ventana
window.addEventListener("resize", function () {
    // Pequeño delay para asegurar que el DOM se haya reajustado
    setTimeout(() => {
        // Destruir gráficas existentes y recrearlas
        const charts = Chart.instances;
        Object.keys(charts).forEach((key) => {
            charts[key].destroy();
        });

        inicializarGraficas();
    }, 100);
});

// Función para animar los contadores
function animarContadores(elementoId, valorFinal, duracion = 1000) {
    const elemento = document.getElementById(elementoId);
    if (!elemento) return;

    let valorInicial = 0;
    const incremento = valorFinal / (duracion / 16); // 60fps

    const animar = () => {
        valorInicial += incremento;
        if (valorInicial >= valorFinal) {
            elemento.textContent = Math.round(valorFinal);
            return;
        }

        elemento.textContent = Math.round(valorInicial);
        requestAnimationFrame(animar);
    };

    animar();
}

// Animación de todos los contadores
function animarTodosLosContadores() {
    // Estadísticas principales
    animarContadores("totalMedicos", dashboardData.totalMedicos, 1500);
    animarContadores(
        "totalEspecialidades",
        dashboardData.totalEspecialidades,
        1500,
    );
    animarContadores(
        "totalSubespecialidades",
        dashboardData.totalSubespecialidades,
        1500,
    );

    // Estados de médicos
    animarContadores(
        "medicosActivos",
        dashboardData.medicosPorEstado.activo,
        1200,
    );
    animarContadores(
        "medicosDesincorporados",
        dashboardData.medicosPorEstado.desincorporado,
        1200,
    );
    animarContadores(
        "medicosJubilados",
        dashboardData.medicosPorEstado.jubilado,
        1200,
    );
    animarContadores(
        "medicosFallecidos",
        dashboardData.medicosPorEstado.fallecido,
        1200,
    );
    animarContadores(
        "medicosTraslado",
        dashboardData.medicosPorEstado.traslado,
        1200,
    );
}
