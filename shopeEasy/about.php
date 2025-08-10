<?php
require_once 'includes/header.php';
?>

<style>
    .about-section {
        padding: 60px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .card {
        border: none;
        transition: all 0.4s ease;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .card-body {
        padding: 3rem;
    }
    
    h2 {
        color: #2c3e50;
        font-weight: 800;
        position: relative;
        padding-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, #3498db, #2ecc71);
        border-radius: 2px;
    }
    
    h4 {
        color: #34495e;
        font-weight: 700;
        margin-bottom: 1.5rem;
        position: relative;
        padding-left: 15px;
    }
    
    h4:before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 20px;
        background: #3498db;
        border-radius: 2px;
    }
    
    .img-fluid {
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        transition: all 0.4s ease;
        border-radius: 10px;
    }
    
    .img-fluid:hover {
        transform: scale(1.03);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }
    
    .list-unstyled li {
        padding: 12px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .list-unstyled li:hover {
        padding-left: 10px;
        background: rgba(52, 152, 219, 0.05);
    }
    
    .list-unstyled li:last-child {
        border-bottom: none;
    }
    
    .fas {
        transition: all 0.4s ease;
        color: #3498db;
    }
    
    .col-md-4 {
        transition: all 0.4s ease;
        padding: 20px;
    }
    
    .col-md-4:hover {
        transform: translateY(-5px);
    }
    
    .col-md-4:hover .fas {
        transform: scale(1.2);
        color: #2ecc71;
    }
    
    .text-primary {
        color: #3498db !important;
    }
    
    p {
        line-height: 1.9;
        color: #555;
        font-size: 1.05rem;
    }
    
    .feature-box {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
        transition: all 0.4s ease;
    }
    
    .feature-box:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .feature-box h5 {
        color: #2c3e50;
        font-weight: 600;
        margin: 15px 0;
    }
    
    .feature-box p {
        color: #666;
        font-size: 0.95rem;
        margin-bottom: 0;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .card-body > * {
        animation: fadeIn 0.6s ease-out forwards;
    }
    
    .card-body > *:nth-child(2) { animation-delay: 0.2s; }
    .card-body > *:nth-child(3) { animation-delay: 0.4s; }
    .card-body > *:nth-child(4) { animation-delay: 0.6s; }
</style>

<div class="container about-section">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center mb-4">About Us</h2>
                    
                    <div class="text-center mb-4">
                        <img src="assets/images/about-banner.jpg" alt="About Us" class="img-fluid rounded mb-4" style="max-height: 300px;">
                    </div>
                    
                    <h4>Our Story</h4>
                    <p>Welcome to our e-commerce platform, where we strive to provide the best shopping experience for our customers. Founded in 2024, we have grown from a small startup to a trusted online shopping destination.</p>
                    
                    <h4 class="mt-4">Our Mission</h4>
                    <p>Our mission is to provide high-quality products at competitive prices while ensuring excellent customer service. We believe in making shopping easy, convenient, and enjoyable for everyone.</p>
                    
                    <h4 class="mt-4">Why Choose Us?</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Wide selection of products</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Competitive prices</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Fast and reliable shipping</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Excellent customer service</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Secure payment options</li>
                    </ul>
                    
                    <h4 class="mt-4">Our Team</h4>
                    <p>We are a dedicated team of professionals committed to providing the best shopping experience. Our team includes experts in customer service, logistics, and product quality assurance.</p>
                    
                    <div class="text-center mb-4">
                        <img src="assets/images/team.jpeg" alt="Our Team" class="img-fluid rounded mb-4" style="max-height: 300px;">
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-4 mb-4">
                            <div class="feature-box text-center">
                                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                <h5>Expert Team</h5>
                                <p>Professional and experienced staff dedicated to your satisfaction</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="feature-box text-center">
                                <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                                <h5>Fast Delivery</h5>
                                <p>Quick and reliable shipping to your doorstep</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="feature-box text-center">
                                <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                                <h5>24/7 Support</h5>
                                <p>Always here to help you with any questions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 