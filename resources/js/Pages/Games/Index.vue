<template>
    <Layout :search="search">
        <SearchBar class="my-2" :search="search"></SearchBar>

        <div class="title text-uppercase grey--text text--darken-2 mt-6" v-if="search === ''">
            POPULAR GAMES
        </div>

        <v-row>
            <v-col
                v-for="game in games"
                :key="game.id"
                :cols="6"
                :lg="3"
                :md="4"
                :sm="4">
                <GameCard
                    v-bind:game="game"
                >
                </GameCard>
            </v-col>
        </v-row>
    </Layout>
</template>

<script>
    import Layout from '@/Components/Layout'
    import GameCard from "@/Components/GameCard";
    import SearchBar from "@/Components/SearchBar";

    export default {
        components: {Layout, GameCard, SearchBar},
        props: [
            'games',
            'search'
        ]
    }

</script>
