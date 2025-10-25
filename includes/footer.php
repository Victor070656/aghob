<!-- Footer -->
<footer class="footer-ag text-white mt-16 relative overflow-hidden">
    <!-- Decorative Top Border -->
    <div class="absolute top-0 left-0 right-0 h-1"
        style="background: linear-gradient(90deg, #1e40af 0%, #ea580c 50%, #d97706 100%);"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <div class="flex items-center mb-4 gap-3">
                    <?php if (empty($settings['logo'])): ?>
                        <div
                            class="w-14 h-14 ag-logo-placeholder text-white rounded-xl flex items-center justify-center group-hover:shadow-lg transition-all flex-shrink-0">
                            <i class="fas fa-dove text-2xl"></i>
                        </div>
                    <?php else: ?>
                        <img src="<?= SITE_URL . '/' . $settings['logo'] ?>" alt=""
                            class="h-10 group-hover:scale-110 transition-transform">
                    <?php endif; ?>
                    <h3 class="text-xl font-bold">AG House of Bread</h3>
                </div>
                <p class="text-gray-300 text-sm mb-4 leading-relaxed">
                    <?= htmlspecialchars(substr($settings['about_text'] ?? 'Building Faith, Transforming Lives through the Living Bread of Jesus Christ', 0, 150)) ?>...
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: #d97706;">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?= SITE_URL ?>/index.php"
                            class="text-gray-300 hover:text-orange-400 transition flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"
                                style="color: #1e40af;"></i>Home
                        </a></li>
                    <li><a href="<?= SITE_URL ?>/pages/about.php"
                            class="text-gray-300 hover:text-orange-400 transition flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"
                                style="color: #1e40af;"></i>About Us
                        </a></li>
                    <li><a href="<?= SITE_URL ?>/pages/pastors.php"
                            class="text-gray-300 hover:text-orange-400 transition flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"
                                style="color: #1e40af;"></i>Leadership
                        </a></li>
                    <li><a href="<?= SITE_URL ?>/pages/events.php"
                            class="text-gray-300 hover:text-orange-400 transition flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"
                                style="color: #1e40af;"></i>Events
                        </a></li>
                    <li><a href="<?= SITE_URL ?>/pages/sermons.php"
                            class="text-gray-300 hover:text-orange-400 transition flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"
                                style="color: #1e40af;"></i>Sermons
                        </a></li>
                    <li><a href="<?= SITE_URL ?>/pages/ministries.php"
                            class="text-gray-300 hover:text-orange-400 transition flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"
                                style="color: #1e40af;"></i>Ministries
                        </a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: #d97706;">Contact Us</h3>
                <ul class="space-y-3 text-sm text-gray-300">
                    <?php if (!empty($settings['site_email'])): ?>
                        <li class="flex items-start group">
                            <i class="fas fa-envelope mt-1 mr-3" style="color: #1e40af;"></i>
                            <a href="mailto:<?= htmlspecialchars($settings['site_email']) ?>"
                                class="hover:text-orange-400 transition">
                                <?= htmlspecialchars($settings['site_email']) ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($settings['site_phone'])): ?>
                        <li class="flex items-start group">
                            <i class="fas fa-phone mt-1 mr-3" style="color: #1e40af;"></i>
                            <a href="tel:<?= htmlspecialchars($settings['site_phone']) ?>"
                                class="hover:text-orange-400 transition">
                                <?= htmlspecialchars($settings['site_phone']) ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($settings['site_address'])): ?>
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3" style="color: #1e40af;"></i>
                            <span><?= nl2br(htmlspecialchars($settings['site_address'])) ?></span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Social Media -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: #d97706;">Follow Us</h3>
                <div class="flex space-x-3">
                    <?php if (!empty($settings['facebook_url'])): ?>
                        <a href="<?= htmlspecialchars($settings['facebook_url']) ?>" target="_blank" class="ag-social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['instagram_url'])): ?>
                        <a href="<?= htmlspecialchars($settings['instagram_url']) ?>" target="_blank"
                            class="ag-social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['youtube_url'])): ?>
                        <a href="<?= htmlspecialchars($settings['youtube_url']) ?>" target="_blank" class="ag-social-icon"
                            style="background: linear-gradient(135deg, #ff0000 0%, #cc0000 100%);">
                            <i class="fab fa-youtube"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['twitter_url'])): ?>
                        <a href="<?= htmlspecialchars($settings['twitter_url']) ?>" target="_blank" class="ag-social-icon"
                            style="background: linear-gradient(135deg, #1DA1F2 0%, #0d8bd9 100%);">
                            <i class="fab fa-twitter"></i>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($settings['live_stream_url'])): ?>
                    <a href="<?= htmlspecialchars($settings['live_stream_url']) ?>" target="_blank"
                        class="mt-6 inline-flex items-center btn-ag-primary text-white px-6 py-3 rounded-full transition-all font-semibold text-sm shadow-lg hover:shadow-xl">
                        <span class="ag-live-indicator mr-2">LIVE</span>
                        Watch Live
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-300">
            <p>&copy; <?= date('Y') ?>
                <?= htmlspecialchars($settings['site_name'] ?? 'Assemblies of God House of Bread') ?>. All rights
                reserved.
            </p>
            <p class="mt-2">
                <a href="<?= SITE_URL ?>/login.php" class="hover:text-orange-400 transition">Admin Login</a>
            </p>
        </div>
    </div>
</footer>
</body>

</html>