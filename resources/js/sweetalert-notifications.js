// Gestion centralisée des notifications SweetAlert2
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des notifications standard
    window.addEventListener('notify', event => {
        const type = event.detail.type || 'success';
        const title = event.detail.title || (type === 'success' ? 'Succès' : 'Information');
        
        Swal.fire({
            icon: type,
            title: title,
            text: event.detail.message,
            toast: event.detail.toast || false,
            position: event.detail.position || 'center',
            showConfirmButton: event.detail.showConfirmButton !== undefined ? event.detail.showConfirmButton : true,
            timer: event.detail.timer || null,
            timerProgressBar: event.detail.timer ? true : false,
        });
    });
    
    // Fonction pour les confirmations de suppression
    window.confirmDelete = function(id, componentName = null) {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si componentName est spécifié, utilisez-le pour cibler un composant spécifique
                if (componentName) {
                    Livewire.dispatch(componentName + ':delete', { id: id });
                } else {
                    // Comportement par défaut
                    Livewire.dispatch('delete', { id: id });
                }
            }
        });
        return false;
    }
});