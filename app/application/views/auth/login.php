<?php $this->load->view('layout/header.php')?>
<h1 id="loginheader">Easy Service</h1>
<div class="container">
	<section id="content">
		<!-- <form action="<?php //echo '/login'; ?>" method="post"> -->
		<form action="login" method="post">
			<h1>Login</h1>
            <p><?php echo $message;?></p>
			<div>
				<input type="text" autocomplete="off" name="identity" placeholder="Username" required="" id="username" />
			</div>
			<div>
				<input type="password" autocomplete="off" name="password" placeholder="Password" required="" id="password" />
			</div>
			<div>
				<input type="submit" value="Log in" />
				<a href="forgot_password">Lost your password?</a>
			</div>
		</form><!-- form -->
		<!--<div class="button">
			<a href="#">Download source file</a>
		</div>--><!-- button -->
	</section><!-- content -->
</div><!-- container -->

<?php $this->load->view('layout/footer.php')?>
