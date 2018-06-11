<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WordX - @yield('subtitle', '')</title>
    <link rel="stylesheet" href="/css/app.css" />
</head>

<body>
    {{--  @include('layouts._header')  --}}
    <div class="container">
      <div class="row">
          <div class="col-md-offset-1 col-md-10">
            @include('shared._messages')
          </div>
      </div>
      @yield('content')
        {{--  @include('layouts._footer')  --}}
    </div>
    <script src="/js/app.js"></script>
    <script src="/js/@yield('jsfile', '')"></script>
</body>

</html>