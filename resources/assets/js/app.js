require('./bootstrap');
window.Vue = require('vue');

// Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
  el: '#app',

  ready() {
    hljs.initHighlightingOnLoad();
    this.removeFlashMessages();
    this.setJqueryAjaxHeaders();
    this.initBackToTopButton();
  },

  methods: {
    removeFlashMessages() {
      if ($('.alert')) {
        $('.alert').delay(5000).fadeOut();
      }
    },

    setJqueryAjaxHeaders() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    },

    initBackToTopButton() {
      $('#back-to-top').on('click', function () {
        $('body,html').animate({
          scrollTop: 0
        }, 800);

        return false;
      });

      $(window).on('scroll', function () {
        var scrollPos = $(window).scrollTop();

        if (scrollPos > 50) {
          $('#back-to-top').fadeIn();
        } else {
          $('#back-to-top').fadeOut();
        }
      });
    }
  }
});
