<!-- Datatables -->
    <link href="/node_modules/gentelella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="/node_modules/gentelella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="/node_modules/gentelella/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="/node_modules/gentelella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="/node_modules/gentelella/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

<!-- page content -->
<div class="right_col" role="main">

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard_graph">
        <div class="row x_title">
          <div class="col-md-12">
            <h3>Lista de Contas</h3>
          </div>
        </div>
        
        <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_SESSION['success'] ?>
            <?php unset($_SESSION['success']); ?>
        </div>
        <?php } ?>
	
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
          <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>Vencimento</th>
                  <th>Valor Base</th>
                  <th>Valor Desconto</th>
                  <th>Valor Final</th>
                  <th>Pago</th>
                  <th>Ação</th>
                </tr>
              </thead>
              <tbody>
              	<?php foreach ($invoices as $invoice) { ?>
                <tr>
                  <td><?php print $invoice->due_date ?></td>
                  <td class="<?php print $invoice->value > 0 ? 'green' : 'red'; ?>"><?php print $invoice->value ?></td>
                  <td><?php printf('%.2f', $invoice->discounts()->sum('value')) ?></td>
                  <td class="<?php print $invoice->value - $invoice->discounts()->sum('value') > 0 ? 'green' : 'red'; ?>"><?php printf('%.2f', $invoice->value - $invoice->discounts()->sum('value')) ?></td>
                  <td><?php print ($invoice->paid) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?></td>
                  <td>
                  	<a class="btn btn-xs btn-info" href="/invoices/edit/<?php echo $invoice->id ?>">gerenciar</a>
                  	<a class="btn btn-xs btn-danger" href=""  data-toggle="modal" data-target="#myModal" data-id="<?php print $invoice->id ?>">excluir</a>
                  </td>
                </tr>
                <?php } ?>
                
              </tbody>
            </table>
        </div>

        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <br />
</div>
<!-- /page content -->

<!-- modal aviso excluir -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title text-danger" id="myModalLabel">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            Aviso
        </h3>
      </div>
      <div class="modal-body">
        <p class="lead">
        Tem certeza de que deseja excluir este registro? 
        </p>
      </div>
      <div class="modal-footer">
        <form method="post" class="form-excluir" data-action="/invoices/delete/">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger btn-prosseguir">Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Datatables -->
    <script src="/node_modules/gentelella/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/node_modules/gentelella/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="/node_modules/gentelella/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/node_modules/gentelella/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="/node_modules/gentelella/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="/node_modules/gentelella/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="/node_modules/gentelella/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="/node_modules/gentelella/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="/node_modules/gentelella/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="/node_modules/gentelella/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/node_modules/gentelella/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="/node_modules/gentelella/vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
    <script src="/node_modules/gentelella/vendors/jszip/dist/jszip.min.js"></script>
    <script src="/node_modules/gentelella/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="/node_modules/gentelella/vendors/pdfmake/build/vfs_fonts.js"></script>

<?php 

    $script = ['invoice.js'];

?>

        