$('#menu-toggle').click(function(e) {
  e.preventDefault();
  $('#wrapper').toggleClass('active');
});

function initMobileMenu() {
  $('.mobile-menu-trigger').on('click', function() {
    $('.mobile-menu').toggleClass('open');
    $('.mobile-menu-trigger').toggleClass('open');
  });
}
initMobileMenu();

/*

FLASH DISPLAY

A. Flash with time in milliseconds to live before close
<div class="m-flash" data-ttl="3000">
    Your message
</div>

B. Flash message that only closes on click
<div class="m-flash" data-ttl="3000">
    Your message
</div>


*/
$(function(){
    if(isNaN(parseInt($('.m-flash').attr('data-ttl')))) {
        $('.m-flash').off().click(function() {
            $('.m-flash').fadeOut().remove();
        });
    } else {
        setTimeout(function() {
            $('.m-flash').fadeOut();
        }, parseInt($('.m-flash').attr('data-ttl')));
    }
});