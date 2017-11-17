<div class="typography>">
    <div class="clearfix add-top"></div>
    <div class="col-sm-12">
        <h2>$Title</h2>
		<div class="content">
			$Content
		</div>
    </div>
	<div class="col-sm-8">
		<% if $Profile %>
        <article>
			<% with $Profile %>
                <h3 class="green">$FullName</h3>
                <div>
					<% if $ProfileImage %>
                        <img src="$ProfileImage.Fill(200,300).URL" class="scale-with-grid half-bottom profile-image">
					<% else %>
                        <img src="resources/dynamic/silverstripe-member-profiles/images/default-profile.png" class="scale-with-grid half-bottom profile-image">
					<% end_if %>
                </div>
                <hr>
                <h4 style="display: inline-block;" class="remove-top add-bottom">Personal Information</h4>
				<p><strong>Email:</strong> <a href="mailto:$Email">$Email</a></p>
			<% end_with %>
        </article>
		<% end_if %>

		$Form
	</div>
	<div class="col-sm-4">
		<% include MemberProfileNav %>
	</div>
</div>