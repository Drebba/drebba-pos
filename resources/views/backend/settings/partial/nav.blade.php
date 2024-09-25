<div class="d-none d-lg-block btn-group btn-group-justified nav-buttons mb-4 custom-btn-group" role="group" aria-label="Basic example">
    <a href="{{route('general-settings')}}" class="btn btn-brand btn-outline-brand-primary {{ active_if_full_match('settings/general') }}"><i class="fas fa-store-alt me-2"></i> {{__('pages.general_settings')}}</a>
    <a href="{{route('currency-settings')}}" class="btn btn-brand btn-outline-brand-primary {{ active_if_full_match('settings/currency') }}"><i class="bi bi-currency-exchange me-2"></i>{{__('pages.currency')}}  </a>
    <a href="{{route('prefix-settings')}}" class="btn btn-brand btn-outline-brand-primary {{ active_if_full_match('settings/prefix') }}"><i class="fab fa-autoprefixer me-2"></i> {{__('pages.prefix')}} </a>
</div>
