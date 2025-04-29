// assets/js/preview_media.js

// Export function to reuse elsewhere (e.g. after dynamic addition of fields)
export function setupImagePreview() {
    // Selects all input fields of type ‘file’ which have the data-preview-target attribute
    const inputs = document.querySelectorAll('input[type="file"][data-preview-target]');

    // For each field found
    inputs.forEach(input => {
        // Avoids duplication : Prevents the same listener being attached more than once 
        // (if setupImagePreview is restarted)
        if (input.dataset.previewBound === 'true') return;

        // When the user selects a file
        input.addEventListener('change', function () {
            // Retrieves the ID of the <img> tag which is to display the preview
            const previewId = this.dataset.previewTarget;
            const previewImg = document.getElementById(previewId);
            const file = this.files[0]; // Takes the 1st file selected

            // Checks whether the target <img> tag exists in the DOM
            if (!previewImg) {
                console.warn(`[preview_media.js] No image element found with ID "${previewId}"`);
                return;
            }

            // If an image file is selected
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                // When the file is read successfully
                reader.onload = e => {
                    // Allocates the content of the image in base64 to the src of <img>.
                    previewImg.src = e.target.result;
                    // Displays the image (removing the Bootstrap `d-none` class)
                    previewImg.classList.remove('d-none');
                };

                // Starts reading the file in Data URL format (base64)
                reader.readAsDataURL(file);
            } else {
                // If no file or invalid file, hide the image
                previewImg.src = '#';
                previewImg.classList.add('d-none');
            }
        });

        // Marks this field as already processed to avoid duplication
        input.dataset.previewBound = 'true'; // marque comme déjà lié
    });
}

// Calls the function automatically on initial page load
document.addEventListener('DOMContentLoaded', () => {
    setupImagePreview();
});
