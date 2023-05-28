<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset("bower_components/admin-lte/dist/img/logo-adv.jpg") }}"
                     class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>{{ session('member_name') }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">HEADER</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="{{ Request::is('invoice') ? 'active' : '' }}">
                <a href="{{ url('invoice') }}"><span>Invoice</span></a>
            </li>
            <li class="{{ Request::is('history') ? 'active' : '' }}">
                <a href="{{ url('history') }}"><span>History</span></a>
            </li>
            <li><a href="{{ url('logout') }}"><span>Logout</span></a></li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>