<?php
session_start();
?>

<?php
include "koneksi.php"; // Pastikan file koneksi disertakan
if (isset($_SESSION['Username'])) {
    $username = $_SESSION['Username'];
    $query = "SELECT profile_pic FROM user WHERE Username = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $profilePic = $user['profile_pic'] ?? 'default.png'; // Jika profile_pic kosong, gunakan default.png
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GalleryPhoto</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    

    <style>
body {
    background: url('images/hero_2.jpg') no-repeat center center fixed;
    background-size: cover;  
    font-family: 'Poppins', sans-serif;
    color: #e6edf3; /* Light text color */
    margin: 0;
    padding: 0;
}
.btn {
    border-radius: 30px;
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .2rem;
    padding: 10px 20px;
    text-decoration: none;
}
.btn-primary {
    background-color: rgb(24, 147, 36);
}


.navbar {
    background-color:rgba(22, 27, 34, 0.6);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 20px 0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.navbar-brand img {
    max-height: 50px;
}

.navbar-nav {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 20px;
}

.nav-link {
    font-weight: 600;
    font-size: 18px;
    color:rgb(49, 117, 195) !important;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color:rgb(213, 223, 236) !important;
}

.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
    background-color: #161b22;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.portfolio-item {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.portfolio-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.portfolio-item img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.3s ease;
}

.portfolio-item img:hover {
    transform: scale(1.1);
}

.portfolio-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 15px;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3));
    color: white;
    text-align: center;
    visibility: hidden;
    opacity: 0;
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.portfolio-item:hover .portfolio-info {
    visibility: visible;
    opacity: 1;
}

.search-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 30px 0;
    padding: 15px;
    background-color:rgba(13, 17, 23, 0.6);
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.album-dropdown select {
    border: 2px solid #30363d;
    border-radius: 5px;
    padding-right: 10px;
    padding: 6px;
    font-size: 16px;
    background-color: #161b22;
    color: #e6edf3;
    transition: border-color 0.3s ease;
}

.album-dropdown select:focus {
    border-color: #58a6ff;
    outline: none;
}

.form-inline input {
    border: 2px solid #30363d;
    border-radius: 5px;
    padding: 10px;
    font-size: 16px;
    width: 300px;
    background-color: #161b22;
    color: #e6edf3;
    transition: border-color 0.3s ease;
}

.form-inline input:focus {
    border-color: #58a6ff;
    outline: none;
}

.form-inline button {
    background-color: #238636;
    border: none;
    color: white;
    font-weight: 600;
    border-radius: 5px;
    padding: 10px 20px;
    transition: background-color 0.3s ease;
}

.form-inline button:hover {
    background-color: #2ea043;
}

@media (max-width: 767px) {
    .portfolio-grid {
        grid-template-columns: 1fr;
    }

    .search-container {
        flex-direction: column;
        gap: 15px;
    }

    .form-inline input {
        width: 100%;
    }
}


    </style>
</head>

<body>

<?php
    include "koneksi.php"; // Pastikan file koneksi disertakan
    if (isset($_SESSION['Username'])) {
        $username = $_SESSION['Username'];
        $query = "SELECT profile_pic FROM user WHERE Username = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $profilePic = $user['profile_pic'] ?? 'default.png'; // Jika profile_pic kosong, gunakan default.png
        $stmt->close();
    }
    ?>
    <?php
    if (isset($_GET['role']) && $_GET['role'] === 'guest') {
        $_SESSION['Role'] = 'guest';
    }
    ?>

     <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" alt="Logo">ShutterKeeper
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="portfolio.php">Portfolio</a>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['Username'])): ?>
                            <a class="nav-link" href="upload-photo.php">Upload</a>
                        <?php else: ?>
                            <a class="nav-link" href="portfolio.php" onclick="alert('Silakan login terlebih dahulu!')">Upload</a>
                        <?php endif; ?>
                    </li>
                    <li>
                    <?php if (isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin'): ?>
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    <?php endif; ?>
                    </li>                    
                </ul>

                <?php if (isset($_SESSION['Username'])) { ?>
                    <a class="nav-link dropdown-toggle text-white" href="profile.php" id="userProfileDropdown">
                        <img src="images/<?php echo htmlspecialchars($profilePic); ?>" class="rounded-circle" width="35" height="35" alt="Profile">
                    </a>
                <?php } else { ?>
                    <a href="index.php" class="btn btn-outline-primary ml-3">Sign In</a>
                    <a href="register.php" class="btn btn-primary ml-3">Sign Up</a>
                <?php } ?>
            </div>
        </div>
    </nav>

    <!-- Search & Album Section -->
    <div class="container">
        <div class="search-container">
            <!-- Dropdown Album -->
            <div class="album-dropdown">
                <select class="form-control" onchange="location = this.value;">
                    <option value="portfolio.php">Semua Album</option>
                    <?php
                    // Koneksi ke database
                    include "koneksi.php";

                    // Query untuk mengambil data album
                    $query = "SELECT AlbumID, NamaAlbum FROM album";
                    $result = mysqli_query($con, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = (isset($_GET['kategori']) && $_GET['kategori'] == $row['NamaAlbum']) ? "selected" : "";
                        echo '<option value="portfolio.php?kategori=' . urlencode($row['NamaAlbum']) . '" ' . $selected . '>' . htmlspecialchars($row['NamaAlbum']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Search Form -->
            <form class="form-inline" method="GET" action="portfolio.php">
                <input class="form-control mr-2" type="search" name="search" placeholder="Cari Foto" aria-label="Search">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>

        <!-- Portfolio Grid -->
        <div class="portfolio-grid">
            <?php
            include "koneksi.php";

            $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            $query = "SELECT foto.FotoID, foto.LokasiFoto, foto.JudulFoto, album.NamaAlbum 
                      FROM foto 
                      LEFT JOIN album ON foto.AlbumID = album.AlbumID
                      WHERE 1 ";

            if ($kategori) {
                $query .= " AND album.NamaAlbum = '$kategori' ";
            }

            if ($search) {
                $query .= " AND (foto.JudulFoto LIKE '%$search%') ";
            }

            $result = mysqli_query($con, $query);

            while ($row = mysqli_fetch_assoc($result)) { ?>

                <div class="portfolio-item">
                    <a href="detail.php?id=<?php echo $row['FotoID']; ?>">
                        <img src="terupload/<?php echo $row['LokasiFoto']; ?>" alt="Foto" class="img-fluid">
                        <div class="portfolio-info">
                            <h5><?php echo $row['JudulFoto']; ?></h5>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
