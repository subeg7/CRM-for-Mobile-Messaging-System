<?php $this->load->view('layout/header.php')?>
<h1 id="loginheader">Easy Service</h1>
<div class="container">
	<section id="content">
		<form action="/reset_password" method="post">
			<h1 id="forgotpassword">Change Password</h1>
            <p><?php echo $message;?></p>
			<div>
            	<input type="password" placeholder="New Password" name="new" value="" id="new" pattern="^.{8}.*$">
			</div>
            <div>
            	<input type="password" placeholder="Confirm Password" name="new_confirm" value="" id="new_confirm" pattern="^.{8}.*$">
			</div>
			<div>
            	<?php echo form_input($user_id);?>
            	<?php echo form_hidden($csrf); ?>
                <input type="hidden" name="code" value="<?php echo $code; ?>" >
				<input type="submit" value="Submit" />
				<a href="/login">Back</a>
			</div>
		</form><!-- form -->
		<!--<div class="button">
			<a href="#">Download source file</a>
		</div>--><!-- button -->
	</section><!-- content -->
</div><!-- container -->

<?php $this->load->view('layout/footer.php')?>
