<br>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title">List of Customers</h3>
		<div class="card-tools">
			<a href="./?page=customers/manage_customer" id="create_new" class="btn btn-flat btn-primary bg-gradient-primary border-0"><span class="fas fa-plus"></span>  Add </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<div class="table-responsive">
				<table class="table table-hover table-striped table-bordered " id="list">
					<colgroup>
						<col width="5%">
						<col width="10%">
						<col width="40%">
						<col width="20%">
						<col width="15%">
						<col width="15%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Customer Name</th>
							<th class="text-center">Home Address</th>
							<th class="text-center">Contact Number</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$i = 1;
							$qry = $conn->query("SELECT * from `customers` order by id asc ");
							while($row = $qry->fetch_assoc()):
						?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<td><?php echo $row['name'] ?></td>
								<td><?php echo $row['home_address'] ?></td>
								<td class="text-center"><?php echo $row['contact_number'] ?></td>
								<td class="align-items-center text-center">
									<?php if($row['is_active'] == 1): ?>
										<span class="badge badge-success px-3 rounded-pill">Active</span>
									<?php else: ?>
										<span class="badge badge-danger px-3 rounded-pill">Inactive</span>
									<?php endif; ?>
								</td>
								<td align="center">
									<button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
											Action
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<div class="dropdown-menu" role="menu">
										<a class="dropdown-item" href="./?page=customers/view_customer&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                            <div class="dropdown-divider"></div>
										<a class="dropdown-item" href="./?page=customers/manage_customer&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-blue"></span> Edit</a>
									</div>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this User permanently?","delete_user",[$(this).attr('data-id')])
		});

		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc'],
			"oLanguage": {
                "sEmptyTable": "No records available."
            }
		});
	});

	function delete_user($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Users.php?f=delete",
			method:"POST",
			data:{id: $id},
			error:err=>{
				// console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(resp == 1){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>