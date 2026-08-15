<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SafeHands | Booking Confirmed</title>
    <link rel="stylesheet" href="assets/css/confirmation.css" />
</head>
<body>

    <!-- Native WebGL Shader Canvas -->
    <div class="fixed-bg">
        <canvas id="shader-canvas"></canvas>
    </div>

    <!-- Navigation Header -->
    <header>
        <div class="container header-content">
            <div class="brand">SafeHands</div>
            <nav class="nav-links">
                <a href="#">Dashboard</a>
                <a href="#">Patients</a>
                <a href="find-caregivers.php">Find Caregivers</a>                <a href="#">My Bookings</a>
            </nav>
            <div class="user-controls">
                <button class="icon-btn" aria-label="Notifications">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                </button>
                <button class="icon-btn" aria-label="Account">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                </button>
                <div class="divider-v"></div>
                <button class="btn-link">Sign Out</button>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Progress Steps -->
        <div class="progress-bar-container">
            <nav class="breadcrumbs">
                <span>Dashboard</span>
                <svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                <span>Caregiver Profile</span>
                <svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                <span class="active">Booking Confirmed</span>
            </nav>
            <div class="steps">
                <div class="step-item">
                    <span class="badge-check"><svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>
                    <span>Booking Details</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <span class="badge-check"><svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>
                    <span>Payment</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <span class="badge-check"><svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>
                    <span class="active">Booking Confirmed</span>
                </div>
            </div>
        </div>

        <div class="fade-in">
            <!-- Hero Banner -->
            <section class="hero">
                <div class="hero-icon scale-in">
                    <svg class="icon icon-lg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                </div>
                <h1>Booking Confirmed &amp; Payment Secured</h1>
                <p>🎉 Your caregiver has been successfully reserved. Your payment is held securely in escrow and will only be released after the service is completed.</p>
            </section>

            <!-- Main Layout Grid -->
            <div class="grid-layout">
                <!-- Main Column -->
                <div class="left-col">
                    <!-- Escrow Notice -->
                    <div class="card card-escrow">
                        <div style="color: var(--primary);">
                            <svg class="icon icon-lg" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-5.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8s0 0 0 0z"/></svg>
                        </div>
                        <div>
                            <h3 style="margin-bottom: 8px;">How Escrow Works</h3>
                            <p style="color: var(--on-surface-variant); font-size: 16px;">Your funds are protected. We only release payment to <strong>Nadeesha Perera</strong> once you provide the 4-digit Visit OTP at the end of each session. This ensures you only pay for care you actually receive.</p>
                        </div>
                    </div>

                    <!-- Details Row -->
                    <div class="card-grid">
                        <!-- Info Card -->
                        <div class="card">
                            <div class="card-header">
                                <span class="card-title">Booking Information</span>
                                <span class="badge-tag">Confirmed</span>
                            </div>
                            <div class="info-list">
                                <div class="info-row">
                                    <span style="color: var(--on-surface-variant);">Booking ID</span>
                                    <span class="val-bold">BK-2026-00125</span>
                                </div>
                                <div class="info-row">
                                    <span style="color: var(--on-surface-variant);">Caregiver</span>
                                    <span class="val-bold">Nadeesha Perera</span>
                                </div>
                                <div class="info-row">
                                    <span style="color: var(--on-surface-variant);">Patient</span>
                                    <span class="val-bold">Mr. Silva</span>
                                </div>
                                <div>
                                    <span style="color: var(--on-surface-variant); font-size: 14px; display: block; margin-bottom: 8px;">Scheduled Sessions</span>
                                    <div class="session-dots">
                                        <div class="dot-item"><span class="dot"></span> 15 July 2026 · Morning</div>
                                        <div class="dot-item"><span class="dot"></span> 16 July 2026 · Evening</div>
                                        <div class="dot-item"><span class="dot"></span> 18 July 2026 · Morning</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Card -->
                        <div class="card summary-card">
                            <div class="card-header">
                                <span class="card-title">Payment Summary</span>
                                <span class="val-bold" style="font-size: 14px;">Held Securely</span>
                            </div>
                            <div class="summary-body">
                                <div class="info-row">
                                    <span style="color: var(--on-surface-variant);">Subtotal</span>
                                    <span>Rs. 6,500</span>
                                </div>
                                <div class="info-row">
                                    <span style="color: var(--on-surface-variant);">Platform Fee</span>
                                    <span>Rs. 300</span>
                                </div>
                                <div class="info-row total-row">
                                    <span>Total Paid</span>
                                    <span style="color: var(--primary);">Rs. 6,800</span>
                                </div>
                            </div>
                            <div class="status-box">
                                <svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-5.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                                Payment Received Successfully
                            </div>
                        </div>
                    </div>

                    <!-- Call To Actions -->
<div class="action-buttons">

<!-- View Booking Details -->
<a href="booking-details.php" class="btn btn-primary">
    <svg class="icon" viewBox="0 0 24 24">
        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
    </svg>
    View My Booking
</a>

<!-- Return to Family Dashboard -->
<a href="family/dashboard.php" class="btn btn-outline">
    Return to Dashboard

</a>

</div>

                <!-- Timeline Sidebar -->
                <aside>
                    <div class="card sticky-sidebar">
                        <h3 class="card-title" style="margin-bottom: 32px;">Service Journey</h3>
                        <div class="timeline">
                            <div class="timeline-step">
                                <div class="timeline-line"></div>
                                <div class="node completed">
                                    <svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                </div>
                                <div class="step-info">
                                    <h4>Booking Confirmed</h4>
                                    <p>Caregiver notified and reserved.</p>
                                </div>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-line"></div>
                                <div class="node active"><div class="node-inner"></div></div>
                                <div class="step-info">
                                    <h4 style="color: var(--primary);">Caregiver Arrival</h4>
                                    <p>Scheduled for 15 Jul · 08:00 AM</p>
                                </div>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-line"></div>
                                <div class="node"></div>
                                <div class="step-info muted">
                                    <h4>Verification OTP</h4>
                                    <p>Share your OTP with caregiver.</p>
                                </div>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-line"></div>
                                <div class="node"></div>
                                <div class="step-info muted">
                                    <h4>Service in Progress</h4>
                                    <p>Caregiver performs requested tasks.</p>
                                </div>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-line"></div>
                                <div class="node"></div>
                                <div class="step-info muted">
                                    <h4>Session Summary</h4>
                                    <p>View notes and vitals report.</p>
                                </div>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-line"></div>
                                <div class="node"></div>
                                <div class="step-info muted">
                                    <h4>Completion Approval</h4>
                                    <p>Approve session in dashboard.</p>
                                </div>
                            </div>

                            <div class="timeline-step">
                                <div class="node"></div>
                                <div class="step-info muted">
                                    <h4>Payment Released</h4>
                                    <p>Funds transferred to caregiver.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container footer-content">
            <div class="brand" style="color: var(--secondary);">SafeHands</div>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">HIPAA Compliance</a>
                <a href="#">Contact Us</a>
            </div>
            <div class="copyright">
                © <?php echo date('Y'); ?> SafeHands Healthcare. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Native JavaScript -->
    <script src="assets/js/confirmation.js"></script>
</body>
</html>