/* global jQuery */
(function ($, window, document, undefined) {
  var CounterModule = CounterModule || {};

  CounterModule = {
    init: function () {
      this.animateCounters();
    },

    animateCounters: function () {
      $('.css-counter').each(function () {
        var $el = $(this);
        var target = parseInt($el.data('target'), 10) || 0;
        var speed = 200;
        var current = 0;
        var increment = Math.ceil(target / speed);

        function update() {
          current += increment;
          if (current >= target) {
            $el.text(target.toLocaleString());
          } else {
            $el.text(current.toLocaleString());
            setTimeout(update, 15);
          }
        }

        update();
      });
    }
  };

  CounterModule.init();
})(jQuery, window, document);
