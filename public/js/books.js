document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.star');
    const starReviews = document.querySelectorAll('.star-review');
    const reviewStarsContainer = document.querySelector('.stars');
    const bookDetails = document.querySelector('.book-details');
    const bookId = bookDetails ? bookDetails.getAttribute('data-book-id') : null;
    const token = bookDetails ? bookDetails.getAttribute('data-csrf-token') : null;

    console.log('Document loaded. Book ID:', bookId, 'CSRF Token:', token);

    if (!bookId || !token) {
        console.error('Book ID or CSRF Token is missing.');
        return;
    }

    // Function to handle star click
    const handleStarClick = (stars, bookId, token) => {
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.getAttribute('data-value'));
                console.log('Star clicked. Rating:', rating);

                fetch("/books/" + bookId + "/rate", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': token
                    },
                    body: JSON.stringify({
                        rating: rating,
                    })
                })
                .then(response => {
                    console.log('Fetch response received. Status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Rating response data:', data);
                    stars.forEach(s => {
                        const sValue = parseInt(s.getAttribute('data-value'));
                        s.classList.toggle('active', sValue <= rating);
                    });
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
            });
        });
    };

    // Apply handleStarClick for both sets of stars
    handleStarClick(stars, bookId, token);

    // For star reviews
    if (reviewStarsContainer) {
        const reviewBookId = reviewStarsContainer.getAttribute('data-book-id');
        const reviewToken = reviewStarsContainer.getAttribute('data-csrf-token');
        console.log('Review stars container found. Book ID:', reviewBookId, 'CSRF Token:', reviewToken);
        if (reviewBookId && reviewToken) {
            handleStarClick(starReviews, reviewBookId, reviewToken);
        } else {
            console.error('Review Book ID or CSRF Token is missing.');
        }
    } else {
        console.error('Review stars container not found.');
    }

    const checkboxes = document.querySelectorAll('input[type="checkbox"]');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const field = checkbox.id;
            const value = checkbox.checked ? 1 : 0;
            console.log('Checkbox changed. Field:', field, 'Value:', value);

            const data = { [field]: value };

            checkboxes.forEach(cb => {
                if (cb !== checkbox && cb.checked) {
                    cb.checked = false;
                    data[cb.id] = 0;
                }
            });

            console.log('Data to be sent:', data);

            fetch(`/books/${bookId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': token
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Fetch response received. Status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Status update response data:', data);
                // Update the book object with the new data
                Object.assign(bookDetails, data.book);
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
        });
    });

    // Add tab functionality
    const tabLabels = document.querySelectorAll('.tab-label');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLabels.forEach(label => {
        label.addEventListener('click', function () {
            const tab = this.dataset.tab;

            console.log('Tab clicked. Tab:', tab);

            tabLabels.forEach(lbl => lbl.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(tab).classList.add('active');
        });
    });

    // Handle dropdown reading status change
    const readingStatusBtn = document.querySelector('.status-button'); // Changed from reading-status-btn to status-button
    const dropdownContent = document.querySelector('.status-dropdown'); // Changed from dropdown-content to status-dropdown
    const dropdownItems = document.querySelectorAll('.dropdown-item');

    console.log('Dropdown elements:', { readingStatusBtn, dropdownContent, dropdownItems });

    if (!readingStatusBtn) {
        console.error('Reading status button not found.');
        return;
    }

    if (!dropdownContent) {
        console.error('Dropdown content not found.');
        return;
    }

    readingStatusBtn.addEventListener('click', (event) => {
        event.stopPropagation();
        console.log('Reading status button clicked.');
        dropdownContent.classList.toggle('show');
    });

    dropdownItems.forEach(item => {
        item.addEventListener('click', () => {
            const status = item.dataset.status;
            console.log('Status selected:', status);

            fetch(`/books/${bookId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': token
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => {
                console.log('Fetch response received. Status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Status update response data:', data);
                // Update the button text to reflect the selected status
                if (status === 'to_be_read') {
                    readingStatusBtn.textContent = 'To be read';
                } else if (status === 'in_progress') {
                    readingStatusBtn.textContent = 'In progress';
                } else if (status === 'completed') {
                    readingStatusBtn.textContent = 'Completed';
                }
                dropdownContent.classList.remove('show');
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
        });
    });

    // Close the dropdown if the user clicks outside of it
    window.addEventListener('click', (event) => {
        if (!event.target.matches('.status-button')) {
            if (dropdownContent.classList.contains('show')) {
                console.log('Click outside dropdown. Closing dropdown.');
                dropdownContent.classList.remove('show');
            }
        }
    });
});

