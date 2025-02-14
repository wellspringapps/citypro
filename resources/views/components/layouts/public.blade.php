<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        @if(isset($description))
            <meta name="description" content=" {{ $description }}" />
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @fluxStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .hover-scale:hover {
              transform: scale(1.05);
              transition: transform 0.3s ease;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased min-h-screen">
        <div class="flex flex-col justify-between min-h-screen">
            <div>
                <header class="bg-white " x-data='{ menuOpen: false }'>
                    <div class="container mx-auto">
                        <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                            <div class="flex lg:flex-1">
                                <a href="/" class="-m-1.5 p-1.5">
                                    <span class="sr-only">Your Company</span>
                                    <img class="h-8 w-auto" src="/ccp-logo.PNG" alt="">
                                </a>
                            </div>
                            <div class="flex lg:hidden" >
                                <button @click="menuOpen = true" type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                                    <span class="sr-only">Open main menu</span>
                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                    </svg>
                                </button>
                            </div>
                            <div class="hidden lg:flex lg:gap-x-12">
                                <a href="/search" class="text-sm/6 font-semibold text-gray-900">Search Pros</a>
                                <a href="/claim-listing" class="text-sm/6 font-semibold text-gray-900">Claim Your Listing</a>
                            </div>
                            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                                @auth
                                    <a href="{{ route('portal.index') }}" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Dashboard</a>
                                @else
                                    <a href="/login" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Log in</a>
                                @endauth
                            </div>
                        </nav>
                        <!-- Mobile menu, show/hide based on menu open state. -->
                        <div class="lg:hidden" role="dialog" aria-modal="true"  x-cloak x-show="menuOpen">
                            <!-- Background backdrop, show/hide based on slide-over state. -->
                            <div class="fixed inset-0 z-10"></div>
                            <div class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                                <div class="flex items-center justify-between">
                                    <a href="#" class="-m-1.5 p-1.5">
                                        <span class="sr-only">Your Company</span>
                                        <img class="h-8 w-auto" src="/ccp-logo.PNG" alt="">
                                    </a>
                                    <button @click="menuOpen = false" type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                                        <span class="sr-only">Close menu</span>
                                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-6 flow-root">
                                    <div class="-my-6 divide-y divide-gray-500/10">
                                        <div class="space-y-2 py-6">
                                            <a href="/search" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Searh Pros</a>
                                            <a href="/claim-listing" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Claim Your Listing</a>
                                        </div>
                                        <div class="py-6">
                                            @auth
                                                <a href="{{ route('portal.index') }}" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Dashboard</a>
                                            @else
                                                <a href="/login" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Log in</a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <main>
                    {{ $slot }}
                </main>
            </div>

            <footer class="bg-white">
                <div class="container mx-auto px-6 py-12 md:flex md:items-center md:justify-between lg:px-8">
                <div class="flex justify-center gap-x-6 md:order-2">
                    <a href="https://www.facebook.com/people/CircleCityPro/61573290895755/" target="_blank" class="text-gray-600 hover:text-gray-800">
                    <span class="sr-only">Facebook</span>
                    <svg class="size-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                    </svg>
                    </a>
                    <a href="https://www.instagram.com/circlecitypro/" target="_blank" class="text-gray-600 hover:text-gray-800">
                    <span class="sr-only">Instagram</span>
                    <svg class="size-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                    </svg>
                    </a>
                </div>
                <p class="mt-8 text-center text-sm/6 text-gray-600 md:order-1 md:mt-0">&copy; {{ now()->format('Y') }} Wellspring Apps, LLC - All rights reserved.</p>
                </div>
            </footer>
        </div>
        @persist('toast')
            <flux:toast position="bottom right"/>
        @endpersist
        @fluxScripts

        @if(!auth()->user() || auth()->user()->role != 'admin')
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-NJYEKLZEGY"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', 'G-NJYEKLZEGY');
            </script>
            <script
            src="https://js.sentry-cdn.com/ad9b5f2c9206a2839ff9e52956a8d918.min.js"
            crossorigin="anonymous"
          ></script>
        @endif
        
    </body>
</html>