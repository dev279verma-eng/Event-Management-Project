// Create base event page template generator
function createEventPage(eventType, icon, title, subtitle) {
    return `<?php
require_once 'config/database.php';
require_once 'config/session.php';

requireLogin();
$user = getCurrentUser();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = trim($_POST['event_name'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $venue = trim($_POST['venue'] ?? '');
    $guests = intval($_POST['guests'] ?? 0);
    $budget = floatval($_POST['budget'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $event_type = '${eventType}';
    
    if (empty($event_name) || empty($event_date) || empty($event_time) || empty($venue)) {
        $error = 'Please fill in all required fields';
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO events (user_id, event_type, event_name, event_date, event_time, venue, guests, budget, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssdds", $user['id'], $event_type, $event_name, $event_date, $event_time, $venue, $guests, $budget, $description);
        
        if ($stmt->execute()) {
            $success = '${title} event created successfully!';
            header('refresh:2;url=my-events.php');
        } else {
            $error = 'Failed to create event. Please try again.';
        }
        
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan ${title} - Event Management</title>
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

    <main class="container" style="padding: 4rem 2rem;">
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 3rem;">
                <div style="font-size: 5rem; margin-bottom: 1rem;">${icon}</div>
                <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 1rem;">
                    ${title}
                </h1>
                <p style="color: var(--text-secondary); font-size: 1.2rem;">
                    ${subtitle}
                </p>
            </div>

            <div class="card">
                <?php if ($error): ?>
                    <div class="alert alert-error" style="margin-bottom: 2rem;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success" style="margin-bottom: 2rem;">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="event_name" class="form-label">Event Name *</label>
                        <input type="text" id="event_name" name="event_name" class="form-input" 
                               placeholder="e.g., My Special ${title}" required
                               value="<?php echo htmlspecialchars($_POST['event_name'] ?? ''); ?>">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="event_date" class="form-label">Event Date *</label>
                            <input type="date" id="event_date" name="event_date" class="form-input" required
                                   value="<?php echo htmlspecialchars($_POST['event_date'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="event_time" class="form-label">Event Time *</label>
                            <input type="time" id="event_time" name="event_time" class="form-input" required
                                   value="<?php echo htmlspecialchars($_POST['event_time'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="venue" class="form-label">Venue *</label>
                        <input type="text" id="venue" name="venue" class="form-input" 
                               placeholder="e.g., Location Name, Address" required
                               value="<?php echo htmlspecialchars($_POST['venue'] ?? ''); ?>">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="guests" class="form-label">Expected Guests</label>
                            <input type="number" id="guests" name="guests" class="form-input" 
                                   placeholder="e.g., 50" min="0"
                                   value="<?php echo htmlspecialchars($_POST['guests'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="budget" class="form-label">Budget ($)</label>
                            <input type="number" id="budget" name="budget" class="form-input" 
                                   placeholder="e.g., 5000" min="0" step="0.01"
                                   value="<?php echo htmlspecialchars($_POST['budget'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Event Description</label>
                        <textarea id="description" name="description" class="form-input" rows="5"
                                  placeholder="Add any special details, requirements, or notes..."
                        ><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            Create ${title} Event
                        </button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('event_date').min = new Date().toISOString().split('T')[0];
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.card').style.animation = 'fadeInUp 0.6s ease-out';
        });
    </script>
</body>
</html>`;
}

// Event pages configuration
const eventPages = [
    {
        filename: 'event-anniversary.php',
        type: 'anniversary',
        icon: '💝',
        title: 'Plan Anniversary',
        subtitle: 'Celebrate love and togetherness with a memorable event'
    },
    {
        filename: 'event-graduation.php',
        type: 'graduation',
        icon: '🎓',
        title: 'Plan Graduation',
        subtitle: 'Honor achievement and success with a special celebration'
    },
    {
        filename: 'event-party.php',
        type: 'party',
        icon: '🎉',
        title: 'Plan General Party',
        subtitle: 'Create an amazing celebration for any occasion'
    }
];

// This is a template generator - actual PHP files are created separately
console.log('Event page templates configuration ready');
