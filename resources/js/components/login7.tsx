import { Link } from '@inertiajs/react';
import type { ReactNode } from 'react';

import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { cn } from '@/lib/utils';
import { home } from '@/routes';

type Login7Props = {
    className?: string;
    contentClassName?: string;
    children: ReactNode;
    footer?: ReactNode;
};

const Login7 = ({ className, contentClassName, children, footer }: Login7Props) => {
    return (
        <section className={cn('flex min-h-svh flex-col items-center justify-center px-4 py-8', className)}>
            <div className={cn('flex w-full max-w-[380px] flex-col items-center gap-4', contentClassName)}>
                <Card className="w-full">
                    <CardHeader className="items-center justify-center">
                        <Link href={home()} className="flex items-center justify-center">
                            <img src="/img/pokemon3d_logo_sm.png" className="max-h-8" alt="Pokémon 3D" />
                        </Link>
                    </CardHeader>
                    <CardContent>{children}</CardContent>
                </Card>
                {footer}
            </div>
        </section>
    );
};

export { Login7 };
