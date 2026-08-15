<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Booking Details | SafeHands</title>
    <link rel="stylesheet" href="assets/css/booking-details.css" />
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="container header-flex">
            <div class="brand">SafeHands</div>
            <nav class="nav-links">
                <a href="#">Dashboard</a>
                <a href="#">Patients</a>
                <a href="#">Find Caregivers</a>
                <a href="#" class="active">My Bookings</a>
            </nav>
            <div class="header-icons">
                <button class="icon-btn" aria-label="Notifications">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                </button>
                <button class="icon-btn" aria-label="Account">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                </button>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Page Title Section -->
        <div class="page-header">
            <div>
                <nav class="breadcrumbs">
                    <span style="cursor:pointer">My Bookings</span>
                    <svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                    <span class="current">BK-2026-00125</span>
                </nav>
                <h1 style="font-size: 32px; font-weight: 600; margin-bottom: 8px;">Booking Details</h1>
                <div class="badge-group">
                    <div class="badge badge-success">
                        <span class="dot-status"></span> Confirmed
                    </div>
                    <div class="badge badge-warning">
                        <svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                        Payment Held Securely
                    </div>
                </div>
            </div>

            <!-- Reference Card -->
            <div class="reference-card">
                <div style="text-align: right;">
                    <p style="font-size: 12px; color: var(--outline); text-transform: uppercase;">Reference ID</p>
                    <p style="font-size: 24px; font-weight: 600; color: var(--primary);">BK-2026-00125</p>
                </div>
                <div style="background-color: var(--primary-fixed); padding: 8px; border-radius: var(--radius-md); color: var(--primary);">
                    <svg class="icon icon-lg" viewBox="0 0 24 24"><path d="M3 5v4h2V5h4V3H5c-1.1 0-2 .9-2 2zm2 10H3v4c0 1.1.9 2 2 2h4v-2H5v-4zm14 4h-4v2h4c1.1 0 2-.9 2-2v-4h-2v4zm0-16h-4v2h4v4h2V5c0-1.1-.9-2-2-2z"/></svg>
                </div>
            </div>
        </div>

        <!-- Accessible Tabs -->
        <div class="tabs" role="tablist">
            <button class="tab-btn active" id="tab-overview" role="tab" aria-selected="true" onclick="switchTab('overview')">Overview</button>
            <button class="tab-btn" id="tab-sessions" role="tab" aria-selected="false" onclick="switchTab('sessions')">Sessions</button>
            <button class="tab-btn" id="tab-payment" role="tab" aria-selected="false" onclick="switchTab('payment')">Payment</button>
            <button class="tab-btn" id="tab-reports" role="tab" aria-selected="false" onclick="switchTab('reports')">Daily Reports</button>
            <button class="tab-btn" id="tab-timeline" role="tab" aria-selected="false" onclick="switchTab('timeline')">Timeline</button>
        </div>

        <!-- Content Grid -->
        <div class="grid-layout">
            <div class="left-content">
                
                <!-- Overview Content -->
                <div class="tab-content active" id="content-overview">
                    <div class="card-pair" style="margin-bottom: 32px;">
                        <!-- Caregiver Card -->
                        <div class="card">
                            <div class="card-label">Assigned Caregiver</div>
                            <div class="profile-flex">
                                <img class="profile-img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAIkWgruRUM4IntcGZYMqZiA5BQfu5BHMHewo-2iLfEZvDtNvLngpjxs6mGM2VoqHg-2w_TBuccpCjSX7Rd6pyDwzyoibnxFk9eg2mcsteYKJ1w1vl3_1aRJhymQzea9-unfTS9cpkLaSLUncum1E2bgGuFOmnivyRFBWcS5RrIVT7MTvLbMMPOX3GpsClU_o_0JMEX5-39PH1kHxpBEJrbt5Qyiz8W4xXJGFS5G4Vzhu-Eim-b0TgfhCjcunXy8yRYWHZRjIaL5N4" alt="Sarah Wijesinghe Portrait" />
                                <div>
                                    <h4 style="font-size: 24px; color: var(--primary);">Sarah Wijesinghe</h4>
                                    <div style="display: flex; align-items: center; gap: 4px; color: var(--status-warning);">
                                        <svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span style="font-weight: 700; color: var(--on-surface);">5.0</span>
                                        <span style="color: var(--outline); font-size: 12px;">(128 reviews)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tag-list">
                                <span class="tag">ELDERLY CARE</span>
                                <span class="tag">PHYSIOTHERAPY</span>
                            </div>
                        </div>

                        <!-- Patient & Location -->
                        <div class="card">
                            <div class="card-label">Patient &amp; Location</div>
                            <div style="display: flex; flex-direction: column; gap: 16px;">
                                <div style="display: flex; gap: 12px;">
                                    <svg class="icon" style="color: var(--primary);" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                    <div>
                                        <p style="font-weight: 700;">Mr. Silva (Father)</p>
                                        <p style="font-size: 12px; color: var(--outline);">Age: 72 | Mobility: Assisted</p>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 12px;">
                                    <svg class="icon" style="color: var(--primary);" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                    <div>
                                        <p style="font-weight: 700;">45, Flower Road, Colombo 07</p>
                                        <p style="font-size: 12px; color: var(--outline);">Residential Villa, Gate Code: 1212</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Care Notes -->
                    <div class="card" style="margin-bottom: 32px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <svg class="icon" style="color: var(--primary);" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/></svg>
                            <h3 style="font-size: 24px;">Service Notes</h3>
                        </div>
                        <p class="notes-box">"Requires assistance with morning stretches and light walking. Please ensure medications are taken at 9:00 AM with breakfast. Mr. Silva prefers gentle conversational engagement during his walk."</p>
                    </div>

                    <!-- Map Preview -->
                    <div class="map-card">
                        <div class="map-bg" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAIVGtgQdkKtBrgFgOi16RnTj3JhAOXhXCTKVi-mEAkZoLAjTClt3CxPVefdzRIVti9jdM8psRsyiD9WwGMnOd_mAtpWVmIhAgIm4wdI8pBsn77rDiTv_C8PgG4Ysf_UYI2eVRiNK2KJo5kuesjXamBfJq3VtP9Abyc5ih547_35D5reabpiODCedmMBqkPzQFcB5e47iWg1r9eLinGzVrdSc5SuAfrRgFIxXESQacRETeXVuuFkgm9B_n6ySSzIbHlqIAxkGMzYoE');"></div>
                        <div class="map-badge">
                            <svg class="icon icon-sm" style="color: var(--status-success);" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            <span>Caregiver verified for this zone</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Content -->
                <div class="tab-content" id="content-payment">
                    <div class="banner-escrow" style="margin-bottom: 32px;">
                        <svg class="icon icon-lg" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-5.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                        <div>
                            <h4 style="font-size: 24px; margin-bottom: 4px;">Secure Escrow Active</h4>
                            <p style="font-size: 16px; opacity: 0.9;">Your payment is securely held by SafeHands until the care service is successfully completed.</p>
                        </div>
                    </div>

                    <div class="invoice-summary">
                        <div class="invoice-header">
                            <h3 style="font-size: 24px;">Invoice Summary</h3>
                            <button class="btn-link" style="display: flex; align-items: center; gap: 4px;">
                                <svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg> Download PDF
                            </button>
                        </div>
                        <div class="invoice-body">
                            <div class="invoice-row">
                                <span style="color: var(--on-surface-variant);">Daily Care Service (7 days)</span>
                                <span style="font-weight: 600;">LKR 45,000.00</span>
                            </div>
                            <div class="invoice-row">
                                <span style="color: var(--on-surface-variant);">Medical Equipment Fee</span>
                                <span style="font-weight: 600;">LKR 2,500.00</span>
                            </div>
                            <div class="invoice-row">
                                <span style="color: var(--on-surface-variant);">SafeHands Platform Fee</span>
                                <span style="font-weight: 600;">LKR 3,800.00</span>
                            </div>
                            <div class="invoice-row invoice-total">
                                <span>Total Amount Paid</span>
                                <span style="color: var(--primary);">LKR 51,300.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Content -->
                <div class="tab-content" id="content-timeline">
                    <div class="card">
                        <h3 style="font-size: 24px; margin-bottom: 32px;">Booking Progress</h3>
                        <div class="timeline">
                            <div class="timeline-item done">
                                <div class="timeline-node">
                                    <svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                </div>
                                <h4>Booking Confirmed</h4>
                                <p style="font-size: 12px; color: var(--outline);">Oct 12, 2026 • 09:15 AM</p>
                            </div>
                            <div class="timeline-item done">
                                <div class="timeline-node">
                                    <svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                </div>
                                <h4>Payment Received</h4>
                                <p style="font-size: 12px; color: var(--outline);">Oct 12, 2026 • 09:20 AM</p>
                            </div>
                            <div class="timeline-item active">
                                <div class="timeline-node"><div class="node-pulse"></div></div>
                                <h4 style="color: var(--primary);">OTP Verification</h4>
                                <p style="font-size: 12px; color: var(--outline);">Awaiting Caregiver Arrival</p>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-node"></div>
                                <h4 style="color: var(--outline);">Service Started</h4>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Controls Sidebar -->
            <aside>
                <div class="sticky-sidebar">
                    <div class="card">
                        <div class="card-label">Service Controls</div>
                        <div style="background-color: var(--surface-muted); padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; border: 1px solid var(--border-subtle);">
                            <p style="font-size: 12px; color: var(--outline); margin-bottom: 4px;">Service Date</p>
                            <p style="font-weight: 700;">Tomorrow, Oct 14 • 08:00 AM</p>
                        </div>
                        
                        <button class="btn btn-primary" id="generate-otp-btn">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/></svg>
                            Generate OTP
                        </button>
                        <p style="text-align: center; font-size: 11px; color: var(--outline); margin-top: 8px;">Provide this OTP to the caregiver only after they arrive at the location.</p>

                        <div style="padding-top: 16px; margin-top: 16px; border-top: 1px solid var(--border-subtle);">
                            <button class="btn btn-outline-danger">Cancel Booking</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-label">Summary</div>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; justify-between: space-between; font-size: 14px;">
                                <span style="color: var(--outline);">Duration</span>
                                <span style="font-weight: 700;">7 Days</span>
                            </div>
                            <div style="display: flex; justify-between: space-between; font-size: 14px;">
                                <span style="color: var(--outline);">Type</span>
                                <span style="font-weight: 700;">Day Care</span>
                            </div>
                            <div style="display: flex; justify-between: space-between; font-size: 14px;">
                                <span style="color: var(--outline);">Total</span>
                                <span style="font-weight: 700; color: var(--primary);">LKR 51,300</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container footer-flex">
            <div style="font-weight: 700;">SafeHands</div>
            <p style="font-size: 12px; color: var(--on-surface-variant);">© <?php echo date('Y'); ?> SafeHands Caregiving Services. Professional Healthcare Solutions.</p>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Support</a>
            </div>
        </div>
    </footer>

    <!-- Native Script -->
    <script src="assets/js/booking-details.js"></script>
</body>
</html>