declare module '*.mdx' {
    import type { ComponentType } from 'react';

    const MDXComponent: ComponentType<Record<string, unknown>>;

    export default MDXComponent;
}

declare module '@markdown/*.mdx' {
    import type { ComponentType } from 'react';

    const MDXComponent: ComponentType<Record<string, unknown>>;

    export default MDXComponent;
}
