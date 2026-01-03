<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JustCaves - Dodaj nową jaskinię</title>

    <link rel="stylesheet" href="public/styles/main.css" />
    <link rel="stylesheet" href="public/styles/addCave.css" />
    <link rel="stylesheet" href="public/styles/navbar.css" />
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="public/scripts/locationPicker.js"></script>
    <script src="public/scripts/fileDrag.js"></script>
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
          <li><a href="/profile">Mój profil</a></li>
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
          <li><a href="/login" class="login-highlight">Zaloguj się</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>

    <main class="container">
      <h1>Dodaj nową jaskinię</h1>

      <form
        class="cave-form"
        action="/addCave"
        method="POST"
        enctype="multipart/form-data"
      >
        <div class="form-grid">
          <div class="left-col">
            <div class="input-group">
              <label>Nazwa jaskini</label>
              <input
                type="text"
                name="name"
                placeholder="Wprowadź nazwę jaskini"
                required
              />
            </div>

            <div class="input-group">
              <label>Opis</label>
              <textarea
                name="description"
                placeholder="Dodaj opis jaskini"
                required
              ></textarea>
            </div>

            <div class="input-group">
              <label>Mapa jaskini (opcjonalnie)</label>
              <input
                type="file"
                id="file-input"
                name="map_image"
                accept="image/png, image/jpeg"
                style="display: none"
              />

              <div class="upload-box" id="drop-zone">
                <i class="fas fa-file-upload"></i>
                <p id="file-name">
                  <strong>Kliknij, aby przesłać</strong> lub przeciągnij i upuść
                </p>
                <span>PNG, JPG (MAX. 10MB)</span>
              </div>
            </div>
            <div class="input-group">
              <label>Region</label>
              <select name="region_id" class="region-select" required>
                <option value="" disabled selected>Wybierz region...</option>
                <?php if(isset($regions)): ?>
                <?php foreach($regions as $region): ?>
                <option value="<?= $region['id']; ?>">
                  <?= $region['name']; ?>
                </option>
                <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>

          <div class="right-col">
            <div class="input-group">
              <label>Lokalizacja</label>
              <p class="sub-label">
                Kliknij na mapie lub przeciągnij pinezkę, aby określić
                lokalizację.
              </p>
              <div id="map"></div>
            </div>

            <div
              class="coords-grid"
              style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem"
            >
              <div class="input-group">
                <label>Szerokość geograficzna</label>
                <input
                  type="text"
                  id="lat"
                  name="latitude"
                  value="50.0614"
                  readonly
                />
              </div>
              <div class="input-group">
                <label>Długość geograficzna</label>
                <input
                  type="text"
                  id="lng"
                  name="longitude"
                  value="19.9366"
                  readonly
                />
              </div>
            </div>
          </div>
        </div>

        <div
          class="form-actions"
          style="
            display: flex;
            justify-content: flex-end;
            gap: 2rem;
            margin-top: 2rem;
          "
        >
          <button type="button" class="btn-cancel">Anuluj</button>
          <button type="submit" class="btn-submit">+ Dodaj jaskinię</button>
        </div>
      </form>
    </main>
  </body>
</html>
