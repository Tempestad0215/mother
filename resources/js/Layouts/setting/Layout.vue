<script setup lang="ts">
import { toUrl } from '@/lib/utils';
import UserProfileController from '@/actions/Laravel/Jetstream/Http/Controllers/Inertia/UserProfileController';
import { NavItem } from '@/types/navigation';
import { useCurrentUrl } from '@/Composable/useCurrentUrl';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: UserProfileController.show(),
    },
    // {
    //     title: 'Security',
    //     href: showSecurity(),
    // },
    // {
    //     title: 'Appearance',
    //     href: editAppearance(),
    // },
];

const { isCurrentOrParentUrl } = useCurrentUrl();
</script>

<template>
    <div class="px-4 py-6">
        <Heading
            title="Settings"
            description="Manage your profile and account settings"
        />

        <div class="flex flex-col lg:flex-row lg:space-x-12">
            <aside class="w-full max-w-xl lg:w-48">
                <nav
                    class="flex flex-col space-y-1 space-x-0"
                    aria-label="Settings"
                >
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="toUrl(item.href)"
                        variant="ghost"
                        :class="[
                            'w-full justify-start',
                            { 'bg-muted': isCurrentOrParentUrl(item.href) },
                        ]"
                        as-child
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" class="h-4 w-4" />
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <Separator class="my-6 lg:hidden" />

            <div class="flex-1">
                <section class="space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
