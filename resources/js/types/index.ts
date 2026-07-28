export type User = {
    id: number;
    name: string;
    username: string;
    email: string;
    profile_photo_url?: string;
    email_verified_at?: string | null;
    unread_notifications_count?: number;
    is_admin?: boolean;
};

export type TermsAcceptance = {
    required: boolean;
    key: string;
    text: string;
};

export type SharedPageProps = {
    auth: {
        user: User | null;
    };
    termsAcceptance: TermsAcceptance | null;
    flash: {
        status?: string | null;
        error?: string | null;
        success?: string | null;
        warning?: string | null;
        banner?: string | null;
        bannerStyle?: string | null;
        token?: string | null;
    };
    appName: string;
    locale: string;
    env: string;
    languages: {
        current: string;
        current_name: string;
        current_flag: string;
        options: Array<{
            code: string;
            name: string;
            flag: string;
            url: string;
        }>;
        contribute_url: string;
        contribute_label: string;
    };
    translations: Record<string, string>;
    socialLogin: {
        discord: boolean;
        twitch: boolean;
        gamejolt: boolean;
        xenforo: boolean;
    };
};

export type HomeStat = {
    key: string;
    label: string;
    value: string;
    hint?: string | null;
};

export type PostCard = {
    uuid: string;
    slug: string;
    title: string;
    excerpt: string;
    sticky: boolean;
    published_at: string;
    published_for_humans: string;
    reading_time: string;
    comment_count: string;
    view_count: string;
    tag?: string | null;
    author: {
        username: string;
        profile_photo_url: string;
    };
    url: string;
};

export type SkinCardData = {
    uuid: string;
    name: string;
    public: boolean;
    owner_id: number;
    image_url: string;
    file_size: string;
    likes_count: number;
    liked: boolean;
    is_owner: boolean;
    uploaded_at: string;
    publisher: { username: string; url: string } | null;
    show_url: string | null;
};

export type Paginated<T> = {
    data: T[];
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    current_page?: number;
    last_page?: number;
    per_page?: number;
    total?: number;
    meta?: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
};
