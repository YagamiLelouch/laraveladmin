Hello <i>{{ $user->name }}</i>,
<p>This is a demo email for testing purposes! Also, it's the HTML version.</p>

<p><u>Demo object values:</u></p>

<div>
    <p><b>Demo One:</b>&nbsp;{{ $user->id }}</p>
    <p><b>Demo Two:</b>&nbsp;{{ $user->email }}</p>
</div>

<p><u>Values passed by With method:</u></p>

<div>
    <p><b>testVarOne:</b>&nbsp;{{ $user->password }}</p>
    <p><b>testVarTwo:</b>&nbsp;{{ $user->remember_token }}</p>
</div>

Thank You,
<br/>
<i>{{ $user->updated_at }}</i>