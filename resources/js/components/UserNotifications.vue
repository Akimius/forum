<template>
    <li class="nav-item dropdown m-2" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell"></i>
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a v-for="notification in notifications"
               class="dropdown-item"
               :href="notification.data.link"
               v-text="notification.data.message"
               @click="markAsRead(notification)"
            >
            </a>
        </div>

    </li>
</template>

<script>
    export default {
        data() {
            return { notifications: false }
        },
        created() {
            axios.get('/profiles/' + window.App.user.name + '/notifications')
                .then(response => this.notifications = response.data);
        },
        methods: {
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id)
            }
        }
    }
</script>

<style scoped>

</style>