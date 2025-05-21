// assets/js/load_more.js

document.addEventListener('DOMContentLoaded', function() {
    let page = 1;

    document.querySelector('.load').addEventListener('click', function(e) {
        e.preventDefault();
        page++;

        fetch('/posts/load_more/' + page, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
        })
        .then(response => response.text())
        .then(code_html => {

            const postList = document.querySelector('.d-flex.flex-row.flex-wrap');
            
            // Uses trim() to remove leading and trailing whitespace otherwise
            // a value of 9 is return and the btn doesn't disapear
            if (code_html.trim().length > 0) {
                postList.insertAdjacentHTML('beforeend', code_html);
            } else {
                document.querySelector('.load').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
