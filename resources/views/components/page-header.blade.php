@props([
    'title',
    'description' => null
])

<div class="mt-4 mb-3">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="flex-grow-1">
            <h2 class="heading-jira-2 mb-1">
                {{ $title }}
            </h2>
            @if($description)
                <p class="text-jira-muted mb-0">
                    {{ $description }}
                </p>
            @endif
        </div>
        @if($slot->isNotEmpty())
            <div class="d-flex flex-wrap gap-2 align-items-center">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
