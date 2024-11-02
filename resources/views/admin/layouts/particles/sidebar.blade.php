<div class="aside-menu-divider mt-0"></div>

<div class="aside-nav-item">
    <a class="aside-nav-link {{ active_if_full_match('home') }}" href="{{route('home')}}">
        <span class="aside-nav-icon"><i class="fas fa-fw fa-tachometer-alt"></i></span>
        <span class="aside-nav-text">{{__('pages.dashboard')}}</span></a>
</div>

<div class="aside-menu-divider"></div>

<ul class="aside-nav-menu">

    <li class="aside-nav-item">
        <a class="aside-nav-link {{ active_if_full_match('business') }} {{ active_if_full_match('business/create') }} {{ active_if_full_match('business/*/edit') }} {{ active_if_full_match('business/*') }}" href="{{route('admin.business.index')}}"><span class="aside-nav-icon"><i class="fas fa-house"></i></span> <span class="aside-nav-text">Business</span></a>

    </li>

    {{-- @canany(['manage_category', 'manage_tax', 'manage_product', 'manage_unit'])
        <li class="aside-nav-heading"> {{__('pages.sells_marketing')}} </li>
        <li class="aside-nav-item toggleable-group">
            <a class="aside-nav-link toggler {{ active_if_match('product') }} {{ active_if_match('tax') }} {{ active_if_match('category') }} {{ active_if_match('unit') }}" href="javascript:void(0)">
                <span class="aside-nav-icon aside-tooltip" data-bs-placement="top" title="{{__('pages.manage_product')}}"><i class="fas fa-boxes"></i></span>
                <span class="aside-nav-text">{{__('pages.manage_product')}}</span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('product') }} {{ active_if_match('tax') }} {{ active_if_match('category') }} {{ active_if_match('unit') }}">
                <ul class="aside-submenu">
                    @can('manage_category')
                        <a class="aside-nav-link {{ active_if_full_match('category') }}" href="{{route('category.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span><span class="aside-nav-text">{{__('pages.categories')}}</span></a>
                    @endcan

                    @can('manage_unit')
                        <a class="aside-nav-link {{ active_if_full_match('unit') }}" href="{{route('unit.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span><span class="aside-nav-text">{{__('pages.units')}}</span></a>
                    @endcan

                    @can('manage_tax')
                        <a class="aside-nav-link {{ active_if_full_match('tax') }}" href="{{route('tax.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span><span class="aside-nav-text">{{__('pages.taxes')}}</span></a>
                    @endcan

                    @can('manage_product')
                            <a class="aside-nav-link {{ active_if_full_match('product') }} {{ active_if_full_match('product/*/edit') }} {{ active_if_full_match('product/*') }} {{ active_if_full_match('product/create') }} " href="{{route('product.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.products')}}</span></a>
                    @endcan
                </ul>
            </div>
        </li>
    @endcanany --}}

</ul>

