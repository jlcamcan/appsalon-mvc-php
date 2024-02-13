let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}


document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
})

function iniciarApp() {
    mostrarSeccion();
    tabs(); // Cambia la sección cuando se presiona los tabs
    botonesPaginador(); //Agrega o quita los botones del paginador
    paginaAnterior();
    paginaSiguiente();
    consultarAPI(); //Consulta la API en el backend.
    idCliente(); //Añade a cita el id del cliente
    nombreCliente(); //Añade a cita el nombre del cliente
    seleccionarFecha(); //Añade la fecha al objeto cita.
    seleccionarHora(); //Añade la hora al objeto cita.
    mostrarResumen(); //Muestra el resumen de la cita
}


function mostrarSeccion() {
    //Ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }
    //Seleccionar la seccion con el paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    //Desactivar el tab que tenga la clase de actual
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }
    //Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');

}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton => {
        boton.addEventListener('click', function(e) {
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        });
    })
}

function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');
    switch (paso) {
        case 1:
            paginaAnterior.classList.add('ocultar');
            paginaSiguiente.classList.remove('ocultar');
            break;
        case 2:
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.remove('ocultar');
            break;
        case 3:
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.add('ocultar');
            mostrarResumen();
            break;
    }
    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {
        if (paso <= pasoInicial) return;
        paso--;
        botonesPaginador();
    });
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {
        if (paso >= pasoFinal) return;
        paso++;
        botonesPaginador();
    });
}

async function consultarAPI() {
    try {
        const url = `${location.origin}/api/servicios`;
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;
        //Mostrar el nombre servicio
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;
        //Mostrar el precio servicio
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `${precio} €`;
        //Creamos el contenedor
        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        };
        //Añadir hijos
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        //Mostrar en pantalla
        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio) {
    const { id } = servicio;
    const { servicios } = cita;
    //Identificar el elemento que se pulsa click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);
    //Comprobar si un servicio ya está seleccionado
    if (servicios.some(agregado => agregado.id === id)) {
        //Eliminar el servicio del array
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    } else {
        //Añade el servicio al array
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
}

function idCliente() {
    const id = document.querySelector('#id').value;
    cita.id = id;
}

function nombreCliente() {
    const nombre = document.querySelector('#nombre').value;
    cita.nombre = nombre;
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        const dia = new Date(e.target.value).getUTCDay();
        if ([6, 0].includes(dia)) {
            e.target.value = '';
            cita.fecha = '';
            mostrarAlerta('Sábados y Domingos no abrimos', 'error', '.formulario', true);
        } else {
            cita.fecha = e.target.value;
        }

    });
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if (hora < 10 || hora > 18) {
            e.target.value = '';
            cita.hora = '';
            mostrarAlerta('Hora no abierto al público', 'error', '.formulario', true);
        } else {
            cita.hora = e.target.value;
        }
    });
}



function mostrarAlerta(mensaje, tipo, elemento, desaparece_auto = true) {
    //Previene que se muestre más de 1 alerta
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    }

    //Codigo para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);
    if (desaparece_auto) {
        //Eliminar la alerta
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');
    //Limpiar el contenido del resumen
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }
    if (Object.values(cita).includes('') || cita.servicios.length === 0) {
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false);
        return;
    }
    //Formatear el DIV de RESUMEN
    const { nombre, fecha, hora, servicios } = cita;

    //Cabecera de los servicios
    const cabeceraServicios = document.createElement('H3');
    cabeceraServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(cabeceraServicios);
    //Montamos los servicios
    servicios.forEach(servicio => {
        //Destructing a los datos del servicio
        const { id, precio, nombre } = servicio;
        const contenedorServicios = document.createElement('DIV');
        contenedorServicios.classList.add('contenedor-servicios');
        //Añadimos el nombre del servicio
        const nombreServicio = document.createElement('P');
        nombreServicio.textContent = nombre;
        //Añadimos el precio del servicio
        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span>${precio} €`;
        //Añadimos los datos al contenedor
        contenedorServicios.appendChild(nombreServicio);
        contenedorServicios.appendChild(precioServicio);
        //Mostramos los servicios
        resumen.appendChild(contenedorServicios);
    });
    //Cabecera de los servicios
    const cabeceraCita = document.createElement('H3');
    cabeceraCita.textContent = 'Resumen de Cita';
    resumen.appendChild(cabeceraCita);
    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span>${nombre}`;
    const fechaCita = document.createElement('P');
    //Formatear la fecha a español
    const fechaObj = new Date(fecha + ' 00:00');
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechaObj.toLocaleDateString('es-ES', opciones);
    fechaCita.innerHTML = `<span>Fecha: </span>${fechaFormateada}`;
    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora: </span>${hora}`;


    //Boton para reserva de cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = "Reservar Cita";
    botonReservar.onclick = reservarCita;


    //Añadimos los párrafos al div
    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.append(botonReservar);
}

async function reservarCita() {
    const { id, nombre, fecha, hora, servicios } = cita;
    const idServicios = servicios.map(servicio => servicio.id);
    const datos = new FormData();
    datos.append('nombre', nombre);
    datos.append('usuarioId', id);
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicios);
    try {
        //Petición hacia la api
        const url = '/api/citas';

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        if (resultado.resultado) {
            //Registro insertado correctamente
            Swal.fire({
                title: "Cita Creada!",
                text: "¡Su cita ha sido creada correctamente!",
                icon: "success"
            }).then(() => {
                window.location.reload();
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "¡Hubo un error al guardar la cita!",
        }).then(() => {
            window.location.reload();
        });
    }
}