<h4>Menu</h4>
<ul>
    <li><a href="$Link">View Profile</a></li>
    <li><a href="$Link/update">Edit Profile</a></li>
    <li>
        <% if $CurrentMember %>
        <a href="Security/Logout">Log Out</a>
        <% else %>
        <a href="Security/Login?BackURL=$Link">Log In</a>
        <% end_if %>
    </li>
</ul>