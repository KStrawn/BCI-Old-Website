<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<%@ Page Language="VB" %>
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

<head runat="server">
<meta content="en-us" http-equiv="Content-Language" />
<meta content="text/html; charset=windows-1252" http-equiv="Content-Type" />
<title>LiteratureRequest</title>
<style type="text/css">
.style1 {
	text-align: center;
}
</style>
</head>

<body>

<form id="form1" runat="server">
	<table style="width: 100%">
		<tr>
			<td>&nbsp;</td>
			<td style="width: 680px">test</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td style="height: 68px"></td>
			<td style="width: 680px; height: 68px">Enter your email Address:
			<asp:TextBox id="txtemail" runat="server" Width="267px"></asp:TextBox>
			<asp:RequiredFieldValidator id="RequiredFieldValidator1" runat="server" ControlToValidate="txtemail" Display="Dynamic" ErrorMessage="Email is Required"></asp:RequiredFieldValidator>
			<asp:RegularExpressionValidator id="RegularExpressionValidator1" runat="server" ControlToValidate="txtemail" Display="Dynamic" ErrorMessage="Email address invalid" ValidationExpression="\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*"></asp:RegularExpressionValidator>
			<br />
			<br />
			Enter your message:<br />
			<asp:TextBox id="txtmessage" runat="server" Height="106px" TextMode="MultiLine" Width="430px"></asp:TextBox>
			<br />
			<asp:RequiredFieldValidator id="RequiredFieldValidator2" runat="server" ControlToValidate="txtmessage" ErrorMessage="Message is Required"></asp:RequiredFieldValidator>
			<br />
			<br />
			<asp:Button id="Button1" runat="server" Text="Submit" Width="102px" />
			<br />
			</td>
			<td style="height: 68px"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="width: 680px">&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="style1" style="width: 680px">&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
</form>

</body>

</html>
