!(function (a) {
    "use strict";
    function e() {
        if (a(".main-header").length) {
            var e = a(window).scrollTop(),
                t = a(".main-header"),
                n = a(".main-header .sticky-header, .header-style-two .outer-container, .header-style-four .header-lower, .header-style-six .outer-container"),
                h = a(".sidebar-side .hooper"),
                s = a(".scroll-to-top");
            e > 55 ? (h.addClass("fixed-advertisement"),t.addClass("fixed-header"), n.addClass("animated slideInDown"), s.fadeIn(300)) : (h.removeClass("fixed-advertisement"),t.removeClass("fixed-header"), n.removeClass("animated slideInDown"), s.fadeOut(300));
        }
    }
    if (
        (e(),
        a(".main-header li.dropdown ul").length &&
            (a(".main-header li.dropdown").append('<div class="dropdown-btn"><span class="fa fa-angle-down"></span></div>'),
            a(".main-header li.dropdown .dropdown-btn").on("click", function () {
                a(this).prev("ul").slideToggle(500);
            }),
            a(".main-header .main-menu li.dropdown .dropdown-btn").on("click", function () {
                a(this).prev(".mega-menu").slideToggle(500);
            }),
            a(".main-header .navigation li.dropdown > a,.hidden-bar .side-menu li.dropdown > a").on("click", function (a) {
                a.preventDefault();
            })),
        a(".sidenav-bar, .hidden-bar").length &&
            (a(".sidenav-bar .navigation li.dropdown > a").on("click", function (e) {
                e.preventDefault();
                var t = a(this).parent("li");
                !0 === a(t).hasClass("active") ? a(t).removeClass("active") : (a(".sidenav-bar .navigation li.dropdown").removeClass("active"), a(this).parent("li").addClass("active"));
            }),
            a(".sidenav-bar .side-nav .navigation li.dropdown > ul").slideUp(),
            a(".sidenav-bar .side-nav .navigation li.dropdown > a").on("click", function () {
                a(this).next("ul").slideToggle(400), a(this).parent().siblings().find("ul").slideUp(400);
            }),
            a(".main-header .nav-toggler").on("click", function (e) {
                e.preventDefault(), a("body").toggleClass("active-side-nav");
            }),
            a(".sidenav-bar .cross-icon, .hidden-bar .cross-icon, .form-back-drop").on("click", function (e) {
                e.preventDefault(), a("body").removeClass("active-side-nav");
            })),
        a(".banner-carousel").length &&
            a(".banner-carousel").owlCarousel({
                animateOut: "fadeOut",
                animateIn: "fadeIn",
                loop: !0,
                margin: 0,
                nav: !0,
                smartSpeed: 700,
                autoHeight: !0,
                autoplay: !0,
                autoplayTimeout: 1e4,
                navText: ['<span class="fa fa-long-arrow-left"></span> prev', 'next<span class="fa fa-long-arrow-right"></span>'],
                responsive: { 0: { items: 1 }, 600: { items: 1 }, 1024: { items: 1 } },
            }),
        a(".banner-carousel-two").length &&
            a(".banner-carousel-two").owlCarousel({
                animateOut: "fadeOut",
                animateIn: "fadeIn",
                loop: !0,
                margin: 30,
                nav: !0,
                smartSpeed: 15e3,
                mouseDrag: !1,
                touchDrag: !1,
                autoHeight: !0,
                autoplay: !0,
                autoplayTimeout: 1e4,
                navText: ['<span class="fa fa-long-arrow-left"></span> prev', 'next<span class="fa fa-long-arrow-right"></span>'],
                responsive: { 0: { items: 1 }, 600: { items: 1 }, 1024: { items: 1 } },
            }),
        a(".banner-carousel-three").length &&
            a(".banner-carousel-three").owlCarousel({
                animateOut: "fadeOut",
                animateIn: "fadeIn",
                loop: !0,
                margin: 0,
                nav: !0,
                smartSpeed: 700,
                autoHeight: !0,
                autoplay: !0,
                autoplayTimeout: 1e4,
                navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
                responsive: { 0: { items: 1 }, 600: { items: 1 }, 1024: { items: 1 } },
            }),
        a(".single-item-carousel").length &&
            a(".single-item-carousel").owlCarousel({
                loop: !0,
                margin: 0,
                nav: !0,
                smartSpeed: 700,
                autoplay: !0,
                navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
                responsive: { 0: { items: 1 }, 600: { items: 1 }, 1024: { items: 1 } },
            }),
        a(".services-carousel-two").length &&
            a(".services-carousel-two").owlCarousel({
                loop: !0,
                margin: 30,
                nav: !0,
                smartSpeed: 700,
                autoplay: !0,
                navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
                responsive: { 0: { items: 1 }, 767: { items: 2 }, 800: { items: 3 }, 1024: { items: 4 } },
            }),
        a(".services-carousel").length &&
            a(".services-carousel").owlCarousel({
                loop: !0,
                margin: 0,
                nav: !0,
                smartSpeed: 700,
                autoplay: !0,
                navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
                responsive: { 0: { items: 1 }, 767: { items: 2 }, 1024: { items: 3 } },
            }),
        a(".testimonial-carousel").length &&
            a(".testimonial-carousel").owlCarousel({
                loop: !0,
                margin: 30,
                nav: !0,
                smartSpeed: 700,
                autoplay: !0,
                navText: ['<span class="fa fa-long-arrow-left"></span>', '<span class="fa fa-long-arrow-right"></span>'],
                responsive: { 0: { items: 1 }, 600: { items: 1 }, 800: { items: 1 }, 1024: { items: 1 } },
            }),
        a(".testimonial-carousel-two").length &&
            a(".testimonial-carousel-two").owlCarousel({
                loop: !0,
                margin: 70,
                nav: !0,
                smartSpeed: 700,
                autoplay: !0,
                navText: ['<span class="fa fa-long-arrow-left"></span> prev', 'next<span class="fa fa-long-arrow-right"></span>'],
                responsive: { 0: { items: 1 }, 600: { items: 1 }, 768: { margin: 30, items: 2 }, 1024: { items: 2 } },
            }),
        a(".projects-carousel").length &&
            a(".projects-carousel").owlCarousel({
                loop: !0,
                margin: 0,
                nav: !0,
                smartSpeed: 700,
                autoplay: !0,
                navText: ['<span class="fa fa-long-arrow-left"></span> prev', 'next<span class="fa fa-long-arrow-right"></span>'],
                responsive: { 0: { items: 1 }, 600: { items: 2 }, 800: { items: 3 }, 1024: { items: 4 }, 1400: { items: 5 } },
            }),
        a(".projects-carousel-two").length &&
            a(".projects-carousel-two").owlCarousel({
                animateOut: "slideOutDown",
                animateIn: "zoomIn",
                loop: !0,
                margin: 30,
                nav: !0,
                smartSpeed: 15e3,
                mouseDrag: !1,
                touchDrag: !1,
                autoHeight: !0,
                autoplay: !0,
                autoplayTimeout: 1e4,
                navText: ['<span class="fa fa-chevron-left"></span>', '<span class="fa fa-chevron-right"></span>'],
                responsive: { 0: { items: 1 }, 600: { items: 1 }, 1024: { items: 1 } },
            }),
        a(".sponsors-carousel").length &&
            a(".sponsors-carousel").owlCarousel({ loop: !0, margin: 30, nav: !0, smartSpeed: 700, autoplay: !0, responsive: { 0: { items: 1 }, 600: { items: 2 }, 768: { items: 3 }, 1024: { items: 4 }, 1400: { items: 5 } } }),
        a(".products-carousel").length &&
            a(".products-carousel").owlCarousel({
                loop: !0,
                margin: 0,
                nav: !0,
                center: !0,
                smartSpeed: 700,
                autoplay: !0,
                navText: ['<span class="fa fa-long-arrow-left"></span> prev', 'next<span class="fa fa-long-arrow-right"></span>'],
                responsive: { 0: { items: 1 }, 600: { items: 2 }, 1024: { items: 3 } },
            }),
        a(".specialize-section-two .image-carousel").length && a(".specialize-section-two .thumbs-carousel").length)
    ) {
        var t = a(".specialize-section-two .image-carousel"),
            n = a(".specialize-section-two .thumbs-carousel"),
            s = !1;
        t
            .owlCarousel({ loop: !1, items: 1, margin: 0, nav: !1, navText: ['<span class="icon fa fa-angle-left"></span>', '<span class="icon fa fa-angle-right"></span>'], dots: !1, autoplay: !0, autoplayTimeout: 5e3 })
            .on("changed.owl.carousel", function (a) {
                s || ((s = !1), n.trigger("to.owl.carousel", [a.item.index, 500, !0]), (s = !1));
            }),
            n
                .owlCarousel({
                    loop: !1,
                    margin: 20,
                    items: 1,
                    nav: !0,
                    navText: ['<span class="fa fa-long-arrow-left"></span> prev', 'next<span class="fa fa-long-arrow-right"></span>'],
                    dots: !1,
                    center: !1,
                    autoplay: !0,
                    mouseDrag: !0,
                    touchDrag: !0,
                    autoplayTimeout: 5e3,
                    responsive: { 0: { items: 2, autoWidth: !1 }, 400: { items: 2, autoWidth: !1 }, 600: { items: 3, autoWidth: !1 }, 800: { items: 4, autoWidth: !1 }, 1024: { items: 4, autoWidth: !1 } },
                })
                .on("click", ".owl-item", function () {
                    t.trigger("to.owl.carousel", [a(this).index(), 500, !0]);
                })
                .on("changed.owl.carousel", function (a) {
                    s || ((s = !0), t.trigger("to.owl.carousel", [a.item.index, 500, !0]), (s = !1));
                });
    }
    function o() {
        a(".full-screen").css("height", a(window).height());
    }
    a(".filter-list").length && a(".filter-list").mixItUp({}),
        a(".count-box").length &&
            a(".count-box").appear(
                function () {
                    var e = a(this),
                        t = e.find(".count-text").attr("data-stop"),
                        n = parseInt(e.find(".count-text").attr("data-speed"), 10);
                    e.hasClass("counted") ||
                        (e.addClass("counted"),
                        a({ countNum: e.find(".count-text").text() }).animate(
                            { countNum: t },
                            {
                                duration: n,
                                easing: "linear",
                                step: function () {
                                    e.find(".count-text").text(Math.floor(this.countNum));
                                },
                                complete: function () {
                                    e.find(".count-text").text(this.countNum);
                                },
                            }
                        ));
                },
                { accY: 0 }
            ),
        a(".accordion-box").length &&
            a(".accordion-box").on("click", ".acc-btn", function () {
                var e = a(this).parents(".accordion-box"),
                    t = a(this).parents(".accordion");
                if ((!0 !== a(this).hasClass("active") && a(e).find(".accordion .acc-btn").removeClass("active "), a(this).next(".acc-content").is(":visible"))) return !1;
                a(this).addClass("active"),
                    a(e).children(".accordion").removeClass("active-block animated fadeInUp"),
                    a(e).find(".accordion").children(".acc-content").slideUp(300),
                    t.addClass("active-block animated fadeInUp"),
                    a(this).next(".acc-content").slideDown(300);
            }),
        a(".tabs-box").length &&
            a(".tabs-box .tab-buttons .tab-btn").on("click", function (e) {
                e.preventDefault();
                var t = a(a(this).attr("data-tab"));
                if (a(t).is(":visible")) return !1;
                t.parents(".tabs-box").find(".tab-buttons").find(".tab-btn").removeClass("active-btn"),
                    a(this).addClass("active-btn"),
                    t.parents(".tabs-box").find(".tabs-content").find(".tab").fadeOut(0),
                    t.parents(".tabs-box").find(".tabs-content").find(".tab").removeClass("active-tab animated fadeIn"),
                    a(t).fadeIn(300),
                    a(t).addClass("active-tab animated fadeIn");
            }),
        a(".time-countdown").length &&
            a(".time-countdown").each(function () {
                var e = a(this),
                    t = a(this).data("countdown");
                e.countdown(t, function (e) {
                    a(this).html(
                        e.strftime(
                            '<div class="counter-column"><span class="count">%D</span>Days</div> <div class="counter-column"><span class="count">%H</span>Hours</div>  <div class="counter-column"><span class="count">%M</span>Mints</div>  <div class="counter-column"><span class="count">%S</span>Sec</div>'
                        )
                    );
                });
            }),
        a(".lightbox-image").length && a(".lightbox-image").fancybox({ openEffect: "fade", closeEffect: "fade", helpers: { media: {} } }),
        a("#contact-form").length && a("#contact-form").validate({ rules: { username: { required: !0 }, email: { required: !0, email: !0 }, phone: { required: !0 }, message: { required: !0 } } }),
        o(),
        a(".sidenav-bar").length && a(".sidenav-bar").mCustomScrollbar({ theme: "dark" }),
        a(".price-range-slider").length &&
            (a(".price-range-slider").slider({
                range: !0,
                min: 0,
                max: 90,
                values: [8, 85],
                slide: function (e, t) {
                    a("input.property-amount").val(t.values[0] + " - " + t.values[1]);
                },
            }),
            a("input.property-amount").val(a(".price-range-slider").slider("values", 0) + " - $" + a(".price-range-slider").slider("values", 1))),
        a(".quantity-spinner").length && a("input.quantity-spinner").TouchSpin({ verticalbuttons: !0 }),
        a(".scroll-to-target").length &&
            a(".scroll-to-target").on("click", function () {
                var e = a(this).attr("data-target");
                a("html, body").animate({ scrollTop: a(e).offset().top }, 1500);
            }),
        a(window).on("resize", function () {
            o();
        }),
        a(window).on("scroll", function () {
            e(), o();
        }),
        a(window).on("load", function () {
            a(".preloader").length && a(".preloader").delay(200).fadeOut(500);
        });
})(window.jQuery);
