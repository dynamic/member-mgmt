<div class="col-md-12">
    <h1>$Title</h1>
    <% if $Content %>
        <div class="typography">$Content</div>
    <% end_if %>
</div>
<div class="col-md-8">
    <% if $CanAddMembers %>
        <div class="row">
            <div class="col-md-6">
                $Form
            </div>
            <div class="col-md-6">
                <h3><%t MemberProfiles.ADDMEMBER 'Add Member' %></h3>
                <p><%t MemberProfiles.ADDMEMBERLINK 'You can use this page to <a href="{addLink}">add a new member</a>.' addLink=$Link(add) %></p>
                <h3><%t MemberProfiles.YOURPROFILE 'Your Profile' %></h3>
            </div>
        </div>
    <% else %>
        $Form
    <% end_if %>
</div>
<div class="col-md-4">
    <% include MemberProfileNav %>
</div>
