<html>
	<head>
	</head>
	<body>
		<table width="600" border="0" cellspacing="0" cellpadding="0">
			<tr style="padding:0;margin:0">
				<td>
					<table width="600" height="122" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #917847; padding:0; margin:0">
						<tr>
							<td align="center">
								<img class="header-logo" width="320" height="61" src="{{Config::get('app.url')}}/images/email/email-logo-2.gif">
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width="600" height="122" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="border:1px solid #e3e3e3; padding:0; margin:0">
						<tr>
							<td width="60">
								&nbsp;
							</td>
							<td width="467">
								@yield('content')
							</td>
							<td width="60">
								&nbsp;
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr style="padding:0;margin:0">
				<td>
					<table width="600" height="122" border="0" cellspacing="0" cellpadding="0" bgcolor="#000000">
						<tr>
							<td width="60">

							</td>
							<td width="467">
								<a style="color:#917847 !important; text-decoration:none;" href="http://sondry.com" target="_blank">sondry.com</a>
							</td>
							<td width="60">
								<img class="header-logo" width="50" height="50" src="{{Config::get('app.url')}}/images/email/email-icon-2.gif">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>