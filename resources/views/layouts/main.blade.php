<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ "AdminLTE Dashboard" }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ asset("bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet"
          type="text/css"/>
    <!-- bootstrap datepicker -->
    <link href="{{ asset('bower_components/admin-lte/plugins/datepicker/datepicker3.css') }}" rel="stylesheet"/>
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- Theme style -->
    <link href="{{ asset("bower_components/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset("bower_components/admin-lte/dist/css/skins/skin-blue.min.css") }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset("bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.css") }}"
          rel="stylesheet">
    @yield('css')

    <![endif]-->
</head>
<body class="skin-blue">
<div class="wrapper">

    <!-- Header -->
@include('layouts.header')

<!-- Sidebar -->
@include('layouts.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{ "Page Title" }}
                <small>{{ "test" }}</small>
            </h1>
            <!-- You can dynamically generate breadcrumbs here -->
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            @include('flash-message')
            @yield('content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Footer -->
    @include('layouts.footer')

</div><!-- ./wrapper -->

<!-- jQuery 2.1.3 -->
<script src="{{ asset ("bower_components/admin-lte/plugins/jQuery/jQuery-2.1.3.min.js") }}"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ asset ("bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}"
        type="text/javascript"></script>
<!-- DataTables -->
<script src="{{ asset("bower_components/admin-lte/plugins/datatables/jquery.dataTables.js") }}"></script>
<script src="{{ asset("bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.js") }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset ("bower_components/admin-lte/dist/js/app.min.js") }}" type="text/javascript"></script>

@yield('script')
<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience -->
</body>
</html>