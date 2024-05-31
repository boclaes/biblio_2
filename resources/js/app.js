import './bootstrap';

// DOM Elements
let hamburgerMenu = document.querySelector('.hamburger-menu');
let menuList = document.querySelector('.menu-list-mobile ul');
const body = document.querySelector('body.books-view'); // The selector '.books-view' is more appropriate for classes within the body tag
let links = document.querySelectorAll('.menu-list-mobile a'); // Select all links in the mobile menu

if (!hamburgerMenu) {
  hamburgerMenu = document.querySelector('.hamburger-menu-library');
  links = document.querySelectorAll('.menu-list-mobile-library a'); // Adjust if necessary for a different selector
}

// Hamburger menu 
hamburgerMenu.addEventListener('click', () => {
  hamburgerMenu.classList.toggle('active');
  menuList.classList.toggle('is-active');
  body.classList.toggle('no-scroll');
});

// Function to handle link clicks
const handleLinkClick = () => {
  body.classList.remove('no-scroll');
  hamburgerMenu.classList.remove('active');
  menuList.classList.remove('is-active');
};

// Adding event listeners to the links
links.forEach(link => {
  link.addEventListener('click', handleLinkClick);
});
