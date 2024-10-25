<div class="btn-group btn-group-justified nav-buttons custom-btn-group" role="group" aria-label="Basic example">
    <a href="{{route('profile')}}" class="btn btn-outline-primary {{ active_if_full_match('settings/profile') }}"><i class="fas fa-user me-1"></i> {{__('pages.profile_settings')}}</a>
    <a href="{{route('password')}}" class="btn btn-outline-primary {{ active_if_full_match('settings/password') }}"><i class="fas fa-key me-1"></i>{{__('pages.change_password')}} </a>
    {{-- <a href="{{route('change-email')}}" class="btn btn-outline-primary {{ active_if_full_match('settings/change-email') }}"><i class="fa fa-envelope me-1"></i> {{__('pages.change_login_email')}} </a> --}}
</div>
