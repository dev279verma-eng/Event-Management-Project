<?php
require_once 'config/database.php';
require_once 'config/session.php';

requireLogin();
$user = getCurrentUser();

// Get user's events
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM events WHERE user_id = ? ORDER BY event_date DESC, created_at DESC");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$events = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

// Event type icons and colors
$eventConfig = [
    'birthday' => ['icon' => '🎂', 'color' => 'var(--primary)'],
    'wedding' => ['icon' => '💒', 'color' => 'var(--accent)'],
    'corporate' => ['icon' => '🏢', 'color' => 'var(--secondary)'],
    'anniversary' => ['icon' => '💝', 'color' => 'var(--accent)'],
    'graduation' => ['icon' => '🎓', 'color' => 'var(--primary)'],
    'party' => ['icon' => '🎉', 'color' => 'var(--primary)']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events - Event Management</title>
    <meta name="description" content="View and manage all your events">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .events-list {
            display: grid;
            gap: 1.5rem;
            margin-top: 3rem;
        }
        
        .event-item {
            background: var(--bg-card);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            border: 1px solid var(--border-color);
            transition: var(--transition);
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 2rem;
            align-items: center;
        }
        
        .event-item:hover {
            border-color: var(--primary);
            transform: translateX(8px);
            box-shadow: var(--shadow);
        }
        
        .event-icon-large {
            font-size: 4rem;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            background: var(--gradient-card);
        }
        
        .event-details h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }
        
        .event-meta {
            display: flex;
            gap: 2rem;
            margin-top: 0.75rem;
            color: var(--text-secondary);
            flex-wrap: wrap;
        }
        
        .event-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .event-actions {
            display: flex;
            gap: 0.75rem;
            flex-direction: column;
        }
        
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: rgba(255, 193, 7, 0.2);
            color: var(--warning);
            border: 1px solid var(--warning);
        }
        
        .status-confirmed {
            background: rgba(76, 175, 80, 0.2);
            color: var(--success);
            border: 1px solid var(--success);
        }
        
        .empty-state {
            text-align: center;
            padding: 6rem 2rem;
        }
        
        .empty-state-icon {
            font-size: 6rem;
            margin-bottom: 2rem;
            opacity: 0.5;
        }
    </style>
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

    <main class="container" style="padding: 4rem 2rem;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-family: 'Playfair Display', serif; font-size: 3.5rem; margin-bottom: 1rem;">
                My Events
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.2rem;">
                Manage all your upcoming and past events
            </p>
        </div>

        <?php if (empty($events)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📅</div>
                <h2 style="font-size: 2rem; margin-bottom: 1rem; color: var(--text-secondary);">
                    No Events Yet
                </h2>
                <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 1.1rem;">
                    Start planning your first event today!
                </p>
                <a href="dashboard.php" class="btn btn-primary">
                    Create Your First Event
                </a>
            </div>
        <?php else: ?>
            <div class="events-list">
                <?php foreach ($events as $event): 
                    $config = $eventConfig[$event['event_type']] ?? ['icon' => '🎉', 'color' => 'var(--primary)'];
                    $isPast = strtotime($event['event_date']) < strtotime('today');
                ?>
                    <div class="event-item">
                        <div class="event-icon-large">
                            <?php echo $config['icon']; ?>
                        </div>
                        
                        <div class="event-details">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                                <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                                <span class="status-badge status-<?php echo $event['status']; ?>">
                                    <?php echo $event['status']; ?>
                                </span>
                            </div>
                            
                            <div class="event-meta">
                                <div class="event-meta-item">
                                    <span>📅</span>
                                    <span><?php echo date('F j, Y', strtotime($event['event_date'])); ?></span>
                                </div>
                                <div class="event-meta-item">
                                    <span>⏰</span>
                                    <span><?php echo date('g:i A', strtotime($event['event_time'])); ?></span>
                                </div>
                                <div class="event-meta-item">
                                    <span>📍</span>
                                    <span><?php echo htmlspecialchars($event['venue']); ?></span>
                                </div>
                                <?php if ($event['guests'] > 0): ?>
                                <div class="event-meta-item">
                                    <span>👥</span>
                                    <span><?php echo $event['guests']; ?> guests</span>
                                </div>
                                <?php endif; ?>
                                <?php if ($event['budget'] > 0): ?>
                                <div class="event-meta-item">
                                    <span>💰</span>
                                    <span>$<?php echo number_format($event['budget'], 2); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($event['description'])): ?>
                            <p style="margin-top: 1rem; color: var(--text-secondary); line-height: 1.6;">
                                <?php echo htmlspecialchars($event['description']); ?>
                            </p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="event-actions">
                            <button class="btn btn-primary btn-small" onclick="viewEvent(<?php echo $event['id']; ?>)">
                                View Details
                            </button>
                            <button class="btn btn-secondary btn-small" onclick="deleteEvent(<?php echo $event['id']; ?>)">
                                Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function viewEvent(id) {
            alert('Event details view coming soon! Event ID: ' + id);
        }
        
        function deleteEvent(id) {
            if (confirm('Are you sure you want to delete this event?')) {
                window.location.href = 'delete-event.php?id=' + id;
            }
        }
        
        // Add stagger animation
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.event-item');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.animation = `fadeInUp 0.6s ease-out ${0.1 * index}s forwards`;
            });
        });
    </script>
</body>
</html>
