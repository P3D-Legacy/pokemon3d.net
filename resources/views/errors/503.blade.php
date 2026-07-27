<x-error-page
    code="503"
    :title="__('Maintenance')"
    :message="__('We are performing a bit of upkeep. Please check back soon.')"
>
    <p class="text-sm text-white/80">
        @lang('Check our status page for more info'):
        <a href="https://status.pokemon3d.net" class="font-semibold text-gamejolt-green underline-offset-4 hover:underline">
            status.pokemon3d.net
        </a>
    </p>
</x-error-page>
