// assets/js/artist_dropdown.js

document.addEventListener('DOMContentLoaded', function () {
    var artistsCheckboxes = document.querySelectorAll('#post_artists input[type="checkbox"]');
    var selectedartistsDiv = document.getElementById('selected_artists');

    artistsCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var artistId = checkbox.value;
            var artistLabel = checkbox.nextElementSibling.textContent;

            if (checkbox.checked) {
                // Create a span element to display the label of the selected artist
                var artistSpan = document.createElement('span');
                artistSpan.textContent = artistLabel;

                // Create a "delete" button for each selected artist
                var deleteButton = document.createElement('button');
                deleteButton.innerHTML = '<i class="fas fa-times"></i>'; // Using the Font Awesome cross icon
                deleteButton.addEventListener('click', function () {
                    // Remove artist from div of selected artists
                    selectedartistsDiv.removeChild(artistSpan);
                    // Uncheck the corresponding box in the post_artists div
                    checkbox.checked = false;
                });

                // Add "delete" button to span element
                artistSpan.appendChild(deleteButton);

                // Add artist to div of selected artists
                selectedartistsDiv.appendChild(artistSpan);
            } else {
                // Remove artist from div of selected artists if unchecked
                var selectedartistSpan = selectedartistsDiv.querySelector('span[data-artist-id="' + artistId + '"]');
                if (selectedartistSpan) {
                    selectedartistsDiv.removeChild(selectedartistSpan);
                }
            }
        });
    });
});
