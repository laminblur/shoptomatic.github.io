<?php
$CI =& get_instance();
?>
<div class="container-fluid wrapper">
	<?php 
	if($CI->session->flashdata('msg_ok')){
		echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$CI->session->flashdata('msg_ok').'</div>';
	}
	?>
	<form class="form-horizontal" id="form" method="post" action="" enctype="multipart/form-data">
		<fieldset>
			<legend>
				<?php echo lang('msg_settings');?>&nbsp;-&nbsp;<?php echo lang('msg_currency');?>
			</legend>

			<div class="form-group">
				<label class="control-label col-xs-2" for="txtName"><?php echo lang('msg_currency');?></label>
				<div class="col-xs-10">
					<select name="currency" class="form-control">
						<?php if($list!=null)
						foreach($list as $r){
							?>
						<option value="<?php echo $r->currency_code;?>"><?php echo $r->currency_code;?>&nbsp;(<?php echo $r->name;?>)</option>
						<?php }?>
					</select>
				</div>
			</div>

		</hr>

		<div class="form-group">
			<div class="col-xs-10 col-xs-offset-2">
				<button type="submit" class="btn btn-primary">
					<?php echo lang('msg_save');?>
				</button>
			</div>
		</div>

	</fieldset>
</form>
