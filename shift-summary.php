<?php
$patient = [
    'name' => 'Arthur Miller',
    'id' => '8842',
    'status' => 'Stable & Verified',
    'caregiver' => 'Sarah Jenkins',
    'next_shift' => 'Tomorrow, 08:00',
    'shift_date' => 'Oct 24, 2024',
    'shift_time' => 'Morning Shift (08:00 - 14:00)',
    'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDfdtnEQ_f5DbchtvFzNz_rnRmSwkmWH_ac9PIifx0db8CFxiSgkRUrHwl-ENry5POUEgi-Lti8HRyRIQ20iH81vsxQ_rWAQuDzv0VdB_HAR5KSnMD-o7hEXkmWXsq1V6BUu-k7w84vcW-HtEpL5yMCuPn8KPTZWYIXKEXClj5-yebpdWmGXZZlBIBUkuygFGi0Ls1OoHPrnkvpU_qv6HLatxo2LURzsUsXf0HKV7mI1j-zIfYdtsrVpKj_ineEzahRi9OnfG2JZrA',
    'caregiver_avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDNLqv1ea-AtBbP7Ei4diQMHRH2bzXg-n01GeVuQL_wUJtnQPuUMoJFYDlr9iAf_bmCq26KURGuN0_67sbW8CtvXsIh7dvIG_K1z3mmrDbiCg6guiYAxoIB2Bfm_4q4ivEPwGJQkUm2tkiUmy7c3UoAol69AEtd-jAtB7q_zCpG2zSo2_wE46rmXNH2gZs_xWdWoMqx9x2mTAWkrh99GzDkA1XKwe727uMsEqryGxxSYD6YwWLMAAutUUhCbqNsjNekDindI5Khw6o'
];

$vitals = [
    ['label' => 'BPM', 'value' => '72', 'status' => 'Within Range'],
    ['label' => 'TEMP', 'value' => '98.4°', 'status' => 'Normal'],
    ['label' => 'BP', 'value' => '120/80', 'status' => 'Optimal'],
    ['label' => 'SPO2', 'value' => '99%', 'status' => 'Ideal']
];

$tasks = [
    [
        'title' => 'Morning Medication Administered',
        'time' => '08:15 AM',
        'desc' => 'Administered Lisinopril (20mg) and Metformin (500mg). Patient swallowed without difficulty.',
        'tags' => []
    ],
    [
        'title' => 'Assisted Grooming & Bathing',
        'time' => '09:30 AM',
        'desc' => 'Full hygiene assistance provided. Skin check performed: No redness or pressure sores noted.',
        'tags' => []
    ],
    [
        'title' => 'Physical Therapy: 15-Min Walk',
        'time' => '11:00 AM',
        'desc' => 'Supported mobility exercise in the garden. Used walker for stability. Steady gait observed.',
        'tags' => ['Mobility', 'Outdoors']
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeHands Care Report | Patient Dashboard</title>
    <link rel="stylesheet" href="assets/css/shift-summary.css">
</head>
<body>

    <header class="navbar">
        <div class="container nav-container">
            <div class="nav-left">
                <span class="logo">SafeHands</span>
                <nav class="nav-links">
                    <a href="#">Dashboard</a>
                    <a href="#" class="active">Patients</a>
                    <a href="#">Find Caregivers</a>
                    <a href="#">My Bookings</a>
                </nav>
            </div>
            <div class="nav-right">
                <button class="icon-btn" aria-label="Notifications">&#128276;</button>
                <button class="icon-btn" aria-label="Account">&#128100;</button>
                <button class="btn btn-primary">Sign Out</button>
            </div>
        </div>
    </header>

    <main class="container main-content">
        <nav class="breadcrumb">
            <a href="#">Patients</a> &gt;
            <a href="#"><?php echo htmlspecialchars($patient['name']); ?></a> &gt;
            <span class="active">Daily Report</span>
        </nav>

        <div class="page-header">
            <div>
                <h1>Shift Summary: <?php echo htmlspecialchars($patient['shift_date']); ?></h1>
                <p><?php echo htmlspecialchars($patient['shift_time']); ?></p>
            </div>
            <div class="action-buttons">
                <button class="btn btn-outline" id="exportBtn">&#128229; Export PDF</button>
                <button class="btn btn-primary" id="shareBtn">&#128279; Share with Family</button>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="col-sidebar">
                <div class="card card-animated">
                    <div class="patient-profile">
                        <img src="<?php echo $patient['avatar']; ?>" alt="<?php echo $patient['name']; ?>" class="avatar-lg">
                        <div>
                            <h3><?php echo htmlspecialchars($patient['name']); ?></h3>
                            <span class="badge badge-info">Patient ID: <?php echo htmlspecialchars($patient['id']); ?></span>
                        </div>
                    </div>
                    <div class="info-list">
                        <div class="info-row">
                            <span>Status</span>
                            <span class="status-indicator">
                                <span class="pulse-dot"></span>
                                <?php echo htmlspecialchars($patient['status']); ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span>Caregiver</span>
                            <span class="caregiver-info">
                                <img src="<?php echo $patient['caregiver_avatar']; ?>" alt="Caregiver" class="avatar-sm">
                                <?php echo htmlspecialchars($patient['caregiver']); ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span>Next Shift</span>
                            <strong><?php echo htmlspecialchars($patient['next_shift']); ?></strong>
                        </div>
                    </div>
                </div>

                <div class="card card-animated">
                    <h4 class="card-subtitle">LIVE VITALS</h4>
                    <div class="vitals-grid">
                        <?php foreach ($vitals as $vital): ?>
                            <div class="vital-item">
                                <div class="vital-label"><?php echo $vital['label']; ?></div>
                                <div class="vital-value"><?php echo $vital['value']; ?></div>
                                <div class="vital-status"><?php echo $vital['status']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-main">
                <div class="card card-animated">
                    <div class="card-header">
                        <h3>Completed Tasks</h3>
                        <span class="meta-text"><?php echo count($tasks); ?> of 3 Done</span>
                    </div>
                    <ul class="task-list">
                        <?php foreach ($tasks as $task): ?>
                            <li class="task-item">
                                <span class="check-icon">&#10003;</span>
                                <div class="task-content">
                                    <div class="task-header">
                                        <h4><?php echo htmlspecialchars($task['title']); ?></h4>
                                        <span class="task-time"><?php echo htmlspecialchars($task['time']); ?></span>
                                    </div>
                                    <p><?php echo htmlspecialchars($task['desc']); ?></p>
                                    <?php if (!empty($task['tags'])): ?>
                                        <div class="tags-container">
                                            <?php foreach ($task['tags'] as $tag): ?>
                                                <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="sub-grid">
                    <div class="card card-animated">
                        <h3>Nutrition</h3>
                        <div class="hydration-block">
                            <div class="hydration-labels">
                                <span>Hydration</span>
                                <strong>1200ml / 2000ml</strong>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 60%;"></div>
                            </div>
                        </div>
                        <div class="meal-info">
                            <strong>Breakfast Intake</strong>
                            <p>Oatmeal with berries, 1 slice of whole grain toast. Finished 100%.</p>
                        </div>
                    </div>

                    <div class="card card-highlight card-animated">
                        <h3>Caregiver's Note</h3>
                        <blockquote class="note-quote">
                            "Arthur was in high spirits today. He spoke fondly about his grandchildren and enjoyed the fresh air in the garden. Appetite is improving compared to yesterday. No signs of cognitive distress."
                        </blockquote>
                        <span class="timestamp">Logged at 13:45 PM</span>
                    </div>
                </div>

                <div class="card card-animated">
                    <h3>Report Attachments</h3>
                    <div class="attachments-grid">
                        <div class="file-card">
                            <span class="file-icon">&#128196;</span>
                            <div class="file-details">
                                <strong>Vitals_Log_Oct24.csv</strong>
                                <small>Continuous monitoring data</small>
                            </div>
                            <button class="file-action" title="Download">&#11015;</button>
                        </div>
                        <div class="file-card">
                            <span class="file-icon">&#128444;</span>
                            <div class="file-details">
                                <strong>Garden_Walk_Oct24.jpg</strong>
                                <small>Activity documentation</small>
                            </div>
                            <button class="file-action" title="View">&#128065;</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert-bar card-animated">
            <div class="alert-info">
                <span class="alert-icon">&#9888;</span>
                <div>
                    <h4>Something not right?</h4>
                    <p>Direct line to our Clinical Support team available 24/7.</p>
                </div>
            </div>
            <div class="alert-actions">
                <button class="btn btn-danger" id="reportIncidentBtn">Report Incident</button>
                <button class="btn btn-outline" id="contactCaregiverBtn">Contact Sarah</button>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container footer-container">
            <div>
                <span class="footer-logo">SafeHands</span>
                <p>&copy; 2024 SafeHands Healthcare. All rights reserved.</p>
            </div>
            <nav class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">HIPAA Compliance</a>
                <a href="#">Contact Us</a>
            </nav>
        </div>
    </footer>

    <script src="assets/js/shift-summary.js"></script>
</body>
</html>