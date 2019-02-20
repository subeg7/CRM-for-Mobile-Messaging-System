<?php $this->load->view('layout/header.php')?>
<h1 id="loginheader">Easy Service</h1>
<div class="container">
	<section id="content">
		<form action="/forgot_password" method="post">
			<h1 id="forgotpassword">Forgot Password</h1>
            <p><?php echo $message;?></p>
			<div>
				<input type="text" autocomplete="off" name="identity" placeholder="Username" required="" id="username" />
			</div>
			<div>
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

