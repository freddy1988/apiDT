<html>
<head>
<script src="../bootstrap/js/functions.js"></script>
</head>
		<div id="c3">
		
			<form id="form2" role="form" style="margin:0 auto;max-width:600px;padding:15px;">
				<legend>Edit Money</legend>
				<div class="form-group">
					<input type="hidden" name="id_money" id="id_money" value=<?php echo $_POST["id"];?>>
					<label for="name">Name</label>
					<input type="text" class="form-control" id="namee" name="namee" value=<?php echo $_POST["name"];?>>
					<label for="price">Price</label>
					<input type="text" class="form-control" id="pricee" name="pricee" value=<?php echo $_POST["price"];?>>
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-primary" id="edit_money" value="edit_money">Update</button> 
					<button type="button" class="btn btn-primary" id="return" value="return">Return</button>
				</div>
				<div id="messages"></div>
			</form>
		</div>

</html>