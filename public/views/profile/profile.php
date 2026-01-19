<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mój Profil - JustCaves</title>
    <link rel="stylesheet" href="/public/styles/main.css">
    <link rel="stylesheet" href="/public/styles/admin/admin.css"> 
    <link rel="stylesheet" href="/public/styles/profile/profile.css">   
    <link rel="stylesheet" href="/public/styles/partials/navbar.css"> 
    <script src="/public/scripts/navbar.js" defer></script>
</head>
<body class="admin-body">
    <?php include 'public/views/partials/navbar.php'; ?>

    <div class="main-content profile-container">
        <header class="profile-header">
            <div class="user-info">
                <h2>Witaj, <?= htmlspecialchars($user->getUsername()) ?>!</h2>
                <p>Email: <?= htmlspecialchars($user->getEmail()) ?></p>
                <p>Twoje statystyki: <strong><?= count($visitedCaves) ?></strong> odwiedzonych jaskiń</p>
            </div>
        </header>

        <section class="visited-section">
            <h3>Twoje odwiedzone jaskinie</h3>
            
            <?php if (empty($visitedCaves)): ?>
                <div class="empty-state">
                    <p>Nie oznaczyłeś jeszcze żadnej jaskini jako odwiedzoną.</p>
                    <a href="/caves">Odkryj jaskinie na mapie</a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nazwa Jaskini</th>
                                <th>Region</th>
                                <th>Twoja Ocena</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($visitedCaves as $item): ?>
                                <?php $cave = $item['details']; ?>
                                <tr>
                                    <td data-label="Nazwa" class="cave-name"><?= htmlspecialchars($cave->getName()) ?></td>
                                    <td data-label="Region"><?= htmlspecialchars($cave->getRegionName()) ?></td>
                                    <td data-label="Ocena">
                                        <span class="rating-display">
                                            <?= $item['rating'] ? $item['rating'] . '/10' : 'Brak oceny' ?>
                                        </span>
                                    </td>
                                    <td data-label="Akcje" class="actions-cell">
                                        <div class="actions-wrapper">
                                            <a href="/cave/<?= $cave->getId() ?>" class="action-btn" title="Zobacz szczegóły">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                                                    <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>