document.addEventListener('DOMContentLoaded', function () {
    // Tab navigation
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    const progressBar = document.querySelector('#progress-bar');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const tabId = this.getAttribute('data-tab');

            // Hide all tabs
            tabContents.forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('active');
            });

            // Deactivate all buttons
            tabBtns.forEach(btn => {
                btn.classList.remove('active', 'text-[#E85D85]', 'border-b-2', 'border-[#E85D85]');
                btn.classList.add('text-gray-500');
            });

            // Show selected tab
            document.getElementById(tabId).classList.remove('hidden');
            document.getElementById(tabId).classList.add('active');
            this.classList.add('active', 'text-[#E85D85]', 'border-b-2', 'border-[#E85D85]');

            // Update progress bar
            updateProgressBar(tabId);
        });
    });

    // Next/Back buttons
    const nextBtns = document.querySelectorAll('.btn-next');
    const backBtns = document.querySelectorAll('.btn-back');

    nextBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const nextTabId = this.getAttribute('data-next-tab');
            document.querySelector(`.tab-btn[data-tab="${nextTabId}"]`).click();
        });
    });

    backBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const prevTabId = this.getAttribute('data-prev-tab');
            document.querySelector(`.tab-btn[data-tab="${prevTabId}"]`).click();
        });
    });

    // Image preview functionality
    const imageInputs = {
        'profile-picture': 'profile-preview',
        'store-logo': 'logo-preview',
        'store-banner': 'banner-preview',
        'cnic-front': 'cnic-front-preview',
        'cnic-back': 'cnic-back-preview'
    };

    for (const [inputId, previewId] of Object.entries(imageInputs)) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const status = input.parentElement.querySelector('.text-gray-500');

        input.addEventListener('change', function (e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                const reader = new FileReader();

                reader.onload = function (event) {
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                    status.textContent = file.name;
                };

                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
                status.textContent = 'No image selected';
            }
        });
    }

    // Password toggle
    const passwordToggle = document.getElementById('change-password-toggle');
    const passwordFields = document.getElementById('password-fields');

    passwordToggle.addEventListener('change', function () {
        passwordFields.classList.toggle('hidden', !this.checked);
    });

    // City-Area relationship
    const citySelect = document.getElementById('city');
    const areaSelect = document.getElementById('area');

    const areaOptions = {
        karachi: ['Clifton', 'Defence', 'Gulshan', 'North Nazimabad'],
        lahore: ['DHA', 'Gulberg', 'Model Town', 'Johar Town'],
        islamabad: ['F-7', 'F-8', 'G-9', 'G-10']
    };

    citySelect.addEventListener('change', function () {
        areaSelect.innerHTML = '<option value="">Select Area</option>';

        if (this.value && areaOptions[this.value]) {
            areaOptions[this.value].forEach(area => {
                const option = document.createElement('option');
                option.value = area.toLowerCase().replace(' ', '-');
                option.textContent = area;
                areaSelect.appendChild(option);
            });
        }
    });

    // Form submission
    const form = document.getElementById('vendor-profile-form');
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        alert('Profile submitted successfully!');
        // Here you would typically send the form data to the server
    });

    // Skip buttons
    const skipBtns = document.querySelectorAll('.btn-skip');
    skipBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            if (confirm('Are you sure you want to skip and complete this section later?')) {
                // Save current progress and redirect or do something else
                alert('Your progress has been saved. You can complete the profile later.');
            }
        });
    });

    // Auto-save functionality
    const formFields = form.querySelectorAll('input, select, textarea');
    formFields.forEach(field => {
        field.addEventListener('change', function () {
            // In a real implementation, you would save the field value to local storage or send to server
            console.log(`Field ${this.id} changed:`, this.value);
        });
    });

    // Update progress bar based on current tab
    function updateProgressBar(tabId) {
        let progress = 33;

        if (tabId === 'store-details') progress = 66;
        else if (tabId === 'address') progress = 100;
        // else if (tabId === 'finish') progress = 100;

        progressBar.style.width = `${progress}%`;
    }
});