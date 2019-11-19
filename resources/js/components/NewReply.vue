<template>
<div>
    <div v-if="signedIn">
        <div class="form-group">
            <label for="body">Post here</label>
            <textarea placeholder="Have something to say ???" rows="2" name="body" id="body"
                      class="form-control"
                      v-model="body"
                      required
            ></textarea>
            <small id="bodyHelp" class="form-text text-muted">Please be polite.</small>
        </div>

        <button type="submit" class="btn btn-primary" @click="addReply()">
            Post
        </button>
    </div>

    <p class="text-center" v-else>
        Please <a href="/login">sign in</a> to participate in forum
    </p>
</div>
</template>

<script>
    export default {
        props: ['endpoint'],

        data() {
            return {
                body: '',
            }
        },

        computed: {
          signedIn() {
             return window.App.signedIn;
          }
        },

        methods: {
            addReply() {
                axios.post(this.endpoint, { body: this.body })
                    .then(({data}) => {
                        this.body = '';
                        flash('Your reply has been posted.');
                        this.$emit('created', data);
                    });
            }
        },
    }
</script>

<style scoped>

</style>
