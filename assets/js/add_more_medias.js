// assets/js/add_more_medias.js

// assets/js/preview_media.js
import { 
    setupImagePreview, 
    setupVideoPreview,
    setupYoutubeVideoPreview,
    setupExclusiveVideoInputs
 } from './preview_media.js';

function previewFile(item) {
    const fileInput = item.querySelector('input[type=file]');
    const file = fileInput?.files[0];

    // Find out if there is an image or video to preview in the block
    const previewImg = item.querySelector('img');
    const previewVideo = item.querySelector('video');

    if (!file) return;

    const reader = new FileReader();

    reader.addEventListener("load", function () {
        // if image found
        if (file.type.startsWith('image/') && previewImg) {
            previewImg.src = reader.result;
            previewImg.classList.remove('d-none');
        }

        // if video found
        else if (file.type.startsWith('video/') && previewVideo) {
            previewVideo.src = reader.result;
            previewVideo.classList.remove('d-none');
            previewVideo.load(); // reload
        }
    }, false);

    reader.readAsDataURL(file);
}

/* ADD & DELETE BUTTONS */
const newItem = (e) => {
    // pick up the collections "#illustrations & #videos"
    const collectionHolder = document.querySelector(e.currentTarget.dataset.collection);
    // create a div class media in JS
    const item = document.createElement("div");
    item.classList.add("media");
    // replace the prototype /__name__/ by our index
    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g, 
            collectionHolder.dataset.index
        );
    // remove the whole div when clicking the remove btn
    item.querySelector(".btn-remove").addEventListener("click", () => item.remove());
    collectionHolder.appendChild(item);
    item.querySelector('input[type=file]').addEventListener("change", () => previewFile(item));

    // function of assets/js/preview_media.js for previewing media
    setupImagePreview();
    setupVideoPreview();
    setupYoutubeVideoPreview();
    setupExclusiveVideoInputs();

    // increase index
    collectionHolder.dataset.index++;
};

document
    .querySelectorAll('.btn-new')
    .forEach(btn => btn.addEventListener("click", newItem));

    document
    .querySelectorAll('.btn-remove')
    .forEach(btn => btn.addEventListener("click", (e) => e.currentTarget.closest('.media').remove()));