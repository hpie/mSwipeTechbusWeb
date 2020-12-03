<style>
.primary_btn{
    background: #276afb;
    color: #fff;
    border-color: #4f60b3;
}
.bdr0{
	border:0px;
}
.bdr0:focus{
	border:0px;
}
.forgot_inp{
	border:2px solid #ccc;
}
.forgot_inp:focus{
	box-shadow:2px 1px 3px #276afb;
}
.addon{
	background: #5287fb;
    border-radius: 0px;
    color: #fff;
    font-size: 16px;
}
</style>
<div class="form-gap"></div>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="text-center">
                  <h3><i class="fa fa-lock fa-4x"></i></h3>
                  <h2 class="text-center">Forgot Password?</h2>
                  <p>You can reset your password here.</p>
                  <div class="panel-body">
    
                    <form id="register-form" role="form" autocomplete="off" class="form" method="post">
    
                      <div class="form-group">
                        <div class="input-group forgot_inp">
                          <span class="input-group-addon bdr0 addon"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                          <input id="email" name="email" placeholder="email address" class="form-control bdr0"  type="email">
                        </div>
                      </div>
                      <div class="form-group">
                        <input name="recover-submit" class="btn btn-md btn-primary btn-block primary_btn" value="Reset Password" type="submit">
                      </div>
                      
                      <input type="hidden" class="hide" name="token" id="token" value=""> 
                    </form>
    
                  </div>
                </div>
              </div>
            </div>
          </div>
	</div>
</div>