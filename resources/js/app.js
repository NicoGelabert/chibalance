import './bootstrap';

import { createApp } from 'vue/dist/vue.esm-bundler';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import {get, post} from "./http.js";
import 'flowbite';
import Splide from '@splidejs/splide';
import { AutoScroll } from '@splidejs/splide-extension-auto-scroll';
import ProductList from './components/products/ProductList.vue';
import BookingModal from './components/BookingModal.vue';

const bookingApp = createApp({
  data() {
    return {
      showBookingModal: false,
      selectedProduct: null,
    };
  },
  methods: {
    openBookingModal(product) {
      this.selectedProduct = product;
      this.showBookingModal = true;
    },
    closeBookingModal() {
      this.showBookingModal = false;
      this.selectedProduct = null;
    },
  },
});

bookingApp.component('booking-modal', BookingModal);
bookingApp.mount('#booking-modal-root');

// Exponer función global para abrir el modal desde cualquier parte
window.openBookingModal = (product) => {
  bookingApp._instance.proxy.openBookingModal(product);
};
const productIndex = createApp({});
productIndex.component('product-list', ProductList);
productIndex.mount('#product-index');

Alpine.plugin(collapse)

window.Alpine = Alpine;

document.addEventListener("alpine:init", async () => {

  Alpine.data("toast", () => ({
    visible: false,
    delay: 5000,
    percent: 0,
    interval: null,
    timeout: null,
    message: null,
    close() {
      this.visible = false;
      clearInterval(this.interval);
    },
    show(message) {
      this.visible = true;
      this.message = message;

      if (this.interval) {
        clearInterval(this.interval);
        this.interval = null;
      }
      if (this.timeout) {
        clearTimeout(this.timeout);
        this.timeout = null;
      }

      this.timeout = setTimeout(() => {
        this.visible = false;
        this.timeout = null;
      }, this.delay);
      const startDate = Date.now();
      const futureDate = Date.now() + this.delay;
      this.interval = setInterval(() => {
        const date = Date.now();
        this.percent = ((date - startDate) * 100) / (futureDate - startDate);
        if (this.percent >= 100) {
          clearInterval(this.interval);
          this.interval = null;
        }
      }, 30);
    },
  }));

  Alpine.data("productItem", (product) => {
    return {
      product,
      addToCart(quantity = 1) {
        post(this.product.addToCartUrl, {quantity})
          .then(result => {
            this.$dispatch('cart-change', {count: result.count})
            this.$dispatch("notify", {
              message: "The item was added into the cart",
            });
          })
          .catch(response => {
            console.log(response);
          })
      },
      removeItemFromCart() {
        post(this.product.removeUrl)
          .then(result => {
            this.$dispatch("notify", {
              message: "The item was removed from cart",
            });
            this.$dispatch('cart-change', {count: result.count})
            this.cartItems = this.cartItems.filter(p => p.id !== product.id)
          })
      },
      changeQuantity() {
        post(this.product.updateQuantityUrl, {quantity: product.quantity})
          .then(result => {
            this.$dispatch('cart-change', {count: result.count})
            this.$dispatch("notify", {
              message: "The item quantity was updated",
            });
          })
      },
    };
  });
  
  Alpine.data('lightbox', () => ({
    isOpen: false,
    imageUrl: '',
    openLightbox(url) {
        this.imageUrl = url;
        this.isOpen = true;
    }
  }))
  
});

Alpine.start();

// dark mode
// const toggleThemeButtons = document.querySelectorAll('.toggle-theme');
// function toggleTheme() {
//   document.documentElement.classList.toggle('dark');
//   toggleThemeButtons.forEach(button => {
//     button.classList.toggle('dark');
//   });

//   if (document.documentElement.classList.contains('dark')) {
//     localStorage.setItem('theme', 'dark');
//   } else {
//     localStorage.setItem('theme', 'light');
//   }

// }
// toggleThemeButtons.forEach(button => {
//   button.addEventListener('click', toggleTheme);
// });

// document.addEventListener('DOMContentLoaded', () => {
//   const savedTheme = localStorage.getItem('theme');

//   if (savedTheme === 'dark') {
//     document.documentElement.classList.add('dark');
//     toggleThemeButtons.forEach(button => {
//       button.classList.add('dark');
//     });
//   }
// });
// dark mode

// SPLIDE
document.addEventListener('DOMContentLoaded', function () {
  // Home Hero Banner
  var homeHeroBannerElement = document.querySelector('.home-hero-banner');
  if (homeHeroBannerElement) {
    var homeHeroBanner = new Splide(homeHeroBannerElement, {
      type        : 'fade',
      rewind      : true,
      pagination  : true,
      isNavigation: false,
      arrows      : false,
      focus       : 'center',
      autoplay    : true,
      interval    : 10000,
      classes: {
        pagination: 'splide__pagination_custom',
      },
    });

    homeHeroBanner.on('mounted move', function () {
      var activeSlide = homeHeroBanner.Components.Slides.getAt(homeHeroBanner.index).slide;
      var previousSlide = homeHeroBanner.Components.Slides.getAt(homeHeroBanner.index - 1);
      if (previousSlide) {
        animateSlideOutElements(previousSlide.slide);
      }
      animateSlideElements(activeSlide);
    });

    homeHeroBanner.mount();

    function animateElement(element, delay) {
      setTimeout(() => {
        element.classList.add('active');
      }, delay);
    }

    function animateSlideElements(slide) {
      var h2 = slide.querySelector('.animate-h2');
      var img = slide.querySelector('.animate-img');
      var p = slide.querySelector('.animate-p');
      var button = slide.querySelector('.animate-button');

      animateElement(h2, 750); // 0.75 segundos después
      animateElement(img, 1000); // 1 segundo después
      animateElement(p, 1250); // 1.25 segundos después
      animateElement(button, 1750); // 1.75 segundos después
    }

    function animateSlideOutElements(slide) {
      var elements = slide.querySelectorAll('.active');
      elements.forEach(function (element) {
        element.classList.remove('active');
      });
    }
  }

  // Productos
  var productsElement = document.querySelector('.treatments');
  if (productsElement) {
    var products = new Splide(productsElement, {
      arrows    : true,
      autoplay  : false,
      autoWidth : true,
      breakpoints: {
          1024: {
              perPage     : 2,
          },
          480: {
              perPage     : 1,        
          }
      },
      classes: {
        pagination: 'splide__pagination_custom',
        arrows    : 'splide__arrows_custom',
      },
      gap       : '1.5rem',
      interval  : 7000,
      isNavigation: false,
      pagination  : true,
      perPage     : '3',
      rewind      : true,
      type        : 'slide',
    });
    products.mount();
  }

  // Noticias
  var newsElement = document.querySelector('.news_gallery');
  if (newsElement) {
    var news = new Splide(newsElement, {      
      arrows      : false,
      breakpoints : {
        480 :{
          perPage : 2,
        }
      },
      classes     : {
        pagination: 'splide__pagination_custom',
        arrows    : 'splide__arrows_custom splide__arrows_custom_news',
      },
      focus      : 'center',
      gap         : '0.5rem',
      pagination  : false,
      perPage     : 3,
      rewind      : false,
      type        : 'carousel',
    });
    news.mount();
  }
});
