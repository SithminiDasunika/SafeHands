<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient Profile | SafeHands</title>
    
    <!-- Google Fonts & Material Symbols (Web Fonts) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/patient-edit.css">
</head>
<body>

    <!-- Success Notification Banner -->
    <div id="success-notification" class="notification-banner">
        <span class="material-symbols-outlined icon-success">check_circle</span>
        <span class="notification-text">Patient profile updated successfully. Redirecting...</span>
    </div>

    <!-- Navigation Header -->
    <header class="navbar-header">
        <div class="navbar-container">
            <div class="nav-left">
                <a href="#" class="brand-logo">SafeHands</a>
                <nav class="nav-links">
                    <a href="family/dashboard.php" class="nav-link">Dashboard</a>
                    <a href="#" class="nav-link active">Patients</a>
                    <a href="find-caregivers.php" class="nav-link">Find Caregivers</a>
                    <a href="#" class="nav-link">My Bookings</a>
                </nav>
            </div>
            <div class="nav-right">
                <button type="button" class="btn-icon" aria-label="Notifications">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="user-avatar-wrapper">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuC4f7qkaAKLQDThwwMoxo2F2405mH8Njr0gDzy-DdYdbGdB6dWhl281b25kDfspw8MFWxD8DhG67cxxTBhNlpEfmcBB2TaVWd4cDF4fcXmyGTYktdSjqsRRTOY_HrHtHnu0f8sBjd-i4r3UJnDsmyrmzYsgHUwGkP2Jj9wj5XZumYKWguzfREknqWEdo_vzlUizN8YNgCQJnFPSr-8lqEOKNSuLK4SjETLrfTxc7FTpvGLHQBYk1qGOArHSf7wvxfrstc5Kc4bxJmA" alt="Admin Profile" class="avatar-img">
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="page-container">
        
        <!-- Breadcrumbs -->
        <nav class="breadcrumb-nav" aria-label="Breadcrumb">
            <a href="#" class="breadcrumb-item">Patients</a>
            <span class="material-symbols-outlined icon-chevron">chevron_right</span>
            <a href="#" class="breadcrumb-item">Mr. Silva</a>
            <span class="material-symbols-outlined icon-chevron">chevron_right</span>
            <span class="breadcrumb-item active">Edit Profile</span>
        </nav>

        <h1 class="page-title">Edit Patient Profile</h1>

        <div class="layout-grid">
            
            <!-- Sidebar: Patient Overview -->
            <aside class="sidebar-column">
                <div class="patient-card">
                    <div class="patient-avatar-container">
                        <div class="avatar-circle">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCJOEWYX8D1wczvr5uR7FGwbklrzRFcUX29ragKPxb4QStgBtSqgw4QjkuAHlWXEvTfdxfNDzth-QXtAlTdWnjQvkZp9_i43irsMfowtzoeYR42mQhCty-93sdp89ere2ulXb89d1qcX9Tp6l74GvTkm8LRDTVvyCxa-GJBtas2v_-oZIHgJO1woIOXASils8JA2D0IrpqTA_IWtBXtYyTtgl-lRLSoZpsQSadeXypfdDDR1zRQvNImgwJC-IUpXaXuBhT__suLsDI" alt="Mr. Silva" class="patient-img">
                        </div>
                        <button type="button" class="btn-avatar-edit" aria-label="Edit Photo">
                            <span class="material-symbols-outlined icon-sm">edit</span>
                        </button>
                    </div>

                    <h2 class="patient-name">Mr. Silva</h2>
                    <p class="patient-relation">Father</p>
                    
                    <div class="status-badge">
                        <span class="status-dot"></span>
                        <span>Currently Receiving Care</span>
                    </div>

                    <button type="button" class="btn-secondary btn-full">
                        <span class="material-symbols-outlined icon-sm">photo_camera</span>
                        <span>Change Photo</span>
                    </button>

                    <div class="profile-stats">
                        <h3 class="stats-heading">Profile Stats</h3>
                        <div class="stat-row">
                            <span class="stat-label">Last Updated</span>
                            <span class="stat-value">Oct 12, 2023</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Care Level</span>
                            <span class="stat-value">Premium</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Form: Edit Details -->
            <section class="form-column">
                <div class="form-card">
                    <form id="edit-profile-form" action="patient-edit.php" method="POST">
                        
                        <!-- Section 1: Personal Information -->
                        <section class="form-section">
                            <div class="section-header">
                                <span class="material-symbols-outlined section-icon">person</span>
                                <h2>Personal Information</h2>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="fullName">Full Name</label>
                                    <input type="text" id="fullName" name="fullName" value="Fernando Silva" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" id="dob" name="dob" value="1952-05-14" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select id="gender" name="gender" class="form-control">
                                        <option value="Male" selected>Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="bloodGroup">Blood Group</label>
                                    <select id="bloodGroup" name="bloodGroup" class="form-control">
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="O+" selected>O+</option>
                                        <option value="O-">O-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nicNumber">NIC Number</label>
                                    <input type="text" id="nicNumber" name="nicNumber" value="521342678V" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="relationship">Relationship</label>
                                    <input type="text" id="relationship" name="relationship" value="Father" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="phoneNumber">Phone Number</label>
                                    <input type="tel" id="phoneNumber" name="phoneNumber" value="+94 77 123 4567" class="form-control">
                                </div>
                                <div class="form-group col-span-2">
                                    <label for="homeAddress">Home Address</label>
                                    <input type="text" id="homeAddress" name="homeAddress" value="42/A, Hospital Road, Colombo 07, Sri Lanka" class="form-control">
                                </div>
                            </div>
                        </section>

                        <!-- Section 2: Medical Information -->
                        <section class="form-section">
                            <div class="section-header">
                                <span class="material-symbols-outlined section-icon">medical_services</span>
                                <h2>Medical Information</h2>
                            </div>
                            <div class="form-stack">
                                <div class="form-group">
                                    <label>Primary Medical Conditions</label>
                                    <div class="tags-container" id="conditions-list">
                                        <span class="tag">
                                            Hypertension
                                            <button type="button" class="btn-tag-remove" aria-label="Remove Hypertension">
                                                <span class="material-symbols-outlined icon-xs">close</span>
                                            </button>
                                        </span>
                                        <span class="tag">
                                            Type 2 Diabetes
                                            <button type="button" class="btn-tag-remove" aria-label="Remove Type 2 Diabetes">
                                                <span class="material-symbols-outlined icon-xs">close</span>
                                            </button>
                                        </span>
                                        <button type="button" class="btn-tag-add" id="btn-add-condition">
                                            <span class="material-symbols-outlined icon-xs">add</span> Add Condition
                                        </button>
                                    </div>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="allergies">Allergies</label>
                                        <input type="text" id="allergies" name="allergies" value="Penicillin, Peanuts" placeholder="e.g. None" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobilityStatus">Mobility Status</label>
                                        <select id="mobilityStatus" name="mobilityStatus" class="form-control">
                                            <option value="Independent">Independent</option>
                                            <option value="Walking Assistance" selected>Walking Assistance</option>
                                            <option value="Wheelchair User">Wheelchair User</option>
                                            <option value="Bedridden">Bedridden</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="medications">Current Medications</label>
                                    <textarea id="medications" name="medications" rows="3" class="form-control">Metformin 500mg (Daily), Lisinopril 10mg (Daily), Baby Aspirin 81mg (Daily)</textarea>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="specialCare">Special Care Requirements</label>
                                        <textarea id="specialCare" name="specialCare" rows="3" placeholder="e.g. Assistance with bathing..." class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="dietary">Dietary Restrictions</label>
                                        <textarea id="dietary" name="dietary" rows="3" placeholder="e.g. Low sodium diet..." class="form-control">Low sodium, low sugar intake recommended.</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="doctorNotes">Doctor's Notes</label>
                                    <textarea id="doctorNotes" name="doctorNotes" rows="3" class="form-control">Stable condition. Needs regular monitoring of blood glucose levels twice a day.</textarea>
                                </div>
                            </div>
                        </section>

                        <!-- Section 3: Emergency Contact -->
                        <section class="form-section">
                            <div class="section-header">
                                <span class="material-symbols-outlined section-icon">emergency</span>
                                <h2>Emergency Contact</h2>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="emergencyName">Contact Name</label>
                                    <input type="text" id="emergencyName" name="emergencyName" value="Mrs. Silva (Daughter-in-law)" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="emergencyRelation">Relationship</label>
                                    <input type="text" id="emergencyRelation" name="emergencyRelation" value="Daughter-in-law" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="emergencyPhone">Phone Number</label>
                                    <input type="tel" id="emergencyPhone" name="emergencyPhone" value="+94 77 987 6543" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="emergencyAltPhone">Alternative Phone</label>
                                    <input type="tel" id="emergencyAltPhone" name="emergencyAltPhone" value="+94 11 234 5678" class="form-control">
                                </div>
                            </div>
                        </section>

                        <!-- Section 4: Medical Documents -->
                        <section class="form-section">
                            <div class="section-header-flex">
                                <div class="section-header">
                                    <span class="material-symbols-outlined section-icon">description</span>
                                    <h2>Medical Documents</h2>
                                </div>
                                <button type="button" class="btn-link">
                                    <span class="material-symbols-outlined icon-sm">add</span> Upload New
                                </button>
                            </div>
                            
                            <div class="document-list">
                                <div class="document-item">
                                    <div class="doc-info">
                                        <div class="doc-icon-box">
                                            <span class="material-symbols-outlined">description</span>
                                        </div>
                                        <div class="doc-details">
                                            <p class="doc-name">Medical Reports - 2023.pdf</p>
                                            <p class="doc-meta">Uploaded on Oct 05, 2023 • 2.4 MB</p>
                                        </div>
                                    </div>
                                    <div class="doc-actions">
                                        <button type="button" class="btn-action-outline">Replace</button>
                                        <button type="button" class="btn-action-danger">Remove</button>
                                    </div>
                                </div>

                                <div class="document-item">
                                    <div class="doc-info">
                                        <div class="doc-icon-box">
                                            <span class="material-symbols-outlined">prescriptions</span>
                                        </div>
                                        <div class="doc-details">
                                            <p class="doc-name">Doctor Prescriptions.pdf</p>
                                            <p class="doc-meta">Uploaded on Sep 18, 2023 • 1.1 MB</p>
                                        </div>
                                    </div>
                                    <div class="doc-actions">
                                        <button type="button" class="btn-action-outline">Replace</button>
                                        <button type="button" class="btn-action-danger">Remove</button>
                                    </div>
                                </div>

                                <div class="document-item">
                                    <div class="doc-info">
                                        <div class="doc-icon-box">
                                            <span class="material-symbols-outlined">lab_research</span>
                                        </div>
                                        <div class="doc-details">
                                            <p class="doc-name">Lab Reports - Blood Work.pdf</p>
                                            <p class="doc-meta">Uploaded on Aug 22, 2023 • 3.8 MB</p>
                                        </div>
                                    </div>
                                    <div class="doc-actions">
                                        <button type="button" class="btn-action-outline">Replace</button>
                                        <button type="button" class="btn-action-danger">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Form Action Buttons -->
                        <div class="form-actions">
                            <button type="reset" class="btn-secondary">Reset Changes</button>
                            <div class="actions-right">
                                <button type="button" class="btn-ghost">Cancel</button>
                                <button type="submit" id="save-btn" class="btn-primary">Save Changes</button>
                            </div>
                        </div>

                    </form>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer Area -->
    <footer class="footer-container">
        <div class="footer-left">
            <span class="footer-brand">SafeHands</span>
            <span class="footer-copy">© 2024 SafeHands Caregiving Services. All rights reserved.</span>
        </div>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact Support</a>
            <a href="#">Help Center</a>
        </div>
    </footer>

    <!-- Custom Script -->
    <script src="assets/js/patient-edit.js"></script>
</body>
</html>