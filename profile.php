<?php
session_start();

// Redirect jika user belum login
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit;
}

// Koneksi ke database
include "koneksi.php";

// Ambil data user
$username = $_SESSION['Username'];
$queryUser = "SELECT * FROM user WHERE Username = '$username'";
$resultUser = mysqli_query($con, $queryUser);
$userData = mysqli_fetch_assoc($resultUser);

// Ambil data foto user
$queryPhotos = "SELECT * FROM foto WHERE UserID = " . $userData['UserID'];
$resultPhotos = mysqli_query($con, $queryPhotos);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- CSS and Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style-profile.css">
</head>
<style>
    
    body {
    background: url('images/hero_2.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    color:rgb(0, 0, 0); /* Warna teks terang */
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
.card {
    background-color:#21262d;
    color:rgb(255, 255, 255);
    border-radius: 20px;
}
.modal-content {
    background-color: #161b22;
    color: #e6edf3;
    border-radius: 15px;
}

.modal-header {
    border-bottom: 1px solid #30363d;
}

.modal-footer {
    border-top: 1px solid #30363d;
}

/* Gallery Card Styling */
.gallery-card {
        background-color: #21262d;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        transition: transform 0.3s ease-in-out;
    }

    .gallery-card:hover {
        transform: translateY(-5px);
    }

    .gallery-card img {
        height: 200px;
        object-fit: cover;
    }

    .gallery-card .card-body {
        padding: 15px;
        background-color: #292d35;
    }

    .gallery-card h5 {
        font-size: 18px;
        color: #58a6ff;
    }

    .gallery-card .btn {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .gallery-card .btn-warning {
        background-color: #f39c12;
        border: none;
    }

    .gallery-card .btn-warning:hover {
        background-color: #e67e22;
    }

    .gallery-card .btn-danger {
        background-color: #e74c3c;
        border: none;
    }

    .gallery-card .btn-danger:hover {
        background-color: #c0392b;
    }

    @media (max-width: 768px) {
        .gallery-card {
            margin-bottom: 20px;
        }

        .gallery-card img {
            height: 150px;
        }
    }
</style>
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
                            <a class="nav-link" href="home.php" onclick="alert('Silakan login terlebih dahulu!')">Upload</a>
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
    <div class="container mt-5">
        <div class="row">
            <!-- Profile Section -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="images/<?php echo htmlspecialchars($userData['profile_pic'] ?? 'default.png'); ?>" 
                             class="rounded-circle mb-3" width="150" height="150" alt="Profile Picture">
                        <h4><?php echo htmlspecialchars($userData['NamaLengkap']); ?></h4>
                        <p>@<?php echo htmlspecialchars($userData['Username']); ?></p>

                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#editProfileModal">Edit Profile</button>
                        <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#logoutModal">Logout</button>
                    </div>
                </div>
            </div>
             <!-- Gallery Section -->
             <div class="col-md-8">
                <div class="row">
                    <?php while ($photo = mysqli_fetch_assoc($resultPhotos)) { ?>
                        <div class="col-md-4 mb-4">
                            <div class="gallery-card card">
                                <img src="terupload/<?php echo htmlspecialchars($photo['LokasiFoto']); ?>" class="card-img-top" alt="Foto">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($photo['JudulFoto']); ?></h5>
                                    <div class="d-flex justify-content-between">
                                        <a href="edit.php?id=<?php echo $photo['FotoID']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $photo['FotoID']; ?>">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Konfirmasi Hapus -->
                        <div class="modal fade" id="deleteModal<?php echo $photo['FotoID']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $photo['FotoID']; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel<?php echo $photo['FotoID']; ?>">Konfirmasi Hapus</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus foto ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <a href="delete-photo.php?id=<?php echo $photo['FotoID']; ?>" class="btn btn-danger">Hapus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


            <!-- Gallery Section -->
            <div class="col-md-8">
    <div class="row">
        <?php while ($photo = mysqli_fetch_assoc($resultPhotos)) { ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="terupload/<?php echo htmlspecialchars($photo['LokasiFoto']); ?>" class="card-img-top" alt="Foto">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($photo['JudulFoto']); ?></h5>
                        <div class="d-flex justify-content-between">
                            <a href="edit-photo.php?id=<?php echo $photo['FotoID']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <!-- Tombol Hapus -->
                            <button class="btn btn-sm btn-danger" data-toggle="modal" 
                                    data-target="#deleteModal<?php echo $photo['FotoID']; ?>">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Konfirmasi Hapus -->
            <div class="modal fade" id="deleteModal<?php echo $photo['FotoID']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $photo['FotoID']; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel<?php echo $photo['FotoID']; ?>">Konfirmasi Hapus</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus foto ini?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <a href="delete-photo.php?id=<?php echo $photo['FotoID']; ?>" class="btn btn-danger">Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>


    <!-- Modal Edit Profile -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="edit-profile.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="">Full Name</label>
                            <input type="text" class="form-control" id="NamaLengkap" name="NamaLengkap" value="<?php echo htmlspecialchars($userData['NamaLengkap']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="profilePic">Profile Picture</label>
                            <input type="file" class="form-control-file" id="profilePic" name="profilePic">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
