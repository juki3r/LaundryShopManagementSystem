<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Laundry Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero {
            background: url('https://images.unsplash.com/photo-1581574203341-98f041fdb515?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
            position: relative;
        }
        .hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .service-card {
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0px 10px 25px rgba(0,0,0,0.1);
        }
        .contact-section {
            background: #2a9d8f;
            color: #fff;
        }
        footer {
            background: #222;
            color: #ccc;
            padding: 20px 0;
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="display-4 fw-bold">Fresh Clothes, Happy Life</h1>
            <p class="lead">Fast, reliable, and eco-friendly laundry solutions in Iloilo.</p>
            <a href="#services" class="btn btn-lg btn-light mt-3">Explore Services</a>
        </div>
    </section>

    <!-- About Section -->
    <section class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1600185365483-26d7cfed56ab?auto=format&fit=crop&w=800&q=80"
                     alt="Laundry Service" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-3">About The Laundry Club</h2>
                <p>We take care of your clothes like they’re our own. With eco-friendly detergents and modern machines, we ensure your laundry is cleaned gently and efficiently.</p>
                <p>From everyday wash-and-fold to delicate dry cleaning, we make laundry day hassle-free.</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5 bg-light">
        <div class="container">
            <h2 class="fw-bold text-center mb-5">Our Services</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card service-card p-4 h-100 text-center">
                        <img src="https://img.icons8.com/fluency/96/washing-machine.png" alt="Wash and Fold" class="mb-3" width="64">
                        <h5 class="fw-bold">Wash & Fold</h5>
                        <p class="text-muted">Starting at ₱140 (5kg minimum)</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card p-4 h-100 text-center">
                        <img src="https://img.icons8.com/fluency/96/dry-clean.png" alt="Dry Cleaning" class="mb-3" width="64">
                        <h5 class="fw-bold">Dry Cleaning</h5>
                        <p class="text-muted">Gentle care for your delicate fabrics.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card p-4 h-100 text-center">
                        <img src="https://img.icons8.com/fluency/96/delivery.png" alt="Pickup and Delivery" class="mb-3" width="64">
                        <h5 class="fw-bold">Pickup & Delivery</h5>
                        <p class="text-muted">Convenient door-to-door service.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Get in Touch</h2>
            <p class="mb-1">📍 Odiongan Street Purok 2 Zone 5, Estancia, Iloilo</p>
            <p class="mb-1">📞 09098515156</p>
            <p class="mb-4">🕒 Mon – Sat: 8:00 AM – 6:00 PM | Sun: Closed</p>
            <a href="tel:09098515156" class="btn btn-light btn-lg">Call Us Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <p class="mb-0">&copy; {{ date('Y') }} The Laundry Club. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
