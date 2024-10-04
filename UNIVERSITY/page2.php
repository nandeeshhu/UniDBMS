<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indian Institute of Technology</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Times New Roman;
        }

        .main-header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
        }

        .sub-header {
            background-color: #444; 
            color: #fff;
            padding: 10px 0;
        }

        .logo {
            max-height: 80px;
            margin-left: 10px;
        }

        .main-header h1 {
            margin: 0 auto;
            font-size: 30px;
        }

        .sub-header .nav-link {
            color: #fff;
            margin: 0 10px;
        }

        .sub-header .nav-link:hover {
            color: #ddd;
        }

        .contact-details img {
            max-height: 20px;
            margin-right: 5px;
        }

        /* Carousel styles */
        .carousel-inner img {
            width: 100%;
            height: 400px; 
        }

        /* Welcome container styles */
        .welcome-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .welcome-heading {
            font-size: 24px;
            font-weight: bold;
        }

        .welcome-content {
            font-size: 16px;
        }

        .read-more-button {
            margin-top: 10px;
        }

    </style>
</head>
<body>

    <div class="main-header d-flex align-items-center">
        <img src="iitlogo.png" alt="Institute Logo" class="logo">
        <h1>Indian Institute of Technology, Guwahati</h1>
    </div>

    <div class="sub-header">
        <nav class="nav justify-content-center">
            <a class="nav-link" href="Department_reg.php">Department Registration</a>
            <a class="nav-link" href="faculty_reg.php">Faculty Registration</a>
            <a class="nav-link" href="hostel_reg.php">Hostel Registration</a>
            <a class="nav-link" href="courses.php">Course Registration</a>
            <a class="nav-link" href="student.php">Student Registration</a>
            <a class="nav-link" href="fees_details.php">Fees Payment</a>
        </div>

    <!-- Bootstrap Carousel -->
    <div id="universityCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="iitg2.jpg" alt="Campus Image 1">
            </div>
            <div class="carousel-item">
                <img src="iitg4.jpg" alt="Campus Image 2">
            </div>
            <div class="carousel-item">
                <img src="main-gate.jpg" alt="Campus Image 3">
            </div>
        </div>
        <a class="carousel-control-prev" href="#universityCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#universityCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

<!-- Welcome Container -->
    <div class="container welcome-container">
        <h2 class="welcome-heading">Welcome</h2>
        <p class="welcome-content">
            Indian Institute of Technology Guwahati, the sixth member of the IIT fraternity, was established in 1994. The academic programme of IIT Guwahati commenced in 1995.
            Indian Institute of Technology Guwahati's campus is on a sprawling 285 hectares plot of land on the north bank of the river Brahmaputra around 20 kms from the heart of the city.
            IIT Guwahati has retained the 7th position among the best engineering institutions of the country in the ‘India Rankings 2023’ declared by the National Institutional
             Ranking Framework (NIRF) of the Union Ministry of Education. IIT Guwahati has been also ranked 2nd in the ‘Swachhata Ranking’ conducted by the GoI.
        <!-- Add "Read More" button -->
        <button class="btn btn-primary read-more-button">Read More</button>
    </div>

    <div class="contact-details">
        <div>
            <img src="phone.png" alt="Phone Icon"> Phone: +123456789
        </div>
        <div>
            <img src="insta.jpeg" alt="Instagram Icon"> Instagram: IITG_official
        </div>
        <div>
            <img src="Linkedin.png" alt="LinkedIn Icon"> LinkedIn: IIT
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
