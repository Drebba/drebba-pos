<!-- Topbar -->
<nav class="navbar px-3 navbar-expand-xl navbar-light bg-white main-nav-header @if(active_if_full_match('admin/purchase/create') == 'active' || active_if_full_match('admin/purchase/*/edit') == 'active' || active_if_full_match('admin/sell/create') == 'active' || active_if_full_match('admin/sell/*/edit') == 'active' || active_if_full_match('admin/requisition/create') == 'active' || active_if_full_match('admin/requisition/*/edit') == 'active') @else @endif  static-top border-bottom-primary-slim">

        <!-- Sidebar Toggle (Topbar) -->
        <div class="me-2 me-lg-4">
            <button type="button" class="btn btn-sm aside-toggler px-2">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ms-auto order-lg-last flex-row">
            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown px-2 px-xl-0">
                <a class="nav-link" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="countable-group">
                    <i class="fas fa-bell fa-fw"></i>
                    <small class="badge-counter">{{notifications()->count()}}</small>
                </span>
                    <!-- Counter - Alerts -->
                </a>

                @if(notifications()->count() > 0)
                <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu scroll-bar notification-mb-pos" aria-labelledby="alertsDropdown">
                    <div class="dropdown-content">
                        <div class="dropdown-header">
                            <h5 class="dropdown-title">Notifications</h5>
                            <span><i class="bi bi-bell"></i></span>
                        </div>
                        <div class="dropdown-body">
                            <a class="dropdown-item dropdown-notification-item" href="#">
                                <div class="me-3">
                                    <div class="dropdown-icon-circle bg-soft-success">
                                        <i class="fas fa-bell fa-fw text-success"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="dropdown-notification-details">This is notification message</div>
                                    <time class="dropdown-time">2 Hour 15 minuit ago</time>
                                </div>
                            </a>
                            @foreach(notifications() as $notification)
                                <a class="dropdown-item custom-dropdown-item" href="{{route('notification', $notification->id)}}">
                                    <div class="me-3">
                                        @if($notification->type == 1)
                                        <div class="dropdown-icon-circle bg-soft-success">
                                            <i class="bi bi-shop-window"></i>
                                        </div>
                                        @else
                                            <div class="dropdown-icon-circle bg-soft-success">
                                            <i class="bi bi-bell"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        {{-- <div class="small text-gray-500">{{$notification->created_at?->diffForHumans()??null}}</div> --}}
                                        <span class="font-weight-bold">{!! $notification->message !!}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </li>


            @if(active_if_full_match('*/*/*') == 'active')
                <li class="nav-item dropdown px-2 px-xl-0">
                    <todo-st3></todo-st3>
                </li>
            @elseif(active_if_full_match('*/*') == 'active')
                <li class="nav-item dropdown px-2 px-xl-0">
                    <todo-st2></todo-st2>
                </li>
            @else
                <li class="nav-item dropdown px-2 px-xl-0">
                    <todo></todo>
                </li>
            @endif




            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow px-2 px-xl-0">
                <a class="nav-link py-1 d-flex align-items-center " href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="nav-avatar"><img class="nav-avatar-img" src="{{asset(auth()->user()->employee?->profile_picture != '' ? auth()->user()->employee?->profile_picture : 'backend/img/user-placeholder.png')}}"></span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu position-absolute nav-language-dropdown">
                    <div class="dropdown-content">
                        <div class="dropdown-header auth-dropdown-header">
                            <h5 class="dropdown-title">
                                <div>{{auth()->user()->name}}</div>
                                {{-- <small class="extra-small">Since in {{auth()->user()?->created_at->diffForHumans()??null}}</small> --}}
                            </h5>
                            <span class="nav-avatar"><img class="nav-avatar-img" src="{{asset(auth()->user()->employee?->profile_picture != '' ? auth()->user()->employee?->profile_picture : 'backend/img/user-placeholder.png')}}"></span>
                        </div>
                        <div class="dropdown-body">
                            <a class="dropdown-item" href="{{route('profile')}}">
                                <span class="me-2"><i class="bi bi-gear"></i></span>
                                {{__('pages.account_settings')}}
                            </a>

                            <a class="dropdown-item" href="{{route('password')}}">
                                <span class="me-2"><i class="bi bi-key"></i></span>
                                {{__('pages.change_password')}}
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="me-2"><i class="bi bi-box-arrow-right"></i></span>
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </li>

        </ul>


    </nav>

