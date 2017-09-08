<!-- page content -->
<div class="right_col" role="main">

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard_graph">
        <div class="row x_title">
          <div class="col-md-12">
            <h3>Gerenciamento de Conta</h3>
          </div>
        </div>
        
        <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
        <?php } ?>
	
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
          
          <form id="form-invoice" class="form-horizontal form-label-left" method="post"  data-parsley-validate 
          	action="<?php print (isset($invoice) && (!isset($duplicate) || !$duplicate)) ? '/invoices/edit/' . $invoice->id : '/invoices'; ?>"> 
          
          	<div class="form-group">
          		<label>Tipo <span class="required">*</span></label>
                  <p>
                    À pagar:
                    <input type="radio" class="flat" name="type" value="-1"
                    	<?php if(!isset($invoice) || $invoice->value < 0) { ?> checked="checked"<?php } ?> /> 
                    À receber:
                    <input type="radio" class="flat" name="type" value="1"
                    <?php if(isset($invoice) && $invoice->value > 0) { ?> checked="checked"<?php } ?> />
                  </p>
              </div>
          
          	<div class="form-group">
                <label for="name">Valor (R$)<span class="required">*</span>
                </label>
                <p>
                  <input maxlength="6" id="value" type="text" class="money form-control col-md-7 col-xs-12" name="value" required="required"
                  value="<?php if(isset($invoice)) { printf('%.2f', abs($invoice->value)); } ?>">
                </p>
            </div>
            
            <div class="form-group">
            <label for="due_date">Vencimento <span class="required">*</span>
            </label>
            <p>
              <input id="due_date" class="form-control col-md-7 col-xs-12" data-inputmask="'mask': '99/99/9999', 'clearIncomplete': true" name="due_date" required="required" type="text"
              value="<?php if(isset($invoice)) { print \DateTime::createFromFormat('Y-m-d', $invoice->due_date)->format('d/m/Y'); } ?>">
            </p>
            </div>
            
            <div class="form-group">
            <label><input type="checkbox" class="flat" name="paid" id="paid" value="1"
            <?php if(isset($invoice) && $invoice->paid) { ?> checked="checked"<?php } ?> /> Pago</label>
                  <p class="paid <?php if(!isset($invoice) || !$invoice->paid) { ?>hidden<?php } ?>">
                    Pago em:
                    <input id="pay_date" data-inputmask="'mask': '99/99/9999', 'clearIncomplete': true" name="pay_date" type="text"
                    	value="<?php if(isset($invoice) && !empty($invoice->pay_date)) { print \DateTime::createFromFormat('Y-m-d', $invoice->pay_date)->format('d/m/Y');} ?>">
                  </p>
            </div>
            <?php 
                // conta não pode ser re-recorrente
                if(!isset($invoice) || !$invoice->invoice_id) { 
            ?>
            <div class="form-group">
            <label><input type="checkbox" class="flat" name="recurrent" id="recurrent" value="1" 
            <?php if(isset($invoice) && $invoice->recurrent) { ?> checked="checked"<?php } ?>/> Recorrente</label>
                  <p class="recurrent <?php if(!isset($invoice) || !$invoice->recurrent) { ?>hidden<?php } ?>">
                    Intervalo de meses <span class="required">*</span>:
                    <input data-inputmask="'mask': '9[9]', 'clearIncomplete': true, 'greedy':false" id="recurrence_interval" name="recurrence_interval" type="text"
                    value="<?php if(isset($invoice)) { print $invoice->recurrence_interval; } ?>">>
                  </p>
            </div>
            <?php } ?>
          	<span class="btn btn-primary btn-submit">Salvar</span>
          </form>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    
    <?php if (isset($invoice) && (!isset($duplicate) || !$duplicate)) { ?>
        
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard_graph">
        <div class="row x_title">
          <div class="col-md-12">
            <h3>Descontos</h3>
          </div>
        </div>
        
        <form class="form-inline newDiscount" action="/discounts/<?php echo $invoice->id ?>">
        	<h4>Novo Desconto</h4>
        	<label>Valor</label>
        	<input type="text" name="value" class="money form-control" />
        	<a class="btn btn-success submitForm" href="javascript:;">Salvar</a> 
        	<span class="msg"></span>       
        </form>
        
        <h4>Descontos Criados</h4>
        
        <?php if (!count($invoice->discounts)) { ?>
        
        	<p>Nenhum registro encontrado</p>
        
        <?php } ?>
        
        <div class="discounts">
        
        <?php 
        
        foreach($invoice->discounts as $discount) {
          
            ?>
         
         <form class="form-inline" action="/discounts/edit/<?php echo $discount->id ?>">
        	<label>Valor</label>
        	<input type="text" name="value" class="money form-control" value="<?php echo $discount->value ?>" />
        	<a class="btn btn-info btn-sm submitForm" href="javascript:;">Salvar</a>        
        	<a class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal" data-action="/discounts/delete/<?php echo $discount->id ?>" href="javascript:;">Excluir</a>   
        	<span class="msg"></span>     
        </form>
            
            <?php 
            
        }
        
        ?>
        
        </div>
        
        <form class="form-inline clone-form hidden">
        	<label>Valor</label>
        	<input type="text" name="value" class="money form-control" />
        	<a class="btn btn-info btn-sm submitForm" href="javascript:;">Salvar</a>        
        	<a class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal" href="javascript:;">Excluir</a>   
        	<span class="msg"></span>     
        </form>
        
    </div>
    </div>
    
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
        <form method="post" class="form-excluir">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger deleteForm">Excluir</button>
            <span class="msg"></span>  
        </form>
      </div>
    </div>
  </div>
</div>
        
    <?php } ?>
    
  </div>
  <br />
</div>
<!-- /page content -->

    <!-- jQuery Tags Input -->
    <script src="/node_modules/gentelella/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="/node_modules/gentelella/vendors/switchery/dist/switchery.min.js"></script>
    <!-- Parsley -->
    <script src="/node_modules/gentelella/vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="/node_modules/gentelella/vendors/autosize/dist/autosize.min.js"></script>
    <!-- jquery.inputmask -->
    <script src="/node_modules/gentelella/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <!-- jquery.maskmoney -->
    <script src="/node_modules/jquery-maskmoney/dist/jquery.maskMoney.min.js" type="text/javascript"></script> 

<?php 

    $script = ['invoice.js'];

?>

        