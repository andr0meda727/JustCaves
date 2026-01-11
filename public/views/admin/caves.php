<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora - JustCaves</title>
    <link rel="stylesheet" href="/public/styles/main.css">
    <link rel="stylesheet" href="/public/styles/navbar.css">
    <link rel="stylesheet" href="/public/styles/admin/admin.css">
</head>
<body class="admin-body">
    <?php include 'public/views/partials/navbar.php'; ?>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-top">
                <div class="admin-profile">
                    <div class="avatar">
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
                    </div>
                    <div class="admin-info">
                        <span class="admin-name">Admin</span>
                        <span class="admin-role">Panel Administratora</span>
                    </div>
                </div>

                <nav class="sidebar-nav">
                    <ul>
                        <li class="<?= $currentPage === '/admin/caves' ? 'active' : '' ?>">
                            <a href="/admin/caves">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F3F3F3"><path d="M480-120q-151 0-255.5-46.5T120-280v-400q0-66 105.5-113T480-840q149 0 254.5 47T840-680v400q0 67-104.5 113.5T480-120Zm0-479q89 0 179-25.5T760-679q-11-29-100.5-55T480-760q-91 0-178.5 25.5T200-679q14 30 101.5 55T480-599Zm0 199q42 0 81-4t74.5-11.5q35.5-7.5 67-18.5t57.5-25v-120q-26 14-57.5 25t-67 18.5Q600-528 561-524t-81 4q-42 0-82-4t-75.5-11.5Q287-543 256-554t-56-25v120q25 14 56 25t66.5 18.5Q358-408 398-404t82 4Zm0 200q46 0 93.5-7t87.5-18.5q40-11.5 67-26t32-29.5v-98q-26 14-57.5 25t-67 18.5Q600-328 561-324t-81 4q-42 0-82-4t-75.5-11.5Q287-343 256-354t-56-25v99q5 15 31.5 29t66.5 25.5q40 11.5 88 18.5t94 7Z"/></svg>
                                <span>Zarządzanie Jaskiniami</span>
                            </a>
                        </li>

                        <li class="<?= $currentPage === '/admin/users' ? 'active' : '' ?>">
                            <a href="/admin/users">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
                                    <path d="M411-480q-28 0-46-21t-13-49l12-72q8-43 40.5-70.5T480-720q44 0 76.5 27.5T597-622l12 72q5 28-13 49t-46 21H411Zm24-80h91l-8-49q-2-14-13-22.5t-25-8.5q-14 0-24.5 8.5T443-609l-8 49ZM124-441q-23 1-39.5-9T63-481q-2-9-1-18t5-17q0 1-1-4-2-2-10-24-2-12 3-23t13-19l2-2q2-19 15.5-32t33.5-13q3 0 19 4l3-1q5-5 13-7.5t17-2.5q11 0 19.5 3.5T208-626q1 0 1.5.5t1.5.5q14 1 24.5 8.5T251-596q2 7 1.5 13.5T250-570q0 1 1 4 7 7 11 15.5t4 17.5q0 4-6 21-1 2 0 4l2 16q0 21-17.5 36T202-441h-78Zm676 1q-33 0-56.5-23.5T720-520q0-12 3.5-22.5T733-563l-28-25q-10-8-3.5-20t18.5-12h80q33 0 56.5 23.5T880-540v20q0 33-23.5 56.5T800-440ZM0-240v-63q0-44 44.5-70.5T160-400q13 0 25 .5t23 2.5q-14 20-21 43t-7 49v65H0Zm240 0v-65q0-65 66.5-105T480-450q108 0 174 40t66 105v65H240Zm560-160q72 0 116 26.5t44 70.5v63H780v-65q0-26-6.5-49T754-397q11-2 22.5-2.5t23.5-.5Zm-320 30q-57 0-102 15t-53 35h311q-9-20-53.5-35T480-370Zm0 50Zm1-280Z"/>
                                </svg>
                                <span>Użytkownicy</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="sidebar-bottom">
                <form action="/logout" method="GET">
                    <button type="submit" class="btn-logout-big">Wyloguj</button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <header class="content-header">
                <div class="header-text">
                    <h1>Zarządzanie Jaskiniami</h1>
                    <p>Przeglądaj wpisy jaskiń w systemie.</p>
                </div>
            </header>

            <div class="filters-bar">
                <div class="search-box">
                    <svg class="search-icon" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                    <input type="text" placeholder="Szukaj po nazwie, ID...">
                </div>
                <div class="select-group">
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

                    <select name="status_id" class="status-select" required>
                        <option value="" disabled selected>Wybierz status...</option>
                        <?php if(isset($statuses)): ?>
                        <?php foreach($statuses as $status): ?>
                        <option value="<?= $status['name']; ?>">
                        <?= $status['name']; ?>
                        </option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nazwa Jaskini</th>
                            <th>Region</th>
                            <th>Status</th>
                            <th>Data Dodania</th>
                            <th>Dodał(a)</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($caves as $cave): ?>
                            <?php 
                                $statusClass = strtolower($cave->getStatus()); // pending, approved, rejected
                                $statusLabel = [
                                    'pending' => 'Oczekująca',
                                    'approved' => 'Zatwierdzona',
                                    'rejected' => 'Odrzucona'
                                ][$statusClass] ?? $statusClass;
                            ?>
                            <tr>
                                <td><?= $cave->getId() ?></td>
                                <td class="cave-name"><?= htmlspecialchars($cave->getName()) ?></td>
                                <td><?= htmlspecialchars($cave->getRegionName() ?? 'Nieznany') ?></td>
                                <td><span class="status-badge status-<?= $statusClass ?>"><?= $statusLabel ?></span></td>
                                <td><?= date('Y-m-d') ?></td>
                                <td class="author-name"><?= htmlspecialchars("abc" ?? 'Anonim') ?></td>
                                <td class="actions-cell">
                                    <a href="/cave/<?= $cave->getId() ?>" class="action-btn"><svg viewBox="0 0 24 24" width="18" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg></a>
                                    <form action="/admin-delete/<?= $cave->getId() ?>" method="POST" onsubmit="return confirm('Usunąć?')">
                                        <button class="action-btn delete"><svg viewBox="0 0 24 24" width="18" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <footer class="table-footer">
                <span>
                    Strona <strong><?= $pageNumber ?></strong> z <strong><?= $totalPages ?: 1 ?></strong> 
                    (Łącznie wyników: <?= $totalCaves ?>)
                </span>
                
                <div class="pagination">
                    <?php if ($pageNumber > 1): ?>
                        <a href="/admin/caves?page=<?= $pageNumber - 1 ?>" class="page-btn">Poprzednia</a>
                    <?php else: ?>
                        <button class="page-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Poprzednia</button>
                    <?php endif; ?>

                    <?php if ($pageNumber < $totalPages): ?>
                        <a href="/admin/caves?page=<?= $pageNumber + 1 ?>" class="page-btn">Następna</a>
                    <?php else: ?>
                        <button class="page-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Następna</button>
                    <?php endif; ?>
                </div>
            </footer>
        </main>
    </div>

</body>
</html>