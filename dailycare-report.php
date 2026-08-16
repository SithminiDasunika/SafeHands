<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Daily Care Reports | SafeHands</title>
    <link rel="stylesheet" href="assets/css/dailycare-report.css"/>
</head>
<body>

<!-- Top Navigation Bar -->
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-brand-group">
            <span class="brand-logo">SafeHands</span>
            <div class="nav-links">
                <a href="#" class="nav-link">Dashboard</a>
                <a href="#" class="nav-link">Patients</a>
                <a href="#" class="nav-link">Find Caregivers</a>
                <a href="#" class="nav-link active">My Bookings</a>
            </div>
        </div>
        <div class="nav-actions">
            <button class="icon-btn" aria-label="Notifications">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            </button>
            <div class="user-profile">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span class="user-name">Sign Out</span>
            </div>
        </div>
    </div>
</nav>

<main class="main-container">
    <!-- Breadcrumb & Header -->
    <div class="header-section">
        <nav class="breadcrumb">
            <a href="#">Dashboard</a>
            <svg class="chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            <a href="#">My Bookings</a>
            <svg class="chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            <span class="active">Daily Care Reports</span>
        </nav>
        <div class="title-area">
            <h1>Daily Care Reports</h1>
            <p>Review reports submitted by caregivers after each completed care session.</p>
        </div>
    </div>

    <!-- Top Summary Card -->
    <div class="summary-card">
        <div class="summary-item">
            <div class="patient-avatar">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCaYmvlAgbG6rgbkme-pm5zMEnQP24VPAec0t9mqGj2FflJgMJjtDTujRsAVHok_wsHsBQoFRyLhWd2jRr4qzRDcPcMUGiLldnUQqiwygTl_aAmfm8Hd9QdUIMzl_DXpc3uXSU2iPBrWHcRBg7OVnYMGpYYJLDyqvYwxQ8WrH_6LLWS3Lf-HdeIVfGcXWzrw1Z7Jinegz0BUq0uxW5iDXXYi3H1nNndeLpzzpKFUDz0yLY3xvnjUt94FrJRHJ2UNSMlVSQxZ21GLa8" alt="Mr. Silva"/>
            </div>
            <div>
                <span class="label">Patient</span>
                <h2>Mr. Silva</h2>
            </div>
        </div>
        <div class="divider"></div>
        <div class="summary-item">
            <div>
                <span class="label">Caregiver</span>
                <div class="caregiver-info">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#004ac6" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                    <strong>Nadeesha Perera</strong>
                </div>
            </div>
        </div>
        <div class="divider"></div>
        <div class="summary-item">
            <div>
                <span class="label">Booking Details</span>
                <p class="booking-id">ID: BK-2026-00125</p>
            </div>
        </div>
        <div class="summary-item status-right">
            <span class="badge badge-in-progress">
                <span class="pulse-dot"></span>
                In Progress
            </span>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="filter-bar">
        <div class="search-wrapper">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" id="searchInput" placeholder="Search by date or keyword..."/>
        </div>
        <div class="filter-controls">
            <select class="select-dropdown">
                <option>All Reports</option>
                <option>Newest First</option>
                <option>Oldest First</option>
                <option>Morning Shift</option>
                <option>Afternoon Shift</option>
                <option>Evening Shift</option>
            </select>
            <button class="filter-btn" aria-label="Filter Options">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
            </button>
        </div>
    </div>

    <!-- Report Grid -->
    <div class="report-grid" id="reportGrid">
        <!-- Card 1 -->
        <div class="report-card">
            <div class="card-header">
                <div class="shift-details">
                    <span class="date">18 July 2026</span>
                    <span class="shift">Morning Shift</span>
                </div>
                <span class="badge badge-success">Stable</span>
            </div>
            <div class="caregiver-bar">
                <img class="avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCuOh1_Yt07yBwqmREeSrvEtFbGTraTB6eTBVkFIPuqkrTpoBr0PUA8GF6rIXtSeaxA1RuOMCEh-PewGoobf8BfXIw9G-ezNuBXP1IjqZRItaYee4uHvIJjYD8I7CIyhTSCpzdwAoSRNzDrkQ_aXOfMRLk4PDOr2ZRmKMfMd2zDndiiZLup3oZshqDK6uXDnj1PoXAlG5M8TPutJA_CeuiWRE_owkJMM06BZsVrmSO2pv-Ep927415pXf0J9WuFufTSwfVnP6U3I7o" alt="Caregiver"/>
                <div>
                    <span class="subtext">Caregiver</span>
                    <span class="name">Nadeesha Perera</span>
                </div>
            </div>
            <p class="summary-text">Medication administered successfully and patient remained comfortable throughout the session. Vital signs are normal and breakfast was well-received.</p>
           <a href="shift-summary.php" class="action-btn">
    View Full Report
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="5" y1="12" x2="19" y2="12"></line>
        <polyline points="12 5 19 12 12 19"></polyline>
    </svg>
</a>
        </div>

        <!-- Card 2 -->
        <div class="report-card">
            <div class="card-header">
                <div class="shift-details">
                    <span class="date">17 July 2026</span>
                    <span class="shift">Afternoon Shift</span>
                </div>
                <span class="badge badge-warning">Needs Attention</span>
            </div>
            <div class="caregiver-bar">
                <img class="avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCWAEi0rRzvXeZOi0JAABItBRZsGHSkJM2twPMuiPTYT97770Fww6zTzZz-cKKTrNVcdRUMmMMpYQpyCagiNFTZe_jdtk5HmQu9cymIXpFEwYYZ_RvG9tbNULA0A7QUKCZXcJgKxaLAFdsakHF4FggL82MsVgg5mv_tykZk010GRgGBbDmTfRHBRax-x64A9ywNZKvyOLex_zJDSbM902nes5KbJB4Iwhuba7FykGAeE59cHv53OQWqkMBjVDDgrZS3q72mU78ICSE" alt="Caregiver"/>
                <div>
                    <span class="subtext">Caregiver</span>
                    <span class="name">James Wilson</span>
                </div>
            </div>
            <p class="summary-text">Patient reported mild discomfort in the lower back area. Assisted with physiotherapy exercises. Noted a slight decrease in appetite during lunch.</p>
            <a href="shift-summary.php" class="action-btn">
    View Full Report
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="5" y1="12" x2="19" y2="12"></line>
        <polyline points="12 5 19 12 12 19"></polyline>
    </svg>
</a>
        </div>

        <!-- Card 3 -->
        <div class="report-card">
            <div class="card-header">
                <div class="shift-details">
                    <span class="date">17 July 2026</span>
                    <span class="shift">Evening Shift</span>
                </div>
                <span class="badge badge-info">Follow-up Required</span>
            </div>
            <div class="caregiver-bar">
                <img class="avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAs6_N9H2WGmXdTv-Puy7riC_ip_lx0e5ga-fNip-C25wme8QTCcY5Po-V2z3w_1H1MTyRGPEwPHdY64H5C2SYADPuhvyaDRWaP60ufS38VxIHroljVnB749Jf1AyiEGBpr-DvW5TiO7D1cZjFvcXP1QKPxSf_mlz8Gcaj-VRjVO2bF0fXYS2N3o5S-OcV7U9Uni43GXWHqhGnPVXIi8woR-VnBzwYXENjQtMbluXWQSDdh8SLyov4vtXDUUZXkGaOAzh_e28CW6CU" alt="Caregiver"/>
                <div>
                    <span class="subtext">Caregiver</span>
                    <span class="name">Sarah Mitchell</span>
                </div>
            </div>
            <p class="summary-text">Routine check-up completed. Recommended a follow-up with the primary physician regarding sleep pattern adjustments discussed during the session.</p>
            <a href="shift-summary.php" class="action-btn">
    View Full Report
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="5" y1="12" x2="19" y2="12"></line>
        <polyline points="12 5 19 12 12 19"></polyline>
    </svg>
</a>
        </div>

        <!-- Card 4 -->
        <div class="report-card">
            <div class="card-header">
                <div class="shift-details">
                    <span class="date">16 July 2026</span>
                    <span class="shift">Morning Shift</span>
                </div>
                <span class="badge badge-success">Stable</span>
            </div>
            <div class="caregiver-bar">
                <img class="avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBzAiYaR2D6rRaGGSH-RczR_IIB072opiLv4FaaJ9R1-6TeKz05tLFP7bi2AWl0ppbwxqqdW77NxMzneFxlnApx3okGEpHlN9k-tUeeXQNrW9wp99KR9E3r-LN8LtdBMNv7F4cXAgX-xeUDgT4APFuhRoO9Im-tjq0ak4v3orr-ySnvlFOfgo34QcSPlpTu-J_JnFQ8AVbh6Dxi5zoLR9PD-CDZXnH16h-SWun4La599yEMwCujo2f3QGENsqDpRNJsI_s2k_8nwXU" alt="Caregiver"/>
                <div>
                    <span class="subtext">Caregiver</span>
                    <span class="name">Nadeesha Perera</span>
                </div>
            </div>
            <p class="summary-text">Excellent session. Patient was highly active and engaged in morning walk. Blood pressure readings are within target range.</p>
            <a href="shift-summary.php" class="action-btn">
    View Full Report
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="5" y1="12" x2="19" y2="12"></line>
        <polyline points="12 5 19 12 12 19"></polyline>
    </svg>
</a>
        </div>

        <!-- Card 5 -->
        <div class="report-card">
            <div class="card-header">
                <div class="shift-details">
                    <span class="date">16 July 2026</span>
                    <span class="shift">Afternoon Shift</span>
                </div>
                <span class="badge badge-success">Stable</span>
            </div>
            <div class="caregiver-bar">
                <img class="avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB-NMDxR5JWHE71aSWg5_FjRLlizbM3kChcxQgnBR3sWgTYP8DNEQy1CPHtvK5t3n5VAlLnCfv9rUEDSXyUPPOBwQQwmnaekFxCykhgsSfgpaiv_vWJzhd1h0-3Tq6frOFs1SBN_3nE3wyVug-zjBkUGQ1Eac6mlOme887nck7FlNZ3_4LUhrEEdgkaJdjouBVCJDOZ3O0HJz_Ecs6vNLlDOxuiVP3Ea8lEuefYEpUCeHtQkgmi7JsL8JV4VIY0SoN4RqlVYMa35QY" alt="Caregiver"/>
                <div>
                    <span class="subtext">Caregiver</span>
                    <span class="name">Ryan Cooper</span>
                </div>
            </div>
            <p class="summary-text">Assisted with lunch and light housekeeping. Patient spent time reading in the garden and mood was very positive throughout the afternoon.</p>
            <a href="shift-summary.php" class="action-btn">
    View Full Report
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="5" y1="12" x2="19" y2="12"></line>
        <polyline points="12 5 19 12 12 19"></polyline>
    </svg>
</a>
        </div>

        <!-- Card 6 -->
        <div class="report-card">
            <div class="card-header">
                <div class="shift-details">
                    <span class="date">15 July 2026</span>
                    <span class="shift">Evening Shift</span>
                </div>
                <span class="badge badge-success">Stable</span>
            </div>
            <div class="caregiver-bar">
                <img class="avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDBw34Bl7nr9YAjrC2GuS60i70gCy7rf3_SfuGnOnO3b1cCNMxpPnIhXk0s8wZGyrj3Pqm9rCtggQFJ3VoaElzkZ15oCrrneuzWW8Hawy-rDczMIelozv6Lql8ql36devUWXqWrA5mwDQwaGpr7qgbKC_i9-GsI26U1BmXqlGCthlvPpy2eYoZDWTtfoMIVW7aTOrBAy_g_5i8OktZwla-VSroAS1JLloXDkU-MrtlmlXSu12-BkfwUEzPZabZ1A4Y4x-0z889NRvg" alt="Caregiver"/>
                <div>
                    <span class="subtext">Caregiver</span>
                    <span class="name">Sarah Mitchell</span>
                </div>
            </div>
            <p class="summary-text">Evening medication administered at 8:00 PM. Patient was settled and resting comfortably before shift end. All safety protocols followed.</p>
            <a href="shift-summary.php" class="action-btn">
    View Full Report
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="5" y1="12" x2="19" y2="12"></line>
        <polyline points="12 5 19 12 12 19"></polyline>
    </svg>
</a>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <button class="page-nav-btn" disabled>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        <button class="page-num active">1</button>
        <button class="page-num">2</button>
        <button class="page-num">3</button>
        <button class="page-nav-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-info">
            <span class="footer-brand">SafeHands</span>
            <p>© <?php echo date("Y"); ?> SafeHands Healthcare. All rights reserved.</p>
        </div>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">HIPAA Compliance</a>
            <a href="#">Contact Us</a>
        </div>
    </div>
</footer>

<script src="assets/js/dailycare-report.js"></script>
</body>
</html>