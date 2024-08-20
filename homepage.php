<?php include 'navbar.footer.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Background Video</title>
<style>
    body {
        margin: 0;
        padding: 0;
    }
    .video-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1; /* Put the video behind other content */
    }
    .v1 {
        width: 100%;
        height: auto;
        object-fit: cover; /* Ensure the video covers the entire container */
    }
    /* Add additional styles for your content */
    .content {
        position: relative;
        z-index: 1; /* Put the content in front of the video */
        margin-top: 400px;
        padding: 20px; /* Example style */
        background-color: white;
    }
    .image-container {
        display: flex;
        justify-content: space-around;
        background-color: white;
    }
    .image-container img {
        width: 300px;
        height: auto;
        border: 2px solid #000;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    }
    .image-info {
        text-align: center;
    }
    .about-section {
        width: 30%;
        padding: 20px;
    }
    .about-section h2 {
        color: #333;
        font-size: 24px;
    }
    .about-section p {
        color: #666;
        font-size: 16px;
    }
    .image-section {
        width: 60%;
        height: 50%;
        display: flex;
        padding: 10px;
    }
    .image-section video {
        width: 250px;
        height: 400px;
        padding:10px;
        border-radius: 10%;
    }
    .lmore {
        color: white;
        font-weight: bold;
        font-size: 15px;
        width: 10px;
        border-radius: 40%;
        background-color: red;
    }
    .lmore:hover {
        text-decoration: underline;
    }
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }
        .about-section,
        .image-section {
            width: 100%;
        }
        .image-section img {
            width: 100%;
            margin-top: 10px;
        }
    }
    .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        background-color: white;
    }
    .background-container {
        width: 100%;
        height: 300px;
        background-image: url('mid.jpg');
        background-size: cover;
        background-position: center;
    }

    /* Testimonials Section */
    .testimonials {
        padding: 50px;
        background-color: #f9f9f9;
        text-align: center;
    }
    .testimonials h2 {
        font-size: 28px;
        margin-bottom: 20px;
    }
    .testimonials p {
        font-size: 16px;
        color: #555;
        margin: 0 auto;
        max-width: 800px;
    }

    /* Contact Section */
    .contact {
        padding: 50px;
        color: white;
        text-align: center;
    }
    .contact h2 {
        font-size: 28px;
        margin-bottom: 20px;
    }
    .contact p {
        font-size: 16px;
        margin: 0 auto;
        max-width: 600px;
    }
    .contact a {
        color: #e8491d;
        text-decoration: none;
        font-weight: bold;
        background-color: none;
    }
    .contact a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="video-container">
    <video autoplay loop muted class="v1">
        <source src="gymmoti.mp4" type="video/mp4">
    </video>
</div>

<div class="content">
    <!-- Your content goes here -->
    <h1>Welcome to our gym</h1>
    <p>We provide state-of-the-art facilities and expert trainers to help you achieve your fitness goals.</p>
</div>

<div class="image-container">
    <div>
        <img src="gymo1.jpeg" alt="Image 1">
        <div class="image-info">
            <h2>Transformation</h2>
        </div>
    </div>
    <div>
        <img src="gymo2.jpeg" alt="Image 2">
        <div class="image-info">
            <h2>Vitality</h2>
        </div>
    </div>
    <div>
        <img src="gymo3.jpg" alt="Image 3">
        <div class="image-info">
            <h2>Empowerment</h2>
        </div>
    </div>
</div>
<br><br>
<br>
<br>
<br>
<br>
<br>

<div class="container">
    <div class="about-section">
        <h2>About Our Gym</h2>
        <p>Welcome to [Your Gym Name], where fitness meets community. Our modern facility and expert trainers are here to help you reach your goals, whether you're a beginner or seasoned athlete. Join us and become part of a supportive environment dedicated to your well-being.</p>
        <br>
        <a class="lmore" href="login.php">Learn more</a>
    </div>
    <div class="image-section">
        <div>
            <video autoplay loop muted>
                <source src="v1.mp4" type="video/mp4">
            </video>
        </div>
        <div>
            <video autoplay loop muted>
                <source src="v2.mp4" type="video/mp4">
            </video>
        </div>
        <div>
            <video autoplay loop muted>
                <source src="v3.mp4" type="video/mp4">
            </video>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="testimonials">
    <h2>What Our Members Say</h2>
    <p>"Joining this gym was the best decision I ever made! The trainers are knowledgeable and the community is incredibly supportive." - Sarah K.</p>
    <p>"I love the variety of classes and the friendly environment. I've achieved my fitness goals faster than I thought possible." - Mark D.</p>
</div>

<!-- Contact Section -->
<div class="contact">
    <h2>Contact Us</h2>
    <p>If you have any questions or want to learn more about our gym, feel free to reach out. We're here to help!</p>
    <p>Email: "mailto:info@yourgym.com"</p>
    <p>Phone: (123) 456-7890</p>
</div>

</body>
</html>
