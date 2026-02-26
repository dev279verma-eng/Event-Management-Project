<?php
require_once 'config/database.php';
require_once 'config/session.php';

requireLogin();
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Event Management System</title>
    <meta name="description" content="Manage your events and create memorable celebrations">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">EventPro</div>
                <ul class="nav-links">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="my-events.php">My Events</a></li>
                    <li><a href="logout.php" class="btn btn-secondary" style="padding: 0.5rem 1.5rem;">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="hero">
            <h1 class="hero-title">Create Unforgettable Moments</h1>
            <p class="hero-subtitle">
                Welcome back, <?php echo htmlspecialchars($user['full_name']); ?>! 
                Choose an event type below to start planning your next celebration.
            </p>
            <div class="hero-cta">
                <a href="my-events.php" class="btn btn-primary">View My Events</a>
                <a href="#events" class="btn btn-secondary">Browse Events</a>
            </div>
        </section>

        <section id="events">
            <h2 class="text-center" style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 1rem;">
                Event Types
            </h2>
            <p class="text-center text-muted" style="font-size: 1.2rem; margin-bottom: 3rem;">
                Select the perfect event type for your special occasion
            </p>

            <div class="event-grid">
                <div class="event-card" onclick="window.location.href='event-birthday.php'">
                    <div class="event-card-content">
                        <div class="event-card-icon">🎂</div>
                        <h3 class="event-card-title">Birthday Party</h3>
                        <p class="event-card-description">
                            Celebrate another year of life with an amazing birthday bash. 
                            From intimate gatherings to grand celebrations.
                        </p>
                        <a href="event-birthday.php" class="btn btn-primary" style="width: 100%;">Plan Birthday</a>
                    </div>
                </div>

                <div class="event-card" onclick="window.location.href='event-wedding.php'">
                    <div class="event-card-content">
                        <div class="event-card-icon">💒</div>
                        <h3 class="event-card-title">Wedding</h3>
                        <p class="event-card-description">
                            Plan your dream wedding with our comprehensive event management tools. 
                            Make your special day perfect.
                        </p>
                        <a href="event-wedding.php" class="btn btn-accent" style="width: 100%;">Plan Wedding</a>
                    </div>
                </div>

                <div class="event-card" onclick="window.location.href='event-corporate.php'">
                    <div class="event-card-content">
                        <div class="event-card-icon">🏢</div>
                        <h3 class="event-card-title">Corporate Event</h3>
                        <p class="event-card-description">
                            Organize professional corporate events, conferences, and business meetings 
                            with ease and efficiency.
                        </p>
                        <a href="event-corporate.php" class="btn btn-primary" style="width: 100%;">Plan Corporate</a>
                    </div>
                </div>

                <div class="event-card" onclick="window.location.href='event-anniversary.php'">
                    <div class="event-card-content">
                        <div class="event-card-icon">💝</div>
                        <h3 class="event-card-title">Anniversary</h3>
                        <p class="event-card-description">
                            Celebrate love and togetherness with a memorable anniversary event. 
                            Create lasting memories together.
                        </p>
                        <a href="event-anniversary.php" class="btn btn-accent" style="width: 100%;">Plan Anniversary</a>
                    </div>
                </div>

                <div class="event-card" onclick="window.location.href='event-graduation.php'">
                    <div class="event-card-content">
                        <div class="event-card-icon">🎓</div>
                        <h3 class="event-card-title">Graduation</h3>
                        <p class="event-card-description">
                            Mark the achievement of graduating with a special celebration. 
                            Honor hard work and success.
                        </p>
                        <a href="event-graduation.php" class="btn btn-primary" style="width: 100%;">Plan Graduation</a>
                    </div>
                </div>

                <div class="event-card" onclick="window.location.href='event-party.php'">
                    <div class="event-card-content">
                        <div class="event-card-icon">🎉</div>
                        <h3 class="event-card-title">General Party</h3>
                        <p class="event-card-description">
                            Host any type of party or celebration. Perfect for holidays, 
                            reunions, or just because!
                        </p>
                        <a href="event-party.php" class="btn btn-primary" style="width: 100%;">Plan Party</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add stagger animation to event cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.event-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.animation = `fadeInUp 0.6s ease-out ${0.1 * index}s forwards`;
            });
        });
    </script>
</body>
</html>
