<div class="container">  
    <form class="form-horizontal login_from box-shadow" id="defaultForm" onsubmit="return validateForm()" name="signup" style="background:#eee;padding: 10px;" action="auth/login" method="post">
        <div class="login_img_container ">
            <center><img src="<?php echo BASE_URL; ?>/assets/image_crud/images/login.png" alt="Avatar" class="img-responsive img-circle"></center>
        </div> 

        <div class="form-group">      
            <div class="col-sm-12">
                <input type="text" class="form-control" placeholder="Enter first name" name="first_name" required="">
            </div>
        </div>

        <div class="form-group">      
            <div class="col-sm-12">
                <input type="text" class="form-control" placeholder="Enter last name" name="last_name" required="">
            </div>
        </div>
        <div class="form-group" data-toggle="validator" role="form">      
            <div class="col-sm-12">
                <input type="email" class="form-control" placeholder="Enter email" name="email" required="">
            </div>
        </div>
        <div class="form-group">      
            <div class="col-sm-12">          
                <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required="">
            </div>
        </div> 

        <div class="form-group">      
            <div class="col-sm-12">          
                <input type="password" class="form-control" placeholder="Retype password" name="retype_password"  id="inputPasswordConfirm" data-match="#password" data-match-error="Whoops, these don't match" required>
            </div>
        </div> 
        <div class="form-group">
            <div class="col-sm-12">
                <p><?php echo $form->field_recaptcha(); ?></p>
            </div>
        </div>
        <div class="form-group">        
            <div class="col-sm-12 login-btn">
                <button type="submit" class="btn btn-success col-xs-12">Submit</button>
            </div>
        </div>
        <div class="form-group">        
            <div class="col-sm-12">                  
                <span style="">Have an Account? <a href="auth/login">Sign Up</a></span>
            </div>
        </div>   
    </form>
</div>


<script>
    function validateEmail(sEmail) {
        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        if (filter.test(sEmail)) {
            return true;
        } else {
            return false;
        }
    }
 $(document).ready(function () {
        $("form").submit(function () {
            var i=0;            
            var password = $('[name=password]').val();
            var retype_password = $('[name=retype_password]').val();
            if (password != retype_password) {
                $('[name=password]').css("border-color", "red");
                $('[name=retype_password]').css("border-color", "red");
                i=1;
            }
            var sEmail = $('[name=email]').val();
            if ($.trim(sEmail).length == 0) {
                 $('[name=email]').css("border-color", "red");
                 i=1;
            }
            if (validateEmail(sEmail)) {
                
            }else {
                $('[name=email]').css("border-color", "red");
                i=1;
            }
            
            if(i==1){
                return false;
            }           
        });
    });
</script>

<!--<script type="text/javascript">
    function validateForm() {
    var password = document.forms["signup"]["password"].value;
    var confirmPassword = document.forms["signup"]["retype_password"].value;
    if (password != confirmPassword) {
        document.get
        return false;
    }
}
</script>-->
<?php
//         echo $form->open();	
//	 echo $form->messages();
//	 echo $form->bs3_text('First Name', 'first_name');
//	 echo $form->bs3_text('Last Name', 'last_name');
//	 echo $form->bs3_email('Email');
//	 echo $form->bs3_password('Password', 'password');
//	 echo $form->bs3_password('Retype Password', 'retype_password');
?>

        <!--<p><?php //echo $form->field_recaptcha();    ?></p>-->
<!--	<div class="form-group">
                Have an Account? <a href="auth/login">Log In</a>
        </div>	-->
<?php //echo $form->bs3_submit('Sign Up');   ?>

<?php
//echo $form->close(); ?>