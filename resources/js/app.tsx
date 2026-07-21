import { createInertiaApp } from '@inertiajs/react';

import AppLayout from '@/layouts/app-layout';
import AuthLayout from '@/layouts/auth-layout';
import GuestLayout from '@/layouts/guest-layout';

const appName = import.meta.env.VITE_APP_NAME || 'Pokémon 3D';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'auth/login':
            case name === 'auth/register':
                return undefined;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name === 'home' || name === 'legal' || name === 'contact' || name === 'terms' || name === 'policy':
                return GuestLayout;
            default:
                return AppLayout;
        }
    },
    strictMode: true,
    progress: {
        color: '#16a34a',
    },
});
