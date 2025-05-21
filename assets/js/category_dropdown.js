// assets/js/category_dropdown.js

document.addEventListener('DOMContentLoaded', function () {
    var categoriesCheckboxes = document.querySelectorAll('#post_categories input[type="checkbox"]');
    var selectedCategoriesDiv = document.getElementById('selected_categories');

    categoriesCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var categoryId = checkbox.value;
            var categoryLabel = checkbox.nextElementSibling.textContent;

            if (checkbox.checked) {
                // Create a span element to display the label of the selected category
                var categorySpan = document.createElement('span');
                categorySpan.textContent = categoryLabel;

                // Create a "delete" button for each selected category
                var deleteButton = document.createElement('button');
                deleteButton.innerHTML = '<i class="fas fa-times"></i>'; // Using the Font Awesome cross icon
                deleteButton.addEventListener('click', function () {
                    // Remove category from div of selected categories
                    selectedCategoriesDiv.removeChild(categorySpan);
                    // Uncheck the corresponding box in the post_categories div
                    checkbox.checked = false;
                });

                // Add "delete" button to span element
                categorySpan.appendChild(deleteButton);

                // Add category to div of selected categories
                selectedCategoriesDiv.appendChild(categorySpan);
            } else {
                // Remove category from div of selected categories if unchecked
                var selectedCategorySpan = selectedCategoriesDiv.querySelector('span[data-category-id="' + categoryId + '"]');
                if (selectedCategorySpan) {
                    selectedCategoriesDiv.removeChild(selectedCategorySpan);
                }
            }
        });
    });
});
