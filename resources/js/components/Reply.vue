<template>
    <div>
    <div :id="'reply-'+id" class="card mb-3">
        <div class="card-header"
             :class="isBest ? 'bg-warning' : ''"
        >
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + reply.owner.name"
                    v-text="reply.owner.name">

                    </a> said <span v-text="ago"></span>
                </h5>
<!--                @if(auth()->check())-->
                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
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
            <div v-else v-html="body"></div>
        </div>

<!--        @can('update', $reply)-->
        <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">

            <div v-if="authorize('owns', reply)" >
                <button class="btn btn-secondary btn-sm mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-sm mr-2" @click="destroy()">DELETE</button>
            </div>
            <div class="ml-auto">
                <button class="btn btn-primary btn-sm " @click="markBestReply()"
                        v-if="authorize('owns', reply.thread)">
                    Best Reply?
                </button>
            </div>
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

        props: ['reply'],

        components: {Favorite, Replies},

        computed: {
          // signedIn() {
          //     return window.App.signedIn;
          // },
            ago() {
              return moment(this.reply.created_at).fromNow() + '...';
            },

            // canUpdate() {
            //   return this.authorize(user => this.reply.user_id === user.id);
            // }
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },

        data() {
            return {
                editing: false,
                id: this.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
            };
        },

        methods: {
            update() {
                axios
                    .patch('/replies/' + this.id, {
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
                axios.delete('/replies/' + this.id);

                this.$emit('deleted', this.id);
                // $(this.$el).fadeOut(1000, () => {
                //     flash('Your reply has been deleted.');
                // });
            },
            markBestReply() {
                axios
                    .post('/replies/' + this.id + '/best', {
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
                ;
                window.events.$emit('best-reply-selected', this.id)

                flash('Reply: '+ this.body +' marked as best')
            },
        }
    }
</script>

<style scoped>

</style>
