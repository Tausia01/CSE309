
<header class="navbar">
    <div class="logo">
        <img src="./iub_logo.png" alt="University Logo" width="120">
    </div>
    <nav class="nav-links">
        <a href="book_room.php">Book a Room</a>
        <a href="my_bookings.php">My Bookings</a>
        
        <!-- Account Dropdown Menu -->
        <div class="dropdown">
            <a href="#" class="account-link">Account</a>
            <div class="dropdown-content">
                <a href="faculty_account.php">Faculty Information</a>
                <a href="index.php">Sign Out</a>
            </div>
        </div>
    </nav>
</header>
<style>
    /* Ensure the navbar spans the full width */
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .navbar {
        width: 100vw; /* Full viewport width */
        box-sizing: border-box; /* Include padding and borders in width calculation */
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: rgb(56, 154, 215);
        color: white;
    }

    .navbar .logo img {
        height: 40px;
    }

    .navbar .nav-links {
        display: flex;
        gap: 20px;
        position: relative;
    }

    .navbar .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
    }

    .navbar .nav-links a:hover {
        text-decoration: underline;
    }

    /* Room Management dropdown */
    .dropdown {
        position: relative;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: rgb(56, 154, 215);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        border-radius: 4px;
        overflow: hidden;
    }

    .dropdown-menu a {
        display: block;
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
    }

    .dropdown-menu a:hover {
        background-color: rgb(45, 125, 175);
    }
</style>

