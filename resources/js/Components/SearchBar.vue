<template>
    <v-text-field
        solo-inverted
        flat
        hide-details
        label="Search"
        prepend-inner-icon="mdi-magnify"
        v-model="form.filter"
        @input="filterGames"
    ></v-text-field>
</template>

<script>
    import pickBy from "lodash/pickBy";
    import throttle from "lodash/throttle";
    import mapValues from "lodash/mapValues";

    export default {
        props: [
            'search'
        ],
        data() {
            return {
                form: {
                    filter: this.search
                }
            }
        },
        methods: {
            filterGames: throttle(function () {
                let query = pickBy(this.form)
                this.$inertia.replace(this.route('games', Object.keys(query).length ? query : {}))
            }, 450),
            reset() {
                this.form = mapValues(this.form, () => null)
            },
        }
    }

</script>
