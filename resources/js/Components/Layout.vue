<template>
    <v-app>
        <v-navigation-drawer
            v-model="drawer"
            app
            clipped
        >
            <v-list dense>
                <inertia-link :href="this.route('games')">
                    <v-list-item link>
                        <v-list-item-action>
                            <v-icon>mdi-view-dashboard fa-fw</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Most Anticipated</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </inertia-link>
                <inertia-link :href="this.route('games', {platform: 'pc'})">
                    <v-list-item link>
                        <v-list-item-action>
                            <v-icon>fas fa-desktop fa-fw</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Popular PC Games</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </inertia-link>
                <inertia-link :href="this.route('games', {platform: 'ps4'})">
                    <v-list-item link>
                        <v-list-item-action>
                            <v-icon>fab fa-playstation fa-fw</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Popular PS4 Games</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </inertia-link>
                <inertia-link :href="this.route('games', {platform: 'xbox-one'})">
                    <v-list-item link>
                        <v-list-item-action>
                            <v-icon>fab fa-xbox fa-fw</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Popular Xbox One Games</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </inertia-link>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar
            app
            clipped-left
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>Game Browser</v-toolbar-title>
        </v-app-bar>

        <v-content>
            <v-container
                fluid
            >
                <slot/>
            </v-container>
        </v-content>

        <v-footer app>
            <span>&copy; 2020</span>
        </v-footer>
    </v-app>
</template>

<script>
    export default {
        props: {
            source: String,
        },

        data: () => ({
            drawer: null,
        }),

        created() {
            this.$vuetify.theme.dark = true
        },
    }
</script>
