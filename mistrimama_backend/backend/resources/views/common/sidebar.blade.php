<div class="site-menubar site-menubar-light">
    <div class="site-menubar-body" style="height: 100%">
        <ul class="site-menu" data-plugin="menu">
            <li class="site-menu-item mt-3">
                <a href="{{route('dashboard')}}">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Dashboard</span>
                </a>
            </li>

            <!-- Finance -->
            <li class="site-menu-category mt-0">Finance</li>
            <li class="site-menu-item has-sub {{ (request()->is('transaction/*')) ? 'active open' : '' }}">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-card" aria-hidden="true"></i>
                    <span class="site-menu-title">Cashbook</span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    <li class="site-menu-item {{(request()->is('transactions')) ? 'active is-shown' : '' }}">
                        <a href="{{route('transactions.index')}}">
                            <span class="site-menu-title">Transactions</span>
                        </a>
                    </li>
                    @if (auth()->user()->hasAnyRole(['admin', 'accountant', 'marketing']))
                    <li class="site-menu-item {{(request()->is('transaction/create')) ? 'active is-shown' : '' }}">
                        <a href="{{route('transaction.create')}}">
                            <span class="site-menu-title">Transaction Entry</span>
                        </a>
                    </li>
                    <li class="site-menu-item {{(request()->is('transaction/heading')) ? 'active is-shown' : '' }}">
                        <a href="{{ route('heading.index') }}">
                            <span class="site-menu-title">Transaction Headings</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            <li class="site-menu-item has-sub {{ (request()->is('recharge/*')) ? 'active open' : '' }}">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-card" aria-hidden="true"></i>
                    <span class="site-menu-title">Recharge <small class="sr-only">(Service Partner)</small> </span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    <li class="site-menu-item {{ (request()->is('recharge/request')) ? 'active is-shown' : '' }}">
                        <a href="{{route('recharge.index')}}">
                            <span class="site-menu-title">Approval request</span>
                        </a>
                    </li>
                    <li class="site-menu-item {{ (request()->is('recharge/history')) ? 'active is-shown' : '' }}">
                        <a href="{{route('recharge.history')}}">
                            <span class="site-menu-title">Recharge History</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="site-menu-item has-sub {{ (request()->is('withdraw/*')) ? 'active open' : '' }}">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-card" aria-hidden="true"></i>
                    <span class="site-menu-title">Service Provider Withdraw</span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    <li class="site-menu-item {{(request()->is('withdraw/request')) ? 'active is-shown' : '' }}">
                        <a href="{{route('withdraw.request')}}">
                            <span class="site-menu-title">Request</span>
                        </a>
                    </li>
                    <li class="site-menu-item {{(request()->is('transaction/history')) ? 'active is-shown' : '' }}">
                        <a href="{{route('withdraw.history')}}">
                            <span class="site-menu-title">History</span>
                        </a>
                    </li>
                </ul>
            </li>
            @if (auth()->user()->hasAnyRole(['admin', 'management', 'accountant', 'marketing']))
            <li class="site-menu-item has-sub {{ (request()->is('cash_out/*')) ? 'active open' : '' }}">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-card" aria-hidden="true"></i>
                    <span class="site-menu-title">User Cash Out</span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    <li class="site-menu-item {{(request()->is('cash_out/request')) ? 'active is-shown' : '' }}">
                        <a href="{{route('cash_out.request')}}">
                            <span class="site-menu-title">Request</span>
                        </a>
                    </li>
                    <li class="site-menu-item {{(request()->is('cash_out/history')) ? 'active is-shown' : '' }}">
                        <a href="{{route('cash_out.history')}}">
                            <span class="site-menu-title">History</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <!-- Finance end -->

            <!-- Marketinng -->
            <li class="site-menu-category mt-0">Marketing & Sales</li>
            <li class="site-menu-item has-sub">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-widgets" aria-hidden="true"></i>
                    <span class="site-menu-title">Order</span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    @if (auth()->user()->hasAnyRole(['admin', 'operation', 'marketing']))
                    <li class="site-menu-item">
                        <a href="{{route('custom.order.create')}}">
                            <span class="site-menu-title">Create Order</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="{{route('custom.bulkorder.create')}}">
                            <span class="site-menu-title">Create Bulk Order</span>
                        </a>
                    </li>

                    <li class="site-menu-item">
                        <a href="{{route('order.index')}}">
                            <span class="site-menu-title">Manage Order</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="{{route('order.ongoing')}}">
                            <span class="site-menu-title">On Going Orders</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="{{route('quickorder.index')}}">
                            <span class="site-menu-title">Manage Quick Order</span>
                        </a>
                    </li>
                    @endif
                    <li class="site-menu-item">
                        <a href="{{ route('order.history') }}">
                            <span class="site-menu-title">Order History</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- <li class="site-menu-item has-sub">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-widgets" aria-hidden="true"></i>
                    <span class="site-menu-title">Dokaan (E-Commerce)</span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    <li class="site-menu-item">
                        <a href="#">
                            <span class="site-menu-title">Add New Products</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="#">
                            <span class="site-menu-title">Manage All Products</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="#">
                            <span class="site-menu-title">Approve SP Products</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="#">
                            <span class="site-menu-title">View B2B Project Progress</span>
                        </a>
                    </li>
                </ul>
            </li> -->

            @if (auth()->user()->hasAnyRole(['admin', 'management', 'marketing']))
            <li class="site-menu-item">
                <a href="{{route('client.index')}}">
                    <i class="site-menu-icon md-widgets" aria-hidden="true"></i>
                    <span class="site-menu-title">Users</span>
                </a>
            </li>
            @endif

            <!-- <li class="site-menu-item has-sub {{ (request()->is('b2b/*')) ? 'active open' : '' }}">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-widgets" aria-hidden="true"></i>
                    <span class="site-menu-title">B2B User</span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    <li class="site-menu-item {{ (request()->is('b2b/ondemand')) ? 'active is-shown' : '' }}">
                        <a href="{{route('ondemand.index')}}">
                            <span class="site-menu-title">On Demand</span>
                        </a>
                    </li>
                    <li class="site-menu-item {{ (request()->is('b2b/affiliation')) ? 'active is-shown' : '' }}">
                        <a href="{{route('affiliation.index')}}">
                            <span class="site-menu-title">Affiliation</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="#">
                            <span class="site-menu-title">Project</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="#">
                            <span class="site-menu-title">Maintenance</span>
                        </a>
                    </li>
                </ul>
            </li> -->

            @if (auth()->user()->hasAnyRole(['admin', 'marketing']))
            <li class="site-menu-item">
                <a href="{{route('offer.index')}}">
                    <i class="site-menu-icon md-widgets" aria-hidden="true"></i>
                    <span class="site-menu-title">Offers</span>
                </a>
            </li>
            
            <li class="site-menu-item {{ (request()->is(['promocode'])) ? 'active open' : '' }}">
                <a href="{{route('promocode.index')}}">
                    <i class="site-menu-icon md-widgets" aria-hidden="true"></i>
                    <span class="site-menu-title">Promocode</span>
                </a>
            </li>
            
            <li class="site-menu-item has-sub {{ (request()->is(['category', 'service','servicebit'])) ? 'active open' : '' }}">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-widgets" aria-hidden="true"></i>
                    <span class="site-menu-title">Manage Services</span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    <li class="site-menu-item {{ (request()->is('category')) ? 'active is-shown' : '' }}">
                        <a href="{{route('category.index')}}">
                            <span class="site-menu-title">Category</span>
                        </a>
                    </li>
                    <li class="site-menu-item {{ (request()->is('service')) ? 'active is-shown' : '' }}">
                        <a href="{{route('service.index')}}">
                            <span class="site-menu-title">Services</span>
                        </a>
                    </li>
                    <li class="site-menu-item {{ (request()->is('servicebit')) ? 'active is-shown' : '' }}">
                        <a href="{{route('servicebit.index')}}">
                            <span class="site-menu-title">Service Bit</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <!-- Marketinng End -->

            <!-- Operations -->
            <li class="site-menu-category mt-0">Operations</li>
            <li class="site-menu-item has-sub {{ (request()->is('service-provider')) || (request()->is('service-provider/*')) ? 'active open' : '' }} {{ (request()->is('service-provider-view')) ? 'active open' : '' }}">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-case" aria-hidden="true"></i>
                    <span class="site-menu-title">Service Provider</span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    @if (auth()->user()->hasAnyRole(['admin', 'operation', 'marketing']))
                    <li class="site-menu-item {{ (request()->is('service-provider/create')) ? 'active is-shown' : '' }}">
                        <a href="{{route('service-provider.create')}}">
                            <span class="site-menu-title">Add Service Provider</span>
                        </a>
                    </li>
                    @endif
                    <li class="site-menu-item {{ (request()->is('service-provider')) ? 'active is-shown' : '' }}">
                        <a href="{{route('service-provider.index')}}">
                            <span class="site-menu-title">Manage Service Providers</span>
                        </a>
                    </li>
                    <li class="site-menu-item {{ (request()->is('service-provider/document-upload-request')) ? 'active is-shown' : '' }}">
                        <a href="{{route('service-provider.document-upload-request')}}">
                            <span class="site-menu-title">Document Upload Request</span>
                        </a>
                    </li>
                    @if (auth()->user()->hasAnyRole(['admin', 'operation', 'marketing']))
                    <li class="site-menu-item {{ (request()->is('service-provider/become')) ? 'active is-shown' : '' }}">
                        <a href="{{route('service-provider.become')}}">
                            <span class="site-menu-title">Become Service Providers</span>
                        </a>
                    </li>
                    <li class="site-menu-item {{ (request()->is('service-provider/low_balance')) ? 'active is-shown' : '' }}">
                        <a href="{{route('service-provider.low-balance')}}">
                            <span class="site-menu-title">Low Balance</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @if (auth()->user()->hasAnyRole(['admin', 'management', 'operation', 'marketing']))
            <li class="site-menu-item has-sub">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-case" aria-hidden="true"></i>
                    <span class="site-menu-title">Manage Comrade</span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    @if (auth()->user()->hasAnyRole(['admin', 'operation', 'marketing']))                    
                    <li class="site-menu-item">
                        <a href="{{route('comrade.request')}}">
                            <span class="site-menu-title">Approval Comrade</span>
                        </a>
                    </li>      
                    <li class="site-menu-item">
                        <a href="{{route('comrade.index')}}">
                            <span class="site-menu-title">View All Comrade</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @if (auth()->user()->hasAnyRole(['admin', 'marketing']))
            <li class="site-menu-item has-sub">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-case" aria-hidden="true"></i>
                    <span class="site-menu-title">Manage Areas</span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    <li class="site-menu-item">
                        <a href="{{route('division.index')}}">
                            <span class="site-menu-title">Division</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="{{route('cluster.index')}}">
                            <span class="site-menu-title">Zone</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="{{route('zone.index')}}">
                            <span class="site-menu-title">Area</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="site-menu-item">
                <a href="{{route('jiggasha.index')}}">
                    <i class="site-menu-icon md-case" aria-hidden="true"></i>
                    <span class="site-menu-title">FAQ</span>
                </a>
            </li>
            <li class="site-menu-item">
                <a href="{{route('feedback.index')}}">
                    <i class="site-menu-icon md-case" aria-hidden="true"></i>
                    <span class="site-menu-title">Feedback</span>
                </a>
            </li>
            <li class="site-menu-item">
                <a href="{{route('baboharbidhi.index')}}">
                    <i class="site-menu-icon md-case" aria-hidden="true"></i>
                    <span class="site-menu-title">User Manual</span>
                </a>
            </li>
            @endif
            <!-- Operations End -->

            <!-- editor -->
            <!-- Office -->
            @if (auth()->user()->hasAnyRole(['admin', 'marketing']))
            <li class="site-menu-category mt-0">Web Controls</li>
            <li class="site-menu-item {{ (request()->is('page')) ? 'active' : '' }}">
                <a href="{{route('page.index')}}">
                    <i class="site-menu-icon md-globe" aria-hidden="true"></i>
                    <span class="site-menu-title">Pages</span>
                </a>
            </li>
            <li class="site-menu-item {{ (request()->is('slider')) ? 'active' : '' }}">
                <a href="{{route('slider.index')}}">
                    <i class="site-menu-icon md-globe" aria-hidden="true"></i>
                    <span class="site-menu-title">Sliders</span>
                </a>
            </li>
            <li class="site-menu-item ">
                <a href="{{ route('blog.index') }}">
                    <i class="site-menu-icon md-globe" aria-hidden="true"></i>
                    <span class="site-menu-title">Blogs</span>
                </a>
            </li>
            <li class="site-menu-item ">
                <a href="{{ route('advertisement.index') }}">
                    <i class="site-menu-icon md-globe" aria-hidden="true"></i>
                    <span class="site-menu-title">Advertisement</span>
                </a>
            </li>
            <li class="site-menu-item ">
                <a href="{{ route('careers.index') }}">
                    <i class="site-menu-icon md-globe" aria-hidden="true"></i>
                    <span class="site-menu-title">Career</span>
                </a>
            </li>
            <li class="site-menu-item ">
                <a href="{{ route('risk-factors.index') }}">
                    <i class="site-menu-icon md-globe" aria-hidden="true"></i>
                    <span class="site-menu-title">Risk Factors</span>
                </a>
            </li>
            <li class="site-menu-item ">
                <a href="{{ route('claims.index') }}">
                    <i class="site-menu-icon md-globe" aria-hidden="true"></i>
                    <span class="site-menu-title">Cliams</span>
                </a>
            </li>
            @endif
            <!-- Office -->
            
            <!-- Authentication Control -->
            @if (auth()->user()->hasAnyRole(['admin', 'management']))
            <li class="site-menu-category mt-0">Authentication Control</li>
            <li class="site-menu-item {{ (request()->is('user/index')) ? 'active' : '' }} ">
                <a href="{{route('users.index')}}">
                    <i class="site-menu-icon md-walk" aria-hidden="true"></i>
                    <span class="site-menu-title">Manage Users</span>
                </a>
            </li>
            <li class="site-menu-item {{ (request()->is('role')) ? 'active' : '' }}">
                <a href="{{route('role.index')}}">
                    <i class="site-menu-icon md-walk" aria-hidden="true"></i>
                    <span class="site-menu-title">Manage Roles</span>
                </a>
            </li>
            <li class="site-menu-item" style="display:none">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-walk" aria-hidden="true"></i>
                    <span class="site-menu-title">Manage Permission</span>
                </a>
            </li> 
            <?php if(($_SERVER['SERVER_NAME'] == 'localhost') || ($_SERVER['SERVER_NAME'] == 'staging.mistrimama.com')){ ?>
            <li class="site-menu-category mt-0">B2B Manage</li>
            <li class="site-menu-item">
                <a href="{{route('b2buser.create')}}">
                    <i class="site-menu-icon md-walk" aria-hidden="true"></i>
                    <span class="site-menu-title">B2B user</span>
                </a>
                <a href="{{route('inventory.create')}}">
                    <i class="site-menu-icon md-walk" aria-hidden="true"></i>
                    <span class="site-menu-title">Setup Inventory</span>
                </a>
                <a href="{{route('projects.create')}}">
                    <i class="site-menu-icon md-walk" aria-hidden="true"></i>
                    <span class="site-menu-title">Setup Projects</span>
                </a>
            </li> 

             
                <!-- <a href="javascript:void(0)">
                    <i class="site-menu-icon md-widgets" aria-hidden="true"></i>
                    <span class="site-menu-title">B2B User</span>
                    <span class="site-menu-arrow"></span>
                </a> -->
                <!-- <ul class="site-menu-sub">
                    <li class="site-menu-item">
                        <a href="{{route('b2buser.index')}}">
                            <span class="site-menu-title">Users <small class="sr-only">Manage B2B user</small> </span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="#">
                            <span class="site-menu-title"> Setup Inventory <small class="sr-only">Setup Inventory (for B2B Maintenance)</small></span>
                        </a>
                    </li> 
                </ul> -->
             
            <?php } ?>
            <!-- <li class="site-menu-item has-sub">
                <a href="javascript:void(0)">
                    <i class="site-menu-icon md-widgets" aria-hidden="true"></i>
                    <span class="site-menu-title">B2B Order</span>
                    <span class="site-menu-arrow"></span>
                </a>
                <ul class="site-menu-sub">
                    <li class="site-menu-item">
                        <a href="#">
                            <span class="site-menu-title">Manage Order (B2B) <small class="sr-only">Manage B2B On Demand/Affiliation Order/Maintenance Order</small> </span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="#">
                            <span class="site-menu-title">B2B Order History <small class="sr-only">(On Demand/ Affiliation Order/Maintenance)</small></span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="#">
                            <span class="site-menu-title">Maintenance Monthly Check up Schedule</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="#">
                            <span class="site-menu-title">View B2B Project Progress</span>
                        </a>
                    </li>
                </ul>
            </li>   -->
            <li class="site-menu-category mt-0">Settings</li>
            <li class="site-menu-item {{ (request()->is('user/index')) ? 'active' : '' }} ">
                <a href="{{route('setting.index')}}">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Setting</span>
                </a>
            </li> 
            <li class="site-menu-category mt-0">Notification</li>
            <li class="site-menu-item {{ (request()->is('notifications/index')) ? 'active' : '' }} ">
                <a href="{{route('notifications.index')}}">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Notification list</span>
                </a>
            </li> 
            <li class="site-menu-item {{ (request()->is('notifications/index')) ? 'active' : '' }} ">
                <a href="{{route('notifications.create')}}">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Notification add</span>
                </a>
            </li> 
            @endif
            <!-- Authentication Control end -->
        </ul>
    </div>
</div>