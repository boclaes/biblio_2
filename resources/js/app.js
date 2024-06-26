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

// slider monthly annual
document.addEventListener('DOMContentLoaded', function () {
  function toggleSlide(plan) {
      const slide = document.querySelector('.toggle-slide');
      const buttons = document.querySelectorAll('.toggle-button');
      buttons.forEach((button, index) => {
          if (button.textContent.toLowerCase() === plan) {
              slide.style.left = index === 0 ? '0' : '50%';
          }
      });
  }

  // Attach event listeners to buttons
  const buttons = document.querySelectorAll('.toggle-button');
  buttons.forEach(button => {
      button.addEventListener('click', () => toggleSlide(button.textContent.toLowerCase()));
  });
});

document.querySelectorAll('.accordion-button').forEach(button => {
  button.addEventListener('click', () => {
      const accordionContent = button.nextElementSibling;

      button.classList.toggle('active');

      if (button.classList.contains('active')) {
          accordionContent.style.maxHeight = accordionContent.scrollHeight + 'px';
      } else {
          accordionContent.style.maxHeight = 0;
      }
  });
});
