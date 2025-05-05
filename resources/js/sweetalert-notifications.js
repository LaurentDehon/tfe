// Gestion centralisée des notifications SweetAlert2
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des notifications standard
    window.addEventListener('notify', event => {
        // Récupération des paramètres de l'événement avec valeurs par défaut selon la doc officielle
        const type = event.detail.type || 'success';
        const title = event.detail.title || '';
        
        // Personnalisation des styles en fonction du type de notification
        let iconColor, bgColor, textColor, borderColor;
        
        switch(type) {
            case 'success':
                iconColor = '#10b981';
                bgColor = 'linear-gradient(to right, #f0fdf4, #dcfce7)';
                textColor = '#166534';
                borderColor = '#86efac';
                break;
            case 'error':
                iconColor = '#ef4444';
                bgColor = 'linear-gradient(to right, #fef2f2, #fee2e2)';
                textColor = '#b91c1c';
                borderColor = '#fca5a5';
                break;
            case 'warning':
                iconColor = '#f97316';
                bgColor = 'linear-gradient(to right, #fff7ed, #ffedd5)';
                textColor = '#c2410c';
                borderColor = '#fdba74';
                break;
            case 'info':
                iconColor = '#3b82f6';
                bgColor = 'linear-gradient(to right, #eff6ff, #dbeafe)';
                textColor = '#1e40af';
                borderColor = '#93c5fd';
                break;
            default:
                iconColor = '#10b981';
                bgColor = 'linear-gradient(to right, #f0fdf4, #dcfce7)';
                textColor = '#166534';
                borderColor = '#86efac';
        }
        
        // Configuration de base pour la notification SweetAlert2
        const Toast = Swal.mixin({
            toast: event.detail.toast !== undefined ? event.detail.toast : true,
            position: event.detail.position || 'top-end',
            showConfirmButton: event.detail.showConfirmButton !== undefined ? event.detail.showConfirmButton : false,
            timer: event.detail.timer || 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Lancement de la notification avec titre et message et style personnalisé
        Toast.fire({
            icon: type,
            title: `<span style="color: ${textColor};">${title}</span>`,
            text: event.detail.message,
            padding: '1rem',
            width: 'auto',
            iconColor: iconColor,
            background: bgColor,
            customClass: {
                popup: `rounded-lg shadow-md border border-${type}`,
                timerProgressBar: `bg-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'orange' : 'blue'}-500`
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        });
    });
    
    // Fonction pour les confirmations de suppression
    window.confirmDelete = function(id, componentName = null) {
        Swal.fire({
            title: '<span class="text-gray-800 font-semibold">Êtes-vous sûr de vouloir supprimer cet élément?</span>',
            html: '<p class="text-gray-600 mb-2">Cette action est irréversible et toutes les données associées seront également supprimées.</p>',
            icon: 'warning',
            iconColor: '#f97316',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Supprimer',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Annuler',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            buttonsStyling: true,
            customClass: {
                popup: 'rounded-lg shadow-md border border-gray-100',
                title: 'text-lg',
                htmlContainer: 'text-left',
                confirmButton: 'rounded-lg px-4 py-2 font-medium transition-all ease-in-out duration-150 hover:shadow-md',
                cancelButton: 'rounded-lg px-4 py-2 font-medium transition-all ease-in-out duration-150 hover:shadow-md',
                actions: 'gap-2'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            },
            backdrop: `rgba(0,0,0,0.4)`,
            allowOutsideClick: false,
            reverseButtons: true,
            focusCancel: true,
            padding: '1.5rem',
            width: 'auto',
            background: 'linear-gradient(to bottom, #ffffff, #f9fafb)',
            position: 'center'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ajouter une animation de chargement pendant la suppression
                Swal.fire({
                    title: 'Suppression en cours...',
                    html: 'Veuillez patienter pendant la suppression des données.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    backdrop: `rgba(0,0,0,0.4)`,
                    padding: '1.5rem',
                    background: 'linear-gradient(to bottom, #ffffff, #f9fafb)',
                    customClass: {
                        popup: 'rounded-lg shadow-md border border-gray-100'
                    }
                });
                
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