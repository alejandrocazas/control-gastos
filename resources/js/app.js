import './bootstrap';

import Swal from 'sweetalert2';
window.Swal = Swal;

// Mostrar alertas de éxito o error
window.showAlert = function (type, message) {
    Swal.fire({
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 1500
    });
};

// Mostrar confirmación antes de eliminar
window.confirmDelete = function (formId) {
    const form = document.getElementById(formId);
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Envía el formulario normalmente
            form.submit();
            
            // Opcional: Mostrar mensaje de carga
            Swal.fire({
                title: 'Eliminando',
                text: 'Por favor espere...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    });
};

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
// Validación de formularios
import Chart  from 'chart.js/auto';
window.Chart=Chart;


