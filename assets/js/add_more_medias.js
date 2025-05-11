// assets/js/add_more_medias.js

import { 
    // Import utility functions for displaying previews of images, local videos and YouTube,
    setupImagePreview, 
    setupVideoPreview,
    setupYoutubeVideoPreview,
    // as well as forcing certain rules such as video field exclusivity.
    setupExclusiveVideoInputs
 } from './preview_media.js';

 // ========== PREVIEW FILE MANAGEMENT ==========
 /**
  * Allows to play a local file (image or video) selected in an input[type=file] and generate a preview directly on the page.
  * param {HTMLElement} item : Element containing the input field and the preview zone (image or video).
  */

/**
 * @param {HTMLElement} item
 */
function previewFile(item) {
    const fileInput = item.querySelector('input[type=file]'); // Retrieves the file field
    const file = fileInput?.files[0]; // Retrieves the first file selected

    // Find out if there is an image or video to preview in the block
    const previewImg = item.querySelector('img');
    const previewVideo = item.querySelector('video');

    if (!file) return; // If no file is selected, exit

    const reader = new FileReader(); // Used to read the file locally

    reader.addEventListener("load", function () {
        // if image found, update the src of the <img> and display it
        if (file.type.startsWith('image/') && previewImg) {
            previewImg.src = reader.result;
            previewImg.classList.remove('d-none');
        }

        // if video found, update the src of the <video> and display it
        else if (file.type.startsWith('video/') && previewVideo) {
            previewVideo.src = reader.result;
            previewVideo.classList.remove('d-none');
            previewVideo.load(); // reload the media
        }
    }, false);

    reader.readAsDataURL(file); // Starts reading the file (in the form of a data: URI)
}

// ========== ADD & DELETE BUTTONS MANAGEMENT ==========

/**
 * Function executed when an ‘Add’ button (.btn-new) is clicked.
 * It dynamically adds a new media element to the form (image or video),
 * with all the necessary fields and associated events (e.g. delete button).
 */
const newItem = (e) => {
    // Retrieves the target parent element (illustrations or videos) from the data-attribute of the clicked button
    const collectionHolder = document.querySelector(e.currentTarget.dataset.collection); // pick up the collections "#illustrations & #videos"
    
    // Dynamically create a new element <div class="media">
    const item = document.createElement("div");
    item.classList.add("media");
    
    // Injects the HTML of the prototype (defined in the Twig view with `data-prototype`)
    // replacing the `__name__` placeholder with the current index (replace the prototype /__name__/ by our index)
    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g, 
            collectionHolder.dataset.index
        );
        
    // Adds an event to delete the item by clicking on its ‘Delete’ button (remove the whole div )
    item.querySelector(".btn-remove").addEventListener("click", () => item.remove());

    // Adds the new element to the DOM
    collectionHolder.appendChild(item);

    // Activates image or video preview as soon as a file is selected
    item.querySelector('input[type=file]').addEventListener("change", () => previewFile(item));

    // Resets the behaviours required for dynamically added elements (assets/js/preview_media.js):
    setupImagePreview(); // - image preview
    setupVideoPreview(); // - video preview
    setupYoutubeVideoPreview(); // - YouTube URL conversion
    setupExclusiveVideoInputs(); // - exclusivity logic (video or file)
    setupExclusiveThumbnailCheckboxes(); // - exclusivity logic (unique thumbnail)

    // Increase index
    collectionHolder.dataset.index++;
};

// ========== THUMBNAILS CHECKBOXES MANAGEMENT ==========

/**
 * Manages the ‘exclusive thumbnail’ logic.
 * Only one [isThumbnail] checkbox can be checked at a time.
 * If the user ticks one box, the others are disabled.
 * If it is unchecked, the others are reactivated.
 */
function setupExclusiveThumbnailCheckboxes() {
    document.addEventListener('change', function (e) {
        const changedCheckbox = e.target;

        // Only applies to “isThumbnail” fields
        if (
            changedCheckbox.matches('input[type="checkbox"][name$="[isThumbnail]"]')
        ) {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name$="[isThumbnail]"]');

            if (changedCheckbox.checked) {
                // Disable all other checkboxes
                checkboxes.forEach(cb => {
                    if (cb !== changedCheckbox) {
                        cb.checked = false;
                        cb.disabled = true;
                    }
                });
            } else {
                // Unchecking the selected one reactivates all the others
                checkboxes.forEach(cb => cb.disabled = false);
            }
        }
    });
}


// Add a listener to all .btn-new buttons (add media)
document
    .querySelectorAll('.btn-new')
    .forEach(btn => btn.addEventListener("click", newItem));

    
// Adds a listener to all .btn-remove buttons already present on loading
document
.querySelectorAll('.btn-remove')
.forEach(btn => btn.addEventListener("click", (e) => e.currentTarget.closest('.media').remove()));

// Call the function when the page first loads (useful if certain fields are already present like in editing mode)
setupExclusiveThumbnailCheckboxes();
