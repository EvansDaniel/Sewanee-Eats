<div class="col-md-3 left_col" style="overflow: scroll">
    <!-- Sidebar -->
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ route('home') }}" class="site_title"><i class="fa fa-paw"></i> <span>SewaneeEats</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            {{--<div class="profile_pic">
                <img src="images/img.jpg" alt="..." class="img-circle profile_img">
            </div>--}}
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ Auth::user()->name }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br/>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Business Related</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{ route('showCourierDashboard') }}"><i class="fa fa-home"></i> Home </a>
                    </li>
                    <li>
                        <a href="{{ route('courierShowSchedule') }}"><i class="fa fa-table"></i> Schedule </a>
                    </li>
                    <li>
                        <a><i class="fa fa-table"></i> Orders <span class="fa fa-chevron-down"></span></a>

                        <ul class="nav child_menu">
                            <li><a href="{{ route('showSchedule') }}">Open On Demand Orders</a></li>
                            <li><a href="{{ route('showShifts') }}">Past Orders</a></li>
                            <li><a href="{{ route('showCreateShift') }}">Past Special Orders</a></li>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-table"></i> Payment <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('showSchedule') }}">Current Pay Period</a></li>
                            <li><a href="{{ route('showShifts') }}">On Demand Payment Summary</a></li>
                            <li><a href="{{ route('showCreateShift') }}">Specials Payment Summary</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            {{--<div class="menu_section">
                <h3>Money Stuff</h3>
                <ul class="nav side-menu">
                    --}}{{--<li><a href="{{ route('managerShowOrdersQueue') }}"><i class="fa fa-bug"></i> Order Queue <span
                                    class="fa fa-chevron-down"></span></a>
                    <li>
                        <ul class="nav child_menu">
                            <li><a href="e_commerce.html">E-commerce</a></li>
                            <li><a href="projects.html">Projects</a></li>
                            <li><a href="project_detail.html">Project Detail</a></li>
                            <li><a href="contacts.html">Contacts</a></li>
                            <li><a href="profile.html">Profile</a></li>
                        </ul>
                    </li>--}}{{--
                    <li><a><i class="fa fa-windows"></i> Income/Expenses <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('showOrderPriceInfo') }}">Income</a></li>
                            <li><a href="page_404.html">Expenses</a></li>
                            <li><a href="{{ route('showCourierPaymentSummary') }}">Courier Specific Outgoings</a></li>
                            <li><a href="plain_page.html">Plain Page</a></li>
                            <li><a href="login.html">Login Page</a></li>
                            <li><a href="pricing_tables.html">Pricing Tables</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="#level1_1">Level One</a>
                            <li><a>Level One<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu"><a href="level2.html">Level Two</a>
                                    </li>
                                    <li><a href="#level2_1">Level Two</a>
                                    </li>
                                    <li><a href="#level2_2">Level Two</a>
                                    </li>
                                </ul>
                            </li>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>--}}

        </div>
    </div>
</div>
