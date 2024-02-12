function confirmarBorrado(event, id) {
    event.preventDefault(); // Previne el envío del formulario inmediatamente
    console.log(id);
    Swal.fire({
        title: 'Confirmación',
        text: '¿Estás seguro de que deseas eliminar este Servicio?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(id).submit();
        }
    });
}