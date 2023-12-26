(function ($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  $(document).ready(function () {
    $('.watch').click(function(){
      var current = $(this);
      // current.addClass("voted");
      if (current.hasClass('voted')) {
        //do something
        current.removeClass("voted");
      }
      else{
        current.addClass("voted");
      }
    });
    $('.submit-section').click(function(){
      var voted_object = {}
      $( ".open-vote" ).each(function(index) {
        var current_cate_id = $(this).data('award_set');
        var voted = $(this).find('.voted');
        var array = [$(this).data("user_id"),$(this).data('award_set')];
        /*
        array[0]=user id
        array[1]=award id
        */
        $(voted).each(function() {
          // console.log($(this).data('post_id'));
          array.push($(this).data('post_id'));
        })
        // voted_object[current_cate_id] = String(array)
        voted_object[index] = array;
        // voted_object[index] = obj;
        // voted_object["post_"+index] = current_cate_id;
        // voted_object["voted_"+index] = array;
        // voted_array.push(object);
      });
      // console.log(voted_object);
      // console.log(JSON.stringify(voted_object));
      $.ajax({
        type: "POST",
        url: "/ajax.php",
        data: {
          voted_object
        },
      }).done(function() {
        console.log("done");
        // window.location.href = $('form').attr('action');
      });
    });
    // $(".watch").click(function ($e) {
    //   $e.preventDefault();
    //   var current = $(this);
    //   $.post(
    //     "/ajax.php",
    //     {
    //       // _ajax_nonce: ajax_poll.nonce,
    //       action: "my_ajax_poll_handler",
    //       user_id: $(this).data("user_id"),
    //       post_id: $(this).data("post_id"),
    //       award_id: $(this).data("award_id"),
    //     },
    //     function (data) {
    //       // console.log(data);
    //       current.siblings(".voted").removeClass("voted");
    //       // console.log(current);
    //       current.addClass("voted");
    //     }
    //   );
    // });
  });
})(jQuery);
