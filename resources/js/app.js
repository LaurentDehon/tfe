import './bootstrap';
import Swal from 'sweetalert2';

// Rendre Swal disponible globalement
window.Swal = Swal;

// Importer le fichier des notifications centralisées
import './sweetalert-notifications';

// Fonction de conversion des dates en heure locale
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour convertir les timestamps en format de date locale
    function convertTimestampsToLocalTime() {
        const dateElements = document.querySelectorAll('.local-datetime');
        dateElements.forEach(element => {
            const timestamp = parseInt(element.getAttribute('data-timestamp'));
            if (!isNaN(timestamp)) {
                const date = new Date(timestamp * 1000); // Conversion en millisecondes
                const options = { 
                    day: '2-digit', 
                    month: '2-digit', 
                    year: 'numeric', 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: false
                };
                element.textContent = date.toLocaleDateString('fr-FR', options);
            }
        });
    }

    // Exécuter la conversion au chargement de la page
    convertTimestampsToLocalTime();
    
    // Exécuter la conversion après chaque mise à jour de Livewire
    document.addEventListener('livewire:update', convertTimestampsToLocalTime);
});
