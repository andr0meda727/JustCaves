<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JustCaves</title>
    <link rel="stylesheet" type="text/css" href="public/styles/main.css" />
    <link rel="stylesheet" type="text/css" href="public/styles/caves.css" />
    <link rel="stylesheet" href="public/styles/navbar.css" />
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
    <script
      src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      defer
    ></script>
    <script src="public/scripts/map.js" defer></script>
    <script src="public/scripts/navbar.js" defer></script>
  </head>
  <body>
    <?php include 'public/views/partials/navbar.php'; ?>

    <main class="main-container">
      <section class="sidebar">
        <h2 class="section-title">Odkrywaj Jaskinie</h2>

        <button id="toggle-map-btn" class="btn-primary mobile-map-toggle">
          Otwórz mapę
        </button>

        <a href="/addCave" class="btn-primary">Dodaj nową jaskinię</a>

        <div class="search-input-wrapper">
          <input
            type="text"
            id="search-input"
            placeholder="Szukaj jaskini po nazwie..."
          />
        </div>

        <div class="caves-list">
          <?php if(isset($caves)): ?>
          <?php foreach($caves as $cave): ?>
          <div
            class="cave-card"
            data-name="<?= strtolower(htmlspecialchars($cave->getName())) ?>"
            data-lat="<?= $cave->getLatitude() ?>"
            data-lng="<?= $cave->getLongitude() ?>"
            onclick="focusCave(<?= $cave->getLatitude() ?>, <?= $cave->getLongitude() ?>)"
          >
            <div
              class="cave-img-mini"
              style="
                background-image: url('<?= $cave->getMapImagePath() ? '/public/uploads/maps/'.$cave->getMapImagePath() : '/public/uploads/maps/cave_default.png' ?>');
              "
            ></div>
            <div class="cave-info">
              <div>
                <h3><?= $cave->getName() ?></h3>
                <p>
                  <?= mb_strimwidth(htmlspecialchars($cave->getDescription()),
                  0, 100, "...") ?>
                </p>
              </div>

              <a href="/cave/<?= $cave->getId() ?>" class="details-btn"
                >Zobacz więcej</a
              >
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

      <section class="map-section">
        <div id="map"></div>
      </section>
    </main>
  </body>
</html>
