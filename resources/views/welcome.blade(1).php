<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmVax - Digital Livestock Health Management Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/farmvax-brand.css') }}">
    <style>
        /* Brand colors */
        .bg-brand-green { background-color: #61CE70; }
        .bg-brand-teal { background-color: #058283; }
        .text-brand-green { color: #61CE70; }
        .text-brand-teal { color: #058283; }
        .border-brand-green { border-color: #61CE70; }
        .border-brand-teal { border-color: #058283; }
        
        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
    </style>
</head>
<body class="bg-white">

    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <!-- Replace this image with your actual logo -->
                    <img src="https://via.placeholder.com/50x50/61CE70/FFFFFF?text=Logo" alt="FarmVax Logo" class="h-12 w-12 rounded-lg">
                    <span class="text-2xl font-bold text-brand-teal">FarmVax</span>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-brand-teal transition">Features</a>
                    <a href="#how-it-works" class="text-gray-700 hover:text-brand-teal transition">How It Works</a>
                    <a href="#about" class="text-gray-700 hover:text-brand-teal transition">About</a>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-brand-teal transition">Sign In</a>
                    <a href="#register" class="bg-brand-green text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
                        Get Started
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-700" onclick="toggleMobileMenu()">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" class="block text-gray-700 hover:text-brand-teal">Features</a>
                <a href="#how-it-works" class="block text-gray-700 hover:text-brand-teal">How It Works</a>
                <a href="#about" class="block text-gray-700 hover:text-brand-teal">About</a>
                <a href="{{ route('login') }}" class="block text-gray-700 hover:text-brand-teal">Sign In</a>
                <a href="#register" class="block bg-brand-green text-white px-6 py-2 rounded-lg text-center">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 pb-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-green-50 to-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Left Content -->
                <div class="fade-in-up">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Digital Livestock Health Management for
                        <span class="text-brand-green">Modern Farmers</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        Track vaccinations, manage livestock records, receive outbreak alerts, and connect with veterinary professionals—all in one platform.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#register" class="bg-brand-green text-white px-8 py-4 rounded-lg text-lg font-semibold hover:opacity-90 transition text-center shadow-lg">
                            Register as Farmer
                        </a>
                        <a href="#how-it-works" class="border-2 border-brand-teal text-brand-teal px-8 py-4 rounded-lg text-lg font-semibold hover:bg-brand-teal hover:text-white transition text-center">
                            Learn More
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mt-12">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-brand-green">10K+</div>
                            <div class="text-sm text-gray-600">Farmers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-brand-green">50K+</div>
                            <div class="text-sm text-gray-600">Livestock</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-brand-green">99%</div>
                            <div class="text-sm text-gray-600">Accuracy</div>
                        </div>
                    </div>
                </div>

                <!-- Right Image/Illustration -->
                <div class="float-animation hidden lg:block">
                    <!-- Replace this with your actual hero image -->
                    <img src="https://via.placeholder.com/600x500/E8F5E9/61CE70?text=Hero+Image" alt="FarmVax Platform" class="rounded-2xl shadow-2xl">
                </div>

            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Comprehensive Features</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to manage your livestock health in one powerful platform
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-brand-green h-16 w-16 rounded-full flex items-center justify-center mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 text-2xl font-bold text-gray-900 ">Digital Record Keeping</h3>
                    <p class="text-gray-600">
                        Maintain comprehensive digital records of all your livestock, including vaccination history, health status, and breeding information.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-brand-teal h-16 w-16 rounded-full flex items-center justify-center mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Outbreak Alerts</h3>
                    <p class="text-gray-600">
                        Receive instant notifications about disease outbreaks in your area to protect your livestock and take preventive measures.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-brand-green h-16 w-16 rounded-full flex items-center justify-center mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Vaccination Tracking</h3>
                    <p class="text-gray-600">
                        Never miss a vaccination schedule. Track all administered vaccines and receive reminders for upcoming doses.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-brand-teal h-16 w-16 rounded-full flex items-center justify-center mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Professional Network</h3>
                    <p class="text-gray-600">
                        Connect with verified veterinarians, paraveterinarians, and community health workers for expert support.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-brand-green h-16 w-16 rounded-full flex items-center justify-center mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Analytics & Reports</h3>
                    <p class="text-gray-600">
                        Get insights into your herd's health trends, vaccination coverage, and growth patterns with detailed analytics.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-brand-teal h-16 w-16 rounded-full flex items-center justify-center mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Mobile Access</h3>
                    <p class="text-gray-600">
                        Access your livestock data anytime, anywhere. Our mobile-responsive platform works on all devices.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">How FarmVax Works</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Get started in three simple steps
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="bg-brand-green text-white h-16 w-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Register Your Account</h3>
                    <p class="text-gray-600">
                        Choose your role (Farmer, Health Professional, or Volunteer) and create your free account in minutes.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="bg-brand-teal text-white h-16 w-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Add Your Livestock</h3>
                    <p class="text-gray-600">
                        Input your animals' details, vaccination history, and health records into our secure digital system.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="bg-brand-green text-white h-16 w-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Stay Protected</h3>
                    <p class="text-gray-600">
                        Receive alerts, track vaccinations, and access professional support to keep your livestock healthy.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Vision Section -->
    <section id="about" class="py-20 bg-brand-teal text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Our Vision</h2>
            <p class="text-2xl leading-relaxed opacity-90">
                To be Africa's leading innovator in animal health—delivering breakthrough solutions, protecting animals, and transforming communities.
            </p>
        </div>
    </section>

    <!-- Registration Section -->
    <section id="register" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Get Started Today</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Choose your role and join thousands of farmers, professionals, and volunteers making a difference
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Farmer Card -->
                <div class="bg-white border-2 border-brand-green rounded-2xl p-8 hover:shadow-2xl transition">
                    <div class="bg-brand-green h-16 w-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-4">Farmer</h3>
                    <ul class="space-y-3 mb-8 text-gray-600">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Manage livestock records
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Track vaccinations
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Receive outbreak alerts
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Request veterinary services
                        </li>
                    </ul>
                    <a href="{{ route('register.farmer') }}" class="block w-full bg-brand-green text-white text-center py-3 rounded-lg font-semibold hover:opacity-90 transition">
                        Register as Farmer
                    </a>
                </div>

                <!-- Health Professional Card -->
                <div class="bg-white border-2 border-brand-teal rounded-2xl p-8 hover:shadow-2xl transition transform md:scale-105">
                    <div class="bg-yellow-500 text-xs font-bold text-white px-3 py-1 rounded-full inline-block mb-4">
                        REQUIRES APPROVAL
                    </div>
                    <div class="bg-brand-teal h-16 w-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-4">Health Professional</h3>
                    <ul class="space-y-3 mb-8 text-gray-600">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-teal mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Provide veterinary services
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-teal mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Access farmer records
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-teal mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Submit vaccination reports
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-teal mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Professional verification
                        </li>
                    </ul>
                    <a href="{{ route('register.professional') }}" class="block w-full bg-brand-teal text-white text-center py-3 rounded-lg font-semibold hover:opacity-90 transition">
                        Apply as Professional
                    </a>
                </div>

                <!-- Volunteer Card -->
                <div class="bg-white border-2 border-brand-green rounded-2xl p-8 hover:shadow-2xl transition">
                    <div class="bg-green-500 text-xs font-bold text-white px-3 py-1 rounded-full inline-block mb-4">
                        INSTANT APPROVAL
                    </div>
                    <div class="h-16 w-16 rounded-full flex items-center justify-center mb-6 mx-auto" style="background: linear-gradient(135deg, #61CE70 0%, #058283 100%);">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-4">Volunteer</h3>
                    <ul class="space-y-3 mb-8 text-gray-600">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Enroll new farmers
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Receive outbreak alerts
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Track your impact
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-brand-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Support your community
                        </li>
                    </ul>
                    <a href="{{ route('register.volunteer') }}" class="block w-full bg-brand-green text-white text-center py-3 rounded-lg font-semibold hover:opacity-90 transition">
                        Join as Volunteer
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="https://via.placeholder.com/40x40/61CE70/FFFFFF?text=Logo" alt="FarmVax" class="h-10 w-10 rounded-lg">
                        <span class="text-xl font-bold">FarmVax</span>
                    </div>
                    <p class="text-gray-400">
                        Digital livestock health management for modern farmers.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-brand-green transition">Features</a></li>
                        <li><a href="#how-it-works" class="hover:text-brand-green transition">How It Works</a></li>
                        <li><a href="#about" class="hover:text-brand-green transition">About Us</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-brand-green transition">Sign In</a></li>
                    </ul>
                </div>

                <!-- Register -->
                <div>
                    <h4 class="font-bold mb-4">Register</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('register.farmer') }}" class="hover:text-brand-green transition">As Farmer</a></li>
                        <li><a href="{{ route('register.professional') }}" class="hover:text-brand-green transition">As Professional</a></li>
                        <li><a href="{{ route('register.volunteer') }}" class="hover:text-brand-green transition">As Volunteer</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-bold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Email: info@farmvax.com</li>
                        <li>Phone: +234 XXX XXX XXXX</li>
                        <li>Address: Abuja, Nigeria</li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} FarmVax. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Close mobile menu if open
                    document.getElementById('mobileMenu').classList.add('hidden');
                }
            });
        });
    </script>

    <script src="{{ asset('js/farmvax-mobile.js') }}"></script>
</body>
</html>