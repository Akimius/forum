<template>
    <div>
    <div :id="'reply-'+id" class="card mb-3">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + data.owner.name"
                    v-text="data.owner.name">

                    </a> said <span v-text="ago"></span>
                </h5>
<!--                @if(auth()->check())-->
                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>
<!--                @endif-->
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-sm btn-primary">Update</button>
                    <button class="btn btn-sx btn-link" @click="editing = false" type="button">Cancel</button>
                </form>
            </div>
            <div v-else v-text="body"></div>
        </div>

<!--        @can('update', $reply)-->
        <div class="card-footer level" v-if="canUpdate">
            <button class="btn btn-secondary btn-sm mr-2" @click="editing = true">Edit</button>
            <button class="btn btn-danger btn-sm mr-2" @click="destroy()">DELETE</button>
        </div>
<!--        @endcan-->
    </div>
    </div>
</template>
<script>

    import Favorite from './Favorite.vue';
    import Replies from './Replies.vue';
    import moment from 'moment';

    export default {

        props: ['data'],

        components: {Favorite, Replies},

        computed: {
          signedIn() {
              return window.App.signedIn;
          },
            ago() {
              return moment(this.data.created_at).fromNow() + '...';
            },

            canUpdate() {
              return this.authorize(user => this.data.user_id === user.id);
            }
        },
        data() {
            return {

                editing: false,
                id: this.data.id,
                body: this.data.body
            };
        },

        methods: {
            update() {
                axios
                    .patch('/replies/' + this.data.id, {
                    body: this.body
                })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
                ;

                this.editing = false;

                flash('Updated reply')
            },

            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);
                // $(this.$el).fadeOut(1000, () => {
                //     flash('Your reply has been deleted.');
                // });
            }
        }
    }
</script>

<style scoped>

</style>
