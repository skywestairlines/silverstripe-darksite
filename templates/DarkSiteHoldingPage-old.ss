<!DOCTYPE html>
<html lang="$ContentLocale">
  <head>
	<% base_tag %>
	<title><% if MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
	$MetaTags(false)
	<link rel="shortcut icon" href="/favicon.ico" />
	<!-- <script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script> -->
	</head>
<body>
	<div id="BgContainer">
		<div id="Container">
			<div class="DarkHeader">
				<div class="DarkLogo"></div>
			</div>
	  	
		  	<div id="Layout">
				<div class="landingPageTop"></div>
				<div class="landingPage">
					<div class="typography">
						<% loop MainStatement %>
						<div class="eightColumnLeft columnBox">
						<h3>$PRPlace</h3>
						$Content
						<br /><br />
						</div>
						<% end_loop %>
						
						<% loop MainStatement %>
						<% if showContacts %>
						<div class="fourColumnBox-Right columnBox">
							<h3>Contacts</h3>
							<p><strong>Media Inquiries</strong>
							<br />
							$MediaInq</p>
							<p><strong>Information for Family Members</strong></p>
							<span style="margin-top:-12px;display:block;">$FamilyInq</span>
							<p>$Familyfone</p>
						</div>
						<% else %>
						<div class="fourColumnBox-Right columnBox">
						&nbsp;
						</div>
						<% end_if %>
						<% end_loop %>
						
						<div class="clear"><!-- --></div>
						
						<% loop MainStatement %>
						<% if showReleases %>
						<div class="fourColumnBox-Left columnBox">
							<h3>Press Releases</h3>
							<% loop Top.DarkReleases %><br />
							<a href="$DarkRelease.URL" target="_blank" class="pdfBoxLink"><div class="prDate">$Date.format(m/d/Y) </div>
							<div class="pdfSummary">$Title</div></a>
							<br />
							<% end_loop %>
						</div>
						<% else %>
						<div class="fourColumnBox-Left columnBox">
						&nbsp;
						</div>
						<% end_if %>
						<% end_loop %>
						
						<% loop MainStatement %>
						<% if showResources %>
						<div class="fourColumnBox-Middle columnBox">
							<h3>Resources</h3>
							<% loop Top.DarkResources %>
							<div class="{$EvenOdd}"><a href="$DarkResource.URL" target="_blank">$Title</a></div>
							<% end_loop %>
						</div>
						<% else %>
						<div class="fourColumnBox-Middle columnBox">
						&nbsp;
						</div>
						<% end_if %>
						<% end_loop %>
						
						<% loop MainStatement %>
						<% if showBriefing %>
						<div class="fourColumnBox-Right columnBox">
							<h3>Media Briefing</h3>
							<div class="briefingLeft">Location:</div> <div class="briefingRight">
								$MediaLoc
							</div>
							<div class="briefingLeft">Address:</div> <div class="briefingRight">
								$MediaAddress
							</div>
							<div class="briefingLeft">Date:</div> <div class="briefingRight">$MediaDate.Format(F d), $MediaDate.Format(Y)</div>
							<div class="briefingLeft">Time:</div> <div class="briefingRight">$MediaTime</div>
							<div class="briefingLeft">Speakers:</div> <div class="briefingRight">$MediaSpeak</div>
						</div>
						<% else %>
						<div class="fourColumnBox-Right columnBox">
						&nbsp;
						</div>
						<% end_if %>
						<% end_loop %>
						
						<div class="clear"><!-- --></div>
						
						<% loop MainStatement %>
						<% if showPartners %>
						<div class="fourColumnBox-Right columnBox">
							<% loop Top.DarkPartner %>
							<% if DarkShowMe %>
							<h3>Partner Info</h3>
							<% if DarkLogo %><img class="left" src="$DarkLogo.URL" /><% end_if %>
							$DarkContent
							<p><a href="$DarkPartnerLink" target="_blank">Click here</a> to access {$DarkTitle}'s site.</p>
							<% end_if %>
							<% end_loop %>
						</div>
						<% else %>
						<div class="fourColumnBox-Right columnBox">
						&nbsp;
						</div>
						<% end_if %>
						<% end_loop %>
						<div class="clear"><!-- --></div>
					</div>
				</div>
				<div class="landingPageBottom"></div>
			</div>
		   <div class="clear"><!-- --></div>
		</div> 
	</div>
</body>
</html>