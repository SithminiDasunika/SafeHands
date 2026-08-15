<?php
session_start();
require_once 'includes/db.php';

/* ==========================================
   PAGINATION
========================================== */

$records_per_page = 8;

$page = isset($_GET['page'])
    ? (int)$_GET['page']
    : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $records_per_page;


/* ==========================================
   FILTER VARIABLES
========================================== */

$search = $_GET['search'] ?? "";
$district = $_GET['district'] ?? "";
$gender = $_GET['gender'] ?? "";
$qualification = $_GET['qualification'] ?? "";
$language = $_GET['language'] ?? "";
$experience = $_GET['experience'] ?? "";
$verified = isset($_GET['verified']);
$sort = $_GET['sort'] ?? "";


/* ==========================================
   BASE QUERY
========================================== */

$sql = "
SELECT
    u.user_id,
    u.first_name,
    u.last_name,
    cp.caregiver_id,
    cp.gender,
    cp.highest_qualification,
    cp.years_experience,
    cp.languages,
    cp.service_areas,
    cp.daily_rate,
    cp.biography,
    cp.profile_photo,
    cp.verification_status
FROM users u
INNER JOIN caregiver_profiles cp ON u.user_id = cp.user_id
WHERE u.role = 'Caregiver'
";


/* ==========================================
   SEARCH
========================================== */

if (!empty($search)) {
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $sql .= "
    AND (
        u.first_name LIKE '%$search_escaped%'
        OR u.last_name LIKE '%$search_escaped%'
        OR cp.biography LIKE '%$search_escaped%'
    )
    ";
}


/* ==========================================
   DISTRICT
========================================== */

if (!empty($district)) {
    $district_escaped = mysqli_real_escape_string($conn, $district);
    $sql .= " AND cp.service_areas LIKE '%$district_escaped%' ";
}


/* ==========================================
   GENDER
========================================== */

if (!empty($gender)) {
    $gender_escaped = mysqli_real_escape_string($conn, $gender);
    $sql .= " AND cp.gender = '$gender_escaped' ";
}


/* ==========================================
   QUALIFICATION
========================================== */

if (!empty($qualification)) {
    $qualification_escaped = mysqli_real_escape_string($conn, $qualification);
    $sql .= " AND cp.highest_qualification = '$qualification_escaped' ";
}


/* ==========================================
   LANGUAGE
========================================== */

if (!empty($language)) {
    $language_escaped = mysqli_real_escape_string($conn, $language);
    $sql .= " AND cp.languages LIKE '%$language_escaped%' ";
}


/* ==========================================
   EXPERIENCE
========================================== */

if (!empty($experience)) {
    switch ($experience) {
        case "0-2":
            $sql .= " AND cp.years_experience BETWEEN 0 AND 2 ";
            break;
        case "3-5":
            $sql .= " AND cp.years_experience BETWEEN 3 AND 5 ";
            break;
        case "6-10":
            $sql .= " AND cp.years_experience BETWEEN 6 AND 10 ";
            break;
        case "10+":
            $sql .= " AND cp.years_experience > 10 ";
            break;
    }
}


/* ==========================================
   VERIFIED ONLY
========================================== */

if ($verified) {
    $sql .= " AND cp.verification_status = 'Verified' ";
}


/* ==========================================
   SORTING
========================================== */

switch ($sort) {
    case "experience":
        $sql .= " ORDER BY cp.years_experience DESC ";
        break;
    case "name":
        $sql .= " ORDER BY u.first_name ASC ";
        break;
    case "price_low":
        $sql .= " ORDER BY cp.daily_rate ASC ";
        break;
    case "price_high":
        $sql .= " ORDER BY cp.daily_rate DESC ";
        break;
    default:
        $sql .= " ORDER BY cp.caregiver_id DESC ";
}


/* ==========================================
   TOTAL RECORDS
========================================== */

$count_query = mysqli_query($conn, $sql);
$total_records = $count_query ? mysqli_num_rows($count_query) : 0;
$total_pages = ceil($total_records / $records_per_page);


/* ==========================================
   LIMIT
========================================== */

$sql .= " LIMIT $offset, $records_per_page ";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Trusted Caregivers | SafeHands</title>
    <link rel="stylesheet" href="assets/css/find-caregivers.css">
</head>
<body>

    <!-- Top Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo">SafeHands</a>

            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="find-caregivers.php" class="active">Find Caregivers</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>

            <div class="nav-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="btn-login">Dashboard</a>
                <?php else: ?>
                    <a href="login.php" class="btn-login">Login</a>
                    <a href="register.php" class="btn-register">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main>
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <h1>Find Trusted Caregivers</h1>
                <p>
                    Browse experienced, verified caregivers across Sri Lanka.
                    Search by district, language, qualifications and experience
                    to find the perfect caregiver for your loved one.
                </p>
            </div>
        </section>

        <!-- Search Bar Section -->
        <section class="search-section">
            <div class="container">
                <form method="GET" action="find-caregivers.php">
                    <div class="search-wrapper">
                        <!-- Search Keyword -->
                        <div class="search-box">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search caregiver..." 
                                value="<?php echo htmlspecialchars($search); ?>"
                            >
                        </div>

                        <!-- District -->
                        <select name="district">
                            <option value="">All Districts</option>
                            <option value="Colombo" <?php if ($district === "Colombo") echo "selected"; ?>>Colombo</option>
                            <option value="Gampaha" <?php if ($district === "Gampaha") echo "selected"; ?>>Gampaha</option>
                            <option value="Kalutara" <?php if ($district === "Kalutara") echo "selected"; ?>>Kalutara</option>
                            <option value="Kandy" <?php if ($district === "Kandy") echo "selected"; ?>>Kandy</option>
                            <option value="Galle" <?php if ($district === "Galle") echo "selected"; ?>>Galle</option>
                            <option value="Kurunegala" <?php if ($district === "Kurunegala") echo "selected"; ?>>Kurunegala</option>
                            <option value="Anuradhapura" <?php if ($district === "Anuradhapura") echo "selected"; ?>>Anuradhapura</option>
                            <option value="Jaffna" <?php if ($district === "Jaffna") echo "selected"; ?>>Jaffna</option>
                        </select>

                        <!-- Gender -->
                        <select name="gender">
                            <option value="">Gender</option>
                            <option value="Male" <?php if ($gender === "Male") echo "selected"; ?>>Male</option>
                            <option value="Female" <?php if ($gender === "Female") echo "selected"; ?>>Female</option>
                        </select>

                        <!-- Qualification -->
                        <select name="qualification">
                            <option value="">Qualification</option>
                            <option value="NVQ Level 3" <?php if ($qualification === "NVQ Level 3") echo "selected"; ?>>NVQ Level 3</option>
                            <option value="NVQ Level 4" <?php if ($qualification === "NVQ Level 4") echo "selected"; ?>>NVQ Level 4</option>
                            <option value="Diploma" <?php if ($qualification === "Diploma") echo "selected"; ?>>Diploma</option>
                            <option value="Certificate" <?php if ($qualification === "Certificate") echo "selected"; ?>>Certificate</option>
                        </select>

                        <!-- Language -->
                        <select name="language">
                            <option value="">Language</option>
                            <option value="Sinhala" <?php if ($language === "Sinhala") echo "selected"; ?>>Sinhala</option>
                            <option value="Tamil" <?php if ($language === "Tamil") echo "selected"; ?>>Tamil</option>
                            <option value="English" <?php if ($language === "English") echo "selected"; ?>>English</option>
                        </select>

                        <!-- Search & Clear Buttons -->
                        <button class="btn-search" type="submit">Search</button>
                        <a href="find-caregivers.php" class="btn-clear">Clear</a>
                    </div>
                </form>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container">
                <div class="layout">
                    <!-- Sidebar Filters -->
                    <aside class="sidebar">
                        <form method="GET" action="find-caregivers.php">
                            <!-- Preserve Top Bar Inputs -->
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                            <input type="hidden" name="district" value="<?php echo htmlspecialchars($district); ?>">
                            <input type="hidden" name="gender" value="<?php echo htmlspecialchars($gender); ?>">
                            <input type="hidden" name="qualification" value="<?php echo htmlspecialchars($qualification); ?>">
                            <input type="hidden" name="language" value="<?php echo htmlspecialchars($language); ?>">

                            <div class="filter-box">
                                <h3>Verification</h3>
                                <label>
                                    <input 
                                        type="checkbox" 
                                        name="verified" 
                                        value="1" 
                                        <?php if ($verified) echo "checked"; ?>
                                    >
                                    Verified Only
                                </label>

                                <hr>

                                <h3>Experience</h3>
                                <label>
                                    <input type="radio" name="experience" value="0-2" <?php if ($experience === "0-2") echo "checked"; ?>>
                                    0 - 2 Years
                                </label>
                                <label>
                                    <input type="radio" name="experience" value="3-5" <?php if ($experience === "3-5") echo "checked"; ?>>
                                    3 - 5 Years
                                </label>
                                <label>
                                    <input type="radio" name="experience" value="6-10" <?php if ($experience === "6-10") echo "checked"; ?>>
                                    6 - 10 Years
                                </label>
                                <label>
                                    <input type="radio" name="experience" value="10+" <?php if ($experience === "10+") echo "checked"; ?>>
                                    10+ Years
                                </label>

                                <hr>

                                <h3>Sort By</h3>
                                <select name="sort">
                                    <option value="">Newest</option>
                                    <option value="experience" <?php if ($sort === "experience") echo "selected"; ?>>Experience</option>
                                    <option value="name" <?php if ($sort === "name") echo "selected"; ?>>Name (A-Z)</option>
                                    <option value="price_low" <?php if ($sort === "price_low") echo "selected"; ?>>Price (Low - High)</option>
                                    <option value="price_high" <?php if ($sort === "price_high") echo "selected"; ?>>Price (High - Low)</option>
                                </select>

                                <button class="btn-filter" type="submit">Apply Filters</button>
                            </div>
                        </form>
                    </aside>

                    <!-- Caregiver Results -->
                    <div class="results">
                        <div class="results-header">
                            <h2><?php echo $total_records; ?> Caregivers Found</h2>
                        </div>

                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <div class="card-grid">
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <div class="caregiver-card">
                                        <div class="card-image">
                                            <?php 
                                            $image = !empty($row['profile_photo']) 
                                                ? $row['profile_photo'] 
                                                : "assets/images/default-user.png"; 
                                            ?>
                                            <img src="<?php echo htmlspecialchars($image); ?>" alt="Caregiver Profile Photo">
                                            
                                            <?php if ($row['verification_status'] === "Verified"): ?>
                                                <span class="verified-badge">✓ Verified</span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="card-body">
                                            <h3><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></h3>
                                            
                                            <div class="qualification">
                                                <?php echo htmlspecialchars($row['highest_qualification']); ?>
                                            </div>

                                            <div class="card-details">
                                                <p><strong>Gender:</strong> <?php echo htmlspecialchars($row['gender']); ?></p>
                                                <p><strong>District:</strong> <?php echo htmlspecialchars($row['service_areas']); ?></p>
                                                <p><strong>Experience:</strong> <?php echo (int)$row['years_experience']; ?> Years</p>
                                                <p><strong>Languages:</strong> <?php echo htmlspecialchars($row['languages']); ?></p>
                                                <p><strong>Daily Rate:</strong> Rs. <?php echo number_format($row['daily_rate']); ?></p>
                                            </div>

                                            <p class="bio">
                                                <?php echo nl2br(htmlspecialchars($row['biography'])); ?>
                                            </p>

                                            <a href="caregiver-profile.php?id=<?php echo $row['caregiver_id']; ?>">
    View Profile
</a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-results">
                                <h2>No Caregivers Found</h2>
                                <p>Try changing your search or filter options.</p>
                            </div>
                        <?php endif; ?>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <div class="pagination">
                                <!-- Previous Button -->
                                <?php if ($page > 1): ?>
                                    <a class="page-btn" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">
                                        &laquo;
                                    </a>
                                <?php endif; ?>

                                <!-- Page Number Links -->
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <a 
                                        href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
                                        class="page-btn <?php if ($page == $i) echo 'active'; ?>"
                                    >
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>

                                <!-- Next Button -->
                                <?php if ($page < $total_pages): ?>
                                    <a class="page-btn" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">
                                        &raquo;
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-box">
                    <h2>Need Help Finding the Right Caregiver?</h2>
                    <p>
                        Our verified caregivers are carefully selected to provide safe, compassionate, 
                        and professional care for your loved ones. Register today and start booking 
                        trusted caregivers across Sri Lanka.
                    </p>
                    <a href="register.php" class="btn-register-now">Register Now</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container footer-container">
            <div class="footer-logo">SafeHands</div>

            <div class="footer-links">
                <a href="index.php">Home</a>
                <a href="find-caregivers.php">Caregivers</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
                <a href="privacy-policy.php">Privacy Policy</a>
            </div>

            <p>&copy; <?php echo date("Y"); ?> SafeHands Caregiver Service Management System. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="assets/js/find-caregivers.js"></script>
</body>
</html>