<!DOCTYPE html>
<html lang="en">

<head>

      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- Custom fonts for this template-->
      <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
      <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Bangers|Nunito+Sans|Quicksand&display=swap" rel="stylesheet"> 
      <!-- Bootstrap core JavaScript and Bootstrap-->
      <link href="/css/bootstrap.min.css" rel="stylesheet">
      <!-- <script src="/vendor/jquery/jquery.js"></script> -->
      <script src="/vendor/datepicker/jquery.js"></script>
      <script src="/vendor/bootstrap/js/bootstrap.min.js"></script> 
      <!-- Core plugin JavaScript-->
      <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
      <!-- Custom scripts for all pages-->
      <script src="/js/sb-admin-2.min.js"></script>
      <script src="/js/aes.js"></script>
      <!-- Page level plugins -->
      <script src="/vendor/chart.js/Chart.min.js"></script>
      <!-- Custom styles for this template-->
      <link href="/css/sb-admin-2.min.css" rel="stylesheet">
      <link href="/css/custome-class.css" rel="stylesheet">
      <link href="/css/login.css" rel="stylesheet">
      <!-- Autocomplete-->
      <link href="/css/easy-autocomplete.min.css" rel="stylesheet">
      <script src="/js/jquery.easy-autocomplete.min.js"></script>
      <!-- For Date time Picker -->
      <link href="/vendor/datepicker/bootstrap-datepicker.css" rel="stylesheet">
      <script src="/vendor/datepicker/bootstrap-datepicker.js"></script>
      <!-- Sweet Alert Message-->
      <script src="/js/sweetalert2.all.min.js"></script>
      <!-- Accounting-->
      <script src="/js/accounting.js"></script>
      <script src="/js/accounting.min.js"></script>

      <title>@yield('title')</title>
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Side Menu -->
    @include('layouts.Side')
    <!-- end menu -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

        <!-- Topbar Navbar -->
            @include('layouts.Top')
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            @yield('content')
        </div>
      <!-- End of Main Content -->
        
      <!-- Footer -->
      <!-- <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2019</span>
          </div>
        </div>
      </footer> -->
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
