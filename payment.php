<?php require_once __DIR__ . '/includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SafeHands | Secure Payment</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Pure Modular Stylesheet -->
    <link rel="stylesheet" href="assets/css/payment.css"/>
</head>
<body>

<!-- WebGL Background Canvas -->
<div class="bg-canvas">
    <canvas id="shader-canvas"></canvas>
</div>

<!-- Header -->
<header class="site-header">
    <div class="header-container">
        <div class="brand-title">SafeHands</div>
        <nav class="nav-links">
            <a class="nav-link" href="#">Dashboard</a>
            <a class="nav-link" href="#">Patients</a>
            <a class="nav-link" href="#">Find Caregivers</a>
            <a class="nav-link" href="#">My Bookings</a>
        </nav>
        <div class="header-actions">
            <button class="icon-btn material-symbols-outlined">notifications</button>
            <button class="icon-btn material-symbols-outlined">account_circle</button>
            <div class="divider-v"></div>
            <button class="sign-out-btn">Sign Out</button>
        </div>
    </div>
</header>

<!-- Main Wrapper -->
<main class="main-wrapper">
    <!-- Progress Indicator -->
    <div class="progress-container">
        <div class="breadcrumbs">
            <span>Dashboard</span>
            <span class="material-symbols-outlined">chevron_right</span>
            <span>Caregiver Profile</span>
            <span class="material-symbols-outlined">chevron_right</span>
            <span class="active">Payment</span>
        </div>
        <div class="steps-list">
            <div class="step-item">
                <span class="step-badge success material-symbols-outlined">check</span>
                <span>Booking Details</span>
            </div>
            <div class="step-line"></div>
            <div class="step-item">
                <span class="step-badge active" id="step-2-icon">2</span>
                <span class="step-text active" id="step-2-text">Payment</span>
            </div>
            <div class="step-line"></div>
            <div class="step-item">
                <span class="step-badge pending" id="step-3-icon">3</span>
                <span class="step-text" id="step-3-text">Confirmation</span>
            </div>
        </div>
    </div>

    <!-- Main Dynamic Content Container -->
    <div id="main-content-area">
        <div class="grid-layout">
            <!-- Left Form Column -->
            <section class="card">
                <h1 class="card-title">Complete Secure Payment</h1>
                <p class="card-subtitle">Your payment will be securely held by SafeHands and released to the caregiver only after service completion.</p>

                <div class="payment-methods-grid">
                    <button type="button" class="method-option selected">
                        <span class="material-symbols-outlined method-icon">credit_card</span>
                        <span class="method-label">Credit/Debit</span>
                    </button>
                    <button type="button" class="method-option">
                        <span class="material-symbols-outlined method-icon">account_balance_wallet</span>
                        <span class="method-label">HelaPay</span>
                    </button>
                    <button type="button" class="method-option">
                        <span class="material-symbols-outlined method-icon">account_balance</span>
                        <span class="method-label">Online Banking</span>
                    </button>
                    <button type="button" class="method-option">
                        <span class="material-symbols-outlined method-icon">payments</span>
                        <span class="method-label">Digital Wallet</span>
                    </button>
                </div>

                <form id="payment-form">
                    <div class="form-group">
                        <label class="form-label">Card Number</label>
                        <div class="input-wrapper">
                            <input class="form-input" name="card_number" placeholder="0000 0000 0000 0000" required type="text"/>
                            <span class="material-symbols-outlined input-icon">credit_card</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Expiry Date</label>
                            <input class="form-input" name="expiry" placeholder="MM/YY" required type="text"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">CVV</label>
                            <input class="form-input" name="cvv" placeholder="***" required type="password"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cardholder Name</label>
                        <input class="form-input" name="card_holder" placeholder="John Doe" required type="text"/>
                    </div>

                    <div class="security-banner">
                        <div class="security-icon-circle">
                            <span class="material-symbols-outlined">verified_user</span>
                        </div>
                        <div>
                            <h4 style="font-weight:700;">Secure Escrow Payment</h4>
                            <p style="font-size:12px; color:var(--on-surface-variant);">Funds are only released after each session is completed and verified.</p>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input id="tc" required type="checkbox"/>
                        <label class="checkbox-label" for="tc">
                            I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Cancellation Policy</a>.
                        </label>
                    </div>

                    <button
    type="button"
    class="btn-primary"
    id="pay-btn"
    onclick="window.location.href='confirmation.php';">
    <span class="material-symbols-outlined">lock</span>
    Pay Securely
</button>
                </form>
            </section>

            <!-- Right Sidebar Invoice -->
            <aside class="card invoice-card">
                <div class="invoice-header">
                    <h3>Booking Summary</h3>
                    <p style="font-size: 12px; color: var(--on-surface-variant);">Review your session details</p>
                </div>
                <div class="invoice-body">
                    <div class="person-row">
                        <div class="person-info">
                            <img alt="Nadeesha Perera" class="avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAo3Gjg6-ngmwyHuor9l7R-PEnkUAazKkK5uNwZJXiZXbKf4Q0eL0GtVEtJxa8qbGsdGQ9e0_e0-EgIJTQGp_b51L2S39FH1T2Usc9IwMWrc3In0pyyjxiGzOS410qkSF_LBh1x-fuP6tiscZF5lC-ohVgKqqQZAggs4Lg4OKeNuZ9UWMCDoP5JEf6P0tiekoa07nxJhRTPB0X-MKPu-G-ABBsxaYvHXpHOTHGYbQ4OeOKZXMAntVmmyYKQ8LjetIqxPO4nT2RW2Io"/>
                            <div>
                                <p style="font-size: 12px; color: var(--on-surface-variant);">Caregiver</p>
                                <strong>Nadeesha Perera</strong>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <p style="font-size: 12px; color: var(--on-surface-variant);">Patient</p>
                            <strong>Mr. Silva</strong>
                        </div>
                    </div>

                    <div class="line-items">
                        <div class="line-item"><span>15 Jul · Morning Session</span><strong>Rs. 2,000</strong></div>
                        <div class="line-item"><span>16 Jul · Evening Session</span><strong>Rs. 2,500</strong></div>
                        <div class="line-item"><span>18 Jul · Morning Session</span><strong>Rs. 2,000</strong></div>
                    </div>

                    <div class="divider-h"></div>

                    <div class="line-items">
                        <div class="line-item"><span>Subtotal</span><span>Rs. 6,500</span></div>
                        <div class="line-item"><span>Platform Fee</span><span>Rs. 300</span></div>
                        <div class="line-item" style="padding-top:16px;">
                            <span class="grand-total">Grand Total</span>
                            <span class="grand-total">Rs. 6,800</span>
                        </div>
                    </div>

                    <div class="status-badge">
                        <span class="pulse-dot"></span> Status: Pending Payment
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>

<!-- Loading Overlay -->
<div class="overlay" id="processing-overlay">
    <div class="loader-ring"></div>
    <h2 style="font-size: 24px; color: var(--primary);">Processing Payment...</h2>
    <p style="color: var(--on-surface-variant);">Finalizing your secure escrow transaction.</p>
</div>

<footer class="site-footer">
    <div class="footer-container">
        <div style="font-size: 20px; font-weight: bold; color: var(--primary);">SafeHands</div>
        <div style="display:flex; gap: 24px; font-size: 14px;">
            <a href="#" style="color: var(--on-surface-variant); text-decoration: none;">Privacy Policy</a>
            <a href="#" style="color: var(--on-surface-variant); text-decoration: none;">Terms of Service</a>
            <a href="#" style="color: var(--on-surface-variant); text-decoration: none;">Contact Us</a>
        </div>
        <div style="font-size: 14px; color: var(--on-surface-variant);">© 2026 SafeHands Healthcare. All rights reserved.</div>
    </div>
</footer>

<script src="assets/js/payment.js"></script>
</body>
</html>