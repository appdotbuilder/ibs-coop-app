import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Indospace Bersama Sejahtera - Cooperative Management System">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
                {/* Header */}
                <header className="relative">
                    <nav className="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
                        <div className="flex items-center gap-2">
                            <div className="h-10 w-10 rounded-lg bg-indigo-600 flex items-center justify-center">
                                <span className="text-white font-bold text-lg">🏢</span>
                            </div>
                            <div>
                                <h1 className="text-xl font-bold text-gray-900 dark:text-white">IBS</h1>
                                <p className="text-xs text-gray-600 dark:text-gray-400">Cooperative</p>
                            </div>
                        </div>
                        <div className="flex items-center gap-4">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition-colors"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors"
                                    >
                                        Register
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                {/* Hero Section */}
                <main className="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                    <div className="mx-auto max-w-2xl text-center">
                        <h1 className="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl dark:text-white">
                            🤝 Indospace Bersama Sejahtera
                        </h1>
                        <p className="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                            Complete cooperative management system for member registration, financial transactions, 
                            inventory management, and point-of-sale operations with installment support.
                        </p>
                        <div className="mt-10 flex items-center justify-center gap-x-6">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="rounded-lg bg-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all"
                                >
                                    Go to Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('register')}
                                        className="rounded-lg bg-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all"
                                    >
                                        Get Started
                                    </Link>
                                    <Link
                                        href={route('login')}
                                        className="text-base font-semibold leading-6 text-gray-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400 transition-colors"
                                    >
                                        Sign In <span aria-hidden="true">→</span>
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>

                    {/* Features Grid */}
                    <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                        <dl className="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                            {/* Member Management */}
                            <div className="flex flex-col bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                                <dt className="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                    <div className="h-10 w-10 flex items-center justify-center rounded-lg bg-blue-600">
                                        <span className="text-white">👥</span>
                                    </div>
                                    Member Management
                                </dt>
                                <dd className="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-300">
                                    <p className="flex-auto">
                                        Complete member registration and management system with share capital, 
                                        savings tracking, and loyalty points program.
                                    </p>
                                    <div className="mt-4">
                                        <div className="flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400">
                                            <span>✓</span> Member registration & profiles
                                        </div>
                                        <div className="flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400">
                                            <span>✓</span> Share capital management
                                        </div>
                                        <div className="flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400">
                                            <span>✓</span> Savings & points tracking
                                        </div>
                                    </div>
                                </dd>
                            </div>

                            {/* POS & Sales */}
                            <div className="flex flex-col bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                                <dt className="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                    <div className="h-10 w-10 flex items-center justify-center rounded-lg bg-green-600">
                                        <span className="text-white">🛒</span>
                                    </div>
                                    Point of Sale
                                </dt>
                                <dd className="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-300">
                                    <p className="flex-auto">
                                        Modern POS system with member pricing, points redemption, 
                                        and flexible installment payment options.
                                    </p>
                                    <div className="mt-4">
                                        <div className="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                                            <span>✓</span> Real-time inventory tracking
                                        </div>
                                        <div className="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                                            <span>✓</span> Member special pricing
                                        </div>
                                        <div className="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                                            <span>✓</span> Installment payments
                                        </div>
                                    </div>
                                </dd>
                            </div>

                            {/* Financial Management */}
                            <div className="flex flex-col bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                                <dt className="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                    <div className="h-10 w-10 flex items-center justify-center rounded-lg bg-purple-600">
                                        <span className="text-white">📊</span>
                                    </div>
                                    Financial Management
                                </dt>
                                <dd className="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-300">
                                    <p className="flex-auto">
                                        Complete financial system with double-entry bookkeeping, 
                                        automated journal entries, and Indonesian cooperative compliance.
                                    </p>
                                    <div className="mt-4">
                                        <div className="flex items-center gap-2 text-sm text-purple-600 dark:text-purple-400">
                                            <span>✓</span> Journal & ledger management
                                        </div>
                                        <div className="flex items-center gap-2 text-sm text-purple-600 dark:text-purple-400">
                                            <span>✓</span> SHU distribution tracking
                                        </div>
                                        <div className="flex items-center gap-2 text-sm text-purple-600 dark:text-purple-400">
                                            <span>✓</span> Financial reporting
                                        </div>
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {/* Role-based Access */}
                    <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24">
                        <div className="text-center">
                            <h2 className="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">
                                🔐 Role-Based Access Control
                            </h2>
                            <p className="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                                Three distinct user roles with appropriate permissions and interfaces
                            </p>
                        </div>
                        <div className="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <div className="rounded-lg bg-orange-50 dark:bg-orange-900/20 p-6 text-center">
                                <div className="text-2xl mb-2">👔</div>
                                <h3 className="font-semibold text-orange-900 dark:text-orange-200">Officer</h3>
                                <p className="text-sm text-orange-700 dark:text-orange-300 mt-2">
                                    Full system access, member management, financial operations
                                </p>
                            </div>
                            <div className="rounded-lg bg-blue-50 dark:bg-blue-900/20 p-6 text-center">
                                <div className="text-2xl mb-2">⚙️</div>
                                <h3 className="font-semibold text-blue-900 dark:text-blue-200">Pengelola</h3>
                                <p className="text-sm text-blue-700 dark:text-blue-300 mt-2">
                                    Inventory management, sales operations, basic reporting
                                </p>
                            </div>
                            <div className="rounded-lg bg-green-50 dark:bg-green-900/20 p-6 text-center">
                                <div className="text-2xl mb-2">👤</div>
                                <h3 className="font-semibold text-green-900 dark:text-green-200">Pelanggan</h3>
                                <p className="text-sm text-green-700 dark:text-green-300 mt-2">
                                    View personal transactions, points balance, installment status
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* CTA Section */}
                    <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24">
                        <div className="rounded-2xl bg-gray-900 px-6 py-16 text-center shadow-2xl sm:px-16">
                            <h2 className="mx-auto max-w-2xl text-3xl font-bold tracking-tight text-white sm:text-4xl">
                                Ready to modernize your cooperative?
                            </h2>
                            <p className="mx-auto mt-6 max-w-xl text-lg leading-8 text-gray-300">
                                Join hundreds of cooperatives using our system to streamline operations, 
                                improve member satisfaction, and ensure regulatory compliance.
                            </p>
                            <div className="mt-10 flex items-center justify-center gap-x-6">
                                {auth.user ? (
                                    <Link
                                        href={route('dashboard')}
                                        className="rounded-lg bg-white px-6 py-3 text-base font-semibold text-gray-900 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition-all"
                                    >
                                        Access Dashboard
                                    </Link>
                                ) : (
                                    <Link
                                        href={route('register')}
                                        className="rounded-lg bg-white px-6 py-3 text-base font-semibold text-gray-900 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition-all"
                                    >
                                        Start Free Trial
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>
                </main>

                {/* Footer */}
                <footer className="mx-auto max-w-7xl px-6 py-12 md:flex md:items-center md:justify-between lg:px-8">
                    <div className="flex justify-center space-x-6 md:order-2">
                        <p className="text-xs leading-5 text-gray-500 dark:text-gray-400">
                            Built with modern technology for Indonesian cooperatives
                        </p>
                    </div>
                    <div className="mt-8 md:order-1 md:mt-0">
                        <p className="text-center text-xs leading-5 text-gray-500 dark:text-gray-400">
                            &copy; 2024 Indospace Bersama Sejahtera. Empowering cooperatives with technology.
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}