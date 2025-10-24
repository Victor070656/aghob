            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <p>&copy; <?= date('Y') ?> Assemblies of God Church House of Bread. All rights reserved.</p>
                    <p>Powered by AGC NRD Admin System</p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.auto-hide');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Confirm delete actions
        document.querySelectorAll('.confirm-delete').forEach(button => {
            button.addEventListener('click', (e) => {
                if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        });

        // Mobile Sidebar Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const openSidebarBtn = document.getElementById('open-sidebar');
            const closeSidebarBtn = document.getElementById('close-sidebar');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full', 'admin-sidebar-closed');
                sidebar.classList.add('translate-x-0', 'admin-sidebar-open', 'sidebar-slide-in');
                sidebarOverlay.classList.remove('hidden');
                setTimeout(() => {
                    sidebarOverlay.classList.add('sidebar-overlay-active');
                    sidebarOverlay.classList.remove('sidebar-overlay-inactive');
                }, 10);
                document.body.style.overflow = 'hidden'; // Prevent background scroll
            }

            function closeSidebar() {
                sidebar.classList.remove('translate-x-0', 'admin-sidebar-open', 'sidebar-slide-in');
                sidebar.classList.add('-translate-x-full', 'admin-sidebar-closed', 'sidebar-slide-out');
                sidebarOverlay.classList.remove('sidebar-overlay-active');
                sidebarOverlay.classList.add('sidebar-overlay-inactive');
                setTimeout(() => {
                    sidebarOverlay.classList.add('hidden');
                    sidebar.classList.remove('sidebar-slide-out');
                }, 300);
                document.body.style.overflow = ''; // Restore background scroll
            }

            // Event listeners
            if (openSidebarBtn) {
                openSidebarBtn.addEventListener('click', openSidebar);
            }

            if (closeSidebarBtn) {
                closeSidebarBtn.addEventListener('click', closeSidebar);
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                    closeSidebar();
                }
            });

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth >= 1024) {
                        // Reset to desktop state
                        sidebar.classList.remove('-translate-x-full', 'admin-sidebar-closed', 'sidebar-slide-in', 'sidebar-slide-out');
                        sidebar.classList.add('lg:translate-x-0');
                        sidebarOverlay.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                }, 250);
            });
        });
    </script>
</body>
</html>
