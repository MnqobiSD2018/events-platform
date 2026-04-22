<nav x-data="{ open: false }" class="sticky top-0 z-30 border-b border-white/60 bg-white/75 backdrop-blur">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-teal-700" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @if (Auth::user()->isCompanyAdmin())
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                                {{ __('Events') }}
                            </x-nav-link>
                            <x-nav-link :href="route('checkin.scanner')" :active="request()->routeIs('checkin.scanner')">
                                {{ __('Scanner') }}
                            </x-nav-link>
                        @endif

                        @if (Auth::user()->isEmployee())
                            <x-nav-link :href="route('employee.home')" :active="request()->routeIs('employee.home')">
                                {{ __('Home') }}
                            </x-nav-link>
                            <x-nav-link :href="route('employee.activities.index')" :active="request()->routeIs('employee.activities.*')">
                                {{ __('Activity') }}
                            </x-nav-link>
                            <x-nav-link :href="route('employee.integrations.index')" :active="request()->routeIs('employee.integrations.*')">
                                {{ __('Integrations') }}
                            </x-nav-link>
                            <x-nav-link :href="route('employee.announcements.index')" :active="request()->routeIs('employee.announcements.*')">
                                {{ __('Announcements') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Account Controls -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:gap-3">
                @auth
                    <span class="text-sm font-medium text-slate-700">{{ Auth::user()->name }}</span>

                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-lg border border-teal-100 bg-teal-50/70 px-3 py-2 text-sm font-medium text-teal-900 hover:bg-teal-100/80 transition ease-in-out duration-150">
                        {{ __('Profile') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 hover:bg-rose-100 transition ease-in-out duration-150">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg border border-teal-100 bg-teal-50/70 px-3 py-2 text-sm font-medium text-teal-900 hover:bg-teal-100/80 transition ease-in-out duration-150">
                        {{ __('Log In') }}
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-slate-500 hover:bg-teal-50 hover:text-teal-700 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if (Auth::user()->isCompanyAdmin())
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                        {{ __('Events') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('checkin.scanner')" :active="request()->routeIs('checkin.scanner')">
                        {{ __('Scanner') }}
                    </x-responsive-nav-link>
                @endif

                @if (Auth::user()->isEmployee())
                    <x-responsive-nav-link :href="route('employee.home')" :active="request()->routeIs('employee.home')">
                        {{ __('Home') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('employee.activities.index')" :active="request()->routeIs('employee.activities.*')">
                        {{ __('Activity') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('employee.integrations.index')" :active="request()->routeIs('employee.integrations.*')">
                        {{ __('Integrations') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('employee.announcements.index')" :active="request()->routeIs('employee.announcements.*')">
                        {{ __('Announcements') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-slate-900">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm font-medium text-rose-700 transition duration-150 ease-in-out hover:bg-rose-50">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log In') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
