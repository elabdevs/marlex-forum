<?php 

use App\Models\Crypto;

$schema = [
    "@context" => "https://schema.org",
    "@type" => "DiscussionForumPosting",
    "headline" => $metaTitle,
    "datePublished" => $topic['created_at'],
    "dateModified" => $topic['updated_at'],
    "author" => ["@type" => "Person", "name" => $topic['username']],
    "publisher" => [
        "@type" => "Organization",
        "name" => $siteTitle,
        "logo" => ["@type" => "ImageObject", "url" => "/assets/favicon/web-app-manifest-512x512.png"]
    ],
    "articleBody" => nl2br(htmlspecialchars(Crypto::decrypt($topic['content'])))
];
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($metaTitle) ?> - <?= htmlspecialchars($siteTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($metaData['canonical_url']) . "/" . htmlspecialchars($topic['slug']) ?>">
    <link rel="icon" href="/assets/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/css.php?file=styles.css" id="mainCSS" media="all">
    <link rel="stylesheet" href="/assets/css/css.php?file=lite.min.css" id="liteCSS" media="none">
    <meta name="theme-color" content="#111111">
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle) ?> - <?= htmlspecialchars($siteTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($metaData['canonical_url']) . "/" . htmlspecialchars($topic['slug']) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($metaData["og_image"]) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($metaTitle) ?> - <?= htmlspecialchars($siteTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($metaData['og_image']) ?>">

   <script type="application/ld+json">
<?= json_encode([
    "@context" => "https://schema.org",
    "@type" => "DiscussionForumPosting",
    "headline" => $metaTitle,
    "datePublished" => $topic['created_at'],
    "dateModified" => $topic['updated_at'],
    "author" => ["@type" => "Person", "name" => $topic['username']],
    "publisher" => [
        "@type" => "Organization",
        "name" => $siteTitle,
        "logo" => ["@type" => "ImageObject", "url" => "/assets/favicon/web-app-manifest-512x512.png"]
    ],
    "articleBody" => Crypto::decrypt($topic['content'])
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>    
</script>

</head>
