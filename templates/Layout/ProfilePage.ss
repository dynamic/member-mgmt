<div class="typography>">
    <div class="clearfix add-top"></div>
    <div class="col-sm-12">
        <h1>$Title</h1>
    </div>
	<div class="col-sm-8">
        <article>
			<% with $Profile %>
                <h3 class="green">$FullName</h3>
                <div>
					<% if $ProfilePhoto %>
                        <img src="$ProfilePhoto.URL" class="scale-with-grid half-bottom profile-image">
					<% else %>
                        <img src="resources/dynamic/silverstripe-member-profiles/images/default-profile.png" class="scale-with-grid half-bottom profile-image">
					<% end_if %>
                </div>
                <hr>
                <h4 style="display: inline-block;" class="remove-top add-bottom">Personal Information</h4>
				<p><strong>Email:</strong> <a href="mailto:$Email">$Email</a></p>
			<% end_with %>
        </article>
	</div>
	<div class="col-sm-4">
		<% include ProfileNav %>
	</div>
</div>