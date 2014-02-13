<ul class="list-group">
	<li class="list-group-item">
		<b>Agencies</b>
	</li>
    @if (!isset($facets))
        <li class="list-group-item">
        <blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">No Agencies</blockquote>
        </li>
    @else
        @foreach ($facets["Agencies"] as $agencyFacet)
            <li class="list-group-item">
            <span class="badge">$agencyFacet["value"]</span>
            <blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">{{$agencyFacet["name"]}}</blockquote>
            </li>
        @endforeach
    @endif
	<li class="list-group-item">
		<b>Offices</b>
	</li>
    @if (!isset($facets))
        <li class="list-group-item">
        <blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">No Offices</blockquote>
        </li>
    @else
        @foreach ($facets["Offices"] as $officeFacet)
            <li class="list-group-item">
            <span class="badge">$officeFacet["value"]</span>
            <blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">{{$officeFacet["name"]}}</blockquote>
            </li>
        @endforeach
    @endif
	<li class="list-group-item">
		<b>Categories</b>
	</li>
    @if (!isset($facets))
        <li class="list-group-item">
        <blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">No Categories</blockquote>
        </li>
    @else
        @foreach ($facets["Categories"] as $agencyFacet)
            <li class="list-group-item">
            <span class="badge">$categoryFacet["value"]</span>
            <blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">{{$categoryFacet["name"]}}</blockquote>
            </li>
        @endforeach
    @endif
</ul>