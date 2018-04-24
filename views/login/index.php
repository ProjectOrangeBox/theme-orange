<?php pear::extends('_templates/orange_default') ?>

<?php pear::section('section_container') ?>

	<?=pear::open_multipart('/login',['method'=>'post','class'=>'form-signin']) ?>
  <h2 class="form-signin-heading">Please Login</h2>
  <label for="inputEmail" class="sr-only">Email address</label>
  <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" autofocus>
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password">
  <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
	<?=pear::close() ?>

<?php pear::end() ?>

<?php pear::section('page_style') ?>
<?php pear::parent() ?>
body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #eee;
}
.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading, .form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
<?php pear::end() ?>
