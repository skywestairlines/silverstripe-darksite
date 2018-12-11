<!DOCTYPE html>
<html lang="$ContentLocale">
  <head>
	<% base_tag %>
	<title><% if MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
	$MetaTags(false)
	<link rel="shortcut icon" href="/favicon.ico" />
	</head>
<body>
	<div id="BgContainer">
		<div id="Container">
			<% include StatusMessage %>
			<div id="Header">
				<a href="/"><div class="logo"></div></a>
			</div>
			
			<div id="Navigation">
				<% include Navigation %>
		  	</div>
	  	
		  	<div class="clear"><!-- --></div>
	  	
		  	<div id="Layout">
				$Layout
			</div>
		   <div class="clear"><!-- --></div>
		</div>
		<div class="push"></div> <!-- sticks the footer to baseline -->
	</div>
	<div class="clear"><br /></div>
	<div class="clear"><br /><!-- --><br /></div>
	<div id="Footer">
		<% include Footer %>
	</div>
</body>
</html>