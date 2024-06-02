window.onload = function() {
    document.getElementById('searchInput').focus();
    setActiveClass('title');  // Set active class on default search type
};

function setSearchType(type) {
    console.log('Setting search type to:', type);  // Debug: Log the search type being set
    const searchTypeInput = document.getElementById('searchType');
    searchTypeInput.value = type;

    const searchInput = document.getElementById('searchInput');
    searchInput.placeholder = type === 'isbn' ? 'Enter ISBN' : 'Enter Book Title';
    searchInput.value = '';
    searchInput.setAttribute('pattern', type === 'isbn' ? '\\d{10}|\\d{13}' : '.*');
    searchInput.setAttribute('title', type === 'isbn' ? 'Please enter a valid 10 or 13 digit ISBN.' : 'Please enter a valid book title.');

    setActiveClass(type);
}

function setActiveClass(type) {
    console.log('Activating class for:', type);  // Debug: Log which type is being activated
    document.querySelectorAll('.search-option').forEach(option => {
        option.classList.remove('active');
    });
    const elementId = 'searchBy' + type.charAt(0).toUpperCase() + type.slice(1).toLowerCase();
    const element = document.getElementById(elementId);
    if (element) {
        element.classList.add('active');
    } else {
        console.error('Element not found:', elementId);
    }
}

document.getElementById('searchForm').addEventListener('submit', function(event) {
    const searchType = document.getElementById('searchType').value;
    const query = document.getElementById('searchInput').value;

    if (searchType === 'isbn' && !/^(?:\d{10}|\d{13})$/.test(query)) {
        alert('Please enter a valid 10 or 13 digit ISBN.');
        event.preventDefault();
    } else if (searchType === 'title' && !query.trim()) {
        alert('Please enter a valid book title.');
        event.preventDefault();
    }
});