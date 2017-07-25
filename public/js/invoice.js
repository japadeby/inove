$(document).ready(function() {

	/**
	 * Scripts tela listagem
	 */
	
	if($('#datatable-responsive').length) {
		/**
		 * Datatable da listagem
		 */
		$('#datatable-responsive').DataTable({
			"paging": true,
	        "lengthChange": false,
	        "ordering": true,
	        "info": true,
	        "columns" : [
	        	{data : 'due_date', type: 'date', render : function ( data, type, full, meta ) {
	        		if (type == 'sort') {
	        			return data;
	        		}
          		  var dado = data.split('-');
         	      return dado[2] + '/' + dado[1] + '/' + dado[0];
        	    }},
                {data : 'value'},
                {data : 'discount'},
                {data : 'final'},
                {data : 'paid'},
                {data : 'status', orderable: false}
	        	],
	        "language": {
	      	  "info": "_TOTAL_ registro(s) encontrado(s)",
	      	  "infoEmpty": "Nenhum registro encontrado",
	      	  "search": "Pesquisar:",
	      	  "searchPlaceholder": "",
	      	  "paginate": {
	      	  	"next": 'Próxima',
	      	  	"previous": "Anterior"
	      	  },
	      	  "zeroRecords": "Nenhum registro encontrado",
	      	  "processing": "Processando...",
	      	  "infoFiltered": " - filtrado à partir de _MAX_ registro(s)"
	        },
	        "initComplete": function(settings, json) {
	        	/**
	        	 * Modal alerta antes de excluir
	        	 */  
	            $('#myModal').on('show.bs.modal', function (e) {
	            	var id = $(e.relatedTarget).data('id');
	            	$('.form-excluir').attr('action', $('.form-excluir').data('action') + id)
	            	$('.btn-prosseguir').off().on('click', function() {
	            		$('.form-excluir').submit();
	            	});
	        	});
	          }
		});
	}
	
	/**
	 * Scripts tela form
	 */
	
	if ($('#form-invoice').length) {
		
		/**
		 * Validação do form
		 */
		$('#form-invoice .btn-submit').on('click', function() {
	          $('#form-invoice').parsley().validate();
	          validateFront();
        });
		var validateFront = function() {
          if (true === $('#form-invoice').parsley().isValid({force:true})) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
            $('#form-invoice').submit();
          } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          }
        };
        /**
         * Máscaras
         */
		$(":input").inputmask();
		$('.money').maskMoney({
			affixesStay: false,
			thousands: ''
		});
		/**
		 * Observers de campos hidden/não hidden dependendo da escolha no form
		 */
		$('#paid').on('ifChecked', function() {
			$('.paid').removeClass('hidden');
		});
		$('#paid').on('ifUnchecked', function() {
			$('.paid').addClass('hidden');
		});
		$('#recurrent').on('ifChecked', function() {
			$('.recurrent').removeClass('hidden');
		});
		$('#recurrent').on('ifUnchecked', function() {
			$('.recurrent').addClass('hidden');
		});
		
		/**
		 * Observer incluir/editar desconto
		 */
		$('body').on('click', '.submitForm', function() {
			var form = $(this).parents('form');
			$('.msg', form).removeClass('text-success text-danger').html('');
			if ($('input', form).val()) {
				$.post(form.attr('action'), {value:$('input', form).val()})
				.done(function(data) {
					data = JSON.parse(data);
					if (data.status) {
						$('.msg', form).addClass('text-success').html(data.msg);
						if (form.hasClass('newDiscount')) {
							$('input', form).val('');
							$('.clone-form').clone().appendTo('.discounts');
							$('form:last', '.discounts')
								.attr('action', '/discounts/edit/' + data.discount.id)
								.removeClass('clone-form  hidden');
							$('input:last', '.discounts').val(data.discount.value);
							$('a:last', '.discounts').data('action', '/discounts/delete/' + data.discount.id);
						}
					} else {
						$('.msg', form).addClass('text-danger').html(data.msg);						
					}
				});
			}
		});
		
		/**
		 * Modal alerta antes de excluir desconto
		 */ 
        $('body').on('show.bs.modal', '#myModal', function (e) {
        	var action = $(e.relatedTarget).data('action');
        	var form = $(e.relatedTarget).parents('form');
        	/**
        	 *  Observer prosseguir com exclusão
        	 */
        	$('.deleteForm').off().on('click', function() {
        		$('#myModal').modal('hide');
        		$.post(action)
        		.done(function(data) {
        			data = JSON.parse(data);
        			if (data.status) {
						form.hide(900, function() {
						    form.remove();
						});
					} else {
						$('.msg', form).addClass('text-danger').html(data.msg);						
					}
        		});
        	});
    	});
		
	}
      

});