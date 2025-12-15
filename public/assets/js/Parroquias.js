// PRIMER METODO
function traerParroquias(select) {
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
            select_parroquia = document.getElementById("id_parroquia");
            select_parroquia.innerHTML = "";
            let option =
                '<option value="" selected disabled>- Seleccione -</option>';
            for (let i in dataD) {
                option += `<option value="${dataD[i].id_parroquia}">${dataD[i].nombre_parroquia}</option>`;
            }
            select_parroquia.innerHTML = option;
        });
}
