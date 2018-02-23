<!DOCTYPE html>
<html lang="{{ config("app.locale") }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>{{ config("app.name") }}</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <!--begin::Page Vendors -->
    <link href="{{ asset("assets/vendors/custom/fullcalendar/fullcalendar.bundle.css") }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors -->
    <link href="{{ asset("assets/vendors/base/vendors.bundle.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("assets/demo/demo9/base/style.bundle.css") }}" rel="stylesheet" type="text/css" />
    <!--end::Base Styles -->
    <link rel="shortcut icon" href="{{ asset("assets/demo/demo9/media/img/logo/favicon.ico") }}" />
</head>
<!-- end::Head -->
<!-- end::Body -->
<body class="m--skin- m-page--loading-enabled m-page--loading m-content--skin-light m-header--fixed m-aside--offcanvas-default">
@include("partials.loader-base")
@include("body")
<!--begin::Base Scripts -->
<script src="{{ asset("assets/vendors/base/vendors.bundle.js") }}" type="text/javascript"></script>
<script src="{{ asset("assets/demo/demo9/base/scripts.bundle.js") }}" type="text/javascript"></script>
<!--end::Base Scripts -->
<!--begin::Page Vendors -->
<script src="{{ asset("assets/vendors/custom/fullcalendar/fullcalendar.bundle.js") }}" type="text/javascript"></script>
<!--end::Page Vendors -->                                                        <!--begin::Page Snippets -->
<script src="{{ asset("assets/app/js/dashboard.js") }}" type="text/javascript"></script>
<!--end::Page Snippets -->
<!-- begin::Page Loader -->
<script>
    $(window).on('load', function () {
        $('body').removeClass('m-page--loading');
    });
</script>
<!-- end::Page Loader -->
</body>
<!-- end::Body -->
</html>