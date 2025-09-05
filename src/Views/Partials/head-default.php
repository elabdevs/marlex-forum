<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($metaData['title']) ?> | <?= htmlspecialchars($siteTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaData['description']) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($metaData['canonical_url']) ?>">
    <link rel="icon" href="/assets/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/css.php?file=styles.css" id="mainCSS" media="all">
    <link rel="stylesheet" href="/assets/css/css.php?file=lite.min.css" id="liteCSS" media="none">

    <meta name="theme-color" content="#fff">
    <meta property="og:title" content="<?= htmlspecialchars($metaData['title']) ?> | <?= htmlspecialchars($siteTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaData['description']) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($metaData['canonical_url']) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($metaData['og_image']) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($metaData['title']) ?> | <?= htmlspecialchars($siteTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaData['description']) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($metaData['og_image']) ?>">
</head>


