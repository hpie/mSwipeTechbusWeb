<!--<form action="auth/login" method="post">
  <div class="imgcontainer">
    <img src="<?php echo BASE_URL; ?>/assets/image_crud/images/login.png" alt="Avatar" class="avatar">
  </div>
  <div class="containers">    
    <input type="email" placeholder="Enter Username" name="email" required >   
    <input type="password" placeholder="Enter Password" name="password" required>        
    <button type="submit">Login</button>
    <label>
        <input type="checkbox" name="remember"> Remember me
    </label>
    <br>
    <label>
    <span>Don't have Account? <a href="auth/sign_up">Sign Up</a></span>
    </label>
    <label>
        <span>Forgot <a href="auth/forgot_password">password?</a></span></label>
  </div>
</form>-->


<div class="container">  
    <form class="form-horizontal login_from box-shadow" action="auth/login" method="post">
      <div class="login_img_container">
          <center><img src="<?php echo BASE_URL; ?>/assets/image_crud/images/login.png" " alt="Avatar" class="img-responsive img-circle"></center>
    </div>      
    <div class="form-group">      
      <div class=" col-sm-12">
        <input type="email" class="form-control" placeholder="Enter username" name="email">
      </div>
    </div>
    <div class="form-group">      
      <div class=" col-sm-12">          
        <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
      </div>
    </div>    
    <div class="form-group">        
      <div class=" col-sm-12 login-btn">
        <button type="submit" class=" btn btn-success col-xs-12">Submit</button>
      </div>
    </div>
        <div class="form-group">        
      <div class=" col-sm-12 ext_forgot_pass"> 
          <label style=""><input type="checkbox" name="remember"> Remember me</label><br>
        <span style="">Forgot <a href="auth/forgot_password">password?</a></span><br>
        <!-- <span style="">Don't have Account? <a href="auth/sign_up">Sign Up</a></span> -->
      </div>
    </div>   
  </form>
</div>


<?php //echo $form->open();

//	echo $form->messages();	
//	echo $form->bs3_email('Email');
//	echo $form->bs3_password('Password'); 
        
?>

<!--	<div class="checkbox">
		<label>
			<input type="checkbox" name="remember"> Remember me
		</label>
	</div>
	<div class="form-group">
		Don't have Account? <a href="auth/sign_up">Sign Up</a>
	</div>
	<div class="form-group">
		<a href="auth/forgot_password">Forgot password?</a>
	</div>-->
<?php 
//    echo $form->bs3_submit('Login'); 
//    echo $form->close();
?>