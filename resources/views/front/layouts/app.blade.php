<!DOCTYPE html>
<html class="no-js" lang="en_AU">
@include('front.layouts.partials.head')
<body data-instant-intensity="mousedown">
@include('front.layouts.partials.header')
@yield('main')
@include('front.layouts.partials.profile-modal')
@include('front.layouts.partials.footer')
@include('front.layouts.partials.scripts')
@yield('customJs')
</body>
</html>
