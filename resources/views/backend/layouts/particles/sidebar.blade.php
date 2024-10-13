<div class="aside-menu-divider mt-0"></div>

<div class="aside-nav-item">
    <a class="aside-nav-link {{ active_if_full_match('home') }}" href="{{route('home')}}">
        <span class="aside-nav-icon"><i class="fas fa-fw fa-tachometer-alt"></i></span>
        <span class="aside-nav-text">{{__('pages.dashboard')}}</span></a>
</div>

<div class="aside-menu-divider"></div>

<ul class="aside-nav-menu">

    {{-- @canany(['do:anything'])
    <li class="aside-nav-heading"> {{__('pages.Business')}} </li>
    <li class="aside-nav-item toggleable-group">
        <a class="aside-nav-link toggler {{ active_if_match('businesses') }} " href="javascript:void(0)">
            <span class="aside-nav-icon aside-tooltip" data-bs-placement="top" title="{{__('pages.manage_business')}}"><i class="fas fa-boxes"></i></span>
            <span class="aside-nav-text">Business</span>
            <span class="aside-nav-dropdown-icon"></span>
        </a>
        <div class="aside-dropdown toggleable-menu {{ active_if_match('businesses') }}">
            <ul class="aside-submenu">
                @can('do:anything')
                    <a class="aside-nav-link {{ active_if_full_match('category') }}" href="{{route('businesses.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span><span class="aside-nav-text">Business</span></a>
                @endcan

            </ul>
        </div>
    </li>
    @endcan --}}


    @canany(['manage_category', 'manage_tax', 'manage_product', 'manage_unit'])
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
    @endcanany

    @canany(['manage_sell_invoice'])
        <li class="aside-nav-item toggleable-group">
            <a class="aside-nav-link toggler {{ active_if_match('sell') }}" href="javascript:void(0)">
                <span class="aside-nav-icon"><i class="fas fa-list"></i></span>
                <span class="aside-nav-text">{{__('pages.manage_sell')}}</span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('sell') }}">
                <ul class="aside-submenu">
                    @can('create_sell_invoice')
                        <a class="aside-nav-link {{ active_if_full_match('sell/create') }}" href="{{route('sell.create')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.create_invoice')}}</span></a>
                    @endcan
                    @can('manage_sell_invoice')
                        <a class="aside-nav-link {{ active_if_full_match('sell') }} {{ active_if_full_match('sell/*/edit') }} {{ active_if_full_match('sell/*') }}" href="{{route('sell.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.sell_invoice')}}</span></a>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan

    @canany(['create_purchase_invoice', 'manage_purchase_invoice','manage_requisition'])
        <li class="aside-nav-item toggleable-group">
            <a class="aside-nav-link toggler {{ active_if_match('purchase') }} {{ active_if_match('requisition') }} {{ active_if_match('pending-requisition') }}" href="javascript:void(0)">
                <span class="aside-nav-icon"><i class="fas fa-store"></i></span>
                <span class="aside-nav-text">{{__('pages.manage_stock')}}</span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('purchase') }} {{ active_if_match('requisition') }} {{ active_if_match('pending-requisition') }}">
                <ul class="aside-submenu">
                    @can('create_purchase_invoice')
                        <a class="aside-nav-link {{ active_if_full_match('purchase/create') }}" href="{{route('purchase.create')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.create_purchase')}}</span></a>
                    @endcan
                    @can('manage_purchase_invoice')
                        <a class="aside-nav-link {{ active_if_full_match('purchase') }} {{ active_if_full_match('purchase/*/edit') }} {{ active_if_full_match('purchase/*') }}" href="{{route('purchase.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.purchase_invoices')}}</span></a>
                    @endcan
                    {{-- @can('manage_requisition')
                        <a class="aside-nav-link {{ active_if_full_match('requisition/create') }}" href="{{route('requisition.create')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.create_requisition')}}</span></a>
                        <a class="aside-nav-link {{ active_if_full_match('pending-requisition') }}" href="{{route('pending-requisition')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.pending_requisition')}} ({{pendingRequisition()}})</span></a>
                        <a class="aside-nav-link {{ active_if_full_match('requisition') }} {{ active_if_full_match('requisition/*/edit') }} {{ active_if_full_match('requisition/*') }} {{ active_if_full_match('requisition-filter') }} " href="{{route('requisition.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.manage_requisition')}}</span></a>
                    @endcan --}}
                </ul>
            </div>
        </li>
    @endcan


    @canany(['manage_expense', 'manage_expense_category'])
        <li class="aside-nav-item toggleable-group">
            <a class="aside-nav-link toggler {{ active_if_match('expense') }} {{ active_if_match('expense-category') }}" href="javascript:void(0)">
                <span class="aside-nav-icon"><i class="fas fa-money-bill"></i></span>
                <span class="aside-nav-text">{{__('pages.expense')}}</span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('expense') }} {{ active_if_match('expense-category') }}">
                <div class="aside-submenu">
                    @can('manage_expense_category')
                        <a class="aside-nav-link {{ active_if_full_match('expense-category') }}" href="{{route('expense-category.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.expense_category')}}</span></a>
                    @endcan

                    @can('manage_expense')
                        <a class="aside-nav-link {{ active_if_full_match('expense/create') }}" href="{{route('expense.create')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.create_expense')}}</span></a>
                        <a class="aside-nav-link {{ active_if_full_match('expense') }} {{ active_if_full_match('expense/*/edit') }} {{ active_if_full_match('expense-filter*') }}" href="{{route('expense.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.expense_list')}}</span></a>
                    @endcan
                </div>
            </div>
        </li>
    @endcan

    @canany(['manage_supplier_payment', 'manage_customer_payment'])
        <li class="aside-nav-item toggleable-group">
            <a class="aside-nav-link toggler {{ active_if_match('payment') }}" href="javascript:void(0)">
                <span class="aside-nav-icon"><i class="fas fa-money-bill"></i></span>
                <span class="aside-nav-text">{{__('pages.payment')}}</span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('payment') }}">
                <div class="aside-submenu">
                    @can('manage_supplier_payment')
                        <a class="aside-nav-link {{ active_if_full_match('payment-to-supplier-filter') }} {{ active_if_full_match('payment-to-supplier') }} {{ active_if_full_match('payment-to-supplier/create') }} {{ active_if_full_match('payment-to-supplier/*/edit') }}" href="{{route('payment-to-supplier.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.payment_to_supplier')}}</span></a>
                    @endcan

                    @can('manage_customer_payment')
                        <a class="aside-nav-link {{ active_if_full_match('payment-from-customer') }} {{ active_if_full_match('payment-from-customer/create') }} {{ active_if_full_match('payment-from-customer/*/edit') }} {{ active_if_full_match('payment-from-customer-filter') }}" href="{{route('payment-from-customer.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">Pending Payment From Customer</span></a>
                    @endcan
                </div>
            </div>
        </li>
    @endcan



    @canany(['manage_customer', 'manage_supplier','manage_department', 'manage_designation','manage_employee','manage_branch', 'manage_sells_target'])
        <div class="aside-menu-divider"></div>
        <li class="aside-nav-heading"> {{__('pages.hr_department')}} </li>
    @endcan


    @canany(['manage_department', 'manage_designation','manage_employee'])
        <li class="aside-nav-item toggleable-group">
            <a class="aside-nav-link toggler {{ active_if_match('department') }} {{ active_if_match('employee') }} {{ active_if_match('designation') }}" href="javascript:void(0)" data-toggle="collapse" data-target="#employee" aria-expanded="true" aria-controls="user">
                <span class="aside-nav-icon"><i class="fas fa-users"></i></span>
                <span class="aside-nav-text">{{__('pages.manage_employees')}}</span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('department') }} {{ active_if_match('employee') }} {{ active_if_match('designation') }}" aria-labelledby="user" data-parent="#accordionSidebar">
                <div class="aside-submenu">
                    @can('manage_department')
                        <a class="aside-nav-link {{ active_if_full_match('department') }}" href="{{route('department.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.departments')}} </span></a>
                    @endcan

                    @can('manage_designation')
                        <a class="aside-nav-link {{ active_if_full_match('designation') }}" href="{{route('designation.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.designations')}}</span></a>
                    @endcan

                    @can('manage_employee')
                        <a class="aside-nav-link {{ active_if_full_match('employee') }} {{ active_if_full_match('employee/*/edit') }} {{ active_if_full_match('employee/*') }}" href="{{route('employee.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.employees')}}</span></a>
                    @endcan
                </div>
            </div>
        </li>
    @endcan

    @canany(['manage_customer', 'manage_supplier'])
        <li class="aside-nav-item toggleable-group">
            <a class="aside-nav-link toggler {{ active_if_match('customer') }} {{ active_if_match('supplier') }}" href="javascript:void(0)" data-toggle="collapse" data-target="#crm" aria-expanded="true" aria-controls="crm">
                <span class="aside-nav-icon"><i class="fas fa-user-secret"></i></span>
                <span class="aside-nav-text">{{__('pages.crm')}}</span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('customer') }} {{ active_if_match('supplier') }}">
                <div class="aside-submenu">
                    @can('manage_customer')
                        <a class="aside-nav-link {{ active_if_full_match('customer') }} {{ active_if_full_match('customer/create') }} {{ active_if_full_match('customer/*/edit') }} {{ active_if_full_match('customer/*') }}" href="{{route('customer.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.manage_customers')}}</span></a>
                    @endcan

                    @can('manage_supplier')
                        <a class="aside-nav-link {{ active_if_full_match('supplier') }} {{ active_if_full_match('supplier/create') }} {{ active_if_full_match('supplier/*/edit') }} {{ active_if_full_match('supplier/*') }}" href="{{route('supplier.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.manage_suppliers')}}</span></a>
                    @endcan
                </div>
            </div>
        </li>
    @endcan



    @canany(['view_sells_report', 'view_purchase_report', 'view_stock', 'view_profit_loss'])
        <div class="aside-menu-divider"></div>
        <li class="aside-nav-heading"> {{__('pages.reports')}} </li>
    @endcan

    @can('view_sells_report')
        <li class="aside-nav-item toggleable-group {{ active_if_full_match('report/sell/*') }}" >
            <a class="aside-nav-link toggler {{ active_if_match('report/sell/') }}" href="javascript:void(0)">
                <span class="aside-nav-icon"><i class="fas fa-user-secret"></i></span>
                <span class="aside-nav-text">{{__('pages.sells_report')}}</span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('report/sell/') }}">
                <div class="aside-submenu">
                    <a href="{{url('report/sell/summary')}}" class="aside-nav-link {{ active_if_full_match('report/sell/summary') }} {{ active_if_full_match('report/sell/summary-filter') }}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.sales_summary')}}</span> </a>
                    <a href="{{url('report/sell/statistics')}}" class="aside-nav-link {{ active_if_full_match('report/sell/statistics') }} {{ active_if_full_match('report/sell/statistics-filter') }}  {{ active_if_full_match('report/sell/statistics/last/*/days') }}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.sales_statistics')}}</span>  </a>
                    <a href="{{url('report/sell/product-wise')}}" class="aside-nav-link {{ active_if_full_match('report/sell/product-wise') }} {{ active_if_full_match('report/sell/product-wise-filter') }}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.product_wise_sales')}}</span>  </a>
                    <a href="{{url('report/sell/sells')}}" class="aside-nav-link {{ active_if_full_match('report/sell/sells') }} {{ active_if_full_match('report/sell/sells-filter-result') }}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span><span class="aside-nav-text">{{__('pages.all')}} {{__('pages.sells')}}</span></a>
                </div>
            </div>
        </li>
    @endcan

    @can('view_purchase_report')
        <li class="aside-nav-item toggleable-group {{ active_if_full_match('report/purchase/*') }}" >
            <a class="aside-nav-link toggler {{ active_if_match('report/purchase/') }}" href="javascript:void(0)" data-toggle="collapse" data-target="#purchaseReport" aria-expanded="true" aria-controls="salesReport">
                <span class="aside-nav-icon"><i class="fas fa-user-secret"></i></span>
                <span class="aside-nav-text">{{__('pages.purchase_reports')}}</span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('report/purchase/') }}">
                <div class="aside-submenu">
                    <a href="{{url('report/purchase/summary')}}" class="aside-nav-link {{ active_if_full_match('report/purchase/summary') }} {{ active_if_full_match('report/purchase/summary-filter') }}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span>  <span class="aside-nav-text">{{__('pages.purchase_summary')}}</span> </a>
                    <a href="{{url('report/purchase/statistics')}}" class="aside-nav-link {{ active_if_full_match('report/purchase/statistics') }} {{ active_if_full_match('report/purchase/statistics-filter') }}  {{ active_if_full_match('report/purchase/statistics/last/*/days') }}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.purchase_statistics')}}</span>   </a>
                    <a href="{{url('report/purchase/product-wise')}}" class="aside-nav-link {{ active_if_full_match('report/purchase/product-wise') }} {{ active_if_full_match('report/purchase/product-wise-filter') }}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span>   <span class="aside-nav-text">{{__('pages.product_wise_purchase')}}</span> </a>
                    <a href="{{url('report/purchase/purchases')}}" class="aside-nav-link {{ active_if_full_match('report/purchase/purchases') }} {{ active_if_full_match('report/purchase/purchases-filter-result') }}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.all')}} {{__('pages.purchase')}}</span></a>
                </div>
            </div>
        </li>
    @endcan

    @can('view_stock')
        <li class="aside-nav-item">
            <a class="aside-nav-link {{ active_if_full_match('report/stock-report') }}" href="{{url('report/stock-report')}}">
                <span class="aside-nav-icon"><i class="fas fa-chart-line"></i></span>
                <span class="aside-nav-text">{{__('pages.stock_report')}}</span>
            </a>
        </li>
    @endcan


    @can('view_profit_loss')
        <li class="aside-nav-item">
            <a class="aside-nav-link {{ active_if_full_match('report/profit-loss') }}" href="{{route('profitLoss')}}">
                <span class="aside-nav-icon"><i class="fas fa-chart-line"></i></span>
                <span class="aside-nav-text">{{__('pages.profit_loss_report')}}</span>
            </a>
        </li>
    @endcan

{{--
    @canany(['application_setting', 'manage_user'])
        <div class="aside-menu-divider"></div>
        <li class="aside-nav-heading"> {{__('pages.miscellaneous')}} </li>
    @endcan

    @can('manage_notice')
        <li class="aside-nav-item toggleable-group">
            <a class="aside-nav-link toggler {{ active_if_match('notice') }}" href="javascript:void(0)" data-toggle="collapse" data-target="#notice" aria-expanded="true" aria-controls="sells">
                <span class="aside-nav-icon"><i class="fas fa-bell fa-fw"></i></span>
                <span class="aside-nav-text">{{__('pages.notice')}} </span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('notice') }}">
                <div class="aside-submenu">
                    <a class="aside-nav-link {{ active_if_full_match('notice/create') }}" href="{{route('notice.create')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.create')}} {{__('pages.notice')}}</span></a>

                    <a class="aside-nav-link {{ active_if_full_match('notice') }}" href="{{route('notice.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span>  <span class="aside-nav-text">{{__('pages.manage')}} {{__('pages.notice')}}</span> </a>
                </div>
            </div>
        </li>
    @endcan

    @can('manage_todo')
        <li class="aside-nav-item">
            <a class="aside-nav-link" href="{{route('todo.index')}}">
                <span class="aside-nav-icon"><i class="fas fa-list-ul"></i></span> <span class="aside-nav-text">{{__('pages.todo')}}</span>
            </a>
        </li>
    @endcan --}}


    @canany(['application_setting', 'manage_user'])
        <div class="aside-menu-divider"></div>
        <li class="aside-nav-heading"> {{__('pages.administrative')}} </li>
    @endcan


    @can('manage_user')
        <li class="aside-nav-item toggleable-group">
            <a class="aside-nav-link toggler {{ active_if_match('role') }} {{ active_if_match('settings/user/permission') }}" href="javascript:void(0)" data-toggle="collapse" data-target="#role" aria-expanded="true" aria-controls="sells">
                <span class="aside-nav-icon"><i class="fas fa-key"></i></span>
                <span class="aside-nav-text">{{__('pages.role_and_permission')}} </span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('role') }} {{ active_if_match('settings/user/permission') }}">
                <div class="aside-submenu">
                    <a class="aside-nav-link {{ active_if_full_match('role') }}" href="{{route('role.index')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span><span class="aside-nav-text">{{__('pages.roles')}}</span></a>

                    <a class="aside-nav-link {{ active_if_full_match('settings/user/permission') }}" href="{{route('userPermission')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.permission')}}</span> </a>
                </div>
            </div>
        </li>
    @endcan

    @can('application_setting')
        <li class="aside-nav-item">
            <a class="aside-nav-link" href="{{route('general-settings')}}">
                <span class="aside-nav-icon"><i class="fas fa-cogs"></i></span> <span class="aside-nav-text">{{__('pages.application_settings')}}</span>
            </a>
        </li>
    @endcan


    {{-- @can('application_setting')
        <li class="aside-nav-item">
            <a class="aside-nav-link" href="{{route('language.index')}}">
                <span class="aside-nav-icon"><i class="fas fa-globe-europe"></i></span> <span class="aside-nav-text">{{__('pages.language_settings')}}</span>
            </a>
        </li>
    @endcan --}}

    {{-- @can('manage_trash')
        <li class="aside-nav-item toggleable-group">
            <a class="aside-nav-link toggler {{ active_if_match('trash/category') }} {{ active_if_match('trash/tax') }} {{ active_if_match('trash/branch') }} {{ active_if_match('trash/designation') }}  {{ active_if_match('trash/department') }} {{ active_if_match('trash/expense-category') }}" href="javascript:void(0)" data-toggle="collapse" data-target="#trash" aria-expanded="true" aria-controls="sells">
                <span class="aside-nav-icon"><i class="fas fa-trash-restore"></i></span>
                <span class="aside-nav-text">{{__('pages.trash')}} </span>
                <span class="aside-nav-dropdown-icon"></span>
            </a>
            <div class="aside-dropdown toggleable-menu {{ active_if_match('trash/category') }} {{ active_if_match('trash/tax') }} {{ active_if_match('trash/branch') }} {{ active_if_match('trash/designation') }}  {{ active_if_match('trash/department') }} {{ active_if_match('trash/expense-category') }}">
                <div class="aside-submenu">
                    @can('manage_category')
                        <a class="aside-nav-link {{ active_if_full_match('trash/category') }}" href="{{route('category-trash')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.categories')}}</span></a>
                    @endcan

                    @can('manage_tax')
                        <a class="aside-nav-link {{ active_if_full_match('trash/tax') }}" href="{{route('tax-trash')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.taxes')}}</span></a>
                    @endcan

                    @can('manage_expense_category')
                        <a class="aside-nav-link {{ active_if_full_match('trash/expense-category') }}" href="{{route('expense-category-trash')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.expense_category')}}</span></a>
                    @endcan

                    @can('manage_department')
                        <a class="aside-nav-link {{ active_if_full_match('trash/department-trash') }}" href="{{route('department-trash')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.departments')}}</span> </a>
                    @endcan

                    @can('manage_designation')
                        <a class="aside-nav-link {{ active_if_full_match('trash/designation-trash') }}" href="{{route('designation-trash')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.designations')}}</span></a>
                    @endcan

                    @can('manage_branch')
                        <a class="aside-nav-link {{ active_if_full_match('trash/branch-trash') }}" href="{{route('branch-trash')}}"><span class="aside-nav-icon"><i class="bi bi-circle"></i></span> <span class="aside-nav-text">{{__('pages.branch')}}</span></a>
                    @endcan
                </div>
            </div>
        </li>
    @endcan --}}
</ul>

