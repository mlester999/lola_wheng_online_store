<style>
    .user-avatar{
        width:3rem;
        height:3rem;
        object-fit:scale-down;
        object-position:center center;
    }
</style>

<br>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title">List of Online Users</h3>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<div class="table-responsive">
				<table class="table table-hover table-striped table-bordered " id="list">
					<colgroup>
						<col width="5%">
						<col width="10%">
						<col width="25%">
						<col width="20%">
						<col width="15%">
						<col width="15%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th class="text-center">Photo</th>
							<th>Name</th>
							<th>Username</th>
							<th class="text-center">Type</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$i = 1;
							$qry = $conn->query("SELECT * from `accounts` where id != '{$_settings->userdata('id')}' AND type = 4 order by full_name asc ");
							while($row = $qry->fetch_assoc()):
						?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<td class="text-center">
									<img src="<?=validate_image($row['image_path']) ?>" alt="" class="img-thumbnail rounded-circle user-avatar">
									<!-- <?php echo $row['image_path'] ?> -->
								</td>
								<td><?php echo $row['full_name'] ?></td>
								<td><?php echo $row['username'] ?></td>
								<td class="text-center">
									<?php if($row['type'] == 1): ?>
										Administrator
									<?php elseif($row['type'] == 2): ?>
										Manager
									<?php elseif($row['type'] == 3): ?>
										Cashier
									<?php elseif($row['type'] == 4): ?>
										Customer
									<?php endif; ?>
								</td>
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
										<a class="dropdown-item" href="./?page=online-users/manage_account&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-dark"></span> Edit</a>
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