document.addEventListener('DOMContentLoaded', function() {
    const bookContainer = document.getElementById('bookContainer');
    const books = Array.from(bookContainer.children);
    const searchInput = document.getElementById('search');
    let activeLetter = null;
    let currentSort = 'title'; // Initialize with default sort type 'title'

    // Function to handle sorting and dynamically displaying books with headers
    function sortAndDisplayBooks() {
        let lastChar = null;
        bookContainer.innerHTML = ''; // Clear the container before appending sorted books

        books.forEach(book => {
            let sortingKey;
            if (currentSort === 'author') {
                sortingKey = book.querySelector('.author').textContent.replace('By:', '').trim().split(' ')[0];
            } else if (currentSort === 'rating') {
                // Get the star rating from the data attribute or default to 0 if not set
                sortingKey = book.querySelector('.stars').dataset.rating || '0';
            } else {
                sortingKey = book.querySelector('h3').textContent.trim();
            }
            const headerLabel = currentSort === 'rating' ? (sortingKey === '0' ? '*' : sortingKey) : sortingKey[0].toUpperCase();

            if (headerLabel !== lastChar) {
                const header = document.createElement('div');
                header.textContent = headerLabel; // Directly using headerLabel without prefix for ratings
                header.classList.add('book-header');
                bookContainer.appendChild(header);
                lastChar = headerLabel;
            }

            bookContainer.appendChild(book);
        });
    }

    // Initial sort by book title on page load
    books.sort((a, b) => a.querySelector('h3').textContent.localeCompare(b.querySelector('h3').textContent));
    sortAndDisplayBooks(); // Display books with headers after initial sort

    // Event listener for changes in the search input
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim().toLowerCase();

        books.forEach(book => {
            const title = book.querySelector('h3').textContent.toLowerCase();
            if (title.includes(searchTerm)) {
                book.style.display = 'block';
            } else {
                book.style.display = 'none';
            }
        });
    });

    // Event listener for sorting options
    document.getElementById('sort').addEventListener('change', function() {
        const sortBy = this.value;
        currentSort = sortBy.includes('name') ? 'title' : sortBy.includes('author') ? 'author' : sortBy.includes('rating') ? 'rating' : currentSort;

        switch (sortBy) {
            case 'name_asc':
                books.sort((a, b) => a.querySelector('h3').textContent.localeCompare(b.querySelector('h3').textContent));
                break;
            case 'name_desc':
                books.sort((a, b) => b.querySelector('h3').textContent.localeCompare(a.querySelector('h3').textContent));
                break;
            case 'rating_asc':
                books.sort((a, b) => (parseInt(a.querySelector('.stars').dataset.rating) || 0) - (parseInt(b.querySelector('.stars').dataset.rating) || 0));
                break;
            case 'rating_desc':
                books.sort((a, b) => (parseInt(b.querySelector('.stars').dataset.rating) || 0) - (parseInt(a.querySelector('.stars').dataset.rating) || 0));
                break;
            case 'author':
                books.sort((a, b) => {
                    const authorA = a.querySelector('.author').textContent.replace('By:', '').trim().split(' ')[0];
                    const authorB = b.querySelector('.author').textContent.replace('By:', '').trim().split(' ')[0];
                    return authorA.localeCompare(authorB);
                });
                break;
            // Additional sorting cases can be added here
        }

        sortAndDisplayBooks(); // Re-sort and display with headers
    });

    document.querySelectorAll('.alphabet-filter a, .alphabet-filter-wishlist a').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const letter = this.dataset.letter.toLowerCase();
    
            if (activeLetter === letter) {
                // Deselect the current letter and show all books
                activeLetter = null;
                document.querySelectorAll('.alphabet-filter a, .alphabet-filter-wishlist a').forEach(a => a.classList.remove('active'));
                books.forEach(book => book.style.display = 'block');
            } else {
                // Select a new letter
                activeLetter = letter;
                document.querySelectorAll('.alphabet-filter a, .alphabet-filter-wishlist a').forEach(a => a.classList.remove('active'));
                this.classList.add('active');
    
                books.forEach(book => {
                    const title = book.querySelector('h3').textContent.toLowerCase();
                    if (letter === 'all' || title.startsWith(letter)) {
                        book.style.display = 'block';
                    } else {
                        book.style.display = 'none';
                    }
                });
            }
        });
    });
});

