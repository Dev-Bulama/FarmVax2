<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - FarmVax</title>
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
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Include Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 overflow-y-auto">
            
            <!-- Top Bar (Optional) -->
            <header class="bg-white shadow-sm sticky top-0 z-20">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Admin Panel')</h1>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>

        </div>

    </div>

    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>