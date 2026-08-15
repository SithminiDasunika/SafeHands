<?php
$pageTitle = "Add New Patient | SafeHands";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/add-patient.css"/>
</head>
<body>

<!-- Navigation Header -->
<header class="top-nav">
    <div class="nav-container">
        <div style="display: flex; align-items: center; gap: 48px;">
            <span class="brand">SafeHands</span>
            <nav class="nav-links">
                <a href="family/dashboard.php"class="nav-link">Dashboard</a>
                <a href="#" class="nav-link active">Patients</a>
                <a href="#" class="nav-link">Find Caregivers</a>
                <a href="#" class="nav-link">My Bookings</a>
            </nav>
        </div>
        <div class="nav-icons">
            <button class="icon-btn" aria-label="Notifications">&#128276;</button>
            <button class="icon-btn" aria-label="Account Profile">&#128100;</button>
        </div>
    </div>
</header>

<main class="main-content">
    <!-- Breadcrumbs -->
    <nav class="breadcrumb">
        <span>Dashboard</span>
        <span>&rsaquo;</span>
        <span>Patients</span>
        <span>&rsaquo;</span>
        <span class="active-crumb">Add New Patient</span>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <h1>Add New Patient</h1>
        <p>Create a patient profile to start booking professional caregiver services.</p>
    </div>

    <!-- Multi-Step Tracker -->
    <div class="progress-tracker" id="progress-tracker">
        <div class="tracker-line"></div>
        <div class="tracker-steps">
            <div class="step-item active" id="step-item-1">
                <div class="dot" id="label-1">1</div>
                <span class="step-label">Personal Information</span>
            </div>
            <div class="step-item" id="step-item-2">
                <div class="dot" id="label-2">2</div>
                <span class="step-label">Medical Information</span>
            </div>
            <div class="step-item" id="step-item-3">
                <div class="dot" id="label-3">3</div>
                <span class="step-label">Emergency &amp; Docs</span>
            </div>
        </div>
    </div>

    <!-- Form Canvas -->
    <div class="form-container">
        
        <!-- STEP 1: Personal Information -->
        <section id="step-1-content" class="step-transition">
            <div class="card">
                <div class="profile-upload">
                    <div class="avatar-placeholder">
                        <span style="font-size: 28px;">&#128247;</span>
                        <span class="upload-text">UPLOAD PHOTO</span>
                    </div>
                    <p class="upload-hint">Recommended size: 512x512px. Max 2MB.</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" class="form-control" placeholder="e.g. Johnathan Doe"/>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select class="form-control">
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Relationship</label>
                        <select class="form-control">
                            <option>Father</option>
                            <option>Mother</option>
                            <option>Grandfather</option>
                            <option>Grandmother</option>
                            <option>Spouse</option>
                            <option>Self</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>NIC Number / ID</label>
                        <input type="text" class="form-control" placeholder="12345-6789012-3"/>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" class="form-control" placeholder="+1 (555) 000-0000"/>
                    </div>
                    <div class="form-group span-full">
                        <label>Home Address</label>
                        <textarea class="form-control" rows="3" placeholder="Street address, City, Apartment, Zip Code"></textarea>
                    </div>
                </div>
                
                <div class="button-group">
                    <button class="btn btn-secondary">Cancel</button>
                    <button class="btn btn-primary" onclick="goToStep(2)">Next</button>
                </div>
            </div>
        </section>

        <!-- STEP 2: Medical Information -->
        <section id="step-2-content" class="step-transition hidden">
            <div class="card">
                <h3 style="font-size: 24px; margin-bottom: 32px;">Clinical Profile</h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select class="form-control">
                            <option>A+</option>
                            <option>A-</option>
                            <option>B+</option>
                            <option>B-</option>
                            <option>AB+</option>
                            <option>AB-</option>
                            <option>O+</option>
                            <option>O-</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mobility Status</label>
                        <select class="form-control">
                            <option>Independent</option>
                            <option>Walking Assistance</option>
                            <option>Wheelchair Bound</option>
                            <option>Bedridden</option>
                        </select>
                    </div>
                    <div class="form-group span-full">
                        <label>Medical Conditions</label>
                        <div class="tags-container">
                            <button type="button" class="tag selected">Diabetes &times;</button>
                            <button type="button" class="tag">Hypertension</button>
                            <button type="button" class="tag">Dementia</button>
                            <button type="button" class="tag">Asthma</button>
                            <button type="button" class="tag">Arthritis</button>
                            <button type="button" class="tag add-tag">+ Add Condition</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Allergies</label>
                        <input type="text" class="form-control" placeholder="e.g. Penicillin, Nuts"/>
                    </div>
                    <div class="form-group">
                        <label>Dietary Restrictions</label>
                        <input type="text" class="form-control" placeholder="e.g. Low sodium, Vegetarian"/>
                    </div>
                    <div class="form-group span-full">
                        <label>Current Medications</label>
                        <textarea class="form-control" rows="2" placeholder="List all prescribed medicines and dosages"></textarea>
                    </div>
                    <div class="form-group span-full">
                        <label>Special Care Requirements</label>
                        <textarea class="form-control" rows="2" placeholder="Any specific needs or behavioral observations"></textarea>
                    </div>
                </div>

                <div class="button-group">
                    <button class="btn btn-secondary" onclick="goToStep(1)">Back</button>
                    <button class="btn btn-primary" onclick="goToStep(3)">Next</button>
                </div>
            </div>
        </section>

        <!-- STEP 3: Emergency Contacts & Documents -->
        <section id="step-3-content" class="step-transition hidden">
            <div class="card">
                <h3 style="font-size: 24px; margin-bottom: 32px;">Safety &amp; Documentation</h3>
                
                <div style="display: flex; flex-direction: column; gap: 32px;">
                    <div>
                        <div class="section-title">Emergency Contact</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Contact Name</label>
                                <input type="text" class="form-control" placeholder="Emergency Person Name"/>
                            </div>
                            
                            <div class="form-group">
                                <label>Relationship</label>
                                <input type="text" class="form-control" placeholder="e.g. Sibling, Friend"/>
                            </div>
                            <div class="form-group">
                                <label>Primary Phone</label>
                                <input type="tel" class="form-control" placeholder="+1 (555) 000-0000"/>
                            </div>
                            <div class="form-group">
                                <label>Secondary Phone (Optional)</label>
                                <input type="tel" class="form-control" placeholder="+1 (555) 000-0000"/>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="section-title">Medical Documents</div>
                        <div class="dropzone">
                            <span style="font-size: 32px; color: var(--primary); margin-bottom: 8px;">&#9729;</span>
                            <p style="font-weight: 700;">Drop files to upload</p>
                            <p style="font-size: 12px; color: var(--on-surface-variant);">PDF, JPG, PNG (Max 10MB per file)</p>
                            <p style="font-size: 12px; color: var(--on-surface-variant);">Prescriptions, Lab Reports, ID copies</p>
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button class="btn btn-secondary" onclick="goToStep(2)">Back</button>
                    <button class="btn btn-primary" onclick="submitForm()">Create Patient Profile</button>
                </div>
            </div>
        </section>

        <!-- Loading View -->
        <section id="loading-state" class="spinner-container hidden">
            <div class="spinner"></div>
            <h2 style="font-size: 24px; margin-top: 24px;">Encrypting patient data...</h2>
            <p style="color: var(--on-surface-variant);">Building clinical profile</p>
        </section>

        <!-- Success View -->
        <section id="success-state" class="hidden">
            <div class="card success-card">
                <div class="success-icon">&#10003;</div>
                <h2 style="font-size: 32px; margin-bottom: 16px;">Patient Profile Created Successfully</h2>
                <p style="color: var(--on-surface-variant); max-width: 576px; margin: 0 auto 40px auto;">
                    Johnathan Doe's profile is now ready. You can immediately start searching for matched caregivers or upload more medical history.
                </p>

                <div class="summary-card">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background-color: var(--surface-container-high); display: flex; align-items: center; justify-content: center; font-size: 32px;">
                        &#128104;
                    </div>
                    <div>
                        <h4 style="font-size: 20px; margin-bottom: 4px;">Johnathan Doe</h4>
                        <p style="font-size: 14px; color: var(--on-surface-variant); margin-bottom: 8px;">Patient ID: SH-2024-8842</p>
                        <div>
                            <span class="badge badge-primary">A+ Blood</span>
                            <span class="badge badge-success">Verified</span>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary"
        onclick="window.location.href='find-caregivers.php'">
    Find a Caregiver
</button>

<button class="btn btn-secondary"
        onclick="window.location.href='patient-profile.php'">
    View Patient Profile
</button>

<button class="btn btn-outline"
        onclick="window.location.href='family/dashboard.php'">
    Return to Dashboard
</button>
            </div>
        </section>

    </div>
</main>

<!-- Footer -->
<footer class="site-footer">
    <div class="footer-container">
        <span class="brand">SafeHands</span>
        <div class="footer-links">
            <a href="#" class="footer-link">Terms of Service</a>
            <a href="#" class="footer-link">Privacy Policy</a>
            <a href="#" class="footer-link">Escrow Terms</a>
            <a href="#" class="footer-link">Contact Support</a>
        </div>
        <p style="color: var(--on-surface-variant); font-size: 14px;">&copy; <?php echo date("Y"); ?> SafeHands Healthcare. All rights reserved.</p>
    </div>
</footer>

<script src="assets/js/add-patient.js"></script>
</body>
</html>