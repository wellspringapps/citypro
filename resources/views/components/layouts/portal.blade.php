<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

$logout = function(){
    Auth::guard('web')->logout();

    Session::invalidate();
    Session::regenerateToken();

    Flux::toast('Thank you for using CircleCity.Pro');

    $this->redirect('/', navigate: true);
}
?>
@props(['mainClassOverride' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
    
            <div class="w-3/4">
                <x-application-logo />
            </div>
                
            <flux:navlist variant="outline">
                <flux:navlist.item icon="computer-desktop" :href="route('public.listing', ['listing' => auth()->user()->listing])">View My Listing</flux:navlist.item>
                <flux:separator variant="subtle" class="my-4"/>
                <flux:navlist.item icon="home" :href="route('portal.index')">My Listing</flux:navlist.item>
                <flux:navlist.item icon="star" :href="route('portal.reviews')">Reviews</flux:navlist.item>
                @if(auth()->user()->listing)
                    @if(auth()->user()->listing->pro)
                        <flux:navlist.item icon="at-symbol" :href="route('portal.messages')">Messages </flux:navlist.item>
                        <flux:navlist.item icon="percent-badge" :href="route('portal.coupons')">Coupons</flux:navlist.item>
                        <flux:navlist.item icon="calendar" :href="route('portal.events')">Events</flux:navlist.item>
                    @endif
                @endif

                @if(auth()->user()->role == 'admin')
                    <flux:navlist.group expandable heading="Admin">

                        <flux:navlist.item href="/admin/claim">Claim Listing</flux:navlist.item>
                        <flux:navlist.item href="/admin/listings">Listings</flux:navlist.item>
                    </flux:navlist.group>
                @endif
            </flux:navlist>
    
            <flux:spacer />
    
    
            @volt
            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                <flux:profile avatar="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&color=FFFFFF&background=1B1C20" :name="auth()->user()->name"/>

                <flux:menu>
                
                    <flux:menu.item icon="arrow-right-start-on-rectangle" wire:click="logout">Logout</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
            @endvolt
        </flux:sidebar>
    
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            
    
            <flux:spacer />
    
            @volt
            <flux:dropdown position="top" alignt="end">
                <flux:profile avatar="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&color=FFFFFF&background=1B1C20" />

                
                <flux:menu>
                    <flux:menu.item icon="arrow-right-start-on-rectangle" wire:click="logout">Logout</flux:menu.item>
                </flux:menu>

            </flux:dropdown>
            @endvolt
        </flux:header>
    
        <flux:main :class="$mainClassOverride">
           {{ $slot }}
        </flux:main>
    
        @persist('toast')
            <flux:toast  position="bottom right"/>
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
        @endif
    </body>
</html>