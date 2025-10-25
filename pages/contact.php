<?php
$pageTitle = 'Contact Us';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="relative text-white py-16" style="min-height: 60vh;">
    <!-- Background with AG Gradient -->
    <div class="absolute inset-0 hero-ag-gradient"></div>
    <div class="absolute inset-0 bg-cover bg-center opacity-40 "
        style="background-image: url('../images/08.jpg');">
    </div>
    <div class="max-w-7xl relative mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="ag-ministry-icon w-20 h-20 mx-auto mb-6">
                <i class="fas fa-envelope text-3xl text-white"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Contact AG House of Bread</h1>
            <p class="text-xl text-gray-100 max-w-2xl mx-auto">
                We'd love to hear from you. Connect with our church family today!
            </p>
        </div>
    </div>
</section>

<!-- Contact Information Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <!-- Email -->
            <?php if ($settings['site_email']): ?>
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-3xl text-teal-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Email Us</h3>
                    <a href="mailto:<?= htmlspecialchars($settings['site_email']) ?>"
                        class="text-teal-600 hover:text-teal-800 hover:underline">
                        <?= htmlspecialchars($settings['site_email']) ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Phone -->
            <?php if ($settings['site_phone']): ?>
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-3xl text-cyan-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Call Us</h3>
                    <a href="tel:<?= htmlspecialchars($settings['site_phone']) ?>"
                        class="text-cyan-600 hover:text-cyan-800 hover:underline">
                        <?= htmlspecialchars($settings['site_phone']) ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Address -->
            <?php if ($settings['site_address']): ?>
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-map-marker-alt text-3xl text-amber-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Visit Us</h3>
                    <p class="text-gray-700">
                        <?= nl2br(htmlspecialchars($settings['site_address'])) ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Contact Form and Prayer Request Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contact Form -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Send Us a Message</h2>
                    <p class="text-gray-600">Have a question or comment? Fill out the form below and we'll get back to
                        you as soon as possible.</p>
                </div>

                <form id="contact-form">
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="contact-name" name="name" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="contact-email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="contact-email" name="email" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="contact-phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Phone Number
                            </label>
                            <input type="tel" id="contact-phone" name="phone"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="contact-subject" class="block text-sm font-medium text-gray-700 mb-1">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="contact-subject" name="subject" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="contact-message" class="block text-sm font-medium text-gray-700 mb-1">
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea id="contact-message" name="message" rows="5" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-600 focus:border-transparent"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-teal-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
                            <i class="fas fa-paper-plane mr-2"></i> Send Message
                        </button>

                        <!-- Message Display -->
                        <div id="contact-message-display" class="hidden mt-4 p-4 rounded-lg"></div>
                    </div>
                </form>
            </div>

            <!-- Prayer Request Form -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Submit a Prayer Request</h2>
                    <p class="text-gray-600">We believe in the power of prayer. Share your prayer request with us and
                        our team will pray for you.</p>
                </div>

                <form id="prayer-form">
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="prayer-name" class="block text-sm font-medium text-gray-700 mb-1">
                                Your Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="prayer-name" name="full_name" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-600 focus:border-transparent">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="prayer-email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address
                            </label>
                            <input type="email" id="prayer-email" name="email"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-600 focus:border-transparent">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="prayer-phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Phone Number
                            </label>
                            <input type="tel" id="prayer-phone" name="phone"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-600 focus:border-transparent">
                        </div>

                        <!-- Prayer Request -->
                        <div>
                            <label for="prayer-request" class="block text-sm font-medium text-gray-700 mb-1">
                                Prayer Request <span class="text-red-500">*</span>
                            </label>
                            <textarea id="prayer-request" name="prayer_request" rows="5" required
                                placeholder="Share your prayer request with us..."
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-600 focus:border-transparent"></textarea>
                        </div>

                        
                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                            <i class="fas fa-praying-hands mr-2"></i> Submit Prayer Request
                        </button>

                        <!-- Message Display -->
                        <div id="prayer-message-display" class="hidden mt-4 p-4 rounded-lg"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<?php if ($settings['site_address']): ?>
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Find Us</h2>
                <p class="text-lg text-gray-600">Visit us at our location</p>
            </div>

            <!-- Map Placeholder -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200 flex items-center justify-center" style="height: 400px;">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt text-6xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 font-medium">Map Integration</p>
                        <p class="text-sm text-gray-500 mt-2"><?= nl2br(htmlspecialchars($settings['site_address'])) ?></p>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($settings['site_address']) ?>"
                            target="_blank"
                            class="mt-4 inline-block bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-directions mr-2"></i> Get Directions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<script>
    // Contact Form Handler
    document.getElementById('contact-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const messageEl = document.getElementById('contact-message-display');
        const submitBtn = form.querySelector('button[type="submit"]');
        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';
        messageEl.classList.add('hidden');

        try {
            // Debug: Log form data
            console.log('Submitting contact form...');
            console.log('API URL: <?= SITE_URL ?>/api/contact.php');

            const response = await fetch('<?= SITE_URL ?>/api/contact.php', {
                method: 'POST',
                body: formData
            });

            console.log('Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Response data:', data);

            if (data.success) {
                messageEl.textContent = data.message;
                messageEl.className = 'mt-4 p-4 rounded-lg bg-green-100 border border-green-400 text-green-700';
                messageEl.classList.remove('hidden');
                form.reset();
            } else {
                messageEl.textContent = data.message || 'An error occurred. Please try again.';
                messageEl.className = 'mt-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700';
                messageEl.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Contact form error:', error);
            messageEl.textContent = 'An error occurred: ' + error.message + '. Please try again later.';
            messageEl.className = 'mt-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700';
            messageEl.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Send Message';
        }
    });

    // Prayer Request Form Handler
    document.getElementById('prayer-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const messageEl = document.getElementById('prayer-message-display');
        const submitBtn = form.querySelector('button[type="submit"]');
        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
        messageEl.classList.add('hidden');

        try {
            // Debug: Log form data
            console.log('Submitting prayer request...');
            console.log('API URL: <?= SITE_URL ?>/api/prayer.php');

            const response = await fetch('<?= SITE_URL ?>/api/prayer.php', {
                method: 'POST',
                body: formData
            });

            console.log('Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Response data:', data);

            if (data.success) {
                messageEl.textContent = data.message;
                messageEl.className = 'mt-4 p-4 rounded-lg bg-green-100 border border-green-400 text-green-700';
                messageEl.classList.remove('hidden');
                form.reset();
            } else {
                messageEl.textContent = data.message || 'An error occurred. Please try again.';
                messageEl.className = 'mt-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700';
                messageEl.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Prayer form error:', error);
            messageEl.textContent = 'An error occurred: ' + error.message + '. Please try again later.';
            messageEl.className = 'mt-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700';
            messageEl.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-praying-hands mr-2"></i> Submit Prayer Request';
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>