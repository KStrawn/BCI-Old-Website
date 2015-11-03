<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Login Form</title>
<link href="loginmodule.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h4 align="center" class="err"><span class="style1">Blessing the Children</span><br />
  Child and Sponsor Database</h4>

<form id="loginForm" name="loginForm" method="post" action="login-exec.php">
  <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
    <tr>
      <td width="112"><strong>User Name</strong></td>
      <td width="188">
	  <input name="login" type="text" class="textfield" id="login" style="width: 150px" /></td>
    </tr>
    <tr>
      <td><b>Password</b></td>
      <td>
	  <input name="password" type="password" class="textfield" id="password" style="width: 150px" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Login" /></td>
    </tr>
  </table>
</form>

<h6 align="center">&nbsp;</h6>
<h6 align="center">If you have not registered, you may register for access
<a href="http://data.blessingthechildren.org/PHP-Login/register-form.php">here</a>.&nbsp;
<br />
Please allow 2 business days for activation after registration.</h6>

</body>
</html>
