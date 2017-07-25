<!-- page content -->
<div class="right_col" role="main">
  <!-- top tiles -->
  <div class="row tile_count">
  	<div class="col-md-12">
  		<h3>Nos próximos 30 dias</h3>
  	</div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-calendar"></i> Contas a Vencer</span>
      <div class="count"><?php print $invoices->count() ?></div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-arrow-up"></i> A Receber</span>
      <div class="count green"><?php printf('%.2f', $toget) ?></div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-arrow-down"></i> A Pagar</span>
      <div class="count red"><?php printf('%.2f', $topay) ?></div>
    </div>
  </div>
  <!-- /top tiles -->

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard_graph">
        <div class="row x_title">
          <div class="col-md-6">
            <h3>Atividade</h3>
            <small>
            	<i class="fa fa-circle" style="color:rgba(38, 185, 154, 0.38)"></i> à receber
            	<i class="fa fa-circle" style="color:rgba(3, 88, 106, 0.38)"></i> à pagar
        	</small>
          </div>
          <div class="col-md-6">
            <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
              <span><?php print \DateTime::createFromFormat('Y-m-d', $dataStart)->format('d/m/Y') ?> 
              - 
              <?php print \DateTime::createFromFormat('Y-m-d', $dataEnd)->format('d/m/Y') ?></span> 
            </div>
          </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
          <div style="width: 100%;">
            <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
          </div>
        </div>

        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <br />
</div>
<!-- /page content -->

<script>
/**
 * Função para criar uma instância Date a partir de ano, mês e dia
 */
function gd(year, month, day) {
    return new Date(year, month - 1, day).getTime();
  }
// dados iniciais do gráfico
data1 = new Array(); // à receber
data2 = new Array(); // à pagar

<?php 

/* 
 * A idéia aqui é: 
 * - percorrer 30 dias a partir de hoje
 * - contas iniciam com valor zero no dia
 * - pegar as contas com vencimento no dia, checar se é pagar ou receber e somar as equivalentes (incluindo descontos)
 * - ao final do dia, adicionar nas variáveis correspondentes do gráfico (data1, data2) os valores 
 * */

for($i = 0; $i <= 30; ++$i) {
    // dia atual
    $atualDate = date("Y-m-d", mktime(0, 0, 0, date("n"), date('j') + $i, date("Y")));
    // valor inicial das contas no dia
    $todayGet = $todayPay = 0;
    // pega todas as contas do dia e soma no valor inicial correspondente
    foreach($invoices->where('due_date', $atualDate) as $invoice) {
        // a receber
       if ($invoice->value > 0) { 
           $todayGet += ($invoice->value - $invoice->discounts()->sum('value'));
       // a pagar
        } else { 
            $todayPay += (abs($invoice->value) - $invoice->discounts()->sum('value'));
        }        
    } // endforeach
?>
    	data1.push([gd(<?php print \DateTime::createFromFormat('Y-m-d', $atualDate)->format('Y,n,j') ?>), <?php print $todayGet ?>]);
    	data2.push([gd(<?php print \DateTime::createFromFormat('Y-m-d', $atualDate)->format('Y,n,j') ?>), <?php print $todayPay ?>]);
<?php 
} // endfor 
?>
</script>

<?php 

    $script = ['home.js'];

?>

        