{{--Bootstrap3--}}
@if ($breadcrumbs)
    <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (!$breadcrumb->last)
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumb->url }}">
                            @if ( isset($breadcrumb->icon) )
                                <i class="fa {{ $breadcrumb->icon }}"></i>
                            @endif
                            {{ $breadcrumb->title }}
                        </a>
                    </li>
                @else
                    <li class="breadcrumb-item active">
                        @if ( isset($breadcrumb->icon) )
                            <i class="fa {{ $breadcrumb->icon }}"></i>
                        @endif
                        {{ $breadcrumb->title }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif

{{--Bootstrap 4--}}
{{--<nav class="breadcrumb" role="navigation">--}}
    {{--@foreach ($breadcrumbs as $breadcrumb)--}}
        {{--@if (!$breadcrumb->last)--}}
            {{--<a class="breadcrumb-item" href="{{ $breadcrumb->url }}">--}}
                {{--@if ( isset($breadcrumb->icon) )--}}
                    {{--<i class="fa {{ $breadcrumb->icon }}"></i>--}}
                {{--@endif--}}
                {{--{{ $breadcrumb->title }}--}}
            {{--</a>--}}
        {{--@else--}}
            {{--<span class="breadcrumb-item active">--}}
                {{--@if ( isset($breadcrumb->icon) )--}}
                    {{--<i class="fa {{ $breadcrumb->icon }}"></i>--}}
                {{--@endif--}}
                {{--{{ $breadcrumb->title }}--}}
            {{--</span>--}}
        {{--@endif--}}
    {{--@endforeach--}}
{{--</nav>--}}

