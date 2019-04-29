<template>
    <button type="submit" :class="classes" @click="toggle">
        <span><i class="fas fa-heart"></i></span>
        <span v-text="favoritesCount"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],

        data() {
            return {
                favoritesCount: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },

        computed: {
          classes() {
              return ['btn', this.isFavorited ? 'btn-primary' : 'btn-default']
          },
            endpoint() {
              return '/replies/' + this.reply.id + '/favorites';
            }
        },

        methods: {
            toggle() {
                return this.isFavorited ? this.unfavorite() : this.favorite()
            },
                favorite() {
                    axios.post(this.endpoint);
                    this.isFavorited = true;
                    this.favoritesCount++;
                },
                unfavorite() {
                    axios.delete(this.endpoint);
                    this.isFavorited = false;
                    this.favoritesCount--;
                }
        }
    }
</script>

<style scoped>

</style>