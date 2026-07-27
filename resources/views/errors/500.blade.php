<x-error-page
    code="500"
    :title="__('Server Error')"
    :message="__('Something went wrong on our end. Please try again later.')"
>
    @if (app()->bound('sentry') && app('sentry')->getLastEventId())
        <p class="text-sm text-white/70">
            @lang('Error ID'): {{ app('sentry')->getLastEventId() }}
        </p>
        <script>
            Sentry.init({ dsn: '{{ config('sentry.dsn') }}' });
            Sentry.showReportDialog({
                eventId: '{{ app('sentry')->getLastEventId() }}',
                user: {
                    name: '{{ auth()->user()->name ?? '' }}',
                    email: '{{ auth()->user()->email ?? '' }}',
                },
            });
        </script>
    @endif
</x-error-page>
