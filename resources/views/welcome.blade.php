<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FarmVax - Africa's #1 AI Animal Health Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#11455B',
                        secondary: '#2FCB6E',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(17, 69, 91, 0.15);
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite;
        }
        
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(47, 203, 110, 0.4); }
            50% { box-shadow: 0 0 40px rgba(47, 203, 110, 0.6); }
        }
    </style>
</head>
<body class="bg-white">

    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-secondary to-primary rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-extrabold text-primary">FarmVax</span>
                    <span class="hidden sm:inline px-3 py-1 text-xs font-semibold bg-secondary/10 text-secondary rounded-full">
                        AI-POWERED
                    </span>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-primary font-medium transition">Features</a>
                    <a href="#how-it-works" class="text-gray-700 hover:text-primary font-medium transition">How It Works</a>
                    <a href="#mission" class="text-gray-700 hover:text-primary font-medium transition">Mission</a>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary font-medium transition">Sign In</a>
                    <a href="#register" class="bg-secondary hover:bg-secondary/90 text-white px-6 py-3 rounded-full font-semibold transition shadow-lg">
                        Get Started Free
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="md:hidden text-primary">
                    <svg id="menu-open" class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="menu-close" class="h-7 w-7 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t shadow-lg">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" onclick="toggleMobileMenu()" class="block text-gray-700 hover:text-primary font-medium py-2">Features</a>
                <a href="#how-it-works" onclick="toggleMobileMenu()" class="block text-gray-700 hover:text-primary font-medium py-2">How It Works</a>
                <a href="#mission" onclick="toggleMobileMenu()" class="block text-gray-700 hover:text-primary font-medium py-2">Mission</a>
                <a href="{{ route('login') }}" class="block text-gray-700 hover:text-primary font-medium py-2">Sign In</a>
                <a href="#register" class="block bg-secondary text-white px-6 py-3 rounded-full font-semibold text-center">Get Started Free</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section - WHITE BACKGROUND -->
    <section class="bg-white pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Left Content -->
                <div class="space-y-8">
                    <!-- Badge -->
                    <div class="inline-flex items-center space-x-2 bg-secondary/10 px-4 py-2 rounded-full">
                        <div class="w-2 h-2 bg-secondary rounded-full animate-pulse"></div>
                        <!--<span class="text-sm font-semibold text-primary">Africa #1 AI Powered Animal Health Platform</span>-->
<!--                        <span class="text-sm font-semibold text-primary whitespace-nowrap">-->
<!--  Africa #1 AI Powered Animal Health Platform-->
<!--</span>-->
<span class="block text-sm font-semibold text-primary whitespace-nowrap text-center mx-auto">
  Africa #1 AI Powered Animal Health Platform
</span>


                    </div>

                    <!-- Main Heading - Blue with Green Accent -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-tight">
                        <span class="text-primary">Livestock Health Made Simple: </span>
                        <!--<span class="text-secondary block mt-2">Real‑time vaccine and outbreak notifications for farmers and veterinary professionals.</span>-->
                        <!--<span class="text-primary">Vaccine & Outbreak Alerts</span>-->
                    </h1>

                    <!-- Subheading - Blue -->
                    <p class="text-xl text-primary/80 leading-relaxed">
Real‑time vaccine and outbreak notifications for farmers and veterinary professionals.                    </p>
                    <!--<p class="text-xl text-primary/80 leading-relaxed">-->
                    <!--    Revolutionary platform for farmers and animal health professionals. Get real-time alerts, track vaccinations, and protect your livestock with cutting-edge AI technology.-->
                    <!--</p>-->

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="#register" class="bg-secondary hover:bg-secondary/90 text-white px-8 py-4 rounded-full text-lg font-bold transition shadow-lg inline-flex items-center justify-center space-x-2 pulse-glow">
                            <span>Start Free Today</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="#how-it-works" class="border-2 border-primary text-primary px-8 py-4 rounded-full text-lg font-bold hover:bg-primary hover:text-white transition inline-flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Watch Demo</span>
                        </a>
                    </div>

                    <!-- Trust Indicators -->
<!--                    <div class="pt-6 border-t border-gray-200 overflow-x-auto">-->
<!--  <div class="flex justify-center items-center gap-6 whitespace-nowrap text-center px-4">-->
      
<!--      <div>-->
<!--          <div class="text-xl sm:text-3xl font-black text-secondary">0</div>-->
<!--          <div class="text-xs sm:text-sm text-primary">Active Farmers</div>-->
<!--      </div>-->

<!--      <div>-->
<!--          <div class="text-xl sm:text-3xl font-black text-secondary">0</div>-->
<!--          <div class="text-xs sm:text-sm text-primary">Active Professionals</div>-->
<!--      </div>-->

<!--      <div>-->
<!--          <div class="text-xl sm:text-3xl font-black text-secondary">0</div>-->
<!--          <div class="text-xs sm:text-sm text-primary">Total Livestock</div>-->
<!--      </div>-->

<!--  </div>-->
<!--</div>-->

                    <div class="flex flex-wrap items-center gap-8 pt-8 border-t border-gray-200 whitespace-nowrap">
                        <div class="text-center">
                            <div class="text-3xl font-black text-secondary" id="farmers-count">{{ number_format($stats['farmers'] ?? 0) }}</div>
                            <div class="text-sm text-primary">Active Farmers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-black text-secondary" id="professionals-count">{{ number_format($stats['professionals'] ?? 0) }}</div>
                            <div class="text-sm text-primary">Active Professionals</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-black text-secondary" id="livestock-count">{{ number_format($stats['livestock'] ?? 0) }}</div>
                            <div class="text-sm text-primary">Total Livestock</div>
                        </div>
                    </div>
                </div>

                <!-- Right Image/Illustration -->
                <div class="relative animate-float hidden lg:block">
                    <div class="relative z-10 bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                        <!-- Dashboard Preview Mock -->
                        <div class="space-y-4">
                            <!-- Alert Card -->
                            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 flex items-start space-x-3">
                                <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold text-red-900">Outbreak Alert</div>
                                    <div class="text-sm text-red-700">FMD detected in Kano - 15km away</div>
                                </div>
                            </div>

                            <!-- Vaccination Reminder -->
                            <div class="bg-blue-50 border-l-4 border-primary rounded-lg p-4 flex items-start space-x-3">
                                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold text-primary">Vaccination Due</div>
                                    <div class="text-sm text-primary/70">15 cattle need CBPP vaccine</div>
                                </div>
                            </div>

                            <!-- Success Card -->
                            <div class="bg-green-50 border-l-4 border-secondary rounded-lg p-4 flex items-start space-x-3">
                                <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold text-secondary">All Vaccinated</div>
                                    <div class="text-sm text-green-700">45 goats protected from PPR</div>
                                </div>
                            </div>

                            <!-- Chart Mock -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="text-sm font-semibold text-primary mb-4">Herd Health Score</div>
                                <div class="flex items-end space-x-2 h-32">
                                    <div class="flex-1 bg-secondary/30 rounded-t" style="height: 40%"></div>
                                    <div class="flex-1 bg-secondary/50 rounded-t" style="height: 60%"></div>
                                    <div class="flex-1 bg-secondary/70 rounded-t" style="height: 75%"></div>
                                    <div class="flex-1 bg-secondary rounded-t" style="height: 95%"></div>
                                    <div class="flex-1 bg-secondary rounded-t" style="height: 100%"></div>
                                </div>
                                <div class="text-2xl font-black text-primary mt-4">95% Healthy</div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-full h-full bg-gradient-to-br from-secondary/10 to-primary/10 rounded-3xl -z-10"></div>
                </div>

            </div>
        </div>
    </section>

    <!-- AI Badge Section -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-center gap-12">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Powered by</div>
                        <div class="font-bold text-primary">Advanced AI</div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Trusted by</div>
                        <div class="font-bold text-primary">50K+ Farmers</div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Coverage</div>
                        <div class="font-bold text-primary">Africa</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-2 bg-primary/10 rounded-full mb-4">
                    <span class="text-sm font-bold text-primary">POWERFUL FEATURES</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-primary mb-4">
                    Everything You Need to
                    <span class="text-secondary block">Protect Your Livestock</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Advanced AI technology meets practical farming needs. Real-time alerts, smart tracking, and expert support—all in one platform.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Feature Cards (keeping all 6 from original) -->
                <!-- Feature 1 -->
                <div class="card-hover bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary to-primary rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-3">Real-Time Outbreak Alerts</h3>
                    <p class="text-gray-600 leading-relaxed">
                        AI-powered disease detection sends instant notifications about outbreaks within your region. Stay ahead of threats with predictive analytics.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card-hover bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary to-primary rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-3">Smart Vaccination Tracking</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Never miss a vaccination schedule. Automated reminders, digital health records, and complete vaccination history at your fingertips.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card-hover bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary to-primary rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-3">AI Health Analytics</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Advanced data insights show herd health trends, disease patterns, and optimize your livestock management with intelligent recommendations.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="card-hover bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary to-primary rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-3">Expert Network Access</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Connect with verified veterinarians and animal health professionals. Get expert advice and emergency support when you need it most.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="card-hover bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary to-primary rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-3">Digital Health Records</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Complete digital documentation of your livestock. Store, access, and share health records, breeding info, and treatment history securely.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="card-hover bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary to-primary rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-3">Mobile-First Design</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Access from anywhere—farm, field, or home. Works perfectly on any device with offline capabilities for rural connectivity.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-2 bg-primary/10 rounded-full mb-4">
                    <span class="text-sm font-bold text-primary">SIMPLE PROCESS</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-primary mb-4">
                    Get Protected in
                    <span class="text-secondary">3 Easy Steps</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Join thousands of farmers protecting their livestock with AI technology
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                
                <!-- Connecting Lines (Desktop) -->
                <div class="hidden md:block absolute top-20 left-1/4 right-1/4 h-1 bg-gradient-to-r from-secondary to-primary -z-10"></div>

                <!-- Step 1 -->
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-gradient-to-br from-secondary to-primary rounded-full flex items-center justify-center text-3xl font-black text-white mx-auto mb-6 shadow-lg">
                        1
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-4">Create Your Account</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sign up as a farmer or animal health professional. Quick verification process gets you started in minutes.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-gradient-to-br from-secondary to-primary rounded-full flex items-center justify-center text-3xl font-black text-white mx-auto mb-6 shadow-lg">
                        2
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-4">Add Your Livestock</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Register your animals with essential details. Our AI starts monitoring and analyzing health data immediately.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-gradient-to-br from-secondary to-primary rounded-full flex items-center justify-center text-3xl font-black text-white mx-auto mb-6 shadow-lg">
                        3
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-4">Stay Protected</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Receive instant alerts, track vaccinations, and access expert support to keep your livestock healthy and productive.
                    </p>
                </div>

            </div>

            <!-- CTA -->
            <div class="text-center mt-16">
                <a href="#register" class="inline-flex items-center space-x-2 bg-secondary hover:bg-secondary/90 text-white px-10 py-5 rounded-full text-lg font-bold transition shadow-xl">
                    <span>Start Your Free Account</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section id="mission" class="py-24 bg-primary text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <!-- Mission -->
            <div class="mb-20">
                <div class="inline-block px-4 py-2 bg-secondary/20 rounded-full mb-6">
                    <span class="text-sm font-bold text-secondary">OUR MISSION</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black mb-8 leading-tight">
                    Transforming animal health into<br>
                    <span class="text-secondary">community strength and shared prosperity</span>
                </h2>
                <p class="text-xl text-gray-200 leading-relaxed max-w-3xl mx-auto">
                    We believe every farmer deserves access to cutting-edge technology. By combining AI, community support, and expert knowledge, we're building a future where livestock diseases are prevented, not just treated.
                </p>
            </div>

            <!-- Vision -->
            <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-12 border border-white/20">
                <div class="inline-block px-4 py-2 bg-secondary/20 rounded-full mb-6">
                    <span class="text-sm font-bold text-secondary">OUR VISION</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-black mb-6 leading-tight">
                    To be Africa's leading innovator in animal health
                </h2>
                <p class="text-xl text-gray-200 leading-relaxed">
                    Delivering breakthrough solutions, protecting animals, and transforming communities across the continent through technology and innovation.
                </p>
            </div>
        </div>
    </section>

    <!-- Registration Section -->
    <section id="register" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-2 bg-primary/10 rounded-full mb-4">
                    <span class="text-sm font-bold text-primary">GET STARTED</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-primary mb-4">
                    Choose Your Role &
                    <span class="text-secondary block">Start Protecting Today</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Join our community of farmers, professionals, and volunteers making a difference
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Farmer Card -->
                <div class="card-hover bg-white rounded-3xl p-8 border-2 border-primary relative overflow-hidden shadow-lg">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-secondary to-primary rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-primary mb-4">I'm a Farmer</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Protect your livestock with AI-powered alerts and smart health tracking
                        </p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Real-time outbreak alerts</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Vaccination reminders</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Digital health records</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Expert veterinary access</span>
                            </li>
                        </ul>
                        <a href="{{ route('register.farmer') }}" class="block w-full bg-secondary hover:bg-secondary/90 text-white text-center py-4 rounded-full font-bold transition shadow-lg">
                            Register as Farmer →
                        </a>
                        <div class="mt-4 text-center">
                            <span class="text-sm text-gray-500">Free forever • No credit card needed</span>
                        </div>
                    </div>
                </div>

                <!-- Health Professional Card -->
                <div class="card-hover bg-gradient-to-br from-primary to-primary/90 rounded-3xl p-8 border-2 border-primary transform md:scale-105 relative overflow-hidden shadow-2xl">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-secondary/10 to-transparent"></div>
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-secondary/20 rounded-full blur-3xl"></div>
                    <div class="relative z-10">
                        <div class="inline-block px-3 py-1 bg-secondary text-white text-xs font-black rounded-full mb-4">
                            MOST POPULAR
                        </div>
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-4">I'm a Professional</h3>
                        <p class="text-gray-200 mb-6 leading-relaxed">
                            Expand your practice and serve more farmers with digital tools
                        </p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Professional verification</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Access farmer databases</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Submit health reports</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Expand your network</span>
                            </li>
                        </ul>
                        <a href="{{ route('register.professional') }}" class="block w-full bg-secondary hover:bg-secondary/90 text-white text-center py-4 rounded-full font-bold transition shadow-xl">
                            Apply as Professional →
                        </a>
                        <div class="mt-4 text-center">
                            <span class="text-sm text-gray-300">Requires verification • Full access</span>
                        </div>
                    </div>
                </div>

                <!-- Volunteer Card -->
                <div class="card-hover bg-white rounded-3xl p-8 border-2 border-gray-200 relative overflow-hidden shadow-lg">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="inline-block px-3 py-1 bg-secondary/10 text-secondary text-xs font-black rounded-full mb-4">
                            INSTANT APPROVAL
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-secondary/20 to-primary/20 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-primary mb-4">I'm a Volunteer</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Help your community by enrolling farmers and spreading awareness
                        </p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Enroll new farmers</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Community outreach</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Track your impact</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-secondary mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-700">Make a difference</span>
                            </li>
                        </ul>
                        <a href="{{ route('register.volunteer') }}" class="block w-full bg-primary/10 hover:bg-primary/20 text-primary text-center py-4 rounded-full font-bold transition border-2 border-primary">
                            Join as Volunteer →
                        </a>
                        <div class="mt-4 text-center">
                            <span class="text-sm text-gray-500">No approval needed • Start today</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                
                <!-- About -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-extrabold">FarmVax</span>
                    </div>
                    <p class="text-gray-300 leading-relaxed mb-6">
                        Africa's #1 AI-powered livestock vaccine and outbreak alert platform. Protecting animals, transforming communities.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-gray-300 hover:text-secondary transition">Features</a></li>
                        <li><a href="#how-it-works" class="text-gray-300 hover:text-secondary transition">How It Works</a></li>
                        <li><a href="#mission" class="text-gray-300 hover:text-secondary transition">Mission & Vision</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-secondary transition">Sign In</a></li>
                    </ul>
                </div>

                <!-- Register -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Get Started</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('register.farmer') }}" class="text-gray-300 hover:text-secondary transition">Register as Farmer</a></li>
                        <li><a href="{{ route('register.professional') }}" class="text-gray-300 hover:text-secondary transition">Apply as Professional</a></li>
                        <li><a href="{{ route('register.volunteer') }}" class="text-gray-300 hover:text-secondary transition">Join as Volunteer</a></li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-white/10 pt-8 text-center">
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} FarmVax. All rights reserved. Protecting Africa's livestock with AI.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const openIcon = document.getElementById('menu-open');
            const closeIcon = document.getElementById('menu-close');
            
            menu.classList.toggle('hidden');
            openIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }

        // Smooth scroll
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
@include('partials.chatbot-bubble')

</body>
</html>