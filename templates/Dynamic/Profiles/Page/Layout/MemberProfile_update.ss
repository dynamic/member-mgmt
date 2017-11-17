<div class="typography>">
    <div class="clearfix add-top">
    </div>
    <div class="col-sm-12">
        <h1>$Title</h1>
	</div>
	<div class="col-sm-8">
        <div class="content">
			$Content
			<% if $CanAddMembers %>
                <div class="row">
                    <div class="col-sm-6">
						$Form
                    </div>
                    <div class="col-sm-6">
                        <h2><%t MemberProfiles.ADDMEMBER 'Add Member' %></h2>
                        <p><%t MemberProfiles.ADDMEMBERLINK 'You can use this page to <a href="{addLink}">add a new member</a>.' addLink=$Link(add) %></p>
                        <h2><%t MemberProfiles.YOURPROFILE 'Your Profile' %></h2>
                    </div>
                </div>
			<% else %>
                <div class="add-top">
					$Form
                </div>
			<% end_if %>
        </div>
    </div>
	<div class="col-sm-4">
		<% include MemberProfileNav %>
	</div>
</div>