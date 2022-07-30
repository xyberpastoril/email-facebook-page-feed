<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="mt-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-10 col-lg-8 col-xl-6 col-xxl-4 mx-auto">
                <h3 class="text-center">Posts from Visayas State University</h3>
                <p class="text-center"><small>As of {{ now()->format('Y-m-d h:i:m') }}</small></p>
            </div>
        </div>
        @foreach($posts as $post)
            <div class="row mb-4">
                <div class="card col-md-10 col-lg-8 col-xl-6 col-xxl-4 mx-auto">
                    <div class="card-body">
                        @if(isset($post['title']))
                            <h4 class="card-title">{{ $post['title']}}</h4>
                        @endif
                        @if(isset($post['link_photo_url']))
                            <img src="{{ $post['link_photo_url'] }}" width="100%" class="mb-3" style="border-radius:8px">
                        @endif
                        {!! $post['text'] !!}
                        @if(isset($post['link_url']))
                            <a href="{{$post['link_url']}}" class="btn btn-sm btn-primary btn-block">
                                Read More
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>

{{-- @foreach($posts as $post)
<div>
    <h3>{{ $post['title']}} </h3>
    <img src="{{ $post['link_photo_url'] }}" width="200px">
    {!! $post['text'] !!}
    <a href="{{ $post['link_url'] }} ">Go to Article</a>
</div>
@endforeach --}}