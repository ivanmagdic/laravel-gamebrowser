<template>
    <v-app>
        <v-app-bar
            app
            clipped-left
            color=""
        >
            <inertia-link :href="this.route('games')" class="title ml-3 mr-5 gb-home">Game Browser</inertia-link>

            <inertia-link :href="this.route('games', {platform: 'pc'})" class="subtitle-1 ml-3 mr-5">PC</inertia-link>
            <inertia-link :href="this.route('games', {platform: 'ps4'})" class="subtitle-1 ml-3 mr-5">PlayStation 4</inertia-link>
            <inertia-link :href="this.route('games', {platform: 'xbox-one'})" class="subtitle-1 ml-3 mr-5">Xbox One</inertia-link>

            <!--<SearchBar class="my-2" :search="search"></SearchBar>-->

            <v-spacer></v-spacer>
        </v-app-bar>

        <v-content>
            <v-container class="gb-container">
                <slot/>
            </v-container>
        </v-content>
    </v-app>
</template>

<!--<script>
    import SearchBar from "@/Components/SearchBar";

    export default {
        components: {
            SearchBar
        },
        props: [
            'search'
        ]
    }
</script>-->

