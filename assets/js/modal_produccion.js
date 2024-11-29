document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal');
    const btnAddClient = document.getElementById('btn-add-client');
    const closeModal = document.querySelector('.close');
    const guardarCliente = document.getElementById('guardar-cliente');

    // Abrir el modal
    btnAddClient.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    // Cerrar el modal
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Cerrar el modal al hacer clic fuera del contenido
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Guardar cliente (lógica simulada)
    guardarCliente.addEventListener('click', () => {
        alert('Cliente guardado con éxito');
        modal.style.display = 'none';
    });
});
