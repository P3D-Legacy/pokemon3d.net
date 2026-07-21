import { Link } from '@inertiajs/react';
import type { ReactNode } from 'react';

import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { cn } from '@/lib/utils';
import { home } from '@/routes';

type Login7Props = {
    className?: string;
    children: ReactNode;
    footer?: ReactNode;
};

const Login7 = ({ className, children, footer }: Login7Props) => {
    return (
        <section className={cn('py-32', className)}>
            <div className="container">
                <div className="flex flex-col items-center gap-4">
                    <Card className="mx-auto w-full max-w-[380px]">
                        <CardHeader className="items-center justify-center">
                            <Link href={home()} className="flex items-center justify-center">
                                <img src="/img/pokemon3d_logo_sm.png" className="max-h-8" alt="Pokémon 3D" />
                            </Link>
                        </CardHeader>
                        <CardContent>{children}</CardContent>
                    </Card>
                    {footer}
                </div>
            </div>
        </section>
    );
};

export { Login7 };
