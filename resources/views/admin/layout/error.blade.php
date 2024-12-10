<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
      data-header-styles="light" data-menu-styles="light" data-toggled="close">

    <head>

        <!-- Meta Data -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ $code }} Error</title>
        <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
        <meta name="Author" content="Spruko Technologies Private Limited">
        <meta name="keywords"
              content="bootstrap html template, dashboard html css, dashboard, html, admin dashboard template, crm dashboard, bootstrap admin, sales dashboard, html and css template, html admin template, admin, bootstrap dashboard template, html and css, projects dashboard, html css js templates">
        <!-- Favicon -->
        <link rel="icon" href="/assets/images/brand-logos/favicon.ico" type="image/x-icon">

        <!-- Main Theme Js -->
        <script src="/assets/js/authentication-main.js"></script>

        <!-- Bootstrap Css -->
        <link id="style" href="/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Style Css -->
        <link href="/assets/css/styles.css" rel="stylesheet">

        <!-- Icons Css -->
        <link href="/assets/css/icons.css" rel="stylesheet">


    </head>

    <body>

        <div class="page error-bg">
            <!-- Start::error-page -->
            <div class="error-page">
                <div class="container">
                    <div class="my-auto">
                        <div class="row align-items-center justify-content-center h-100 gap-5">
                            <div class="col-xl-4 col-lg-5 col-md-6 d-lg-block d-none">
                                <img src="/assets/images/media/media-87.png" alt=""
                                     class="bg-white-transparent rounded-circle backdrop-blur img-fluid p-5">
                            </div>
                            <div class="col-xl-6 col-lg-5 col-md-6 col-12">
                                <p class="fs-16 mb-3 text-fixed-white">{{ $hint }}</p>
                                <p class="fs-1 fw-semibold mb-3 text-fixed-white">{{ $code }}</p>
                                <p class="fs-16 text-fixed-white mb-4 op-8">{{ $description }}</p>
                                <a href="{{ route('admin') }}" class="btn btn-lg mb-2 border-0 bg-fixed-white me-3">Назад на главную</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    </body>

</html>
