<template>

    <div class="card" style="width:400px">
        <div class="card-header">
            <h4 class="card-title" v-text="user.name"></h4>
            <a href="#" class="btn btn-primary" @click="goHome()">Go to threads</a>
        </div>
        <div class="card-body">
            <img :src="avatar" class="card-img-top">
            <div class="form-group">
                <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
                    <image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
                </form>
            </div>

        </div>
    </div>

</template>

<script>
    import ImageUpload from './ImageUpload.vue';

    export default {
        props: ['user'],

        components: { ImageUpload },

        data() {
            return {
                avatar: this.user.avatar_path
            };
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            }
        },

        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;

                this.persist(avatar.file);
            },

            persist(avatar) {
                let data = new FormData();

                data.append('avatar', avatar);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded!'));
            },

            goHome() {
                window.location.href = "http://forum.test/threads"
            }
        }
    }
</script>