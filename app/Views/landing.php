<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'AuthTool' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #1e293b;
            --accent: #f59e0b;
            --dark: #0f172a;
            --light: #f8fafc;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--light); color: #334155; line-height: 1.6; }

        /* Navbar */
        .navbar-landing {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
        }
        .navbar-landing .nav-brand { font-size: 1.5rem; font-weight: 700; color: var(--primary); text-decoration: none; }
        .navbar-landing .nav-link { color: #64748b; text-decoration: none; margin: 0 1rem; font-weight: 500; transition: color 0.3s; }
        .navbar-landing .nav-link:hover { color: var(--primary); }
        .navbar-landing .btn-nav { background: var(--primary); color: white; padding: 0.5rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; border: none; transition: all 0.3s; }
        .navbar-landing .btn-nav:hover { background: var(--primary-dark); transform: translateY(-1px); }

        /* Hero */
        .hero {
            background: linear-gradient(135deg, var(--dark) 0%, #1e3a5f 50%, var(--primary-dark) 100%);
            color: white;
            padding: 8rem 0 5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-content { position: relative; z-index: 1; }
        .hero h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 1.5rem; line-height: 1.2; }
        .hero h1 span { color: var(--accent); }
        .hero p { font-size: 1.25rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem; }
        .hero-badges { display: flex; gap: 1rem; justify-content: center; margin-bottom: 2rem; flex-wrap: wrap; }
        .hero-badge { background: rgba(255,255,255,0.1); padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; }
        .hero-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-hero-primary { background: var(--accent); color: var(--dark); padding: 1rem 2.5rem; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 1.1rem; transition: all 0.3s; border: none; }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(245,158,11,0.4); }
        .btn-hero-secondary { background: transparent; color: white; padding: 1rem 2.5rem; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 1.1rem; border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s; }
        .btn-hero-secondary:hover { border-color: white; color: white; }

        /* Stats */
        .stats { background: white; padding: 3rem 0; border-bottom: 1px solid #e2e8f0; }
        .stat-item { text-align: center; padding: 1rem; }
        .stat-number { font-size: 2.5rem; font-weight: 800; color: var(--primary); }
        .stat-label { color: #64748b; font-size: 0.95rem; }

        /* Features */
        .features { padding: 5rem 0; background: white; }
        .section-title { text-align: center; margin-bottom: 3rem; }
        .section-title h2 { font-size: 2.5rem; font-weight: 700; color: var(--secondary); margin-bottom: 1rem; }
        .section-title p { color: #64748b; font-size: 1.1rem; max-width: 600px; margin: 0 auto; }
        .feature-card { background: var(--light); border-radius: 16px; padding: 2rem; height: 100%; border: 1px solid #e2e8f0; transition: all 0.3s; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); border-color: var(--primary); }
        .feature-icon { width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; font-size: 1.5rem; color: white; }
        .feature-card h4 { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--secondary); }
        .feature-card p { color: #64748b; font-size: 0.95rem; }

        /* Pricing */
        .pricing { padding: 5rem 0; background: linear-gradient(180deg, #f1f5f9 0%, white 100%); }
        .pricing .section-title h2 { color: var(--secondary); }
        .pricing-card { background: white; border-radius: 20px; padding: 2.5rem; text-align: center; border: 2px solid #e2e8f0; transition: all 0.3s; position: relative; height: 100%; }
        .pricing-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .pricing-card.popular { border-color: var(--primary); transform: scale(1.05); }
        .pricing-card.popular::before { content: 'Phổ biến'; position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--primary); color: white; padding: 0.25rem 1rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600; }
        .pricing-card h3 { font-size: 1.5rem; font-weight: 700; color: var(--secondary); margin-bottom: 1rem; }
        .pricing-price { font-size: 3rem; font-weight: 800; color: var(--primary); margin-bottom: 0.5rem; }
        .pricing-price span { font-size: 1rem; font-weight: 400; color: #64748b; }
        .pricing-desc { color: #64748b; margin-bottom: 1.5rem; font-size: 0.95rem; }
        .pricing-features { list-style: none; padding: 0; margin: 0 0 2rem; text-align: left; }
        .pricing-features li { padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.5rem; }
        .pricing-features li i { color: var(--primary); }
        .btn-pricing { display: block; width: 100%; padding: 1rem; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s; border: 2px solid var(--primary); color: var(--primary); }
        .btn-pricing:hover { background: var(--primary); color: white; }
        .pricing-card.popular .btn-pricing { background: var(--primary); color: white; }
        .pricing-card.popular .btn-pricing:hover { background: var(--primary-dark); }

        /* CTA */
        .cta { background: linear-gradient(135deg, var(--dark) 0%, var(--primary-dark) 100%); color: white; padding: 5rem 0; text-align: center; }
        .cta h2 { font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; }
        .cta p { font-size: 1.2rem; opacity: 0.9; max-width: 500px; margin: 0 auto 2rem; }
        .cta .btn-cta { background: var(--accent); color: var(--dark); padding: 1rem 3rem; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 1.2rem; transition: all 0.3s; display: inline-block; }
        .cta .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(245,158,11,0.4); }

        /* Footer */
        .footer { background: var(--dark); color: white; padding: 3rem 0 1.5rem; }
        .footer-brand { font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 1rem; }
        .footer-desc { color: #94a3b8; font-size: 0.95rem; max-width: 300px; }
        .footer h5 { font-weight: 600; margin-bottom: 1rem; color: white; }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 0.5rem; }
        .footer-links a { color: #94a3b8; text-decoration: none; transition: color 0.3s; }
        .footer-links a:hover { color: white; }
        .footer-bottom { border-top: 1px solid #334155; margin-top: 2rem; padding-top: 1.5rem; text-align: center; color: #64748b; font-size: 0.9rem; }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .pricing-card.popular { transform: scale(1); margin-top: 1rem; }
            .section-title h2 { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar-landing">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="<?= site_url('/') ?>" class="nav-brand">
                    <i class="bi bi-shield-lock me-2"></i>AuthTool
                </a>
                <div class="d-none d-md-flex align-items-center">
                    <a href="#features" class="nav-link">Tính năng</a>
                    <a href="#pricing" class="nav-link">Bảng giá</a>
                    <a href="<?= site_url('login') ?>" class="nav-link">Đăng nhập</a>
                    <a href="<?= site_url('login') ?>" class="btn-nav">Bắt đầu ngay</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="container hero-content">
            <div class="hero-badges">
                <span class="hero-badge"><i class="bi bi-lightning-charge-fill"></i> Tạo Key nhanh chóng</span>
                <span class="hero-badge"><i class="bi bi-shield-check"></i> Bảo mật cao cấp</span>
                <span class="hero-badge"><i class="bi bi-graph-up-arrow"></i> Quản lý dễ dàng</span>
            </div>
            <h1>Quản lý <span>License Key</span><br>dễ dàng & hiệu quả</h1>
            <p>Hệ thống tạo và quản lý license key cho phần mềm của bạn. Tích hợp API mạnh mẽ, bảo mật cao, hỗ trợ nhiều nền tảng.</p>
            <div class="hero-btns">
                <a href="<?= site_url('login') ?>" class="btn-hero-primary">
                    <i class="bi bi-rocket-takeoff me-2"></i>Dùng thử miễn phí
                </a>
                <a href="#features" class="btn-hero-secondary">
                    <i class="bi bi-info-circle me-2"></i>Tìm hiểu thêm
                </a>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">License Keys</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Người dùng</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Uptime</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Hỗ trợ</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Tính năng nổi bật</h2>
                <p>Mọi thứ bạn cần để quản lý license key một cách chuyên nghiệp</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-key"></i></div>
                        <h4>Tạo License Key</h4>
                        <p>Tạo license key tự động với nhiều tùy chọn: thời hạn, thiết bị, package. Hỗ trợ batch generation.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
                        <h4>Bảo mật cao cấp</h4>
                        <p>Mã hóa license key, chống clone và debug. Kiểm tra tính hợp lệ real-time qua API.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-cpu"></i></div>
                        <h4>Quản lý thiết bị</h4>
                        <p>Theo dõi và giới hạn số thiết bị sử dụng license. Khóa/kích hoạt từ xa dễ dàng.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-braces"></i></div>
                        <h4>API mạnh mẽ</h4>
                        <p>Tích hợp dễ dàng vào phần mềm của bạn với REST API đầy đủ tài liệu và examples.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-graph-up"></i></div>
                        <h4>Thống kê chi tiết</h4>
                        <p>Theo dõi lượt sử dụng, doanh thu, và hoạt động của users. Báo cáo trực quan.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-people"></i></div>
                        <h4>Hệ thống Reseller</h4>
                        <p>Tạo tài khoản reseller, phân chia gói và quota. Quản lý phân cấp người dùng.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-title">
                <h2>Bảng giá đơn giản</h2>
                <p>Chọn gói phù hợp với nhu cầu của bạn. Không phí ẩn.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="pricing-card">
                        <h3>Basic</h3>
                        <div class="pricing-price">100K <span>/ tháng</span></div>
                        <p class="pricing-desc">Gói cơ bản cho cá nhân</p>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill"></i> 1 Package</li>
                            <li><i class="bi bi-check-circle-fill"></i> 20 Keys / tháng</li>
                            <li><i class="bi bi-check-circle-fill"></i> 1 thiết bị/key</li>
                            <li><i class="bi bi-check-circle-fill"></i> API access</li>
                            <li><i class="bi bi-check-circle-fill"></i> Hỗ trợ email</li>
                        </ul>
                        <a href="<?= site_url('register') ?>" class="btn-pricing">Chọn Basic</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="pricing-card popular">
                        <h3>Pro</h3>
                        <div class="pricing-price">500K <span>/ tháng</span></div>
                        <p class="pricing-desc">Gói chuyên nghiệp cho doanh nghiệp</p>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill"></i> 3 Packages</li>
                            <li><i class="bi bi-check-circle-fill"></i> 50 Keys / tháng</li>
                            <li><i class="bi bi-check-circle-fill"></i> 3 thiết bị/key</li>
                            <li><i class="bi bi-check-circle-fill"></i> API access đầy đủ</li>
                            <li><i class="bi bi-check-circle-fill"></i> Thống kê chi tiết</li>
                            <li><i class="bi bi-check-circle-fill"></i> Hỗ trợ ưu tiên</li>
                        </ul>
                        <a href="<?= site_url('register') ?>" class="btn-pricing">Chọn Pro</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="pricing-card">
                        <h3>Advanced</h3>
                        <div class="pricing-price">1M <span>/ tháng</span></div>
                        <p class="pricing-desc">Gói cao cấp cho tổ chức lớn</p>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill"></i> 5 Packages</li>
                            <li><i class="bi bi-check-circle-fill"></i> 100 Keys / tháng</li>
                            <li><i class="bi bi-check-circle-fill"></i> 5 thiết bị/key</li>
                            <li><i class="bi bi-check-circle-fill"></i> API không giới hạn</li>
                            <li><i class="bi bi-check-circle-fill"></i> Reseller system</li>
                            <li><i class="bi bi-check-circle-fill"></i> Hỗ trợ 24/7</li>
                        </ul>
                        <a href="<?= site_url('register') ?>" class="btn-pricing">Chọn Advanced</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <div class="container">
            <h2>Sẵn sàng bắt đầu?</h2>
            <p>Đăng ký ngay hôm nay và nhận 14 ngày dùng thử miễn phí. Không cần thẻ tín dụng.</p>
            <a href="<?= site_url('register') ?>" class="btn-cta">
                <i class="bi bi-rocket-takeoff me-2"></i>Bắt đầu miễn phí
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="footer-brand"><i class="bi bi-shield-lock me-2"></i>AuthTool</div>
                    <p class="footer-desc">Hệ thống quản lý license key hàng đầu Việt Nam. Bảo mật, nhanh chóng, dễ sử dụng.</p>
                </div>
                <div class="col-6 col-md-2">
                    <h5>Sản phẩm</h5>
                    <ul class="footer-links">
                        <li><a href="#features">Tính năng</a></li>
                        <li><a href="#pricing">Bảng giá</a></li>
                        <li><a href="<?= site_url('api-docs') ?>">API Docs</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-2">
                    <h5>Hỗ trợ</h5>
                    <ul class="footer-links">
                        <li><a href="#">Tài liệu</a></li>
                        <li><a href="#">Hướng dẫn</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-2">
                    <h5>Công ty</h5>
                    <ul class="footer-links">
                        <li><a href="#">Giới thiệu</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Tuyển dụng</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-2">
                    <h5>Theo dõi</h5>
                    <ul class="footer-links">
                        <li><a href="#"><i class="bi bi-facebook me-2"></i>Facebook</a></li>
                        <li><a href="#"><i class="bi bi-github me-2"></i>GitHub</a></li>
                        <li><a href="#"><i class="bi bi-telegram me-2"></i>Telegram</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 AuthTool. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>