function handleDecision(decision, googleBooksId) {
    const bookElements = document.querySelectorAll('.book-section');
    const books = Array.from(bookElements).map(bookElement => {
        return JSON.parse(bookElement.dataset.book);
    });

    const book = books.find(b => b.google_books_id === googleBooksId);

    fetch('/book/decision', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            decision: decision,
            google_books_id: googleBooksId,
            ...book
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const bookSection = document.getElementById(`book-${googleBooksId}`);
            bookSection.classList.add('fade-out');

            // Wait for the fade-out animation to complete before updating the content
            setTimeout(() => {
                // Check for duplicates before updating
                if (!isDuplicateBook(data.newBook)) {
                    updateBookSection(googleBooksId, data.newBook);
                } else {
                    // If duplicate, fetch another book
                    handleDecision(decision, googleBooksId);
                }
            }, 500); // Match this duration to the CSS transition duration

            // Show the success message
            showSuccessMessage();
        } else {
            showErrorMessage(data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An unexpected error occurred.');
    });
}

function isDuplicateBook(newBook) {
    const bookElements = document.querySelectorAll('.book-section');
    const books = Array.from(bookElements).map(bookElement => {
        return JSON.parse(bookElement.dataset.book);
    });

    return books.some(b => b.google_books_id === newBook.google_books_id);
}

function updateBookSection(googleBooksId, newBook) {
    const bookSection = document.getElementById(`book-${googleBooksId}`);
    if (bookSection) {
        bookSection.classList.remove('fade-out');
        bookSection.classList.add('fade-in');

        bookSection.dataset.book = JSON.stringify(newBook);
        bookSection.id = `book-${newBook.google_books_id}`;
        bookSection.innerHTML = `
            <img class="arrow-right" src="/images/arrow-right.png" onclick="handleDecision('accept', '${newBook.google_books_id}')">
            <div class="book-cover">
                <img src="${newBook.cover}" alt="Cover Image of ${newBook.title}">
            </div>
            <div class="book-details">
                <h3 class="book-title">${newBook.title}</h3>
                <p class="book-author">${newBook.author}</p>
                <p class="book-description">${newBook.description.substring(0, 100)}...</p>
            </div>
        `;

        // Trigger reflow to restart the animation
        void bookSection.offsetWidth;

        bookSection.classList.add('fade-in-active');
        setTimeout(() => {
            bookSection.classList.remove('fade-in');
            bookSection.classList.remove('fade-in-active');
        }, 500); // Match this duration to the CSS transition duration
    }
}

function showSuccessMessage() {
    const successMessage = document.getElementById('success-message');
    successMessage.style.display = 'block';

    // Allow the display change to take effect
    setTimeout(() => {
        successMessage.style.opacity = '1';
    }, 10);

    // Hide the message after 3 seconds
    setTimeout(() => {
        successMessage.style.opacity = '0';
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 500); // Match this duration to the CSS transition duration
    }, 3000);
}

function showErrorMessage(message) {
    const errorMessage = document.getElementById('error-message');
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';

    // Allow the display change to take effect
    setTimeout(() => {
        errorMessage.style.opacity = '1';
    }, 10);

    // Hide the message after 3 seconds
    setTimeout(() => {
        errorMessage.style.opacity = '0';
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 500); // Match this duration to the CSS transition duration
    }, 3000);
}
