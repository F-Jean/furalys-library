// dateTimeFields.js
// for video/image field 'releasedThe'

import flatpickr from 'flatpickr';

document.addEventListener('DOMContentLoaded', function () {
    flatpickr('.datetimepicker', {
        // Flatpickr configuration options
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });
});