// assets/js/preview_media.js

// ========== LOCAL IMAGE MANAGEMENT ==========

/**
 * Function exported so that it can be called from other JS files (e.g. after dynamic addition of fields).
 * Enables local image preview (file downloaded by the user).
 */
export function setupImagePreview() {
    // Selects all <input type="file"> fields with a data-preview-target attribute (target image identifier).
    const inputs = document.querySelectorAll('input[type="file"][data-preview-target]');

    // For each field found
    inputs.forEach(input => {
        // Avoids duplication : Prevents the same listener being attached more than once 
        // (if setupImagePreview is restarted)
        if (input.dataset.previewBound === 'true') return;

        // When the user selects a file
        input.addEventListener('change', function () {
            // Retrieves the ID of the <img> tag which is to display the preview
            const previewId = this.dataset.previewTarget; // ID of the <img> tag to be updated
            const previewImg = document.getElementById(previewId);
            const file = this.files[0]; // Takes the 1st file selected

            // If the <img> tag is not found, we alert the console and exit
            if (!previewImg) {
                console.warn(`[preview_media.js] No image element found with ID "${previewId}"`);
                return;
            }

            // If an image file is selected
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                // When the file is read successfully
                reader.onload = e => {
                    previewImg.src = e.target.result; // Allocates the content of the image in base64 to the src of <img>.
                    previewImg.classList.remove('d-none'); // Displays the image (removing the Bootstrap `d-none` class)
                };
                reader.readAsDataURL(file); // Starts reading the file in Data URL format (base64)
            } else {
                // If no file or invalid file, hide the <img> element
                previewImg.src = '#';
                previewImg.classList.add('d-none');
            }
        });

        input.dataset.previewBound = 'true'; // Marks this field as already processed to avoid duplication
    });
}

// Calls the function automatically on initial page load
document.addEventListener('DOMContentLoaded', () => {
    setupImagePreview();
});

// ========== LOCAL VIDEO MANAGEMENT ==========

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
                    previewVideo.load(); // Reload the video file
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

document.addEventListener('DOMContentLoaded', () => {
    setupVideoPreview();
});

// ========== YOUTUBE LINK MANAGEMENT ==========

export function setupYoutubeVideoPreview() {
    const youtubeInputs = document.querySelectorAll('input[data-youtube-preview-target]');

    youtubeInputs.forEach(input => {
        if (input.dataset.previewBound === 'true') return;

        input.addEventListener('input', function () {
            const previewId = this.dataset.youtubePreviewTarget;
            const previewIframe = document.getElementById(previewId);
            const url = this.value.trim(); // Entered URL

            if (!previewIframe) {
                console.warn(`[preview_media.js] No iframe element found with ID "${previewId}"`);
                return;
            }

            const videoId = extractYoutubeVideoId(url); // Extract the YouTube video ID

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

// Utility function for extracting the ID of a YouTube video from various forms of URL
function extractYoutubeVideoId(url) {
    try {
        const regExp = /(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const match = url.match(regExp);
        return match ? match[1] : null;
    } catch (e) {
        return null;
    }
}

// ========== EXCLUSIVE LOGIC: VIDEO FILE OR YOUTUBE LINK ==========

// Prevents a user from entering both a video file and a YouTube link in the same block
export function setupExclusiveVideoInputs() {
    const videoGroups = document.querySelectorAll('[data-video-toggle-group]');

    videoGroups.forEach(group => {
        const fileInput = group.querySelector('input[type="file"]');
        const urlInput = group.querySelector('input[type="text"]');

        const toggleFields = () => {
            const hasFile = fileInput?.files?.length > 0;
            const hasUrl = urlInput?.value?.trim().length > 0;

            // If a file is selected, hide the URL field
            if (hasFile) {
                urlInput.closest('.text-light').classList.add('d-none');
            } else {
                urlInput.closest('.text-light').classList.remove('d-none');
            }

            // If a URL is entered, the file field is hidden
            if (hasUrl) {
                fileInput.closest('.col-md-6').classList.add('d-none');
            } else {
                fileInput.closest('.col-md-6').classList.remove('d-none');
            }
        };

        // Add event listeners to each field
        fileInput?.addEventListener('change', toggleFields);
        urlInput?.addEventListener('input', toggleFields);

        toggleFields(); // Applies the initial state on loading
    });
}

document.addEventListener('DOMContentLoaded', () => {
    setupExclusiveVideoInputs();
});
