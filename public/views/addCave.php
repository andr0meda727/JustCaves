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
    <?php include 'public/views/partials/navbar.php'; ?>

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
