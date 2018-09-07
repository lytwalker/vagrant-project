<?php

include '../shared/header.php';
include '../shared/access_levels/admin.php';
include '../shared/user.php';

$dbUtils = new DbUtils ();
if(!empty($_GET['a'])){
	$list = getUsers ();
	$title = "Blah Users";
}else{
	$list = getUsers ();
	$title = "Users";
}
?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<h3><?= $title; ?></h3>
			<div class="x_panel">
				<a href="edit.php" class="btn btn-primary">Add User</a>
				<div class="x_content">
				
					<div class="table-responsive">
						<table class="table table-striped jambo_table bulk_action">
							<thead>
								<tr class="headings">
									<th class="column-title">Username</th>
									<th class="column-title">Email</th>
									<th class="column-title">Action</th>
								</tr>
							</thead>
						<?php while($row = $list->fetch_assoc()) { ?>
						<tr>
									<td><?= $row['username'] ?></td>
									<td><?= $row['email'] ?></td>
									<td class="action">
										<a href="edit.php?userid=<?= $row['id'] ?>" data-name="<?= $row['username'] ?>" title="Edit <?= $row['username'] ?>" data-toggle="tooltip"><i class="fa fa-pencil"></i></a> 
										<a class="delete" href="delete_user.php?userid=<?= $row['id'] ?>" data-name="<?= $row['username'] ?>"  title="Delete <?= $row['username'] ?>" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
									</td>
								</tr>	
						<?php } ?>
						</table>
					</div>
						
				</div>				
			</div>			
		</div>		
	</div>
</div>
<?php 
	include '../shared/footer.php';
?>