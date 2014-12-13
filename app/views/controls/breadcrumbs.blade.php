<h3>
@foreach ($breadcrumbs as $breadcrumb)
 @if(is_array($breadcrumb))
  @foreach($breadcrumb as $name => $url)
   @if(isset($url))
    <a href="{{ $url }}">{{{ $name }}}</a>
   @else
    <span>{{{ $name }}}</span>
   @endif
  @endforeach
 @else
    <span>{{{ $breadcrumb }}}</span>
 @endif
@endforeach
</h3>