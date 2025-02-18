<h5 style="margin: 0;">Hello</h5>
<p style="margin: 0;">&nbsp;</p>
<p>I'm {{ $data->name }}. Please contact me on mobile {{ $data->phone }}.</p>
<p>Below check out my message, I wait for your reply.</p>
<p>{{ $data->message }}</p>
<p style="margin: 0;">&nbsp;</p>
<p style="margin: 0;">&nbsp;</p>
@if(!empty($data->company))
<p style="margin: 0;"><strong>Company name : {{ $data->company }}</strong></p>
@endif