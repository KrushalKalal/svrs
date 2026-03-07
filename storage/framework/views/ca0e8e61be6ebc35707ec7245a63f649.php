<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from themesflat.co/html/icolandhtml/home-defi-v1.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 16 Feb 2026 04:38:22 GMT -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?></title>

    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo e(asset('front/app/dist/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('front/app/dist/swiper-bundle.min.css')); ?>">
    <!-- end Styles -->

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="<?php echo e(asset('front/assets/images/logo/favicon.png')); ?>">
    <link rel="apple-touch-icon-precomposed" href="<?php echo e(asset('front/assets/images/logo/favicon.png')); ?>">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="home-defi-1 header-fixed" data-magic-cursor="show">
    <div class="preloader">
        <div class=" loader">
            <div class="square"></div>
            <div class="path">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <p class="text-load">Loading :</p>
        </div>
    </div>
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>
    <?php echo $__env->make('front.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('content'); ?>
    <?php echo $__env->make('front.layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script src="<?php echo e(asset('front/app/js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('front/app/js/jquery.magnific-popup.min.js')); ?>"></script>
    <script src="<?php echo e(asset('front/app/js/jquery.easing.js')); ?>"></script>
    <script src="<?php echo e(asset('front/app/js/app.js')); ?>"></script>
    <script src="<?php echo e(asset('front/app/js/count-down.js')); ?>"></script>
    <script src="<?php echo e(asset('front/app/js/aos.js')); ?>"></script>
    <script src="<?php echo e(asset('front/app/js/chart.js')); ?>"></script>

    <script src="<?php echo e(asset('front/app/js/swiper-bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('front/app/js/swiper.js')); ?>"></script>
    <script>
        const data3 = {
            labels: [
                'Reserve Fund',
                'Team & Advisor',
                'Presale Token',
                'Bounty Program',
                'Bounty Program',
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [40, 30, 10, 10, 10],
                backgroundColor: [
                    '#1998CA',
                    '#343EBF',
                    '#A00763',
                    '#DEAD2F',
                    '#1CA151'
                ],
            }]
        };

        const config3 = {
            type: 'doughnut',
            data: data3,
            width: 280,
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: false // Hide legend
                },
                scales: {
                    y: {
                        display: false // Hide Y axis labels
                    },
                    x: {
                        display: false // Hide X axis labels
                    }
                }
            }
        };

        const myChartv3 = new Chart(
            document.getElementById('myChart3'),
            config3
        );


        const data4 = {
            labels: [
                'Product Development',
                'Marketing',
                'Business Development',
                'Legal & Regulation',
                'Operational',
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [40, 30, 10, 10, 10],
                backgroundColor: [
                    '#1998CA',
                    '#343EBF',
                    '#A00763',
                    '#DEAD2F',
                    '#1CA151'
                ],
            }]
        };

        const config4 = {
            type: 'doughnut',
            data: data4,
            width: 280,
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: false
                },
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        display: false
                    }
                }
            }
        };

        const myChartv4 = new Chart(
            document.getElementById('myChart4'),
            config4
        );
    </script>
    <script src="<?php echo e(asset('front/app/js/mouse.js')); ?>"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
<?php /**PATH D:\xampp\htdocs\SVRS\resources\views/front/layout/main-layout.blade.php ENDPATH**/ ?>