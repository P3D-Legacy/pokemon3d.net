import { Link, router, usePage } from '@inertiajs/react';
import { useState } from 'react';

import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { logout } from '@/routes';
import { acceptRequired } from '@/routes/profile/consents';
import { show as policyShow } from '@/routes/policy';
import { show as termsShow } from '@/routes/terms';
import type { SharedPageProps } from '@/types';

export function TermsAcceptanceDialog() {
    const { termsAcceptance } = usePage<SharedPageProps>().props;
    const [processing, setProcessing] = useState(false);

    if (!termsAcceptance?.required) {
        return null;
    }

    const accept = () => {
        setProcessing(true);

        router.post(
            acceptRequired.url(),
            {},
            {
                preserveScroll: true,
                onFinish: () => setProcessing(false),
            },
        );
    };

    return (
        <Dialog open>
            <DialogContent
                showCloseButton={false}
                className="sm:max-w-md"
                onEscapeKeyDown={(event) => event.preventDefault()}
                onInteractOutside={(event) => event.preventDefault()}
                onPointerDownOutside={(event) => event.preventDefault()}
            >
                <DialogHeader>
                    <DialogTitle>Updated terms required</DialogTitle>
                    <DialogDescription>
                        Please review and accept our updated Terms and Conditions and Privacy Policy to continue using the
                        website.
                    </DialogDescription>
                </DialogHeader>

                <p
                    className="text-sm text-muted-foreground"
                    dangerouslySetInnerHTML={{ __html: termsAcceptance.text }}
                />

                <p className="text-sm text-muted-foreground">
                    Read the{' '}
                    <a
                        href={termsShow.url()}
                        target="_blank"
                        rel="noreferrer"
                        className="underline underline-offset-3 hover:text-foreground"
                    >
                        Terms and Conditions
                    </a>{' '}
                    and{' '}
                    <a
                        href={policyShow.url()}
                        target="_blank"
                        rel="noreferrer"
                        className="underline underline-offset-3 hover:text-foreground"
                    >
                        Privacy Policy
                    </a>
                    .
                </p>

                <DialogFooter className="sm:justify-between">
                    <Link
                        href={logout.url()}
                        method="post"
                        as="button"
                        className="inline-flex h-8 items-center justify-center px-2.5 text-xs font-medium text-muted-foreground underline-offset-4 hover:underline"
                    >
                        Log out
                    </Link>
                    <Button type="button" onClick={accept} disabled={processing}>
                        {processing ? 'Saving…' : 'I accept'}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
