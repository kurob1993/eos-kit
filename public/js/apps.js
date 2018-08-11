var handleSlimScroll = function() {
        "use strict";
        $("[data-scrollbar=true]").each(function() {
            generateSlimScroll($(this))
        })
    },
    generateSlimScroll = function(e) {
        var a = $(e).attr("data-height");
        a = a ? a : $(e).height();
        var t = {
            height: a,
            alwaysVisible: !0
        };
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? ($(e).css("height", a), $(e).css("overflow-x", "scroll")) : $(e).slimScroll(t)
    },
    handleSidebarMenu = function() {
        "use strict";
        $(".sidebar .nav > .has-sub > a").click(function() {
            var e = $(this).next(".sub-menu"),
                a = ".sidebar .nav > li.has-sub > .sub-menu";
            0 === $(".page-sidebar-minified").length && ($(a).not(e).slideUp(250, function() {
                $(this).closest("li").removeClass("expand")
            }), $(e).slideToggle(250, function() {
                var e = $(this).closest("li");
                $(e).hasClass("expand") ? $(e).removeClass("expand") : $(e).addClass("expand")
            }))
        }), $(".sidebar .nav > .has-sub .sub-menu li.has-sub > a").click(function() {
            if (0 === $(".page-sidebar-minified").length) {
                var e = $(this).next(".sub-menu");
                $(e).slideToggle(250)
            }
        })
    },
    handleMobileSidebarToggle = function() {
        var e = !1;
        $(".sidebar").on("click touchstart", function(a) {
            0 !== $(a.target).closest(".sidebar").length ? e = !0 : (e = !1, a.stopPropagation())
        }), $(document).on("click touchstart", function(a) {
            0 === $(a.target).closest(".sidebar").length && (e = !1), a.isPropagationStopped() || e === !0 || ($("#page-container").hasClass("page-sidebar-toggled") && (e = !0, $("#page-container").removeClass("page-sidebar-toggled")), $("#page-container").hasClass("page-right-sidebar-toggled") && (e = !0, $("#page-container").removeClass("page-right-sidebar-toggled")))
        }), $("[data-click=right-sidebar-toggled]").click(function(a) {
            a.stopPropagation();
            var t = "#page-container",
                i = "page-right-sidebar-collapsed";
            i = $(window).width() < 979 ? "page-right-sidebar-toggled" : i, $(t).hasClass(i) ? $(t).removeClass(i) : e !== !0 ? $(t).addClass(i) : e = !1, $(window).width() < 480 && $("#page-container").removeClass("page-sidebar-toggled")
        }), $("[data-click=sidebar-toggled]").click(function(a) {
            a.stopPropagation();
            var t = "page-sidebar-toggled",
                i = "#page-container";
            $(i).hasClass(t) ? $(i).removeClass(t) : e !== !0 ? $(i).addClass(t) : e = !1, $(window).width() < 480 && $("#page-container").removeClass("page-right-sidebar-toggled")
        })
    },
    handlePageContentView = function() {
        "use strict";
        $.when($("#page-loader").addClass("hide")).done(function() {
            $("#page-container").addClass("in")
        })
    },
    handelTooltipPopoverActivation = function() {
        "use strict";
        $("[data-toggle=tooltip]").tooltip(), $("[data-toggle=popover]").popover()
    },
    handleScrollToTopButton = function() {
        "use strict";
        $(document).scroll(function() {
            var e = $(document).scrollTop();
            e >= 200 ? $("[data-click=scroll-top]").addClass("in") : $("[data-click=scroll-top]").removeClass("in")
        }), $("[data-click=scroll-top]").click(function(e) {
            e.preventDefault(), $("html, body").animate({
                scrollTop: $("body").offset().top
            }, 500)
        })
    },
    handleAfterPageLoadAddClass = function() {
        0 !== $("[data-pageload-addclass]").length && $(window).load(function() {
            $("[data-pageload-addclass]").each(function() {
                var e = $(this).attr("data-pageload-addclass");
                $(this).addClass(e)
            })
        })
    },
    handleIEFullHeightContent = function() {
        var e = window.navigator.userAgent,
            a = e.indexOf("MSIE ");
        (a > 0 || navigator.userAgent.match(/Trident.*rv\:11\./)) && $('.vertical-box-row [data-scrollbar="true"][data-height="100%"]').each(function() {
            var e = $(this).closest(".vertical-box-row"),
                a = $(e).height();
            $(e).find(".vertical-box-cell").height(a)
        })
    },
    handleUnlimitedTabsRender = function() {
        function e(e, a) {
            var t = (parseInt($(e).css("margin-left")), $(e).width()),
                i = $(e).find("li.active").width(),
                n = a > -1 ? a : 150,
                o = 0;
            if ($(e).find("li.active").prevAll().each(function() {
                    i += $(this).width()
                }), $(e).find("li").each(function() {
                    o += $(this).width()
                }), i >= t) {
                var l = i - t;
                o != i && (l += 40), $(e).find(".nav.nav-tabs").animate({
                    marginLeft: "-" + l + "px"
                }, n)
            }
            i != o && o >= t ? $(e).addClass("overflow-right") : $(e).removeClass("overflow-right"), i >= t && o >= t ? $(e).addClass("overflow-left") : $(e).removeClass("overflow-left")
        }

        function a(e, a) {
            var t = $(e).closest(".tab-overflow"),
                i = parseInt($(t).find(".nav.nav-tabs").css("margin-left")),
                n = $(t).width(),
                o = 0,
                l = 0;
            switch ($(t).find("li").each(function() {
                $(this).hasClass("next-button") || $(this).hasClass("prev-button") || (o += $(this).width())
            }), a) {
                case "next":
                    var s = o + i - n;
                    n >= s ? (l = s - i, setTimeout(function() {
                        $(t).removeClass("overflow-right")
                    }, 150)) : l = n - i - 80, 0 != l && $(t).find(".nav.nav-tabs").animate({
                        marginLeft: "-" + l + "px"
                    }, 150, function() {
                        $(t).addClass("overflow-left")
                    });
                    break;
                case "prev":
                    var s = -i;
                    n >= s ? ($(t).removeClass("overflow-left"), l = 0) : l = s - n + 80, $(t).find(".nav.nav-tabs").animate({
                        marginLeft: "-" + l + "px"
                    }, 150, function() {
                        $(t).addClass("overflow-right")
                    })
            }
        }

        function t() {
            $(".tab-overflow").each(function() {
                var a = $(this).width(),
                    t = 0,
                    i = $(this),
                    n = a;
                $(i).find("li").each(function() {
                    var e = $(this);
                    t += $(e).width(), $(e).hasClass("active") && t > a && (n -= t)
                }), e(this, 0)
            })
        }
        $('[data-click="next-tab"]').live("click", function(e) {
            e.preventDefault(), a(this, "next")
        }), $('[data-click="prev-tab"]').live("click", function(e) {
            e.preventDefault(), a(this, "prev")
        }), $(window).resize(function() {
            $(".tab-overflow .nav.nav-tabs").removeAttr("style"), t()
        }), t()
    },
    handleMobileSidebar = function() {
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && $("#page-container").hasClass("page-sidebar-minified") && ($('#sidebar [data-scrollbar="true"]').css("overflow", "visible"), $('.page-sidebar-minified #sidebar [data-scrollbar="true"]').slimScroll({
            destroy: !0
        }), $('.page-sidebar-minified #sidebar [data-scrollbar="true"]').removeAttr("style"), $(".page-sidebar-minified #sidebar [data-scrollbar=true]").trigger("mouseover"));
        var e = 0;
        $(".page-sidebar-minified .sidebar [data-scrollbar=true] a").live("touchstart", function(a) {
            var t = a.originalEvent.touches[0] || a.originalEvent.changedTouches[0],
                i = t.pageY;
            e = i - parseInt($(this).closest("[data-scrollbar=true]").css("margin-top"))
        }), $(".page-sidebar-minified .sidebar [data-scrollbar=true] a").live("touchmove", function(a) {
            if (a.preventDefault(), /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                var t = a.originalEvent.touches[0] || a.originalEvent.changedTouches[0],
                    i = t.pageY,
                    n = i - e;
                $(this).closest("[data-scrollbar=true]").css("margin-top", n + "px")
            }
        }), $(".page-sidebar-minified .sidebar [data-scrollbar=true] a").live("touchend", function() {
            var a = $(this).closest("[data-scrollbar=true]"),
                t = $(window).height();
            e = $(a).css("margin-top");
            var i = 0;
            $(".sidebar").find(".nav").each(function() {
                i += $(this).height()
            });
            var n = -parseInt(e) + $(".sidebar").height();
            if (n >= i) {
                var o = t - i;
                $(a).animate({
                    marginTop: o + "px"
                })
            } else parseInt(e) >= 0 && $(a).animate({
                marginTop: "0px"
            });
            return !0
        })
    },
    App = function() {
        "use strict";
        return {
            init: function() {
                handleSlimScroll(), 
                handleSidebarMenu(), 
                handleMobileSidebarToggle(), 
                handleMobileSidebar(), 
                handleAfterPageLoadAddClass(), 
                handelTooltipPopoverActivation(), 
                handleScrollToTopButton(), 
                handlePageContentView(), 
                handleIEFullHeightContent(), 
                handleUnlimitedTabsRender()
            }
        }
    }();
