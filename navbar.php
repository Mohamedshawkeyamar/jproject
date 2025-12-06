<?php
  $current = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
  <style>
    /* 🛑 1. إزالة الهوامش الأساسية وحل مشكلة الفوتر 🛑 */
    html, body {
      margin: 0;
      padding: 0;
      width: 100%;
      overflow-x: hidden;
    }
    
    /* 🛑 2. دفع المحتوى للأسفل لحل مشكلة التغطية 🛑 */
    body {
      padding-top: 100px; /* القيمة التقديرية (100px) */
    }
    
    :root {
      --color-bg: #FFF2F2;
      --color-primary: #2D336B;
      --color-primary-soft: #7886C7;
      --color-accent: #A9B5DF;
      --color-text-light: #F9FAFB;
    }

    .navbar-custom {
      background: linear-gradient(120deg, rgba(45, 51, 107, 0.96), rgba(120, 134, 199, 0.96));
      backdrop-filter: blur(14px);
      padding: 10px 0;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.35);
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      width: 100%;
      z-index: 1030;
      
      /* التأكد من ثبات النافبار في أعلى اليسار تماماً */
      position: fixed; 
      top: 0;
      left: 0;
    }

    .navbar-custom .container {
      max-width: 1100px;
    }

    /* Brand (logo + text) */
    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 0;
      margin: 0;
    }

    .navbar-brand img {
      height: 50px;
      width: auto;
      display: block;
    }

    .brand-title {
      display: flex;
      flex-direction: column;
      line-height: 1.2;
    }

    .brand-title-main {
      color: #ffffff;
      font-weight: 700;
      font-size: 1.05rem;
      letter-spacing: 0.03em;
    }

    .brand-title-sub {
      color: rgba(248, 250, 252, 0.8);
      font-size: 0.78rem;
    }

    /* Links */
    .navbar-nav {
      align-items: center;
      gap: 6px;
    }

    .nav-link {
      color: rgba(248, 250, 252, 0.9) !important;
      font-weight: 500;
      font-size: 0.95rem;
      padding: 8px 16px !important;
      border-radius: 999px;
      border: 1px solid transparent;
      transition: all 0.22s ease;
    }

    .nav-link:hover {
      background: rgba(15, 23, 42, 0.22);
      border-color: rgba(248, 250, 252, 0.6);
      color: #ffffff !important;
    }

    .nav-link.active {
      border-color: var(--color-accent);
      background: rgba(15, 23, 42, 0.28);
      color: #ffffff !important;
    }

    /* Toggler (mobile) */
    .navbar-toggler {
      border-color: rgba(255, 255, 255, 0.6);
    }

    .navbar-toggler-icon {
      filter: invert(1);
    }

    @media (max-width: 991.98px) {
      .navbar-custom {
        padding: 8px 0;
      }

      .navbar-nav {
        align-items: flex-start;
        margin-top: 10px;
        gap: 4px;
      }

      .nav-link {
        width: 100%;
      }
    }
  </style>

  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="logo.png" alt="Lost & Found System Logo" >
      <div class="brand-title">
        <span class="brand-title-main">Lost &amp; Found System</span>
        <span class="brand-title-sub">University of Tabuk</span>
      </div>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?php if ($current === 'index.php') echo 'active'; ?>" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if ($current === 'add_report.php') echo 'active'; ?>" href="add_report.php">Add Report</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if ($current === 'show_report.php') echo 'active'; ?>" href="show_report.php">Show Reports</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if ($current === 'admin.php') echo 'active'; ?>" href="admin.php">Admin Login</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
