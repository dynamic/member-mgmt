<h3>Menu</h3>
<div class="list-group">
    <a href="$Link" class="list-group-item">View Profile</a>
    <a href="$Link/update" class="list-group-item">Edit Profile</a>
    <% if $CurrentMember %>
        <a href="Security/Logout" class="list-group-item">Log Out</a>
    <% else %>
        <a href="Security/Login?BackURL=$Link" class="list-group-item">Log In</a>
    <% end_if %>
</div>