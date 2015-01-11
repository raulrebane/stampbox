(function ( $ ) {

    $.fn.flipswitch = function( options ) {
        
        // Defaults
        var settings = $.extend({
            width: "500",
            height: "300",
            backgroundColor: "#FFF",
            fadeDuration: 800,
            slideDuration: 4000
        }, options );

        var timeoutID;
        var currentSlide = 0;
        var totalSlides;

        // Set styles
        this.css('position', 'relative');
        this.css('width', settings.width);
        this.css('height', settings.height);
        this.css('background-color', settings.backgroundColor);

        // Parse user data
        var slides = parseUserElems(this.find('div'));

        // Remove user data
        this.find('div').remove();

        // Create new image elements and navigation
        var imageElems = $('<div class="images"></div>');
        imageElems.css('width', '100%');
        imageElems.css('height', settings.height - 8 + 'px');
        var navElems = $('<div class="nav"></div>');
        navElems.css('position', 'absolute');
        navElems.css('bottom', '0px');
        navElems.css('width', '100%');
        navElems.css('font-size', '0px');
        navElems.css('z-index', '101');
        totalSlides = slides.length;

        var widthLeft = settings.width;
        for(var i = 0; i < slides.length; i++) {
            var slide = slides[i];

            // Slide item
            var imageElem = $('<div></div>');
            imageElem.css('position', 'absolute');
            imageElem.css('width', '100%');
            imageElem.css('height', '100%');
            imageElem.css('display', 'none');
            imageElem.css('background', 'url(' + slide.image + ')');
            imageElem.css('background-repeat', 'no-repeat');
            imageElem.css('background-position', 'center');
            imageElem.css('background-size', 'cover');
            imageElem.attr('data-link', slide.link);
            if(slide.link) {
                imageElem.css('cursor', 'pointer');
                imageElem.click(function() {
                    location.href = $(this).attr('data-link');
                });
            }
            slide.elem = imageElem;
            imageElems.append(imageElem);

            // Nav item
            var navElem = $('<div></div>');
            navElem.css('position', 'relative');
            navElem.css('display', 'inline-block');
            var navItemWidth;
            if(i == slides.length - 1) {
                navItemWidth = widthLeft;
            } else {
                navItemWidth = Math.floor(settings.width / totalSlides);
                widthLeft = widthLeft - navItemWidth;
            }
            navElem.css('width', navItemWidth - 1 + 'px');
            navElem.css('height', '8px');
            navElem.css('background', 'linear-gradient(to right, #c9c8c8 , #dad9d9)');
            navElem.css('border-right', '1px solid #e8e8e8');
            navElem.css('cursor', 'pointer');
            navElem.attr('index', i);
            navElem.click(function() {
                showSlide($(this).attr('index'), slides, 300);
            });
            slide.nav = navElem;
            navElems.append(navElem);
        };

        // Right arrow nav background gradient
        var rightBgElem = $('<div></div>');
        rightBgElem.css('position', 'absolute');
        rightBgElem.css('width', settings.width / 2 + 'px');
        rightBgElem.css('height', settings.height + 'px');
        rightBgElem.css('background-image', 'url(/img/gallery-next-hover-grad.png)');
        rightBgElem.css('background-repeat', 'no-repeat');
        rightBgElem.css('background-position', 'right center');
        rightBgElem.css('z-index', '108');
        rightBgElem.css('top', '0');
        rightBgElem.css('right', '0');
        rightBgElem.css('display', 'none');

        // Right arrow nav
        var rightElem = $('<div></div>');
        rightElem.css('position', 'absolute');
        rightElem.css('width', '40px');
        rightElem.css('height', '40px');
        rightElem.css('right', '20px');
        rightElem.css('cursor', 'pointer');
        rightElem.css('top', '50%');
        rightElem.css('margin-top', '-20px');
        rightElem.css('background', 'url(/img/gallery-next.png)');
        rightElem.css('background-repeat', 'no-repeat');
        rightElem.css('background-position', 'center');
        rightElem.css('background-size', 'cover');
        rightElem.css('z-index', '110');
        rightElem.click(function() {
            currentSlide = showNextSlide(currentSlide, totalSlides, slides);
        });
        rightElem.mouseenter(function() {
            rightBgElem.stop(true, true).fadeIn();
        });
        rightElem.mouseleave(function() {
            rightBgElem.stop(true, true).fadeOut();
        });

        // Left arrow nav background gradient
        var leftBgElem = rightBgElem.clone();
        leftBgElem.css('background-image', 'url(/img/gallery-prev-hover-grad.png)');
        leftBgElem.css('left', '0');
        leftBgElem.css('background-position', 'left center');

        // Left arrow nav
        var leftElem = rightElem.clone();
        leftElem.css('right', 'null');
        leftElem.css('left', '20px');
        leftElem.css('background', 'url(/img/gallery-prev.png)');
        leftElem.click(function() {
            currentSlide = showPreviousSlide(currentSlide, totalSlides, slides);
        });
        leftElem.mouseenter(function() {
            leftBgElem.stop(true, true).fadeIn();
        });
        leftElem.mouseleave(function() {
            leftBgElem.stop(true, true).fadeOut();
        });

        this.append(imageElems);
        this.append(leftBgElem);
        this.append(rightBgElem);
        this.append(navElems);
        this.append(rightElem);
        this.append(leftElem);

        showSlide(0, slides, 800);
        if(totalSlides > 1) {
            timeoutID = setInterval(function(){
                currentSlide = showNextSlide(currentSlide, totalSlides, slides);
            }, settings.slideDuration);
            this.mouseenter(function() {
                clearInterval(timeoutID);
            });
            this.mouseleave(function() {
                timeoutID = setInterval(function(){
                    currentSlide = showNextSlide(currentSlide, totalSlides, slides);
                }, settings.slideDuration);
            });
        }

        return this;
 
    };

    function parseUserElems(elems) {
        var items = [];
        elems.each(function(){
            var elem = $(this);
            items.push({image: elem.attr('data-image'), link: elem.attr('data-link')});
        });
        return items;
    }

    function showSlide(index, slides, duration) {
        duration = 800;
        slides[index].elem.css('z-index', '100');
        slides[index].elem.stop().fadeIn(duration, function() {
            // Hide others
            for(var i = 0; i < slides.length; i++) {
                if(i != index) {
                    slides[i].elem.stop().hide();
                    slides[i].elem.css('display', 'none');
                }
            }
        });
        // Navigation update
        slides[index].nav.css('background', 'linear-gradient(to right, #de281f , #de281f)');
        for(var i = 0; i < slides.length; i++) {
            if(i != index) {
                slides[i].elem.stop();
                slides[i].nav.css('background', 'linear-gradient(to right, #c9c8c8 , #dad9d9)');
                slides[i].elem.css('z-index', i);
            }
        }
    }

    function showNextSlide(currentSlide, totalSlides, slides, duration) {
        currentSlide++;
        if(currentSlide > totalSlides - 1) currentSlide = 0;
        showSlide(currentSlide, slides);
        return currentSlide;
    }

    function showPreviousSlide(currentSlide, totalSlides, slides, duration) {
        currentSlide--;
        if(currentSlide < 0) currentSlide = totalSlides - 1;
        showSlide(currentSlide, slides);
        return currentSlide;
    }
 
}( jQuery ));