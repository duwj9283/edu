{% extends "templates/basic.volt" %}

{% block css%}
	{{ stylesheet_link("3rdpart/dataTables/datatables.min.css") }}
{% endblock %}

{% block content%}

	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>类型列表</h5>
						<div class="ibox-tools">
							<a class="collapse-link">
								<i class="fa fa-chevron-up"></i>
							</a>
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="fa fa-wrench"></i>
							</a>
							<ul class="dropdown-menu dropdown-user">
								<li><a href="#">Config option 1</a>
								</li>
								<li><a href="#">Config option 2</a>
								</li>
							</ul>
							<a class="close-link">
								<i class="fa fa-times"></i>
							</a>
						</div>
					</div>
					<div class="ibox-content">
						<div class="table-responsive">

							<table class="table table-striped table-bordered table-hover " id="editable">
								<thead>
								<tr>
									<th>类型ID</th>
									<th>名称</th>
								</tr>
								</thead>
								<tbody>

								{% for type in types %}
									<tr class="gradeX">
										<td>{{ type.id }} </td>
										<td>{{ type.name}} </td>
									</tr>
								{% endfor %}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

{% endblock %}

{% block plugin_js %}
	{{ javascript_include("3rdpart/dataTables/datatables.min.js") }}
	{{ javascript_include("3rdpart/jeditable/jquery.jeditable.js") }}
{% endblock %}

{% block page_js %}
	<script>
		$(document).ready(function(){
			$('.dataTables-example').DataTable({
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [
					{ extend: 'copy'},
					{extend: 'csv'},
					{extend: 'excel', title: 'ExampleFile'},
					{extend: 'pdf', title: 'ExampleFile'},

					{extend: 'print',
						customize: function (win){
							$(win.document.body).addClass('white-bg');
							$(win.document.body).css('font-size', '10px');

							$(win.document.body).find('table')
								.addClass('compact')
								.css('font-size', 'inherit');
						}
					}
				]

			});

			/* Init DataTables */
			var oTable = $('#editable').DataTable();

			/* Apply the jEditable handlers to the table */
			oTable.$('td').editable( '../example_ajax.php', {
				"callback": function( sValue, y ) {
					var aPos = oTable.fnGetPosition( this );
					oTable.fnUpdate( sValue, aPos[0], aPos[1] );
				},
				"submitdata": function ( value, settings ) {
					return {
						"row_id": this.parentNode.getAttribute('id'),
						"column": oTable.fnGetPosition( this )[2]
					};
				},

				"width": "90%",
				"height": "100%"
			} );


		});

		function fnClickAddRow() {
			$('#editable').dataTable().fnAddData( [
				"Custom row",
				"New row",
				"New row",
				"New row",
				"New row" ] );

		}
	</script>
{% endblock %}