// assets/js/preview_media.js

// FOR IMAGES
// Export function to reuse elsewhere to activate image's preview (e.g. after dynamic addition of fields)
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

// FOR LOCAL VIDEOS
export function setupVideoPreview() {
    const inputs = document.querySelectorAll('input[type="file"][data-video-preview-target]');

    inputs.forEach(input => {
        if (input.dataset.previewBound === 'true') return;

        input.addEventListener('change', function () {
            const previewId = this.dataset.videoPreviewTarget;
            const previewVideo = document.getElementById(previewId);
            const file = this.files[0];

            if (!previewVideo) {
                console.warn(`[preview_media.js] No video element found with ID "${previewId}"`);
                return;
            }

            if (file && file.type.startsWith('video/')) {
                const reader = new FileReader();

                reader.onload = e => {
                    previewVideo.src = e.target.result;
                    previewVideo.classList.remove('d-none');
                    previewVideo.load(); // recharge la vidéo
                };

                reader.readAsDataURL(file);
            } else {
                previewVideo.src = '';
                previewVideo.classList.add('d-none');
            }
        });

        input.dataset.previewBound = 'true';
    });
}

// Also launch the video preview at the end
document.addEventListener('DOMContentLoaded', () => {
    setupVideoPreview();
});

// FOR YOUTUBE VIDEOS
export function setupYoutubeVideoPreview() {
    const youtubeInputs = document.querySelectorAll('input[data-youtube-preview-target]');

    youtubeInputs.forEach(input => {
        if (input.dataset.previewBound === 'true') return;

        input.addEventListener('input', function () {
            const previewId = this.dataset.youtubePreviewTarget;
            const previewIframe = document.getElementById(previewId);
            const url = this.value.trim();

            if (!previewIframe) {
                console.warn(`[preview_media.js] No iframe element found with ID "${previewId}"`);
                return;
            }

            const videoId = extractYoutubeVideoId(url);

            if (videoId) {
                previewIframe.src = `https://www.youtube.com/embed/${videoId}`;
                previewIframe.classList.remove('d-none');
            } else {
                previewIframe.src = '';
                previewIframe.classList.add('d-none');
            }
        });

        input.dataset.previewBound = 'true';
    });
}
// Utility function for extracting the ID of a YouTube video from a classic, embed, short or youtu.be URL
function extractYoutubeVideoId(url) {
    try {
        const regExp = /(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const match = url.match(regExp);
        return match ? match[1] : null;
    } catch (e) {
        return null;
    }
}