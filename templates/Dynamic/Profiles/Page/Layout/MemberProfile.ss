<div class="col-md-12">
    <article role="article">
        <h1>$Title</h1>
        <% if $Content %>
            <div class="typography">$Content</div>
        <% end_if %>
    </article>
</div>
<div class="col-md-8">
    <% if $Profile %>
        <% with $Profile %>
            <h3>$FullName</h3>

            <% if $ProfileImage %>
                <img src="$ProfileImage.Fill(200,300).URL" class="img-responsive profile-image">
            <% else %>
                <img src="resources/dynamic/silverstripe-member-profiles/images/default-profile.png" class="img-responsive profile-image">
            <% end_if %>
            
            <hr>
            <h4>Personal Information</h4>
            <p><strong>Email:</strong> <a href="mailto:$Email" title="Email $Email">$Email</a></p>
        <% end_with %>
    <% end_if %>

    $Form
</div>
<div class="col-md-4">
    <% include MemberProfileNav %>
</div>
