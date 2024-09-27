<!DOCTYPE html>
@if(session()->get('is_rtl_support') == 'yes')
<html dir="rtl" lang="{{app()->getLocale()}}">
@else
<html  lang="{{app()->getLocale()}}">
@endif
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" href="{{asset(get_option('app_fav_icon'))}}" type="image/gif" sizes="16x16">
    @include('backend.layouts.assets.css')

    @yield('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/helper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/main.css')}}">
    @stack('css')

</head>

<body id="page-top">


<div class="panel-wrapper">
    <aside class="wrapping-aside">
        <div class="wrapping-content">
            <div class="wrapping-content-header d-flex justify-content-between">
                <a href="{{route('home')}}" class="aside-brand">{{get_option('app_name')}}</a>
                <div>
                    <span class="btn btn-sm cursor text-white aside-toggler d-lg-none"><i class="bi bi-x-octagon"></i></span>
                </div>
            </div>
            <div class="wrapping-content-body ps-scrollbar">
                @include('backend.layouts.particles.sidebar')
            </div>
        </div>
    </aside>
    <main class="wrapping-body">
        <div class="main-navbar">
            @include('backend.layouts.particles.header')
        </div>
        <div class="main-body py-4">
            @yield('content')
        </div>
        <div class="main-footer">
            @include('backend.layouts.particles.footer')
        </div>

    </main>
</div>


{{--============== Script =================--}}
<script>
    function enableWrappingToggled() {
        var enableToggled = localStorage.getItem('wrappingEnable') === 'true';
        document.querySelector('.panel-wrapper').classList.toggle('wrapping-enable', enableToggled);
    }
    enableWrappingToggled();
</script>
@include('backend.layouts.assets.js')
{!! Toastr::message() !!}

@if(Session::has('message'))
    <input type="hidden" value="{{ Session::get('type') }}" id="toastrType">
    <input type="hidden" value="{{ Session::get('message') }}" id="toastrMessage">
    <script src="{{asset('/backend/js/custom-toastr.js')}}"></script>
@endif
@yield('js')
@stack('js')
<script>

    let isToggled = localStorage.getItem('wrappingEnable') === 'true';
    $(document).on('click', '.aside-toggler', function () {
        // Toggle the state
        isToggled = !isToggled;
        // Store the updated state in localStorage
        // localStorage.setItem('wrappingEnable', isToggled);
    });



    $(document).on('click', '.aside-toggler', function () {
        $('.panel-wrapper').stop().toggleClass('wrapping-enable');
    });


    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-aside-toggle="tooltip"]'))
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // function asideTooltip() {
    //     let width = $(window).width();
    //     if(width < 992) {
    //         let tooltipTriggered = [].slice.call(document.querySelectorAll('.aside-tooltip'))
    //         tooltipTriggered.map(function (tooltipTriggerEl) {
    //             return new bootstrap.Tooltip(tooltipTriggerEl)
    //         })
    //     }
    // }
    // asideTooltip();
    //
    // $('.wrapping-aside').on('resize', function () {
    //     asideTooltip()
    // })


    $(document).on('click', '.toggler', function (e) {
        e.preventDefault();
        $('.wrapping-aside').find('.toggleable-group').not($(this).parents('.toggleable-group')).find('.toggleable-menu').stop().slideUp(300).queue(function () {
            $(this).removeClass('show');
        });
        $('.wrapping-aside').find('.toggleable-group').not($(this).parents('.toggleable-group')).find('.toggler').removeClass('active');
        $(this).stop().toggleClass('active');
        $(this).parents('.toggleable-group').find('.toggleable-menu').stop().slideToggle(300).queue(function () {
            $(this).toggleClass('show').dequeue();
        });
    });
</script>
</body>

</html>
