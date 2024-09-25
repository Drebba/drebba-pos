<!-- Bootstrap core JavaScript-->
{{--<script src="{{asset('/backend/vendor/jquery/jquery.min.js')}}"></script>--}}
{{--<script src="{{asset('/backend/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
{{--<!-- Core plugin JavaScript-->--}}
{{--<script src="{{asset('/backend/vendor/jquery-easing/jquery.easing.min.js')}}"></script>--}}

{{--<!-- Custom scripts for all pages-->--}}
{{--<script src="{{asset('/backend/vendor/datepicker/bootstrap-datepicker.js')}}"></script>--}}
{{--<script src="{{asset('/backend/vendor/select2/select2.full.js')}}"></script>--}}
{{--<script src="{{asset('/backend/vendor/parsley.js/dist/parsley.min.js')}}"></script>--}}
{{--<script src="{{ asset('/backend/vendor/datatables/jquery.dataTables.js') }}"></script>--}}
{{--<script src="{{ asset('/backend/vendor/sweetalert/sweetalert.min.js') }}"></script>--}}
{{--<script src="{{ asset('/backend/js/toastr.min.js') }}"></script>--}}
{{--<script src="{{ asset('backend/js/app.js') }}"></script>--}}
{{--<script src="{{asset('backend/js/script.js')}}"></script>--}}



<script type="text/javascript" src="{{asset('admin/plugin/jquery/jquery-3.7.0.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/plugin/popper/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/plugin/bootstrap5/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/plugin/ps-scrollbar/dist/perfect-scrollbar.min.js')}}"></script>

<script>
    // new PerfectScrollbar('.ps-scrollbar');


    // ======== Perfect Scrollbar ================
    let scrollList = document.querySelectorAll('.ps-scrollbar')
    //
    let scrollArray = [...scrollList];

    if (scrollArray) {
        scrollArray.forEach((element) => {
            const ps = new PerfectScrollbar(element, {
                handlers: ['click-rail', 'drag-thumb', 'keyboard', 'wheel', 'touch'],
                scrollingThreshold: 1000,
                wheelSpeed: 1,
                wheelPropagation: false,
                minScrollbarLength: null,
                maxScrollbarLength: null,
                useBothWheelAxes: false,
                suppressScrollX: false,
                suppressScrollY: false,
                swipeEasing: true,
                scrollXMarginOffset: 30,
                scrollYMarginOffset: 0
            });

            ps.update();
            // ps.on('mouseenter', function() {
            //     ps.update();
            // });
        });
    }
</script>


{{--<!-- Custom scripts for all pages-->--}}
<script src="{{ asset('/backend/vendor/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('/backend/js/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/app.js') }}"></script>
<script>
    (function($) {
        "use strict"; // Start of use strict

        // Toggle the side navigation
        $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
            $("body").toggleClass("sidebar-toggled");
            $(".sidebar").toggleClass("toggled");
            if ($(".sidebar").hasClass("toggled")) {
                $('.sidebar .collapse').collapse('hide');
            };
        });

        // Close any open menu accordions when window is resized below 768px
        $(window).resize(function() {
            if ($(window).width() < 768) {
                $('.sidebar .collapse').collapse('hide');
            };
        });

        // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
        $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
            if ($(window).width() > 768) {
                var e0 = e.originalEvent,
                    delta = e0.wheelDelta || -e0.detail;
                this.scrollTop += (delta < 0 ? 1 : -1) * 30;
                e.preventDefault();
            }
        });

        // Scroll to top button appear
        $(document).on('scroll', function() {
            var scrollDistance = $(this).scrollTop();
            if (scrollDistance > 100) {
                $('.scroll-to-top').fadeIn();
            } else {
                $('.scroll-to-top').fadeOut();
            }
        });


        // Smooth scrolling using jQuery easing
        $(document).on('click', 'a.scroll-to-top', function(e) {
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: ($($anchor.attr('href')).offset().top)
            }, 1000, 'easeInOutExpo');
            e.preventDefault();
        });

        if ($('.select2').length > 0) {
            $('.select2').select2();
        }

        // if ($('.datepicker').length > 0) {
        //     $('.datepicker').datepicker({
        //         autoclose: true,
        //     })
        // }

        $('.sluggable').on('input', function (e) {
            e.preventDefault();
            var target = $(this).data('slug');
            if (target) {
                $(target).val($(this).val().toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-'));
            }
        });

        $.fn.confirmDelete = function (formId) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data again!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    formId.submit();
                }
            });
        };

        $.fn.confirm = function (url) {
            swal({
                title: "Are you sure?",
                text: "To change status",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    window.location.replace(url);
                }
            });
        };


        $.fn.confirmRestore = function (formId) {
            swal({
                title: "Are you sure?",
                text: "You want to restore this item!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    formId.submit();
                }
            });
        };

        if(window.matchMedia("(max-width: 767px)").matches){
            $("body").toggleClass("sidebar-toggled");
            $("#accordionSidebar").toggleClass("toggled");
        }
    })(jQuery); // End of use strict

</script>



{{--======== Custom Js ==============--}}
<script type="text/javascript" src="{{asset('admin/js/main.js')}}"></script>



