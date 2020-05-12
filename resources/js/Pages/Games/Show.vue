<template>
    <Layout>
        <v-card class="gb-game-show-card pa-2" width="100%">
        <v-row justify="space-around">
            <v-col cols="6">
                <v-img :src="game.image" aspect-ratio="2" contain></v-img>
                <div class="text-center my-2" v-if="game.rating">
                    <v-rating v-model="game.rating" :readonly="true"></v-rating>
                </div>
            </v-col>
            <v-col cols="6">
                <div class="title mb-5">{{ game.name}}</div>

                <div class="mb-2" v-if="game.release_date">
                    <div class="subheading font-weight-bold">
                        Release date
                    </div>
                    <span>
                            {{ game.release_date}}
                        </span>
                </div>

                <div class="mb-2" v-if="game.summary">
                    <div class="subheading font-weight-bold">
                        Summary
                    </div>
                    <span>
                            {{ game.summary}}
                        </span>
                </div>

                <div class="mb-2" v-if="game.storyline">
                    <div class="subheading font-weight-bold">
                        Storyline
                    </div>
                    <span>
                            {{ game.storyline}}
                        </span>
                </div>

                <div class="mb-2" v-if="game.platforms">
                    <div class="subheading font-weight-bold">
                        Platforms
                    </div>
                    <span v-for="(platform, index) in game.platforms">
                    <span v-if="index !== 0">, </span><span>{{ platform.name }}</span>
                  </span>
                </div>

                <div class="mb-2" v-if="game.genres">
                    <div class="subheading font-weight-bold">
                        Genres
                    </div>
                    <span v-for="(genre, index) in game.genres">
                    <span v-if="index !== 0">, </span><span>{{ genre.name }}</span>
                  </span>
                </div>

            </v-col>
        </v-row>
        </v-card>
    </Layout>
</template>

<script>
    import Layout from '@/Components/Layout'

    export default {
        components: {Layout},
        props: [
            'game'
        ]
    }

</script>
