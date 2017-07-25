<!-- footer content -->
        <footer>
          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    
    <!-- Bootstrap -->
    <script src="/node_modules/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="/node_modules/gentelella/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="/node_modules/gentelella/vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="/node_modules/gentelella/vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="/node_modules/gentelella/vendors/bernii/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="/node_modules/gentelella/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="/node_modules/gentelella/vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="/node_modules/gentelella/vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="/node_modules/gentelella/vendors/Flot/jquery.flot.js"></script>
    <script src="/node_modules/gentelella/vendors/Flot/jquery.flot.pie.js"></script>
    <script src="/node_modules/gentelella/vendors/Flot/jquery.flot.time.js"></script>
    <script src="/node_modules/gentelella/vendors/Flot/jquery.flot.stack.js"></script>
    <script src="/node_modules/gentelella/vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="/node_modules/gentelella/production/js/flot/jquery.flot.orderBars.js"></script>
    <script src="/node_modules/gentelella/production/js/flot/date.js"></script>
    <script src="/node_modules/gentelella/production/js/flot/jquery.flot.spline.js"></script>
    <script src="/node_modules/gentelella/production/js/flot/curvedLines.js"></script>
    <!-- jVectorMap -->
    <script src="/node_modules/gentelella/production/js/maps/jquery-jvectormap-2.0.3.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="/node_modules/gentelella/production/js/moment/moment.min.js"></script>
    <script src="/node_modules/gentelella/production/js/datepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="/node_modules/gentelella/build/js/custom.min.js"></script>
    
    <?php 
    
        if (isset($script) && is_array($script)) {
            foreach ($script as $src) {
                print '<script src="/js/' . $src . '"></script>';
            }
        }
    
    ?>


  </body>
</html>