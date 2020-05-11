<template>
    <inertia-link
        :href="route('games.show', game.id)"
    >
        <v-card>
            <v-img
                height="300px"
                :src="game.image"
            >
            </v-img>

            <v-card-title class="pb-0">{{ game.name }}</v-card-title>

            <v-card-text class="text--primary">
                <div v-if="game.first_release_date"> {{ game.first_release_date }}</div>
                <div v-if="game.platforms">
                  <span v-for="(platform, index) in game.platforms">
                    <span v-if="index !== 0">, </span><span>{{ platform.name }}</span>
                  </span>
                </div>
                <div class="text-center" v-if="game.rating">
                    <v-rating v-model="game.rating" :readonly="true"></v-rating>
                </div>
            </v-card-text>
        </v-card>
    </inertia-link>
</template>

<script>
    export default {
        props: {
            game: Object
        }
    }

</script>
