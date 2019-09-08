<template>

</template>

<script>
    import Replies from '../components/Replies'
    export default {
        props: ['initialRepliesCount'],
        comments: {Replies},

        data() {
            return {
                repliesCount: this.initialRepliesCount,
            }
        }
    }
</script>

<style scoped>

</style>
