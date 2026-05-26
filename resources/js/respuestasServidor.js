class respuestasServidor {


    async consultaServidor(action, body) {

        try {
            // 2. Define el cuerpo completo como el ARRAY que PHP espera: [acción, datos]
            const bodyArray = [
                action,
                body
            ];
            const response = await fetch('controllers/response/responses.php', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(bodyArray)
            });
            const data = await response.json();

            return (data);
        } catch (error) {

            console.error('Error:', error);
        }

    }
    fechaFormato(listaId) {
        listaId.forEach(id => {
            flatpickr("#" + id, {
                dateFormat: "d/m/Y",
            });
        });

    }

}


const respuesta_servidor = new respuestasServidor();