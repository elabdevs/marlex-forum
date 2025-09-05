<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Bakımdayız | Forum</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="/assets/css/css_004/styles.css">
    <link rel="icon" type="image/svg+xml" href="/assets/favicon.svg">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f3f0ff 0%, #ede9fe 100%);
            overflow: hidden;
        }
        .maintenance-glass {
            max-width: 420px;
            margin: 7vh auto 0 auto;
            padding: 2.5rem 2rem 2rem 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 8px 40px 0 rgba(139,92,246,0.13);
            position: relative;
            z-index: 10;
        }
        .maintenance-icon {
            width: 4.5rem;
            height: 4.5rem;
            margin: 0 auto 1.5rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(139,92,246,0.12);
            border-radius: 1.25rem;
            color: var(--secondary);
            font-size: 2.5rem;
            box-shadow: 0 2px 12px 0 rgba(139,92,246,0.08);
        }
        .maintenance-title {
            text-align: center;
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--foreground);
            margin-bottom: 0.75rem;
            letter-spacing: -1px;
        }
        .maintenance-desc {
            text-align: center;
            color: var(--muted-foreground);
            font-size: 1.08rem;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }
        .maintenance-countdown {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .countdown-segment {
            background: rgba(139,92,246,0.08);
            border-radius: 0.75rem;
            padding: 0.7rem 1.1rem;
            text-align: center;
            min-width: 60px;
        }
        .countdown-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 0.1rem;
            display: block;
        }
        .countdown-label {
            font-size: 0.85rem;
            color: var(--muted-foreground);
            letter-spacing: 0.04em;
        }
        .maintenance-progress {
            width: 100%;
            height: 8px;
            background: var(--muted);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            position: relative;
        }
        .maintenance-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #a78bfa 0%, #8b5cf6 100%);
            width: 0%;
            border-radius: 4px;
            transition: width 0.7s cubic-bezier(.4,0,.2,1);
        }
        .maintenance-contact {
            text-align: center;
            margin-bottom: 1.2rem;
        }
        .maintenance-contact a {
            color: var(--secondary);
            text-decoration: none;
            margin: 0 0.5rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        .maintenance-contact a:hover {
            color: #7c3aed;
            text-decoration: underline;
        }
        .maintenance-socials {
            display: flex;
            justify-content: center;
            gap: 1.2rem;
            margin-bottom: 0.5rem;
        }
        .maintenance-socials a {
            color: var(--secondary);
            background: rgba(139,92,246,0.08);
            border-radius: 50%;
            width: 2.3rem;
            height: 2.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            transition: background 0.2s, color 0.2s;
        }
        .maintenance-socials a:hover {
            background: var(--secondary);
            color: #fff;
        }
        .maintenance-footer {
            text-align: center;
            font-size: 0.95rem;
            color: var(--muted-foreground);
            margin-top: 1.5rem;
        }
        /* Liquid orbs */
        .maintenance-orb {
            position: absolute;
            border-radius: 50%;
            z-index: 1;
            pointer-events: none;
            filter: blur(2px);
            opacity: 0.7;
            animation: liquid-flow 10s infinite;
        }
        .maintenance-orb1 {
            width: 320px; height: 320px;
            top: -80px; left: -90px;
            background: radial-gradient(circle, #a78bfa 0%, #ede9fe 100%);
            animation-delay: 0s;
        }
        .maintenance-orb2 {
            width: 180px; height: 180px;
            bottom: -60px; right: -60px;
            background: radial-gradient(circle, #8b5cf6 0%, #f3f0ff 100%);
            animation-delay: 3s;
        }
        .maintenance-orb3 {
            width: 120px; height: 120px;
            top: 60%; left: 80%;
            background: radial-gradient(circle, #ede9fe 0%, #a78bfa 100%);
            animation-delay: 5s;
        }
        @media (max-width: 600px) {
            .maintenance-glass { padding: 1.2rem 0.5rem; }
            .maintenance-title { font-size: 1.3rem; }
            .maintenance-orb1, .maintenance-orb2, .maintenance-orb3 { display: none; }
        }
    </style>
</head>
<body>
    <!-- Liquid Glass Orbs -->
    <div class="maintenance-orb maintenance-orb1"></div>
    <div class="maintenance-orb maintenance-orb2"></div>
    <div class="maintenance-orb maintenance-orb3"></div>

    <main>
        <section class="liquid-glass maintenance-glass">
            <div class="maintenance-icon">
                <!-- SVG wrench/gear icon -->
                <svg width="44" height="44" fill="none" viewBox="0 0 44 44">
                  <circle cx="22" cy="22" r="21" fill="#ede9fe" stroke="#a78bfa" stroke-width="2"/>
                  <path d="M28.5 15.5l-2.1 2.1a6 6 0 01-8.5 8.5l-2.1 2.1a1 1 0 001.4 1.4l2.1-2.1a6 6 0 018.5-8.5l2.1-2.1a1 1 0 00-1.4-1.4z" fill="#8b5cf6"/>
                  <circle cx="22" cy="22" r="3.5" fill="#fff" stroke="#8b5cf6" stroke-width="2"/>
                </svg>
            </div>
            <h1 class="maintenance-title">Bakımdayız</h1>
            <div class="maintenance-desc">
                Forumumuz şu anda <strong>planlı bakım</strong> nedeniyle geçici olarak kapalıdır.<br>
                En iyi deneyimi sunmak için sistemlerimizi güncelliyoruz.<br>
                Lütfen sabırlı olun, kısa süre sonra tekrar buradayız!
            </div>
            <!-- İletişim -->
            <div class="maintenance-contact">
                Sorularınız için <a href="mailto:<?= $siteVariables['email'] ?>"><?= $siteVariables['email'] ?></a> adresine yazabilirsiniz.
            </div>
            <!-- Sosyal Medya -->
            <div class="maintenance-socials">
                <a href="https://twitter.com/<?= $siteVariables['twitter'] ?>" target="_blank" aria-label="Twitter">
                    <!-- Twitter SVG -->
                    <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24"><path d="M22 5.924c-.793.352-1.646.59-2.54.698a4.48 4.48 0 001.964-2.475 8.93 8.93 0 01-2.828 1.082A4.48 4.48 0 0016.616 4c-2.485 0-4.5 2.014-4.5 4.5 0 .353.04.697.117 1.026C8.08 9.37 5.1 7.884 2.98 5.67a4.48 4.48 0 00-.608 2.263c0 1.563.796 2.942 2.008 3.75a4.48 4.48 0 01-2.04-.564v.057c0 2.183 1.553 4.004 3.617 4.42a4.52 4.52 0 01-2.034.077c.573 1.788 2.236 3.09 4.205 3.126A9.01 9.01 0 012 19.07a12.72 12.72 0 006.88 2.017c8.26 0 12.785-6.84 12.785-12.785 0-.195-.004-.39-.013-.583A9.18 9.18 0 0024 4.59a8.97 8.97 0 01-2.6.713z"/></svg>
                </a>
                <a href="https://instagram.com/<?= $siteVariables['instagram'] ?>" target="_blank" aria-label="Instagram">
                    <!-- Instagram SVG -->
                    <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.34 3.608 1.314.974.974 1.252 2.242 1.314 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.34 2.633-1.314 3.608-.974.974-2.242 1.252-3.608 1.314-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.34-3.608-1.314-.974-.974-1.252-2.242-1.314-3.608C2.175 15.647 2.163 15.267 2.163 12s.012-3.584.07-4.85c.062-1.366.34-2.633 1.314-3.608C4.521 2.573 5.789 2.295 7.155 2.233 8.421 2.175 8.801 2.163 12 2.163zm0-2.163C8.741 0 8.332.013 7.052.072 5.771.13 4.659.37 3.678 1.352 2.697 2.334 2.457 3.446 2.399 4.728 2.34 6.008 2.327 6.417 2.327 12c0 5.583.013 5.992.072 7.272.058 1.282.298 2.394 1.279 3.376.981.981 2.093 1.221 3.375 1.279C8.332 23.987 8.741 24 12 24s3.668-.013 4.948-.072c1.282-.058 2.394-.298 3.375-1.279.981-.982 1.221-2.094 1.279-3.376.059-1.28.072-1.689.072-7.272 0-5.583-.013-5.992-.072-7.272-.058-1.282-.298-2.394-1.279-3.376-.981-.982-2.093-1.222-3.375-1.279C15.668.013 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/></svg>
                </a>
                <a href="mailto:<?= $siteVariables['email'] ?>" aria-label="Mail">
                    <!-- Mail SVG -->
                    <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 2v.01L12 13 4 6.01V6h16zm-16 12V8.99l7.99 6.99c.39.34.99.34 1.38 0L20 8.99V18H4z"/></svg>
                </a>
            </div>
            <div class="maintenance-footer">
                &copy; <?= date('Y') ?> <?= $siteTitle ?>. Tüm hakları saklıdır.
            </div>
        </section>
    </main>
</body>
</html>