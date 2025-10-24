<aside id="sidebar" class="fixed lg:relative lg:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out z-30 w-64 bg-neutral-900 text-white flex-shrink-0 h-screen lg:h-auto overflow-y-auto">
    <div class="p-4 lg:p-6">
        <div class="flex items-center justify-between lg:justify-start space-x-3 mb-6 lg:mb-8">
            <div class="flex items-center space-x-3">
                <div class="w-10 lg:w-12 h-10 lg:h-12 bg-gradient-to-br from-orange-700 to-amber-800 rounded-lg flex items-center justify-center shadow-lg">
                    <i class="fas fa-bread-slice text-white text-lg lg:text-xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-base lg:text-lg text-white">House of Bread</h2>
                    <p class="text-xs text-orange-400 hidden lg:block">Admin Panel</p>
                </div>
            </div>
            <!-- Mobile close button -->
            <button id="close-sidebar" class="lg:hidden text-gray-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="index.php" class="flex items-center space-x-3 px-3 lg:px-4 py-2 lg:py-3 rounded-lg transition <?= $currentPage === 'index' ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-neutral-800' ?>">
                <i class="fas fa-home w-4 lg:w-5 text-sm lg:text-base"></i>
                <span class="text-sm lg:text-base">Dashboard</span>
            </a>

            <!-- Pastors -->
            <a href="pastors.php" class="flex items-center space-x-3 px-3 lg:px-4 py-2 lg:py-3 rounded-lg transition <?= $currentPage === 'pastors' ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-neutral-800' ?>">
                <i class="fas fa-users w-4 lg:w-5 text-sm lg:text-base"></i>
                <span class="text-sm lg:text-base">Pastors</span>
            </a>

            <!-- Events -->
            <a href="events.php" class="flex items-center space-x-3 px-3 lg:px-4 py-2 lg:py-3 rounded-lg transition <?= $currentPage === 'events' ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-neutral-800' ?>">
                <i class="fas fa-calendar-alt w-4 lg:w-5 text-sm lg:text-base"></i>
                <span class="text-sm lg:text-base">Events</span>
            </a>

            <!-- Sermons -->
            <a href="sermons.php" class="flex items-center space-x-3 px-3 lg:px-4 py-2 lg:py-3 rounded-lg transition <?= $currentPage === 'sermons' ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-neutral-800' ?>">
                <i class="fas fa-microphone w-4 lg:w-5 text-sm lg:text-base"></i>
                <span class="text-sm lg:text-base">Sermons</span>
            </a>

            <!-- Ministries -->
            <a href="ministries.php" class="flex items-center space-x-3 px-3 lg:px-4 py-2 lg:py-3 rounded-lg transition <?= $currentPage === 'ministries' ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-neutral-800' ?>">
                <i class="fas fa-hands-helping w-4 lg:w-5 text-sm lg:text-base"></i>
                <span class="text-sm lg:text-base">Ministries</span>
            </a>

            <!-- Prayer Requests -->
            <a href="prayer-requests.php" class="flex items-center space-x-3 px-3 lg:px-4 py-2 lg:py-3 rounded-lg transition <?= $currentPage === 'prayer-requests' ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-neutral-800' ?>">
                <i class="fas fa-praying-hands w-4 lg:w-5 text-sm lg:text-base"></i>
                <span class="text-sm lg:text-base">Prayer Requests</span>
            </a>

            <!-- Contact Messages -->
            <a href="contact-messages.php" class="flex items-center space-x-3 px-3 lg:px-4 py-2 lg:py-3 rounded-lg transition <?= $currentPage === 'contact-messages' ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-neutral-800' ?>">
                <i class="fas fa-envelope w-4 lg:w-5 text-sm lg:text-base"></i>
                <span class="text-sm lg:text-base">Contact Messages</span>
            </a>

            <!-- Newsletter -->
            <!-- <a href="newsletter.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?= $currentPage === 'newsletter' ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-neutral-800' ?>">
                <i class="fas fa-newspaper w-5"></i>
                <span>Newsletter</span>
            </a> -->

            <div class="border-t border-neutral-800 my-4"></div>

            <!-- Settings -->
            <a href="settings.php" class="flex items-center space-x-3 px-3 lg:px-4 py-2 lg:py-3 rounded-lg transition <?= $currentPage === 'settings' ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-neutral-800' ?>">
                <i class="fas fa-cog w-4 lg:w-5 text-sm lg:text-base"></i>
                <span class="text-sm lg:text-base">Site Settings</span>
            </a>

            


            <div class="border-t border-neutral-800 my-3 lg:my-4"></div>

            <!-- Logout -->
            <a href="<?= SITE_URL ?>/logout.php" class="flex items-center space-x-3 px-3 lg:px-4 py-2 lg:py-3 rounded-lg transition text-red-400 hover:bg-neutral-800">
                <i class="fas fa-sign-out-alt w-4 lg:w-5 text-sm lg:text-base"></i>
                <span class="text-sm lg:text-base">Logout</span>
            </a>
        </nav>
    </div>
</aside>
