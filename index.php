<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <title>Basic CMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="./asset/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="./asset/css/style.css"/>

    <!-- Include the Quill library -->
    <link href="./asset/css/quill.css" rel="stylesheet">
    <script src="./asset/js/quill.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <script src="https://unpkg.com/vue-router@2.0.0/dist/vue-router.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body>


<div class="container">
    <div class="page-body mt-2">
        <div id="app">
            <navbar></navbar>
            <router-view></router-view>
        </div>
    </div>
    <div class="footer-area">
        <div class="col-md-12 text-center">
            <p>All rights reserved &copy; Basic-CMS</p>
        </div>
    </div>
</div>
<script type="text/javascript" src="./asset/js/jquery.min.js"></script>
<script type="text/javascript" src="./asset/js/bootstrap.min.js"></script>
<script type="module" src="./view/main.js"></script>

</body>
</html>

