<ul class="list-group facets-list" id="facets">
    <li class="list-group-item">
		<b>Agencies</b>
	</li>
    @if (!isset($facets))
    <li class="list-group-item active">
        <blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">No Agencies</blockquote>
        </li>
    @else
        @foreach ($facets["agencies"] as $name => $count)
            <li class="list-group-item">
            <span class="badge">{{$count}}</span>
            <blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">{{$name}}</blockquote>
            </li>
        @endforeach
    @endif

</ul>