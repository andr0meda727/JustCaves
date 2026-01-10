<?php
    $isLoggedIn = isset($_SESSION['user_id']);
    $isAdmin = isset($_SESSION['user_id']) && $_SESSION['role_id'] == 3;
    $currentPage = $_SERVER['REQUEST_URI'];
?>

<nav class="navbar">
      <div class="nav-container">
        <div class="hamburger-menu" id="hamburger">
          <span></span>
          <span></span>
          <span></span>
        </div>

        <div class="logo-header">
          <svg
            width="35"
            height="28"
            viewBox="0 0 40 33"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M19.76 7.77563L32.3866 28.875H7.13336L19.76 7.77563ZM19.76 0L0 33H39.52L19.76 0Z"
              fill="#17CF17"
            />
          </svg>
          <h1><a href="/caves" class="logo-link">JustCaves</a></h1>
        </div>

        <ul class="nav-links" id="nav-menu">
          <li><a href="/caves">Mapa</a></li>

          <?php if ($isLoggedIn): ?>
          <li><a href="/addCave">Dodaj jaskinię</a></li>

          <?php if (!$isAdmin): ?>
            <li><a href="/profile">Mój profil</a></li>
          <?php endif; ?>

          <?php if ($isAdmin): ?>
            <li><a href="/admin" class="admin-link">Panel Administratora</a></li>
          <?php endif; ?>

          <li class="nav-icons-container">
          <?php if (!$isAdmin): ?>
            <a href="/profile" title="Mój profil">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="25px"
                viewBox="0 -960 960 960"
                width="25px"
                fill="#ffffff"
              >
                <path
                  d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"
                />
              </svg>
            </a>
          <?php endif; ?>


            <a href="/logout" title="Wyloguj się" class="logout-icon">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="25px"
                viewBox="0 -960 960 960"
                width="25px"
                fill="#ffffff"
              >
                <path
                  d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"
                />
              </svg>
            </a>
          </li>

          <?php else: ?>
            <?php if ($currentPage === '/login'): ?>
                <li><a href="/register" class="login-highlight">Zarejestruj się</a></li>
            <?php else: ?>
                <li><a href="/login" class="login-highlight">Zaloguj się</a></li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
      </div>
    </nav>  