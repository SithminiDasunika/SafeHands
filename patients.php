<?php
// Sample backend data sources
$stats = [
    'total_patients'    => 2,
    'receiving_care'    => 1,
    'upcoming_sessions' => 2,
    'recent_reports'    => 2,
];

$patients = [
    [
        'id'          => 1,
        'name'        => 'Mr. Ananda Silva',
        'relation'    => 'Father',
        'age'         => 78,
        'gender'      => 'Male',
        'blood_group' => 'A+',
        'photo'       => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC0nJLV9WF9TyMh8N6n3piDgu7-WZvOlhuvqWTVAKp7lwUzNGwk7sitGxo9knPwekk3L1BN_PXu7-sUJroPFYCV8072Mgnhp8fH9iySCpNkpjkW0A1TOO5mMNvDv-DPRn9Xgo0aHmrjvUWyX63OKA0F0uiaY6AO_VOEq5fZ7b9FpK8poADmKNikVfcUlyqFD6B7ePmsZvYzNMgonXkZjiyddEffCRh_p3WiKLBKdvEBsRnurHNJZzyh',
        'conditions'  => ['Hypertension', 'Walking Assistance'],
        'status_code' => 'active',
        'status_label'=> 'Currently Receiving Care',
        'caregiver'   => 'Nadeesha Perera',
        'next_session'=> '16 August • 08:00 AM - 12:00 PM',
    ],
    [
        'id'          => 2,
        'name'        => 'Mrs. Kamala Silva',
        'relation'    => 'Mother',
        'age'         => 72,
        'gender'      => 'Female',
        'blood_group' => 'O+',
        'photo'       => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAIksYZ6xch8WzDyzuSr53f125sHS1apXqm5qiLJ5EzhJY2G0BsodYdRO6jQ4iBo97xvTJUHcNHPnJF3yZN_FfxEoXoXfLJHVN1KI0N5YfcCwJoqT3qcR4AJ2ThLCW-I4NUsYWkItz3cDCCfmzsUv5VoWgytcNGxLL3kKGVp0N7PoZSUSNDxUCzv1yD597FvCg1PgB9c8K7BDy1VJDJ-ivuvVguvTWIZCv5-DYsY8Drwg-4kJMzchHS',
        'conditions'  => ['Type 2 Diabetes', 'Independent'],
        'status_code' => 'scheduled',
        'status_label'=> 'Care Scheduled',
        'caregiver'   => 'Sunil Jayasuriya',
        'next_session'=> '15 August • 04:00 PM - 08:00 PM',
    ]
];

$reports = [
    [
        'id'        => 101,
        'patient'   => 'Mr. Silva',
        'date'      => '15 Aug 2026',
        'status'    => 'Completed',
        'caregiver' => 'Nadeesha Perera',
        'note'      => 'Patient was comfortable this morning. Medication was taken on time and light stretching was completed.'
    ],
    [
        'id'        => 102,
        'patient'   => 'Mrs. Kamala',
        'date'      => '14 Aug 2026',
        'status'    => 'Completed',
        'caregiver' => 'Sunil Jayasuriya',
        'note'      => 'Blood glucose was monitored and prescribed medication was taken after breakfast.'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Patients - SafeHands</title>
    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/patients.css">
</head>
<body>

    <!-- Header / Navigation -->
    <header class="navbar">
        <div class="nav-container">
            <div class="nav-left">
                <a href="#" class="brand-logo">SafeHands</a>
                <nav class="nav-links">
                    <a href="family/dashboard.php" class="nav-link">Dashboard</a>
                    <a href="#" class="nav-link active">Patients</a>
                    <a href="#" class="nav-link">Find Caregivers</a>
                    <a href="#" class="nav-link">My Bookings</a>
                </nav>
            </div>
            <div class="nav-right">
                <button class="icon-button" aria-label="Notifications">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="profile-avatar">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHvHBSrCENrUdKY4WuLbKNeekXwBrv2Hs4Pv_dIBlPi7yKBGqGtP9CWTQ5Rbb-pwe3eYeSPz5-0GUkx8ENdVOTteskZXvfQQ4H4sVx3bnqHljl1UxHGwwkHE-GzzrZlb_xA_p4Y3izPf3Y_MQy1WszJvleEPwV4XCafxuTsSBwBOH7UPVF7gPZTjeRTDAt6FmioOZM3P3GOYnXS0kNIFr2fckZ-mxtpoO_2FvSSzSNSf2-JFFUBW0a" alt="User profile photo">
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="breadcrumb">
            <a href="#">Dashboard</a>
            <span class="material-symbols-outlined separator">chevron_right</span>
            <span class="current">Patients</span>
        </nav>

        <!-- Page Title & Action Bar -->
        <div class="page-header">
            <div>
                <h1>My Patients</h1>
                <p>View and manage the people you care for through SafeHands.</p>
            </div>
            <button class="btn btn-primary" id="add-patient-btn">
                <span class="material-symbols-outlined">add</span>
                Add Patient
            </button>
        </div>

        <!-- Summary Stats Grid -->
        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="material-symbols-outlined stat-icon primary">group</span>
                    <span class="stat-label">Total Patients</span>
                </div>
                <div class="stat-value"><?php echo htmlspecialchars($stats['total_patients']); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="material-symbols-outlined stat-icon success">medical_services</span>
                    <span class="stat-label">Receiving Care</span>
                </div>
                <div class="stat-value"><?php echo htmlspecialchars($stats['receiving_care']); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="material-symbols-outlined stat-icon warning">event</span>
                    <span class="stat-label">Upcoming Sessions</span>
                </div>
                <div class="stat-value"><?php echo htmlspecialchars($stats['upcoming_sessions']); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="material-symbols-outlined stat-icon primary">description</span>
                    <span class="stat-label">Recent Reports</span>
                </div>
                <div class="stat-value"><?php echo htmlspecialchars($stats['recent_reports']); ?></div>
            </div>
        </section>

        <!-- Patients Directory -->
        <section class="section">
            <div class="section-header">
                <div>
                    <h2>All Patients</h2>
                    <p>Patients registered under your family account.</p>
                </div>
                <div class="search-box">
                    <span class="material-symbols-outlined search-icon">search</span>
                    <input type="text" id="patient-search" placeholder="Search patients..." autocomplete="off">
                </div>
            </div>

            <div class="patient-grid" id="patient-list">
                <?php foreach ($patients as $patient): ?>
                    <article class="patient-card" data-name="<?php echo strtolower(htmlspecialchars($patient['name'])); ?>">
                        <div class="patient-card-header">
                            <div class="patient-profile">
                                <img src="<?php echo htmlspecialchars($patient['photo']); ?>" alt="<?php echo htmlspecialchars($patient['name']); ?>" class="patient-avatar">
                                <div>
                                    <h3><?php echo htmlspecialchars($patient['name']); ?></h3>
                                    <p class="patient-subtext">
                                        <?php echo htmlspecialchars($patient['relation']); ?> • 
                                        <?php echo htmlspecialchars($patient['age']); ?> yrs • 
                                        <?php echo htmlspecialchars($patient['gender']); ?> • 
                                        Blood: <?php echo htmlspecialchars($patient['blood_group']); ?>
                                    </p>
                                    <div class="tag-list">
                                        <?php foreach ($patient['conditions'] as $condition): ?>
                                            <span class="tag"><?php echo htmlspecialchars($condition); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <span class="badge badge-<?php echo $patient['status_code']; ?>">
                                <span class="dot"></span>
                                <?php echo htmlspecialchars($patient['status_label']); ?>
                            </span>
                        </div>
                        
                        <div class="patient-card-body">
                            <div class="info-row">
                                <span class="material-symbols-outlined">person</span>
                                <span>Caregiver: <?php echo htmlspecialchars($patient['caregiver']); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="material-symbols-outlined">calendar_today</span>
                                <span>Next: <?php echo htmlspecialchars($patient['next_session']); ?></span>
                            </div>
                        </div>

                        <div class="patient-card-footer">
                        <a href="patient-profile.php" class="btn btn-outline flex-1">
    View Profile
</a>    
<a href="dailycare-report.php" class="btn btn-primary flex-1">
    Daily Care Reports
</a>                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Recent Daily Care Reports -->
        <section class="section">
            <div class="section-header">
                <div>
                    <h2>Recent Daily Care Reports</h2>
                    <p>Latest updates from your patients' caregivers.</p>
                </div>
                <a href="#" class="link-arrow">
                    View All Reports <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>

            <div class="reports-list">
                <?php foreach ($reports as $report): ?>
                    <div class="report-card">
                        <div class="report-content">
                            <div class="report-meta">
                                <span class="patient-name"><?php echo htmlspecialchars($report['patient']); ?></span>
                                <span class="bullet">•</span>
                                <span class="report-date"><?php echo htmlspecialchars($report['date']); ?></span>
                                <span class="bullet">•</span>
                                <span class="status-chip"><?php echo htmlspecialchars($report['status']); ?></span>
                                <span class="bullet">•</span>
                                <span class="caregiver-name">
                                    <span class="material-symbols-outlined">person</span>
                                    <?php echo htmlspecialchars($report['caregiver']); ?>
                                </span>
                            </div>
                            <p class="report-text">"<?php echo htmlspecialchars($report['note']); ?>"</p>
                        </div>
                        <button class="btn btn-outline view-report-btn" data-report-id="<?php echo $report['id']; ?>">View Report</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <!-- Modal for Adding Patient -->
    <div class="modal-overlay" id="patient-modal">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Add New Patient</h3>
                <button class="icon-button" id="close-modal-btn">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="add-patient-form" class="modal-body">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" required placeholder="e.g. John Doe">
                </div>
                <div class="form-group">
                    <label for="relationship">Relationship</label>
                    <input type="text" id="relationship" required placeholder="e.g. Father, Mother">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" required min="1" max="120">
                    </div>
                    <div class="form-group">
                        <label for="blood_group">Blood Group</label>
                        <select id="blood_group">
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancel-modal-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Patient</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">SafeHands</div>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact Support</a>
                <a href="#">Help Center</a>
            </div>
            <div class="footer-copyright">
                © <?php echo date('Y'); ?> SafeHands Healthcare Management. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Custom JavaScript -->
    <script src="assets/js/patients.js"></script>
</body>
</html>