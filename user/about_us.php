<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobforce - Job Searching Web Page</title>
    <link rel="stylesheet" href="../assets/css/about_us.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
             @import url('https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    list-style: none;
    font-family: 'Montserrat', sans-serif;
}

        .social-link {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            border-radius: 50%;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .social-link:hover,
        .social-link:focus {
            background: #ddd;
            text-decoration: none;
            color: #555;
        }

        .team-member img {
            width: 120px;
            height: 120px;
        }
        .counter-box {
	display: block;
	background: #f6f6f6;
	padding: 40px 20px 37px;
	text-align: center
}

.counter-box p {
	margin: 5px 0 0;
	padding: 0;
	
	font-size: 18px;
	font-weight: 500
}

.counter-box i {
	font-size: 60px;
	margin: 0 0 15px;
	
}

.counter { 
	display: block;
	font-size: 32px;
	font-weight: 700;
	color: #666;
	line-height: 28px
}



    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="bg-light">
        <div class="container py-5">
            <div class="row h-100 align-items-center py-5">
                <div class="col-lg-6">
                    <h1 class="display-4">About Us</h1>
                    <p class="lead text-muted mb-0">Find Your Dream Job with Jobforce</p>
                    <p class="lead text-muted">Explore the best job opportunities that match your skills and aspirations.</p>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="https://bootstrapious.com/i/snippets/sn-about/illus.png" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- About 1 - Bootstrap Brain Component -->
    <section class="py-3 py-md-5">
    <div class="container">
        <div class="row gy-3 gy-md-4 gy-lg-0 align-items-lg-center">
            <div class="col-12 col-lg-6 col-xl-5">
                <img style="max-width: 80%; max-height: 80%;" class="img-fluid rounded" loading="lazy" src="../assets/img/logo/jobforce01.jpg" alt="About 1">
            </div>
            <div class="col-12 col-lg-6 col-xl-7">
                <div class="row justify-content-xl-center">
                    <div class="col-12 col-xl-11">
                        <h2 class="mb-3">Who Are We?</h2>
                        <p class="lead fs-4 text-secondary mb-3">Job Force is a dynamic job portal where candidates can search for their dream jobs and recruiting companies can find their perfect candidates.</p>
                        <p class="mb-5">We also offer a comprehensive learning section to help users enhance their skills and improve their employability. Our goal is to provide outstanding, captivating services.</p>
                        <div class="row gy-4 gy-md-0 gx-xxl-5">
                            <div class="col-12 col-md-6">
                                <div class="d-flex">
                                    <div class="me-4 text-primary">
                                        <!-- Add icon or image if needed -->
                                    </div>
                                    <div>
                                        <h2 class="h4 mb-3">Empowering Careers</h2>
                                        <p class="text-secondary mb-0">We are dedicated to creating a seamless and effective platform for both job seekers and employers across all mediums</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex">
                                    <div class="me-4 text-primary">
                                        <!-- Add icon or image if needed -->
                                    </div>
                                    <div>
                                        <h2 class="h4 mb-3">Innovative Learning</h2>
                                        <p class="text-secondary mb-0">We believe in innovation by merging primary skills with elaborate ideas.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <div class="container">
    
    <div class="row">

	<div class="four col-md-3">
		<div class="counter-box colored">
			<i class="fa fa-thumbs-o-up"></i>
			<span class="counter">2147</span>
			<p>Happy Customers</p>
		</div>
	</div>
	<div class="four col-md-3">
		<div class="counter-box">
			<i class="fa fa-group"></i>
			<span class="counter">3275</span>
			<p>Registered Organizations</p>
		</div>
	</div>
	<div class="four col-md-3">
		<div class="counter-box">
			<i class="fa  fa-shopping-cart"></i>
			<span class="counter">289</span>
			<p>Sold cources</p>
		</div>
	</div>
	<div class="four col-md-3">
		<div class="counter-box">
			<i class="fa  fa-user"></i>
			<span class="counter">1563</span>
			<p>Registered Users</p>
		</div>
	</div>
  </div>
  <script>
    
        
        
$(document).ready(function() {

$('.counter').each(function () {
$(this).prop('Counter',0).animate({
Counter: $(this).text()
}, {
duration: 4000,
easing: 'swing',
step: function (now) {
    $(this).text(Math.ceil(now));
}
});
});

});  




  </script>	
</div>

    <div class="bg-white py-5">
        <div class="container py-5">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <h2 class="font-weight-light">Vision</h2>
                    <p class="font-italic text-muted mb-4">Our vision is to create a global platform that bridges the gap between job seekers and employers. We strive to provide an environment where every individual has the opportunity to realize their career aspirations. We believe in empowering professionals with the right resources, support, and guidance to achieve their full potential in the job market.</p>
                </div>
                <div class="col-lg-5 px-5 mx-auto order-1 order-lg-2">
                    <img src="https://bootstrapious.com/i/snippets/sn-about/img-1.jpg" alt="" class="img-fluid mb-4 mb-lg-0">
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-5 px-5 mx-auto">
                    <img src="https://bootstrapious.com/i/snippets/sn-about/img-2.jpg" alt="" class="img-fluid mb-4 mb-lg-0">
                </div>
                <div class="col-lg-6">
                    <h2 class="font-weight-light">Mission</h2>
                    <p class="font-italic text-muted mb-4">Our mission is simple: connect professionals in various industries to make them more productive and successful. We aim to be the leading job search platform that not only helps people find jobs but also provides tools and resources to enhance their career development. We are dedicated to fostering a community where job seekers can grow, learn, and succeed in their careers.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-light py-5">
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-lg-12 text-center">
                <h2 class="display-4 font-weight-light">Our Team</h2>
                <p class="font-italic text-muted">Meet Jobforce's Team</p>
            </div>
        </div>
        <div class="row text-center">
            <!-- Team item-->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-5">
                <div class="bg-white rounded shadow-sm py-5 px-4 team-member">
                    <img src="https://bootstrapious.com/i/snippets/sn-about/avatar-2.png" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                    <h5 class="mb-0">Nineth Pramushka</h5>
                    <span class="small text-uppercase text-muted">Project Manager & Backend Developer</span>
                    <ul class="social mb-0 list-inline mt-3">
                        <li class="list-inline-item"><a href="https://web.facebook.com/nineth.dabare" class="social-link"><i class="fa fa-facebook-f"></i></a></li>
                        <li class="list-inline-item"><a href="https://www.instagram.com/nineth_pramushka_?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="social-link"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
            <!-- End-->
            <!-- Team item-->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-5">
                <div class="bg-white rounded shadow-sm py-5 px-4 team-member">
                    <img src="https://bootstrapious.com/i/snippets/sn-about/avatar-3.png" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                    <h5 class="mb-0">Kavindu Pabasara</h5>
                    <span class="small text-uppercase text-muted">Fullstack Developer and Database Engineer</span>
                    <ul class="social mb-0 list-inline mt-3">
                        <li class="list-inline-item"><a href="https://web.facebook.com/kavindu.pabasara.96" class="social-link"><i class="fa fa-facebook-f"></i></a></li>
                        <li class="list-inline-item"><a href="https://www.instagram.com/kav1i._/?utm_source=ig_web_button_share_sheet" class="social-link"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
            <!-- End-->
            <!-- Team item-->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-5">
                <div class="bg-white rounded shadow-sm py-5 px-4 team-member">
                    <img src="https://bootstrapious.com/i/snippets/sn-about/avatar-2.png" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                    <h5 class="mb-0">Chasira Sahas</h5>
                    <span class="small text-uppercase text-muted">Front-end Developer & System Analyst</span>
                    <ul class="social mb-0 list-inline mt-3">
                        <li class="list-inline-item"><a href="https://web.facebook.com/profile.php?id=100094771093001" class="social-link"><i class="fa fa-facebook-f"></i></a></li>
                        <li class="list-inline-item"><a href="https://www.instagram.com/chasira_sahas_?igsh=ZTM0OTk0MnkyYXd1" class="social-link"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
            <!-- End-->
            <!-- Team item-->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-5">
                <div class="bg-white rounded shadow-sm py-5 px-4 team-member">
                    <img src="https://bootstrapious.com/i/snippets/sn-about/avatar-1.png" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                    <h5 class="mb-0">Chalaka Ranathunga</h5>
                    <span class="small text-uppercase text-muted">Front-end Developer & QA Engineer</span>
                    <ul class="social mb-0 list-inline mt-3">
                        <li class="list-inline-item"><a href="https://web.facebook.com/chalaka.javendra" class="social-link"><i class="fa fa-facebook-f"></i></a></li>
                        <li class="list-inline-item"><a href="https://www.instagram.com/_cj_ranathunga?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="social-link"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
            <!-- End-->
        </div>
        <div class="row text-center justify-content-center">
            <!-- Team item-->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-5">
                <div class="bg-white rounded shadow-sm py-5 px-4 team-member">
                    <img src="https://bootstrapious.com/i/snippets/sn-about/avatar-1.png" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                    <h5 class="mb-0">Sashen Kavinda</h5>
                    <span class="small text-uppercase text-muted">Front-end Developer & QA Engineer</span>
                    <ul class="social mb-0 list-inline mt-3">
                        <li class="list-inline-item"><a href="https://web.facebook.com/shashen.kavinda.75" class="social-link"><i class="fa fa-facebook-f"></i></a></li>
                        <li class="list-inline-item"><a href="https://www.instagram.com/shashen/?utm_source=ig_web_button_share_sheet" class="social-link"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
            <!-- End-->
        </div>
    </div>
</div>



    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>

</body>
<?php include 'footer.php'; ?>
</html>
