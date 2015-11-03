<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<%@ Page Language="VB" %>
<%@ Import Namespace="System.Net.Mail" %>
<%@ Import Namespace="System.Text" %>

<script runat="server">

	Protected Sub Page_Load(ByVal sender As Object, ByVal e As EventArgs )
	
		If IsPostBack Then
			
			Dim sc As SmtpClient = New SmtpClient("smtp.yourServer.com")
            Dim sb As StringBuilder = New StringBuilder()
            Dim msg As MailMessage = Nothing
			
			sb.Append("Email from: " + txtEmail.Text + vbCrLf)
			sb.Append("Message   : " + txtMessage.Text + vbCrLf)
			
			Try
                msg = New MailMessage(txtEmail.Text, _
                    "yourEmail@domain.com", "Message from Web Site", _
                    sb.ToString())
				
				sc.Send(msg)
			
			Catch ex As Exception
				
				' something bad happened
				Response.Write("Something bad happened!")
				
			Finally
			
				If Not msg Is Nothing Then msg.Dispose()		
   				
			End Try
		
		End If
	
	End Sub

</script>




<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

<head runat="server">
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Enter your e-mail address</title>
</head>

<body>

<form id="form1" runat="server">
	Enter your e-mail address:<br />
	<asp:TextBox runat="server" id="txtEmail" Width="203px"></asp:TextBox>
&nbsp;<asp:RequiredFieldValidator runat="server" ErrorMessage="E-mail is required." id="RequiredFieldValidator1" ControlToValidate="txtEmail" Display="Dynamic">
	</asp:RequiredFieldValidator>
	<asp:RegularExpressionValidator runat="server" ErrorMessage="E-mail address invalid." id="RegularExpressionValidator1" ControlToValidate="txtEmail" ValidationExpression="\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*">
	</asp:RegularExpressionValidator>
	<br />
	<br />
	Enter your message:<br />
	<asp:TextBox runat="server" id="txtMessage" Width="244px" Height="125px" TextMode="MultiLine">
	</asp:TextBox>
	<br />
	<asp:RequiredFieldValidator runat="server" ErrorMessage="Message is required." id="RequiredFieldValidator2" ControlToValidate="txtMessage">
	</asp:RequiredFieldValidator>
	<br />
	<br />
	<asp:Button runat="server" Text="Submit" id="Button1" Width="116px" />
</form>

</body>

</html>
