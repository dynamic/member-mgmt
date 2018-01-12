<div class="col-md-12">
    <article role="article">
        <h1>$Title</h1>
        <% if $Content %>
            <div class="typography">$Content</div>
        <% end_if %>
    </article>
</div>

<div class="col-md-4">
    <h2>Register</h2>
    $Form
</div>
<div class="col-md-4">
    <h2>Existing Profile?</h2>
    <p>Log in to your profile.</p>
    <a class="btn btn-primary" href="Security/Login?BackURL=$Link">Log In</a>
</div>
<div class="col-md-4">
    <% include MemberProfileNav %>
</div>
