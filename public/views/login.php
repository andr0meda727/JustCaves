<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JustCaves - zaloguj się</title>
    <link rel="stylesheet" type="text/css" href="public/styles/main.css" />
    <link rel="stylesheet" href="public/styles/navbar.css" >
    <link rel="stylesheet" type="text/css" href="public/styles/login.css" />
    <script src="public/scripts/navbar.js" defer></script>
  </head>
  <body>
    <?php
      if (session_status() === PHP_SESSION_NONE) {
        session_start();
      }
      $isLoggedIn = isset($_SESSION['user_id']);
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
          <li><a href="#">Mój profil</a></li>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="28px"
            viewBox="0 -960 960 960"
            width="28px"
            fill="#e3e3e3"
          >
            <path
              d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"
            />
          </svg>

          <?php else: ?>
            <li><a href="/register">Zarejestruj się</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
    <div class="container">
      <form class="form" action="\login" method="POST">
        <div class="header">
          <h1 id="title">Witaj w JustCaves</h1>
          <h3>Zaloguj się, aby odkrywać dziuuury :)</h3>
        </div>

        <div class="username-input">
          <label for="username">Nazwa użytkownika</label>
          <div class="input-wrapper">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="#e3e3e3"
              class="input-icon"
            >
              <path
                d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"
              />
            </svg>
            <input name="username" placeholder="Wprowadź swoją nazwę" />
          </div>
        </div>

        <div class="password-input">
          <label for="password">Hasło</label>
          <div class="input-wrapper">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="#e3e3e3"
              class="input-icon"
            >
              <path
                d="M240-640h360v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85h-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640Zm0 480h480v-400H240v400Zm240-120q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM240-160v-400 400Z"
              />
            </svg>
            <input
              name="password"
              type="password"
              placeholder="Wprowadź swoje hasło"
            />
          </div>
        </div>

        <div class="messages">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
        </div>

        <button class="log-in-btn" type="submit">Zaloguj się</button>

        <div class="register">
          <p>Nie masz konta?</p>
          <a href="\register">Zarejestruj się</a>
        </div>
      </form>
    </div>
  </body>
</html>
