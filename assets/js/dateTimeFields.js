// dateTimeFields.js
// for video/image field 'releasedThe'

import flatpickr from 'flatpickr';

document.addEventListener('DOMContentLoaded', function () {
    flatpickr('.datetimepicker', {
        // Flatpickr configuration options
        enableTime: false,
        dateFormat: "Y-m-d",
    });
});