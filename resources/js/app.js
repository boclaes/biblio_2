import './bootstrap';

// DOM Elements
const hamburgerMenu = document.querySelector('.hamburger-menu');
const menuList = document.querySelector('.menu-list-mobile ul');
const body = document.querySelector('body');
const links = document.querySelectorAll('.menu-list-mobile a'); // Select all links in the mobile menu

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