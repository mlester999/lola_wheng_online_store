
<br>
<div class="card card-outline rounded-0 card-navy ">
	<div class="card-header">
		<h3 class="card-title">List of Units of Measurements</h3>
		<div class="card-tools">
			<a href="./?page=measurements/manage_measurement" id="create_new" class="btn btn-flat btn-primary bg-gradient-primary border-0"><span class="fas fa-plus"></span> Add </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<div class="table-responsive">
				<table class="table table-sm table-hover table-striped table-bordered" id="list">
					<colgroup>
						<col width="5%">
						<col width="20%">
                        <col width="10%">
						<col width="25%">
						<col width="20%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Unit / Measurement</th>
                            <th>Code</th>
							<th>Description</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$i = 1;
						$qry = $conn->query("SELECT * from `measurements` order by `measurement_name` asc ");

                        if($qry->num_rows > 0) {
						    while($row = $qry->fetch_assoc()):
						?>
                                <tr>
                                    <td class="align-items-center text-center"><?php echo $i++; ?></td>
                                    <td class="align-items-center"><?php echo $row['measurement_name']; ?></td>
                                    <td class="align-items-center"><?php echo $row['measurement_code']; ?></td>
                                    <td class="align-items-center"><p class="mb-0 truncate-1"><?= $row['description'] ?></p></td>
                                    <td class="align-items-center text-center">
                                        <?php if($row['is_active'] == 1): ?>
                                            <span class="badge badge-success px-3 rounded-pill">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger px-3 rounded-pill">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-items-center" align="center">
                                        <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                Action
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item" href="./?page=measurements/view_measurement&measurement_id=<?php echo $row['measurement_id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="./?page=measurements/manage_measurement&measurement_id=<?php echo $row['measurement_id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                        </div>
                                    </td>
                                </tr>
						    <?php endwhile; ?>
                        <?php
                            }
                        ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<script>
    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Are you sure to delete this unit of measurement permanently?","delete_measurement",[$(this).attr('data-measurement_id')]);
            
        });
        $('.table').dataTable({
            columnDefs: [
                    { orderable: false, targets: [2,4] }
            ],
            order:[0,'asc'],
            "oLanguage": {
                "sEmptyTable": "No records available."
            }
        });
        $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle');
    });

    function delete_measurement($measurement_id){
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=delete_measurement",
            method:"POST",
            data:{measurement_id: $measurement_id},
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("An error occured.",'error');
                end_loader();
            },
            success:function(resp){
                if(typeof resp== 'object' && resp.status == 'success'){
                    location.reload();
                }else{
                    alert_toast("An error occured.",'error');
                    end_loader();
                }
            }
        })
    }
</script>