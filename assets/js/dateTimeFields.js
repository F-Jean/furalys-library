// dateTimeFields.js
// for video/image field 'releasedThe'

import flatpickr from 'flatpickr';

document.addEventListener('DOMContentLoaded', function () {
    flatpickr('.datetimepicker', {
        // Options de configuration de Flatpickr
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        // Vous pouvez ajouter d'autres options selon vos besoins
    });
});