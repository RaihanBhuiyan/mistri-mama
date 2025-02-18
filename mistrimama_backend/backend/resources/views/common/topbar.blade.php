<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
            data-toggle="menubar">
            <span class="sr-only">Toggle navigation</span>
            <span class="hamburger-bar"></span>
        </button>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
            data-toggle="collapse">
            <i class="icon md-more" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
            {{-- <img class="navbar-brand-logo" src="../assets/images/logo.png" title="Remark"> --}}
            <span class="navbar-brand-text hidden-xs-down">{{ config('app.name', 'Mistrimama') }}</span>
        </div>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search" data-toggle="collapse">
            <span class="sr-only">Toggle Search</span>
            <i class="icon md-search" aria-hidden="true"></i>
        </button>
    </div>
    <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
            <!-- Navbar Toolbar -->
            <ul class="nav navbar-toolbar">
                <li class="nav-item hidden-float" id="toggleMenubar">
                    <a class="nav-link" data-toggle="menubar" href="#" role="button">
                        <i class="icon hamburger hamburger-arrow-left">
                            <span class="sr-only">Toggle menubar</span>
                            <span class="hamburger-bar"></span>
                        </i>
                    </a>
                </li>
                {{-- <li class="nav-item hidden-float">
                    <a class="nav-link icon md-search" data-toggle="collapse" href="#" data-target="#site-navbar-search"
                        role="button">
                        <span class="sr-only">Toggle Search</span>
                    </a>
                </li> --}}
            </ul>
            <!-- End Navbar Toolbar -->

            <!-- Navbar Toolbar Right -->
            <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
                <li class="nav-item dropdown">
                    <a class="nav-link" id="notifications" data-toggle="dropdown" href="javascript:void(0)" title="Notifications" aria-expanded="false" data-animation="scale-up" role="button">
                        <i class="icon md-notifications" aria-hidden="true"></i>
                        <span class="badge badge-pill badge-danger up" id="notification_counter">{{ $notification_counter }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
                        <div class="list-group">
                            <div data-role="container">
                                <div data-role="content">
                                    @if(!empty($notifications))
                                    @foreach($notifications as $notification)
                                    <a style="display:block; padding: 0; {{ (empty($notification->read_at)) ? 'background-color: #eee;' : '' }}" class="list-group-item" @if(!empty(json_decode($notification->data)->path)) href="{{ json_decode($notification->data)->path }}" @else href="javascript:void(0)" @endif role="menuitem">
                                        <div class="media" style="padding: 10px 5px;">
                                            <div style="line-height: 33px; padding-right:10px">
                                                <i class="icon md-receipt bg-red-600 white icon-circle" aria-hidden="true"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading" style="font-size: 12px; margin-bottom:1px">{{ json_decode($notification->data)->title }}</h6>
                                                <time class="media-meta" style="font-size: 11px;">at {{ $notification->created_at }}</time>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-menu-footer">
                            <a class="dropdown-item" href="{{ route('notifications.index') }}" role="menuitem">See all notifications</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
                        data-animation="scale-up" role="button">
                        <span class="avatar avatar-online">
                            <img src="{{!empty(auth()->user()->admin->photo) ? auth()->user()->admin->photo_url : 'https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_960_720.png'}}"
                            alt="...">
                        </span>
                    </a>
                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" href="{{ route('profile.show') }}" role="menuitem"><i class="icon md-account" aria-hidden="true"></i> Profile</a>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}" role="menuitem"><i class="icon md-account" aria-hidden="true"></i> Profile Edit</a>
                        <!-- <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon md-card"
                                aria-hidden="true"></i> Billing</a>
                        <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon md-settings"
                                aria-hidden="true"></i> Settings</a> -->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();" role="menuitem"><i class="icon md-power"
                                aria-hidden="true"></i> Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>

            </ul>
            <!-- End Navbar Toolbar Right -->
        </div>
        <!-- End Navbar Collapse -->

        <!-- Site Navbar Seach -->
        <div class="collapse navbar-search-overlap" id="site-navbar-search">
            <form role="search">
                <div class="form-group">
                    <div class="input-search">
                        <i class="input-search-icon md-search" aria-hidden="true"></i>
                        <input type="text" class="form-control" name="site-search" placeholder="Search...">
                        <button type="button" class="input-search-close icon md-close" data-target="#site-navbar-search"
                            data-toggle="collapse" aria-label="Close"></button>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Site Navbar Seach -->
    </div>
</nav>